<?php
namespace App\Http\Controllers;

use App\Models\Annee;

use App\Models\Matiere;
use Illuminate\Http\Request;
use App\Http\Requests\FamilleRequest;
use App\Models\Famille;
use App\Models\RefTypesFamille;;
use App\Models\Etudiant;
use App\Models\RefNationnalite;
use DataTables;
use App\Models\EtudMat;
use App\User;
use App\Models\Profil;
use App;

use PDF;
use URL;
use Excel;
use Auth;

class editionController extends Controller
{
    private $module = 'editions';
    //private $viewLink = 'backend.'.$this->module;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $profils = Profil::all();
        $groupes =App\Models\RefGroupe::all();
        return view($this->module.'.index',['profils'=>$profils,'groupes'=>$groupes]);
    }

    public function getDT($profil='all',$groupe='1',$selected='all')
    {
	    $annee=Annee::where('etat',1)->get()->first();
		$etds =EtudMat::where('annee_id',$annee->id)->get();
		$et='';
        global $fils;

		foreach($etds as $etd)
		{

		if($et !=$etd->etudiant_id)
		{
            $fils[]=$etd->etudiant_id;
			//$ids .=.',';
		}
		$et=$etd->etudiant_id;
		}


        $etudiants = Etudiant::whereIn('id',$fils)->where('DECF','1');
        if ($selected != 'all')
            $etudiants = $etudiants->orderByRaw('id = ? desc', [$selected]);
        if ($profil != 'all')
        {
            $etds =EtudMat::where('annee_id',$annee->id)->where('profil_id',$profil)->get();
            $et='';
            $fils=array();
            foreach($etds as $etd)
            {
                if($et !=$etd->etudiant_id)
                {
                    $niveau = Profil::find($profil)->ref_niveau_etude_id;
                    if($et !=$etd->etudiant_id)
                    {
                         if ($niveau == 1){
                            $test1 =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etd->etudiant_id)
                                ->where('ref_semestre_id',3)->orderBy('ref_groupe_id')->get();
                            if ($test1->count()>0){}else{  $fils[]=$etd->etudiant_id; }
                        }
                        else if ($niveau == 2){
                            $test1 =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etd->etudiant_id)
                                ->where('ref_semestre_id',5)->orderBy('ref_groupe_id')->get();
                            if ($test1->count()>0){}else{  $fils[]=$etd->etudiant_id; }
                        }

                        else if ($niveau == 4){
                            $test1 =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etd->etudiant_id)
                                ->where('ref_semestre_id',3)->orderBy('ref_groupe_id')->get();
                            if ($test1->count()>0){}else{  $fils[]=$etd->etudiant_id; }
                        }
                        else {
                            $fils[]=$etd->etudiant_id;
                        }


                        //$ids .=.',';
                    }
                    //$ids .=.',';
                }
                $et=$etd->etudiant_id;
            }
            $etudiants = Etudiant::whereIn('id',$fils)->where('DECF','1');
        }

        if ($groupe != 'all')
        {

            $etds =EtudMat::where('annee_id',$annee->id)->where('ref_groupe_id',$groupe)->where('profil_id',$profil)->get();
            $et='';
            $fils=array();
            foreach($etds as $etd)
            {

                if($et !=$etd->etudiant_id)
                {
                    $fils[]=$etd->etudiant_id;

                }
                $et=$etd->etudiant_id;
            }
            $etudiants = Etudiant::whereIn('id',$fils)->where('DECF','1');
        }

        return DataTables::of($etudiants)
            ->addColumn('actions', function(Etudiant $etudiants) {
                $html = '<div class="btn-group">';
              /*  if (Auth::user()->hasAccess(1) or Auth::user()->hasAccess(4,3) or Auth::user()->hasAccess(5,3))
                {*/
                    $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$etudiants->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
               // }
                    if (Auth::user()->hasAccess([1,7], 4))
                {
                    $html .='<button type="button" class="btn btn-sm btn-success" onClick="exporteattestationPDF('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="'.trans('text_me.exporter').'"><i class="fas fa-fw fa-file-pdf"></i></button>';
                }
                   $msg_supp ='مسح التلميذ';
                if (Auth::user()->hasAccess([1], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->module.'/delete/'.$etudiants->id).'\',\''.trans('text.confirm_suppression').'\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button> ';

                if (Auth::user()->hasAccess([1], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="bloquer('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.bloquer') . '"><i class="fas fa-ban"></i></button> ';

                if (Auth::user()->hasAccess([1], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="bloquer1('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.bloquer1') . '"><i class="fas fa-ban"></i></button> ';

                if (Auth::user()->hasAccess([1], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="corrigerAttestation('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.coorige') . '"><i class="fas fa-window-restore"></i></button> ';

                if (Auth::user()->hasAccess([1], 5) or (Auth::user()->id==33) or (Auth::user()->id==28)) {
                    $html .= '<button type="button" class="btn btn-sm btn-secondary" onClick="chagerProfil(' . $etudiants->id . ')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.changeProfil') . '"><i class="fas fa-edit"></i></button> ';

                }
                if (Auth::user()->hasAccess([1], 5) or (Auth::user()->id==33))
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="supprimerReinscription('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.supprimerReinsc') . '"><i class="fas fa-user-edit"></i></button> ';
                if (Auth::user()->hasAccess([1], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-warning" onClick="chanerNumero('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.changerNum') . '"><i class="fas fa-sort-numeric-down-alt"></i></button> ';
                if (Auth::user()->hasAccess([1], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-infos" onClick="chagerProfil1('.$etudiants->id.')" data-toggle="tooltip" data-placement="top" title="' . trans('text_me.modifierProfil1') . '"><i class="fas fa-edit"></i></button> ';

                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($etudiants) use ($selected) {
                return $etudiants->id == $selected ? 'alert-success' : '';
            })
            ->addColumn('case_coche', function(Etudiant $etudiants) {
                $html = '<input type="checkbox" id='.$etudiants->id.' value="'.$etudiants->id.'" name="cases[]" onClick="selectEtudiantsEdit('.$etudiants->id.')"/>';
                return $html;
            })
            ->rawColumns(['id','actions','case_coche'])
            ->make(true);
    }

    public function inserteTemp($id)
    {
       $tmp =new App\Models\TmpAttesationColl();
       $tmp->etudiant_id=$id;
       $tmp->save();
    }
    public function DeleteTemp($id)
    {
       $tmps=App\Models\TmpAttesationColl::where('etudiant_id',$id)->get();
       foreach ($tmps as $tmp)
       {
           $tm=App\Models\TmpAttesationColl::find($tmp->id);
           $tm->forceDelete();
       }
    }

    public function DeleteAll()
    {
       $tmps=App\Models\TmpAttesationColl::where('etudiant_id','<>','')->get();
       foreach ($tmps as $tmp)
       {
           $tm=App\Models\TmpAttesationColl::find($tmp->id);
           $tm->forceDelete();
       }
    }
    public function corrigerAttestation($id)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etudiantMat = EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->get();
        return view($this->module.'.ajax.corrigerAttestation',['etudiantMat'=>$etudiantMat]);
    }

    public function chagerProfil($id)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etudiant=Etudiant::find($id);

        $profil_id = EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->orderBy('ref_semestre_id','DESC')->get()->first()->profil_id;

        $niveau = Profil::find($profil_id)->ref_niveau_etude_id;
            $profils  =Profil::where('ref_niveau_etude_id',$niveau)->get();
        return view($this->module.'.ajax.updateProfil',['profils'=>$profils,'id'=>$id,'profil_id'=>$profil_id]);
    }

    public function chagerProfil1($id)
    {

        $profils  =Profil::all();
        return view($this->module.'.ajax.updateProfil1',['profils'=>$profils,'id'=>$id]);
    }
    public function chanerNumero($id)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $an=Annee::find($annee->id);
        $faculte =App\Models\Faculte::where('etat',1)->get()->first();
        $dernierNum1=$annee->numero+1;
        $nodos=$faculte->code.''.($annee->numero+1);
        $etudiant  =Etudiant::find($id);
        $dernierNum=$nodos;
        return view($this->module.'.ajax.updateNumeroEtudiant',['etudiant'=>$etudiant,'dernierNum1'=>$dernierNum1,'dernierNum'=>$dernierNum,'id'=>$id]);
    }

    public function formAdd()
    {
        return view($this->module.'.add');
    }

    public function listeConcours()
    {
        $listes=App\Models\Moussabaka::all();
        foreach ($listes as $liste){
           // echo '<br>code:'.$liste->id.' nni :'.$liste->nni;
            $etudiants=Etudiant::where('NNI',$liste->nni)->get();
            if ($etudiants->count()>0)
            {
                $a=App\Models\Moussabaka::find($liste->id);
                $a->etudiants=$etudiants->first()->NODOS;
                $a->etat=1;
                $a->nometd=$etudiants->first()->NOMA;
                $a->save();
            }
        }
    }
    public function UpdateProfil(Request $request)
    {
        $etudiant = Etudiant::find($request->id);
        $ancien = $request->ancien;
        $profil = $request->profil;
        if ($profil == $ancien)
        {
            return response()->json(1,200);
        }
        else{
            $annee=Annee::where('etat',1)->get()->first();
            $etudiant->profil_id=$profil;
            $etudiant->save();
            $etudiantMat = EtudMat::where('etudiant_id',$request->id)->where('profil_id',$ancien)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
            foreach ($etudiantMat as $etudian)
            {
                $etdM=EtudMat::find($etudian->id);
                $etdM->delete();
            }
            $prl=Profil::find($profil)->ref_niveau_etude_id;
            $sa=$sb='';$cptSem3=$cptSem4=0;
            if ($prl==1 )
            {
                $sa=1;$sb=2;
                $matieres = Matiere::where('profil_id', $profil)->whereIn('ref_semestre_id',[$sa,$sb])->get();

            }
            if ($prl==2 )
            {
                $sa=3;$sb=4;
                $etudiantMatSS1 = EtudMat::where('etudiant_id',$request->id)->where('ref_semestre_id',1)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
                $etudiantMatSS2 = EtudMat::where('etudiant_id',$request->id)->where('ref_semestre_id',2)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
                foreach ($etudiantMatSS1 as $etudiantMatS1)
                {
                    $cre =Matiere::find($etudiantMatS1->matiere_id);
                    if ($cre)
                    {
                        $cptSem3 +=$cre->credit;
                    }

                }
                foreach ($etudiantMatSS2 as $etudiantMatS2)
                {
                    $cre =Matiere::find($etudiantMatS2->matiere_id);
                    if ($cre)
                    {
                        $cptSem4 +=$cre->credit;
                    }

                }
                $matieres = Matiere::where('profil_id', $profil)->whereIn('ref_semestre_id',[$sa,$sb])->where('created_at', '>','2022-10-10')->get();

            }
            // dd();
            $credit = 0;
            foreach ($matieres as $matiere) {
                $credit += $matiere->credit;
                if ($matiere->ref_semestre_id == 3 ) {
                    $cptSem3 += $matiere->credit;
                }
                if ($matiere->ref_semestre_id == 4 ) {
                    $cptSem4 += $matiere->credit;
                }
                if ($cptSem3 < 40 and $matiere->ref_semestre_id == 3) {
                    $etd_mat=new EtudMat();
                    $etd_mat->etudiant_id=$request->id;
                    $etd_mat->profil_id=$profil;
                    $etd_mat->NODOS=$etudiant->NODOS;
                    $etd_mat->Code= $matiere->modulle_id;
                    $etd_mat->NOMAT=$matiere->code;
                    $etd_mat->matiere_id=$matiere->id;
                    $etd_mat->ref_semestre_id=$matiere->ref_semestre_id;
                    $etd_mat->annee_id=$this->annee_id();
                    $etd_mat->save();
                }
                if ($cptSem4 < 40 and $matiere->ref_semestre_id == 4) {
                    $etd_mat=new EtudMat();
                    $etd_mat->etudiant_id=$request->id;
                    $etd_mat->profil_id=$profil;
                    $etd_mat->NODOS=$etudiant->NODOS;
                    $etd_mat->Code= $matiere->modulle_id;
                    $etd_mat->NOMAT=$matiere->code;
                    $etd_mat->matiere_id=$matiere->id;
                    $etd_mat->ref_semestre_id=$matiere->ref_semestre_id;
                    $etd_mat->annee_id=$this->annee_id();
                    $etd_mat->save();
                }


                if ($credit < 61 and $prl==1) {
                    $etd_mat=new EtudMat();
                    $etd_mat->etudiant_id=$request->id;
                    $etd_mat->profil_id=$profil;
                    $etd_mat->NODOS=$etudiant->NODOS;
                    $etd_mat->Code= $matiere->modulle_id;
                    $etd_mat->NOMAT=$matiere->code;
                    $etd_mat->matiere_id=$matiere->id;
                    $etd_mat->ref_semestre_id=$matiere->ref_semestre_id;
                    $etd_mat->annee_id=$this->annee_id();
                    $etd_mat->save();
                }
            }
            return response()->json($etudiant->id,200);
        }

    }

    public function UpdateProfil1(Request $request)
    {
        $etudiant = Etudiant::find($request->id);
            $annee=Annee::where('etat',1)->get()->first();
            $etudiant->profil_id=$request->profil;
            $etudiant->save();
            $profil=$request->profil;
        $niveaux = Profil::find($profil);
        $niv=$niveaux->ref_niveau_etude_id;
        $s1=$s2=0;
        if ($niv==1){ $s1=1;$s2=2; }
        if ($niv==2){ $s1=3;$s2=4; }
        if ($niv==3){ $s1=5;$s2=6; }
        if ($niv==4){ $s1=1;$s2=2; }
        if ($niv==5){ $s1=3;$s2=4; }
            $etudiantMat = EtudMat::where('etudiant_id',$request->id)->where('profil_id',$request->profil)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
            foreach ($etudiantMat as $etudian)
            {
                $etdM=EtudMat::find($etudian->id);
                $etdM->delete();
            }
            $matieres = Matiere::where('profil_id', $profil)->whereIn('ref_semestre_id',[$s1,$s2])->get();

            $credit = 0;
            foreach ($matieres as $matiere) {
                $credit += 0;
                if ($credit < 61) {
                    $etd_mat=new EtudMat();
                    $etd_mat->etudiant_id=$request->id;
                    $etd_mat->profil_id=$profil;
                    $etd_mat->NODOS=$etudiant->NODOS;
                    $etd_mat->Code= $matiere->modulle_id;
                    $etd_mat->NOMAT=$matiere->code;
                    $etd_mat->matiere_id=$matiere->id;
                    $etd_mat->ref_semestre_id=$matiere->ref_semestre_id;
                    $etd_mat->annee_id=$this->annee_id();
                    $etd_mat->save();
                }
            }
            return response()->json($etudiant->id,200);
    }

    public function UpdateNumero(Request $request)
    {
        $etudiant = Etudiant::find($request->id);
        $etudiant->AD1= $etudiant->NODOS;
        $etudiant->NODOS=$request->newNODOS;
        $etudiant->save();
        $annee=Annee::where('etat',1)->get()->first();
        $an1 = Annee::find($annee->id);

        $an1->numero=$request->newnumb;
        $an1->save();

        return response()->json($etudiant->id,200);
    }

   /* public function chagerProfil1($id)
    {
        $famille = new Famille;
        $famille->libelle = $request->libelle;
        $famille->save();
        return response()->json($famille->id,200);
    }*/

    public function edit(FamilleRequest $request)
    {
        $famille = Famille::find($request->id);
        $famille->libelle = $request->libelle;
        $famille->save();
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
                $groupes =App\Models\RefGroupe::all();
                $nationnalites=RefNationnalite::all();
                $parametres = ['etudiant' => $etudiant,'groupes' => $groupes,'nationnalites' => $nationnalites];
                break;
            default :
                $parametres = ['etudiant' => $etudiant];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $famille = Etudiant::find($id);
            $famille->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }

    public function SupprimeerMatierDejaProgrammee($id)
    {
        $mat = EtudMat::find($id);
        $mat->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('text.element_well_deleted')],200);
        }

    public function exporteattestationPDF($id)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etudiant = Etudiant::find($id);
        $etds =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$id)->get()->first();
        $profil= $etds->profil_id;
       /* $etdsss= EtudMat::where('profil_id',75)->where('matiere_id',1031)->where('annee_id',4)->orderBy('ref_semestre_id')->get();
        foreach ($etdsss as $etdsmm)
        {
           // dd($etdsmm);

            $verif1m =EtudMat::where('annee_id',4)->where('etudiant_id',$etdsmm->etudiant_id)->where('matiere_id',1557)->get();
            if ($verif1m->count()>0 ){
              // dd('ren');
            }

            else{
              //  dd('new');
                $matiere=Matiere::find(1557);

               // dd($matiere);
                $etd_mat = new EtudMat();
                $etd_mat->etudiant_id = $etdsmm->etudiant_id;
                $etd_mat->profil_id = 75;
                $etd_mat->NODOS = $etdsmm->NODOS;
                $etd_mat->Code = $matiere->modulle_id;
                $etd_mat->NOMAT = $matiere->code;
                $etd_mat->matiere_id = $matiere->id;
                $etd_mat->ref_semestre_id = $matiere->ref_semestre_id;
                $etd_mat->annee_id = 4;
                $etd_mat->save();
            }
        }*/
		//icici nnnnn
		/*
        if ($profil == 74 and $annee->id=3)
        {

            $etdsss= EtudMat::where('profil_id',74)->where('matiere_id',1553)->where('annee_id',3)->orderBy('ref_semestre_id')->get();
            foreach ($etdsss as $etdsmm)
            {
            $verif1m =EtudMat::where('annee_id',3)->where('etudiant_id',$etdsmm->etudiant_id)->where('matiere_id',1555)->get();
            if ($verif1m->count()>0 ){
            }
            else{
                $matiere=Matiere::find(1555);
                $etd_mat = new EtudMat();
                $etd_mat->etudiant_id = $etdsmm->etudiant_id;
                $etd_mat->profil_id = 74;
                $etd_mat->NODOS = $etdsmm->NODOS;
                $etd_mat->Code = $matiere->modulle_id;
                $etd_mat->NOMAT = $matiere->code;
                $etd_mat->matiere_id = $matiere->id;
                $etd_mat->ref_semestre_id = $matiere->ref_semestre_id;
                $etd_mat->annee_id = 3;
                $etd_mat->save();
            }
            /*$verif2m =EtudMat::where('annee_id',3)->where('etudiant_id',$etdsmm->etudiant_id)->where('matiere_id',1554)->get();
            if ($verif2m->count()>0 ){
            }
            else{
                $matiere=Matiere::find(1554);
                $etd_mat = new EtudMat();
                $etd_mat->etudiant_id = $etdsmm->etudiant_id;
                $etd_mat->profil_id = 74;
                $etd_mat->NODOS = $etdsmm->NODOS;
                $etd_mat->Code = $matiere->modulle_id;
                $etd_mat->NOMAT = $matiere->code;
                $etd_mat->matiere_id = $matiere->id;
                $etd_mat->ref_semestre_id = $matiere->ref_semestre_id;
                $etd_mat->annee_id = 3;
                $etd_mat->save();
            }
            }
        }*/
		//jusqau ici
        $niveauEtude=Profil::find($profil);
        if ($niveauEtude->ref_niveau_etude_id == 1)
        {
            $credits=0;
            $matieres = Matiere::where('profil_id', $profil)->whereIn('ref_semestre_id',[1,2])->get();
            foreach ($matieres as $matiere) {
               $MtMat  = EtudMat::where('etudiant_id',$id)->where('matiere_id',$matiere->id)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
            if ( $MtMat->count()>0){

            }
            else{

                if ($etudiant->NODOS > 'L24264') {

                    $etd_mat = new EtudMat();
                    $etd_mat->etudiant_id = $id;
                    $etd_mat->profil_id = $profil;
                    $etd_mat->NODOS = $etudiant->NODOS;
                    $etd_mat->Code = $matiere->modulle_id;
                    $etd_mat->NOMAT = $matiere->code;
                    $etd_mat->matiere_id = $matiere->id;
                    $etd_mat->ref_semestre_id = $matiere->ref_semestre_id;
                    $etd_mat->annee_id = $this->annee_id();
                    $etd_mat->save();
                }
            }

            }
            /*foreach ($etudiantMat as $etudMat)
            {
               $matiere= App\Models\Matiere::find($etudMat->matiere_id);
               $credits +=$matiere->credit;
            }
            if ($credits<57)
            {

            }*/
        }
        $etudiantMat = EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
        $exm = new ExamenCONController();
        $titre=trans("text_me.attestation");
        $html = $exm->entete($titre.'<br>'.$annee->libelle.'','P',false,$etudiant);
        $html .=$this->infosEtudiant($etudiant);
        $html .=$this->infosInscription($etudiantMat,$etudiant->groupe);
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.attestation').'');
        PDF::SetSubject(''.trans('text_me.attestation').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }
    public function exporteattestationPDFSP($id)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etudiant = Etudiant::find($id);
        $profil= $etudiant->profil_id;
        $niveauEtude=Profil::find($profil);
        if ($niveauEtude->ref_niveau_etude_id == 1)
        {
            $credits=0;
            $matieres = Matiere::where('profil_id', $profil)->whereIn('ref_semestre_id',[1,2])->get();
            foreach ($matieres as $matiere) {
               $MtMat  = EtudMat::where('etudiant_id',$id)->where('matiere_id',$matiere->id)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
            if ( $MtMat->count()>0){

            }
            else{
                if ($etudiant->NODOS > 'L22089') {
                    //dd('h');
                    $etd_mat = new EtudMat();
                    $etd_mat->etudiant_id = $id;
                    $etd_mat->profil_id = $profil;
                    $etd_mat->NODOS = $etudiant->NODOS;
                    $etd_mat->Code = $matiere->modulle_id;
                    $etd_mat->NOMAT = $matiere->code;
                    $etd_mat->matiere_id = $matiere->id;
                    $etd_mat->ref_semestre_id = $matiere->ref_semestre_id;
                    $etd_mat->annee_id = $this->annee_id();
                    $etd_mat->save();
                }
            }

            }
            /*foreach ($etudiantMat as $etudMat)
            {
               $matiere= App\Models\Matiere::find($etudMat->matiere_id);
               $credits +=$matiere->credit;
            }
            if ($credits<57)
            {

            }*/
        }
        $etudiantMat = EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->orderBy('ref_semestre_id')->get();
        $exm = new ExamenCONController();
        $titre=trans("text_me.attestation");
        $html = $exm->entete($titre.'<br>'.$annee->libelle.'',$etudiant);
        $html .=$this->infosEtudiant($etudiant);
        $html .=$this->infosInscription($etudiantMat,$etudiant->groupe);
       $html .='<div style="page-break-after: always"></div>';
        return $html;
    }
    public function annee_id()
    {
        $id = Annee::where('etat', 1)->get()->first()->id;
        return $id;
    }
    public  function infosEtudiant($etudiant,$profil1='')
    {
        $html ='<table style="width: 100%" border="">
            <tr>
            <td style="width: 15%">
                <table style="width: 100%" >
                    <tr>
                        <td align="center" style="width: 100%">';

            if($etudiant->photo && file_exists( $etudiant->photo))
        {
            $url = url("$etudiant->photo");

        }
        else{
            $url = URL::asset('img/avatar_2x.png');
        }

        $html .= '<img id="img_pic" src="'.$url.'" alt="Profile Image" style="width:60px; height: 60px;" align="center"/>';
        $html .='</td>

                    </tr>

                </table>
            </td>
            <td style="width: 35%" align="right">
                <table style="width: 100%" >
                    <tr>
                        <td align="right" style="width: 50%">'.$etudiant->DATN.'</td>
                        <td align="right" style="width: 50%">'.trans("text_me.dateN").':</td>
                    </tr>
                    <tr>
                        <td align="right" style="width: 50%">'.$etudiant->NNI.'</td>
                        <td align="right" style="width: 50%">'.trans("text_me.nni").':</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%" align="right">
                <table style="width: 100%" >
                    <tr>
                        <td align="right" style="width: 70%">'.$etudiant->NODOS.'</td>
                        <td align="right" style="width: 30%">'.trans("text_me.nodos").':</td>
                    </tr>
                    <tr>
                        <td align="right" style="width: 70%">';
						/*if($etudiant->NOMF =='_')
						{*/
							//$html .=' '.$etudiant->NOMA;
						/*}
						else
						{*/
						if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
							$html .=' '.$etudiant->NOMF;
						else
                            $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
						//}
						$html .='</td>
                        <td align="right" style="width: 30%">'.trans("text_me.nom").':</td>
                    </tr>
                    <tr>
                        <td align="right" style="width: 70%">'.$etudiant->LIEUNA.'</td>
                        <td align="right" style="width: 30%">'.trans("text_me.lieuN").':</td>
                    </tr>';
                    $annee=Annee::where('etat',1)->get()->first();
                    if ($profil1=='')
                    {
                        $profil1 = EtudMat::where('etudiant_id',$etudiant->id)->where('annee_id',$annee->id)->orderBy('ref_semestre_id','DESC')->get()->first()->profil_id;
                    }
                     $profil=Profil::find($profil1);
                    //- '.$profil->departement->libelle.'
                    $html .='<tr>
                        <td align="right" style="width: 70%">'.$profil->ref_niveau_etude->libelle.' - '.$profil->libelle.'</td>
                        <td align="right" style="width: 30%">'.trans("text_me.profil").':</td>
                    </tr>
                </table>
</td>

            </tr>
</table>';
        return $html;
    }
    public function infosInscription($etudiantMat,$groupe='ا'){
        $ip=0;
        if ($etudiantMat->count()>0){
          $t=$etudiantMat->first()->profil_id;
          $ip=Profil::find($t)->ref_niveau_etude_id;
        }
        $html ='<table style="width: 100%" border="">
            <tr>
                <td align="center" style="width: 30%"><b>الوحدة</b></td>
                <td align="center" style="width: 10%"><b>الرصيد</b></td>
                <td align="right" style="width: 40%"><b>العنصر</b></td>
                <td align="center" style="width: 10%"><b>المجموعة</b></td>';
        if ($ip==4 or $ip==5){
            $html .=' <td align="center" style="width: 10%"><b>الرباعي</b></td>';
        }
       else {
           $html .=' <td align="center" style="width: 10%"><b>السداسي</b></td>';
       }
        $html .=' </tr>';
        foreach ($etudiantMat as $mat) {
            if (isset($mat->matiere->modulle->libelle))
            {
                $html .= '
           <tr>
                <td align="center">' . $mat->matiere->modulle->libelle . '</td>
                <td align="center">' . $mat->matiere->credit . '</td>
                <td align="right">' . $mat->matiere->libelle . '</td>
                <td align="center"> ' . $groupe . ' </td>
                <td align="center">' . $mat->ref_semestre_id . '</td>
            </tr>';
        }
        }

        $html .='</table>';
        $html .='<table><tr><td align="right"><b>عدد العناصر :'.count($etudiantMat).'</b></td></tr></table>';
        $html .='<br><br><br><br><table><tr><td align="left"><b>رئيس مصلحة الشؤون الطلابية</b></td></tr></table>';
        return $html;
    }

    public function pdfListeEtudiant($profil='all')
    {
        $annee=Annee::where('etat',1)->get()->first();
        if ($profil !='all')
            $profils =Profil::where('id',$profil)->get();
        else
            $profils =Profil::all();
        $html ='';

        foreach ($profils as $profil)
        {
            $etds =EtudMat::where('annee_id',$annee->id)->where('profil_id',$profil->id)->orderBy('NODOS')->get();
            $et='';


           $etudiants='';
			$html .=$this->etudiantProfil($profil->id,$etudiants);

        }
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('لائحة الطلاب').'');
        PDF::SetSubject(''.trans('لائحة الطلاب').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, true, true, true, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }

    public function pdfstatiNSEtudiant($profil='all')
    {
        $annee=Annee::where('etat',1)->get()->first();
        if ($profil !='all')
            $profils =Profil::where('id',$profil)->get();
        else
            $profils =Profil::all();
        $html ='';

        foreach ($profils as $profil)
        {
            $etds =EtudMat::where('annee_id',$annee->id)->where('profil_id',$profil->id)->orderBy('NODOS')->get();
            $et='';


           $etudiants='';
			$html .=$this->etudiantProfilEXPORT($profil->id,$etudiants);

        }
        //$html ='<table border=1 ><tr><td>a</td></tr></table>';
        return Excel::download(new App\Exports\ExportEquipement($html), ''.$profil->libelle.'_satatistiaues.xlsx');
      //  return Excel::download(new App\Exports\ExportEquipement($html), ''.$profil->libelle.'_satatistiaues.xlsx');
    }

    public function pdfListeRenvoyer()
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etudiants = Etudiant::all();
        $html ='';
        $exm = new ExamenCONController();
        $titre='لوائح المطرودين';
        $html = $exm->entete($titre.' <br>'.$annee->libelle);
        $html .='<table style="width: 100%" border="1">
                <tr>
                    <th align="right" style="width: 20%">الملاحظة</th>
                    <th align="right" style="width: 35%">التخصص </th>
                    <th align="right" style="width: 35%">الاسم</th>
                    <th align="right" style="width: 10%">رقم التسجيل</th>
                </tr>';
        foreach ($etudiants as $etudiant)
        {
                 $etdSUPs =EtudMat::where('annee_id',$annee->id)
                    ->where('etudiant_id',$etudiant->id)->orderBy('ref_semestre_id','DESC')
                    ->get();

            if ($etdSUPs->count()>0)
            {
                $semestre= $niveau=0;
               $semestre=$etdSUPs->first()->ref_semestre_id;
               $profil=$etdSUPs->first()->profil_id;
               $niveau=0;
               if ($semestre==1 or $semestre==2)
               {
                   $niveau=1;
               }
                if ($semestre==3 or $semestre==4)
                {
                    $niveau=2;
                }
                if ($semestre==5 or $semestre==6)
                {
                    $niveau=3;
                }

             //   if ($etdSUPs->count()>0)
                    $html .=$this->profilEtRevo($profil,$niveau,$etudiant);
            }

        }
        $html.='</table>';
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('لائحة الطلاب').'');
        PDF::SetSubject(''.trans('لائحة الطلاب').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, true, true, true, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }

    public function profilEtRevo($profl,$niveau,$etudiants){
        $html='';
        $annees=Annee::all();
        $anneee=Annee::where('etat',1)->get()->first();
        $profil=Profil::find($profl)->libelle;
        $cp=0;
        //dd($annees);
        foreach ($annees as $annee)
        {
            $etdSUP1s =EtudMat::where('annee_id',$annee->id)
                ->where('etudiant_id',$etudiants->id)
                ->get();

            if ($etdSUP1s->count()>0)
            {
                $cp +=1;
            }


        }




       // $profi=Profil::find($profil);
       // if ( $cp==2 )
         //   dd($niveau .''.$cp .' '.$etudiants->NODOS);
           if (($niveau==1) )
           {
               if ($cp==2 or $cp==3 or $cp==4 ){
               $etudiaI=Etudiant::find($etudiants->id);
               $etudiaI->DECF=3;
               $etudiaI->save();
               $etds =EtudMat::where('annee_id',$anneee->id)->where('etudiant_id',$etudiants->id)->get();
              // dd($etds);
               foreach ($etds as $etddd)
               {
                 $a=EtudMat::find($etddd->id);
                 $a->AB=90;
                 $a->save();
                   $b=EtudMat::find($etddd->id);
                   $b->delete();
               }
               $html .='
                <tr>
                    <td align="right" style="width: 20%">مطرود</td>
                    <td align="right" style="width: 35%">'.$profil.' </td>
                    <td align="right" style="width: 35%">'.$etudiants->NOMA.'</td>
                    <td align="right" style="width: 10%">'.$etudiants->NODOS.'</td>
                </tr>';
           }
    }
        if (($niveau==2))
        {
            if ($cp==5 or $cp==3 or $cp==4 or $cp==6 ){
            $etudiaI=Etudiant::find($etudiants->id);
            $etudiaI->DECF=3;
            $etudiaI->save();
            $html .='
                <tr>
                    <td align="right" style="width: 20%">مطرود</td>
                    <td align="right" style="width: 35%">'.$profil.' </td>
                    <td align="right" style="width: 35%">'.$etudiants->NOMA.'</td>
                    <td align="right" style="width: 10%">'.$etudiants->NODOS.'</td>
                </tr>';
            $etds =EtudMat::where('annee_id',$anneee->id)->where('etudiant_id',$etudiants->id)->get();
            //dd($etds);
            foreach ($etds as $etddd)
            {
                $a=EtudMat::find($etddd->id);
                $a->AB=90;
                $a->save();
                $b=EtudMat::find($etddd->id);
                $b->delete();
            }
            }
        }
        if (($niveau== 3))
        {
            if ($cp==5 or $cp==7 or $cp==8 or $cp==6 ){
            $etudiaI=Etudiant::find($etudiants->id);
            $etudiaI->DECF=3;
            $etudiaI->save();
            $html .='
                <tr>
                    <td align="right" style="width: 20%">مطرود</td>
                    <td align="right" style="width: 35%">'.$profil.' </td>
                    <td align="right" style="width: 35%">'.$etudiants->NOMA.'</td>
                    <td align="right" style="width: 10%">'.$etudiants->NODOS.'</td>
                </tr>';
            $etds =EtudMat::where('annee_id',$anneee->id)->where('etudiant_id',$etudiants->id)->get();
           // dd($etds);
            foreach ($etds as $etddd)
            {
                $a=EtudMat::find($etddd->id);
                $a->AB=90;
                $a->save();
                $b=EtudMat::find($etddd->id);
                $b->delete();
            }
            }
        }


return $html;
    }
    public function EtRevoL(){
        $exm = new ExamenCONController();
        $titre='لوائح المطرودين';
        $annee=Annee::where('etat',1)->get()->first();
        $etudiants=Etudiant::where('DECF',3)->get();
        $html = $exm->entete($titre.' <br>'.$annee->libelle);
        $html .='<table style="width: 100%" border="1">
                <tr>
                    <th align="right" style="width: 15%">الملاحظة</th>
                    <th align="right" style="width: 35%">التخصص </th>
                    <th align="right" style="width: 35%">الاسم</th>
                    <th align="right" style="width: 10%">رقم التسجيل</th>
                    <th align="right" style="width: 5%"></th>
                </tr>';

            $cp=0;
        foreach ($etudiants as $etudiant)
        {
$cp +=1;

            $profi=Profil::find($etudiant->profil_id);
                $html .=' <tr>
                    <th align="right" style="width: 15%"></th>
                    <th align="right" style="width: 35%">'.$profi->libelle.' </th>
                    <th align="right" style="width: 35%">'.$etudiant->NOMA.' </th>
                    <th align="right" style="width: 10%">'.$etudiant->NODOS.'</th>
                     <th align="right" style="width: 5%">'.$cp.'</th>
                </tr>';

        }
        $html .='</table>';
        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }

    public function pdfattestationColl($profil,$groupe)
    {
       // dd($groupe);
       //$libG=App\Models\RefGroupe::find($groupe)->libelle;
        $html ='';
       // whereIn('id',EtudiantInscrit::where('ref_annee_id',$anne->id)->where('classe_id',$classe)->pluck('etudiant_id'))
        $etudiants = Etudiant::whereIn('id',App\Models\TmpAttesationColl::where('etudiant_id','<>','')->pluck('etudiant_id'))->get();
        //dd($etudiants);
        foreach ($etudiants as $etudiant)
        {
            $html .=$this->exporteattestationPDFSP($etudiant->id);
        }
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans( "text_me.exportCollectiveAtt").'');
        PDF::SetSubject(''.trans( "text_me.exportCollectiveAtt").'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, true, true, true, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }
    public function etudiantProfilEXPORT($id,$etudiants)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $groupes =App\Models\RefGroupe::all();
        $html='';
        global $fils;
		$etds =EtudMat::where('annee_id',$annee->id)->where('profil_id',$id)->orderBy('NODOS')->get();

            $et='';
            $fils=array();
		foreach($etds as $etd) {

                if ($et != $etd->etudiant_id) {
                    $niveau = Profil::find($id)->ref_niveau_etude_id;

                    if ($niveau == 1) {
                        $test1 = EtudMat::where('annee_id', $annee->id)->where('etudiant_id', $etd->etudiant_id)
                            ->where('ref_semestre_id', 3)->orderBy('NODOS')->get();//->orwhere('ref_semestre_id', 4)
                        if ($test1->count() > 0) {
                        } else {
                            $fils[] = $etd->etudiant_id;
                        }
                    }
                    else if ($niveau == 2) {

                        $test1 = EtudMat::where('annee_id', $annee->id)->where('etudiant_id', $etd->etudiant_id)
                            ->where('ref_semestre_id', 5)->get();

                        if ($test1->count() > 0) {
                        } else {
                            $fils[] = $etd->etudiant_id;
                        }
                    }

                    else if ($niveau == 4) {
                        $test1 = EtudMat::where('annee_id', $annee->id)->where('etudiant_id', $etd->etudiant_id)
                            ->where('ref_semestre_id', 3)->orderBy('NODOS')->get();
                        if ($test1->count() > 0) {
                        } else {
                            $fils[] = $etd->etudiant_id;
                        }
                    }
                    else{  $fils[] = $etd->etudiant_id;  }
                    $et = $etd->etudiant_id;
                }
            }
        foreach ($groupes as $grou)
        {



            $groupeli=$grou->libelle;
            $etudiants = Etudiant::where('DECF','1')->whereIn('id',$fils)->where('groupe',$groupeli)
                ->orderByRaw('LENGTH(NODOS)', 'ASC')
                ->orderBy('NODOS', 'ASC')
                ->get();
           /* $etudiants = Etudiant::where('DECF','1')->whereIn('id',$fils)->where('groupe',$groupeli)
                ->orderByRaw("CAST(NODOS as UNSIGNED) ASC")
                ->get();*/

//->orderBy('NODOS', 'ASC')
            if ($etudiants->count()>0)
            {
                $html .=''.$this->getEtudiantAllProfilsTAT($id,$grou->libelle,$etudiants);
            }
        }
        return $html;
    }
    public function etudiantProfil($id,$etudiants)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $groupes =App\Models\RefGroupe::all();
        $html='';
        global $fils;
		$etds =EtudMat::where('annee_id',$annee->id)->where('profil_id',$id)->orderBy('NODOS')->get();

            $et='';
            $fils=array();
		foreach($etds as $etd) {

                if ($et != $etd->etudiant_id) {
                    $niveau = Profil::find($id)->ref_niveau_etude_id;

                    if ($niveau == 1) {
                        $test1 = EtudMat::where('annee_id', $annee->id)->where('etudiant_id', $etd->etudiant_id)
                            ->where('ref_semestre_id', 3)->orderBy('NODOS')->get();//->orwhere('ref_semestre_id', 4)
                        if ($test1->count() > 0) {
                        } else {
                            $fils[] = $etd->etudiant_id;
                        }
                    }
                    else if ($niveau == 2) {

                        $test1 = EtudMat::where('annee_id', $annee->id)->where('etudiant_id', $etd->etudiant_id)
                            ->where('ref_semestre_id', 5)->get();

                        if ($test1->count() > 0) {
                        } else {
                            $fils[] = $etd->etudiant_id;
                        }
                    }

                    else if ($niveau == 4) {
                        $test1 = EtudMat::where('annee_id', $annee->id)->where('etudiant_id', $etd->etudiant_id)
                            ->where('ref_semestre_id', 3)->orderBy('NODOS')->get();
                        if ($test1->count() > 0) {
                        } else {
                            $fils[] = $etd->etudiant_id;
                        }
                    }
                    else{  $fils[] = $etd->etudiant_id;  }
                    $et = $etd->etudiant_id;
                }
            }
        foreach ($groupes as $grou)
        {



            $groupeli=$grou->libelle;
            $etudiants = Etudiant::where('DECF','1')->whereIn('id',$fils)->where('groupe',$groupeli)
                ->orderByRaw('LENGTH(NODOS)', 'ASC')
                ->orderBy('NODOS', 'ASC')
                ->get();
           /* $etudiants = Etudiant::where('DECF','1')->whereIn('id',$fils)->where('groupe',$groupeli)
                ->orderByRaw("CAST(NODOS as UNSIGNED) ASC")
                ->get();*/

//->orderBy('NODOS', 'ASC')
            if ($etudiants->count()>0)
            {
                $html .=''.$this->getEtudiantAllProfil($id,$grou->libelle,$etudiants);
            }
        }
        return $html;
    }
    public function getEtudiantAllProfilsTAT($id,$groupe,$etudiants)
    {
        $nbgarson=$nbfille=0;
        $profil=Profil::find($id);
        $annee=Annee::where('etat',1)->get()->first();
        $exm = new ExamenCONController();
        $titre=trans("text_me.listeEtudiant");//'.$profil->departement->libelle.' -
        $html = $exm->entete($titre.'<br> '.$profil->ref_niveau_etude->libelle.' -  '.$profil->libelle.'<br>'.trans("text_me.groupe").' '.$groupe);
        $html='';
        $html .='<table style="width: 100%;" border="1">
                    <tr>
                    
                     <td align="center">لغة التكوين</td>
                     <td align="center">الجنسية</td>
                     <td align="center">ذا كان  الجواب نعم حدد   المؤسسة  القادم منها</td>
                     <td align="center">إذا كان  الجواب نعم , حدد   تاريخ أول تسجيل في  المؤسسة</td>
                     <td align="center">قادم من مؤسسة  أخري ؟ </td>
                     <td align="center">ممنوح أو مستفيد من مساعدة </td>
                     <td align="center">معيد ؟</td>
                     <td align="center">لتكوين (آكادمي  أو مهني)</td>
                     <td align="center">تاريخ أول تسجيل في هذا المستوى</td>
                     <td align="center">الشهادة</td>
                     <td align="center">'.trans("text_me.lieuN").'</td>
                     <td  align="center">'.trans("text_me.dateN").'</td>
                     <td style="" align="center">'.trans("text_me.genre").'</td>
                     <td style="" align="center">'.trans("text_me.profil").'</td>
                     <td align="right">'.trans("text_me.nom").'</td>
                     <td align="right">'.trans("text_me.bac").'</td>
                      <td  align="center">'.trans("text_me.nni").'</td>
                     <td  align="right">'.trans("text_me.nodos").'</td>
                     <td  align="right">'.trans("text_me.rang").'</td>
                    </tr>';
        $rang=0; $nez=0;
        foreach ($etudiants as $etudiant)
        {
            $annee=Annee::where('etat',1)->get()->first();
            $an=$annee->libelle;
            $firtAnne='';
            if ($etudiant->created_at >'2023-09-30')
            {
                $nez +=1;
            }
           $niveau = EtudMat::where('annee_id','<>',$annee->id)->where('etudiant_id',$etudiant->id)
               ->where('profil_id',$id)->orderBy('annee_id','DESC')->get();
            $niv = EtudMat::where('annee_id','<>',$annee->id)->where('etudiant_id',$etudiant->id)
                ->orderBy('annee_id','DESC')->get();
            if ($niv->count()>0){
                $firtAnne=$niv->first()->annee->libelle;
            }
            $oui='لا';
           if ($niveau->count())
           {
               $an= $niveau->first()->annee->libelle;
               $oui='نعم';
           }
           $et = EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->get();

            if($et->count()>0)
            {
                $rang +=1;
                $sexe='';

                if ($etudiant->SEXE=='F')
                {
                    $sexe='أنثى';
                    $nbfille +=1;
                }
                elseif ($etudiant->SEXE=='M')
                {
                    $sexe='ذكر';
                    $nbgarson +=1;
                }
                else{
                    $sexe=$etudiant->SEXE;
            }
                if ($etudiant->SEXE=='ذكر')
                {
                    $nbgarson +=1;
                }
                if ($etudiant->SEXE=='انثى' or $etudiant->SEXE=='أنثى'  )
                {
                    $nbfille +=1;
                }
                $nation='موريتاني';

                if (RefNationnalite::find($etudiant->ref_nationnalite_id))
                {
                    $nation=RefNationnalite::find($etudiant->ref_nationnalite_id)->libelle;
                }
                $html .='<tr>
                            <td align="center">العربية</td>
                            <td align="center">'.$nation.'</td>
                           <td align="center">ليس قادم من مؤسسة أخري</td>
                            <td align="center">'.$firtAnne.'</td>
                            <td align="center">لا</td>
                            <td align="center">ليس ممنوح</td>
                            <td align="center">'.$oui.'</td>
                            <td align="center">أكاديمي</td>
                            <td align="center">'.$an.'</td>
                            <td align="center">ل'.$profil->ref_niveau_etude_id.'</td>
                            <td style="" align="center">'.$etudiant->LIEUNA.'</td>
                            <td style="" align="center">'.$etudiant->DATN.'</td>
                            <td style="" align="center">'.$sexe.'</td>
                            <td style="" align="center">'.$profil->libelle.'</td>
                            <td style="" align="right">';
                           /* if ($etudiant->NOMF =='_' or $etudiant->NOMF=='')
                            {
                                $html .= $etudiant->NOMA.'';
                            }
                            else if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
                                $html .=' '.$etudiant->NOMF;
                            else*/
                                $html .=' '.$etudiant->NOMF ;
                            $html .='</td>
                        <td style="" align="center">'.$etudiant->NOBAC.'</td>
                        <td style="" align="center">'.$etudiant->NNI.'</td>';
                $html .=' <td style="" align="right">'.$etudiant->NODOS.'</td>
                            <td style="" align="right">'.$rang.'</td>
                        </tr>';
            }
            }
            $html .='</table>';
        $html .='<br><br><br><br><br>';
        $html .=' <table border="1" style="width: 100%;">
                         <tr>
                         <td style="" align="right">المسلك :'.$profil->libelle.'</td>
                            <td style="" align="right">عدد المسجلين</td>
                        </tr>
                        </table>';
        $html .=' <table border="1" style="width: 100%;">
                         <tr>
                         <td style="" align="right">الحميع</td>
                          <td style="" align="right">الجدد</td>
                            <td style="" align="right">الاناث</td>
                           
                            <td style="" align="right">الذكور</td>
                        </tr>
                         <tr>
                         <td style="" align="right">'.$rang.'</td>
                            <td style="" align="right">'.$nez.'</td>
                            <td style="" align="right">'.$nbfille.'</td>
                            <td style="" align="right">'.$nbgarson.'</td>
                        </tr>
                        </table>';

        //$html .='<div style="page-break-after: always"></div>';
        return $html;
    }
    public function getEtudiantAllProfil($id,$groupe,$etudiants)
    {
        $profil=Profil::find($id);
        $annee=Annee::where('etat',1)->get()->first();
        $exm = new ExamenCONController();
        $titre=trans("text_me.listeEtudiant").'<br>'.$annee->libelle; // '.$profil->departement->libelle.' -
        $html = $exm->entete($titre.'<br> '.$profil->ref_niveau_etude->libelle.' - '.$profil->libelle.'<br>'.trans("text_me.groupe").' '.$groupe);

        $html .='<table style="width: 100%;" border="1">
                    <tr>
                     <td style="width: 20%;" align="center">'.trans("text_me.nni").'</td>
                     <td style="width: 10%" align="center">'.trans("text_me.lieuN").'</td>
                     <td style="width: 12%" align="center">'.trans("text_me.dateN").'</td>
                     <td style="width: 39%" align="right">'.trans("text_me.nom").'</td>
                     <td style="width: 12%" align="right">'.trans("text_me.nodos").'</td>
                    <td style="width: 7%" align="right">'.trans("text_me.rang").'</td>
                    </tr>';
        $rang=0;
        foreach ($etudiants as $etudiant)
        {
            $annee=Annee::where('etat',1)->get()->first();

           $et = EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->get();
            if($et->count()>0)
            {
                $rang +=1;
                if($etudiant->photo && file_exists( $etudiant->photo))
                {
                    $url = url("$etudiant->photo");
                }
                else{
                    $url = URL::asset('img/avatar_2x.png');
                }
                // <td style="width: 10%" align="center"><img id="img_pic" src="'.$url.'" alt="Profile Image" style="width:40px; height: 30px;" align="center"/></td>

                $html .='<tr>
                            <td style="width: 20%" align="center">'.$etudiant->NNI.'</td>
                            <td style="width: 10%" align="center">'.$etudiant->LIEUNA.'</td>
                            <td style="width: 12%" align="center">'.$etudiant->DATN.'</td>
                            <td style="width: 39%" align="right">';
                if ($etudiant->NOMF =='_' or $etudiant->NOMF=='')
                {
                    $html .= $etudiant->NOMA.'';
                }
                else if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
                    $html .=' '.$etudiant->NOMF;
                else
                    $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
                $html .='</td>';

                $html .=' <td style="width: 12%" align="right">'.$etudiant->NODOS.'</td>
                            <td style="width: 7%" align="right">'.$rang.'</td>
                        </tr>';
            }
            }
            $html .='</table>';
        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }
    public function bloqueEtudiant($id)
    {

        $etudiant =Etudiant::find($id);
        $etudiant->DECF=2;
        $etudiant->save();
        $annee=Annee::where('etat',1)->get()->first();
        $etud_mat= EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->get();
        if ($etud_mat->count()>0){
            foreach ($etud_mat as $etud1)
            {
                $etud_u=EtudMat::find($etud1->id);
                $etud_u->delete();
            }

        }
    }
    public function bloqueEtudiant1($id)
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etud_mat= EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->get();
        if ($etud_mat->count()>0){
            foreach ($etud_mat as $etud1)
            {
                $etud_u=EtudMat::find($etud1->id);
                $etud_u->forceDelete();
            }

        }
        $etud_sem=App\Models\EtudSemestre::where('etudiant_id',$id)->where('annee_id',$annee->id)->get();
        if ($etud_sem->count()>0)
        {
            foreach ($etud_sem as $etuS1)
            {
                $etuS=App\Models\EtudSemestre::find($etuS1->id);
                $etuS->forceDelete();
            }

        }
    }

    public function supprimerReinscription($id)
    {
        $etudiant =Etudiant::find($id);
        $etudiant->DECF=0;
        $etudiant->save();
        $annee=Annee::where('etat',1)->get()->first();
        $MtMat  = EtudMat::where('etudiant_id',$id)->where('annee_id',$annee->id)->get();
        foreach ($MtMat as $Mt)
        {
            $matiere=EtudMat::find($Mt->id);
            $matiere->delete();
        }
        $semestres  = App\Models\EtudSemestre::where('etudiant_id',$id)->where('annee_id',$annee->id)->get();
        foreach ($semestres as $semestre)
        {
            $ss=App\Models\EtudSemestre::find($semestre->id);
            $ss->delete();
        }
    }
}
