<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EtudiantRequest;
use App\Models\Annee;
use App\Models\Profil;
use App\Models\Bachelier;
use App\Models\RefTypesFamille;
use App\Models\Matiere;
use App\Models\TempMatiere;
use App\Models\TempMatieres1;
use  App\Models\Faculte;
use App\Models\RefNationnalite;
use App\Models\Etudiant;
use App\Models\EtudMat;
use App\Models\EtudSemestre;
use Carbon\Carbon;
use DataTables;
use App\User;
use App;
use Auth;

class InscriptionController extends Controller
{
    private $module = 'inscriptions';

    public function __construct()
    {
        //$this->middleware('auth');


    }

    public function index()
    {

        TempMatiere::truncate();
        TempMatieres1::truncate();
        $annees = Annee::all();
        $annee = Annee::where('etat',1)->get();
        return view($this->module.'.index',['annees'=>$annees,'annee'=>$annee]);
    }

    public function annee_id()
    {
        $id = Annee::where('etat', 1)->get()->first()->id;
        return $id;
    }

    public function getDT($annee,$selected='all')
    {
        $bachaliers = Bachelier::where('annee','1999')->where('etat',1)->get();
        if ($annee !='all' and $annee !='CC' and $annee !='S')
            $bachaliers = Bachelier::where('nat',$annee)->where('annee',$annee)->where('etat',1)->get();
        if ($annee !='all' and $annee =='CC')
            $bachaliers = Bachelier::where('nat',$annee)->where('etat',1)->get();
        if ($annee !='all' and $annee =='S')
            $bachaliers = Bachelier::where('nat',$annee)->where('etat',1)->get();
        if ($selected != 'all')
            $bachaliers = Bachelier::where('annee',$annee)->where('etat',1)->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($bachaliers)
            ->addColumn('actions', function(Bachelier $bachaliers) {
               if (Auth::user()->hasAccess([1,7],4)) {
                    $html = '<div class="btn-group">';
                    $html .= ' <button type="button" class="btn btn-sm btn-dark" onClick="getBachellier(' . $bachaliers->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fa fa-fw fa-eye"></i></button> ';
                    $html .= '</div>';
                }
                return $html;
            })
            ->setRowClass(function ($bachaliers) use ($selected) {
                return $bachaliers->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        $nationnalites=RefNationnalite::all();
        return view($this->module.'.add',['nationnalites'=>$nationnalites]);
    }

    public function addNewBachalier(App\Http\Requests\BachalierRequest $request )
    {
        $bachalier =new Bachelier();
        $bachalier->nobac = $request->nobac;
        $bachalier->nni = $request->nni;
        $bachalier->nompl = $request->nom;
        $bachalier->datn = $request->dateN;
        $bachalier->lieu = $request->lieuN;
        $bachalier->sexe = $request->sexe;
        $bachalier->tel = $request->tel;
        $bachalier->nat = 'S';
        $bachalier->save();
        return response()->json($bachalier->id,200);
    }
    public function chagerProfil($id)
    {
        $profils=Profil::all();
        return view($this->module.'.ajax.updateProfil',['profils'=>$profils]);

    }
    public function add(EtudiantRequest $request)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $an=Annee::find($annee->id);
        $faculte =Faculte::where('etat',1)->get()->first();
        $ref_niveau_etude_id  =Profil::find($request->profil)->ref_niveau_etude_id;
        if ($ref_niveau_etude_id ==1)
        { $nodos=$faculte->code.''.($annee->numero+1);  $an->numero=$annee->numero+1;}
        if ($ref_niveau_etude_id ==4)
        { $nodos='M00'.($annee->numeroMst+1); $an->numeroMst=($annee->numeroMst+1); }
        $an->save();
        $groupe='ا';
        $etudgrouMax=Etudiant::max('id');
        if ($etudgrouMax)
        {
        $groupeder=Etudiant::find($etudgrouMax)->groupe;
        $groupe_id=App\Models\RefGroupe::where('libelle',$groupeder)->get()->first()->id;
        $groupeliste=App\Models\RefGroupe::where('id','>',$groupe_id)->get();
        if ($groupeliste->count()>0)
        {
            $groupe=$groupeliste->first()->libelle;
        }
        }
        $etudiant = new Etudiant();
        $etudiant->NODOS = $nodos;
        $etudiant->DECF = 1;
        $etudiant->NNI = $request->nni;
        $etudiant->NOMF = $request->nom;
        $etudiant->NOMA = $request->nom;
        $etudiant->NOBAC = $request->nobac;
        $etudiant->DATN = $request->dateN;
        $etudiant->LIEUNA = $request->lieuN;
        $etudiant->profil_id = $request->profil;
        $etudiant->groupe = $groupe;
        $etudiant->SEXE = $request->sexe;
        $etudiant->whatsapp = $request->tel;
        $etudiant->ref_nationnalite_id = $request->nationnalite;
        $etudiant->save();
        if ($etudiant->id)
        {
            $etud_mat= EtudMat::where('etudiant_id',$etudiant->id)->get();
            if ($etud_mat->count()>0){
                $etud_mat->delete();
            }
            $etud_sem=EtudSemestre::where('etudiant_id',$etudiant->id)->get();
            if ($etud_sem->count()>0)
            {
                $etud_sem->delete();
            }
            $matieres=TempMatiere::all();
            foreach ($matieres as $matiere)
            {
                $m=Matiere::find($matiere->id_matiere);
                $etd_mat=new EtudMat();
                $etd_mat->etudiant_id=$etudiant->id;
                $etd_mat->profil_id=$request->profil;
                $etd_mat->NODOS=$etudiant->NODOS;
                $etd_mat->Code= $m->modulle_id;
                $etd_mat->NOMAT=$matiere->code;
                $etd_mat->matiere_id=$matiere->id_matiere;
                $etd_mat->ref_semestre_id=$m->ref_semestre_id;
                $etd_mat->annee_id=$this->annee_id();
                $etd_mat->save();
            }
            $sem1=new EtudSemestre();
            $sem1->NODOS=$etudiant->NODOS;
            $sem1->etudiant_id=$etudiant->id;
            $sem1->profil_id=$request->profil;
            $sem1->ref_semestre_id=1;
            $sem1->annee_id=$this->annee_id();
            $sem1->save();
            $sem2=new EtudSemestre();
            $sem2->NODOS=$etudiant->NODOS;
            $sem2->etudiant_id=$etudiant->id;
            $sem2->profil_id=$request->profil;
            $sem2->ref_semestre_id=2;
            $sem2->annee_id=$this->annee_id();
            $sem2->save();
            $bachalier=Bachelier::find($request->id_b);
            $bachalier->etat=3;
            $bachalier->save();
        }
        return response()->json($etudiant->id,200);
    }

    public function edit(App\Http\Requests\EtudiantEditRequest$request)
    {
        $etudiant = Etudiant::find($request->id);

        // etat civil
        $etudiant->NNI = $request->nni;
        $etudiant->NOMF = $request->nom;
        $etudiant->NOMA = $request->nom;
        $etudiant->DATN = $request->dateN;
        $etudiant->LIEUNA = $request->lieuN;
        $etudiant->SEXE = $request->sexe;
        $etudiant->ref_nationnalite_id = $request->nationnalite;
        if (Auth::user()->hasAccess([1]))
        {
            $etudiant->NODOS = $request->nodos;
        }

        // contact
        $etudiant->adress = $request->adresse;
        $etudiant->TEL = $request->tel;
        $etudiant->email = $request->email;
        $etudiant->whatsapp = $request->whatsapp;
        $etudiant->groupe = $request->groupe;
        $etudiant->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $etudiant = Etudiant::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.$etudiant->NOMF.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {

        $etudiant = Etudiant::find($id);
        switch ($tab) {
            case '1':
                $bachellier= Bachelier::find($id);
                $profils  =Profil::where('ref_niveau_etude_id',1)->get();
                $nationnalites=RefNationnalite::all();
                $parametres = ['etudiant' => $etudiant,'bachellier' => $bachellier,'nationnalites' => $nationnalites];
                break;
            default :
                $parametres = ['etudiant' => $etudiant];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $famille = Famille::find($id);
        if ($famille->has_articles)
            return response()->json(['success'=>'false', 'msg'=>trans('text.famille_cant_be_del_bcuz_of_articles')],200);
        else {
            $famille->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }
    }
    public function getBachellier($id)
    {
        $bachellier= Bachelier::find($id);
		//->orWhere('ref_niveau_etude_id',1)
        //if (Auth::user()->hasAccess([1], 5)){
        if (Auth::user()->hasAccess([1,7], 4)){
            $profils = Profil::orWhere('ref_niveau_etude_id', 4)->orWhere('ref_niveau_etude_id', 1)->get();
        }
        else {
            $profils = Profil::Where('ref_niveau_etude_id', 1)->get();
        }
        $nationnalites=RefNationnalite::all();
        return view($this->module.'.ajax.addBachalier',['bachellier'=>$bachellier,'profils'=>$profils,'nationnalites'=>$nationnalites]);
    }
    public function inserteTemp($id)
    {
        TempMatiere::truncate();
        TempMatieres1::truncate();
        $credit = 0;
        if (count(TempMatiere::all()) == 0) {
            $matieres = Matiere::where('profil_id', $id)->whereIn('ref_semestre_id',[1,2])->get();
            foreach ($matieres as $matiere) {
                $credit += $matiere->credit;
                if ($credit < 61) {
                    $tmp = new TempMatiere;
                    $tmp->libelle = '' . $matiere->libelle;
                    $tmp->id_matiere = $matiere->id;
                    $tmp->code = $matiere->code;
                    $tmp->credit = $matiere->credit;
                    $tmp->save();
                } else {
                    $tmp1 = new TempMatieres1;
                    $tmp1->libelle = '' . $matiere->libelle;
                    $tmp1->id_matiere = $matiere->id;
                    $tmp1->code = $matiere->code;
                    $tmp1->credit = $matiere->credit;
                    $tmp1->save();
                }
            }
        }
        $tmps1 = '';
        $credits = $credits1 = 0;
        $tmps = TempMatiere::all();
        foreach ($tmps as $t) {
            $credits += $t->credit;
        }
        if ($credit > 60 or count(TempMatieres1::all()) > 0) {
        $tmps1 = TempMatieres1::all();
            foreach ($tmps1 as $t1)
            {
                $credits1 +=$t1->credit;
            }
         }
        return view($this->module . '.ajax.tmp', ['tmps' => $tmps,'tmps1' => $tmps1,'credits' => $credits,'credits1' => $credits1]);
    }
    public function changeProfil($id)
    {
        TempMatiere::truncate();
        TempMatieres1::truncate();
        $credit = 0;
        if (count(TempMatiere::all()) == 0) {
            $matieres = Matiere::where('profil_id', $id)->whereIn('ref_semestre_id',[1,2])->get();
            foreach ($matieres as $matiere) {
                $credit += $matiere->credit;
                if ($credit < 61) {
                    $tmp = new TempMatiere;
                    $tmp->libelle = '' . $matiere->libelle;
                    $tmp->id_matiere = $matiere->id;
                    $tmp->code = $matiere->code;
                    $tmp->credit = $matiere->credit;
                    $tmp->save();
                } else {
                    $tmp1 = new TempMatieres1;
                    $tmp1->libelle = '' . $matiere->libelle;
                    $tmp1->id_matiere = $matiere->id;
                    $tmp1->code = $matiere->code;
                    $tmp1->credit = $matiere->credit;
                    $tmp1->save();
                }
            }
        }
        $tmps1 = '';
        $credits = $credits1 = 0;
        $tmps = TempMatiere::all();
        foreach ($tmps as $t) {
            $credits += $t->credit;
        }
        if ($credit > 60 or count(TempMatieres1::all()) > 0) {
            $tmps1 = TempMatieres1::all();
            foreach ($tmps1 as $t1)
            {
                $credits1 +=$t1->credit;
            }
        }
        return 1;
    }

    public function annulerAttribution($id)
    {
        $credit = 0;
        $matiere=TempMatiere::find($id);
        $tmp1 = new TempMatieres1;
        $tmp1->libelle = '' . $matiere->libelle;
        $tmp1->id_matiere = $matiere->id;
        $tmp1->code = $matiere->code;
        $tmp1->credit = $matiere->credit;
        $tmp1->save();
        $matiere->forceDelete();
        $tmps1 = '';
        $credits = $credits1 = 0;
        $tmps = TempMatiere::all();
        foreach ($tmps as $t) {
            $credits += $t->credit;
        }
        if (count(TempMatieres1::all()) > 0) {
            $tmps1 = TempMatieres1::all();
            foreach ($tmps1 as $t1)
            {
                $credits1 +=$t1->credit;
            }
        }
        return view($this->module . '.ajax.contenu', ['tmps' => $tmps,'tmps1' => $tmps1,'credits' => $credits,'credits1' => $credits1]);
    }
    public function attribuerAttribution($id)
    {
        $credit = 0;
        $credits = $credits1 =$cred= 0;
        $matiere=TempMatieres1::find($id);
        $tmpsa = TempMatiere::all();
        foreach ($tmpsa as $t) {
            $cred += $t->credit;
        }
        $cred +=$matiere->credit;
        if($cred <= 60){
            $tmp1 = new TempMatiere();
            $tmp1->libelle = '' . $matiere->libelle;
            $tmp1->id_matiere = $matiere->id;
            $tmp1->code = $matiere->code;
            $tmp1->credit = $matiere->credit;
            $tmp1->save();
            $matiere->forceDelete();
        }
        $tmps1 = '';
        $tmps = TempMatiere::all();
        foreach ($tmps as $t) {
            $credits += $t->credit;
        }
        if (count(TempMatieres1::all()) > 0) {
            $tmps1 = TempMatieres1::all();
            foreach ($tmps1 as $t1)
            {
                $credits1 +=$t1->credit;
            }
        }
        return view($this->module . '.ajax.contenu', ['tmps' => $tmps,'tmps1' => $tmps1,'credits' => $credits,'credits1' => $credits1]);
    }
    public function openModalImage($id)
    {
        $de = Etudiant::find($id);
        return view($this->module . '.openModalImage', ['de' => $de]);
    }

    public function updateImage(Request $request)
    {
        $this->validate($request, [
            'fichier' => 'required',
        ]);
        $id = $request->id;
        if ($request->fichier) {
            $de = Etudiant::find($id);
            $extension = $request->file('fichier')->getClientOriginalExtension();

            if ($de->photo && file_exists($de->photo)) {
                if (\File::exists(public_path($de->photo))) {

                    \File::delete(public_path($de->photo));
                }
            }
            $path = "files/etudiants/etudiant$id.$extension";
            $de->photo = $path;
            $de->save();
            $imageName = "etudiant$id" . '.' . $request->file('fichier')->getClientOriginalExtension();
            $request->file('fichier')->move(
                base_path() . '/public/files/etudiants/', $imageName
            );
            $link = $path;
        }
        // $link = url("redirectto/requestlissements/".$specialite->id);

        /*$val =   "<img id='avatar' src='".$path."' style='height: 200px;width: 100%'
                             class='avatar img-circle img-thumbnail' alt='avatar'>";*/

        return response()->json($link, 200);
    }
}

