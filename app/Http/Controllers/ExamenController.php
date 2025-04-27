<?php
namespace App\Http\Controllers;
use App\Models\Annee;
use App\Models\Profil;
use App\Models\NoteExamen;
use App\Models\RefSemestre;
use Doctrine\DBAL\Cache\ArrayStatement;
use Elibyy\TCPDF\TCPDF;
use Illuminate\Http\Request;
use App\Http\Requests\FamilleRequest;
use App\Models\Famille;
use App\Models\RefTypesFamille;;
use App\Models\Etape;
use App\Models\Faculte;
use App\Models\EnteteEtablissement;
use App\Models\MatieresProfilsEtape;
use App\Models\Etudiant;
use App\Models\EtudMat;
use App\Models\Matiere;
use App\Models\RefGroupe;
use App\Models\Plage;
use App\Models\Anonymat;
use DataTables;
use App\User;
use App;
use PDF;
/*use \Mpdf\Mpdf as PDF;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;*/
use URL;
use App\Models\NoteDevoir;
use App\Models\NoteExamenRt;
use Auth;

class ExamenController extends Controller
{
    private $module = 'examens';
    public function __construct()
    {
        //$this->middleware('auth');imprimerListeEmergemet1
    }

    public function index()
    {
        $semestres =RefSemestre::all();

        if (Auth::user()->code == null and (Auth::user()->hasAccess([1]) or Auth::user()->hasAccess([6,3])))
        {
            $profils=Profil::all();
        }
        else
        {
            $profils=Profil::where('departement_id',Auth::user()->code)->get();
        }
        $etapes=Etape::all();
        $groupes=RefGroupe::all();
        /*for($i=0;$i<10;$i++){
            $j=random_int ( 1000 ,  1200 );
          echo  $j.'<br>';
    }*/
        return view($this->module.'.index',['profils'=> $profils,'semestres'=>$semestres,'etapes'=>$etapes,'groupes'=>$groupes]);
    }

    public function getDT($profil='all')
    {
        $annee=Annee::where('etat',1)->get()->first();
        $etudiants = Etudiant::where('profil_id',8888);
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
        return DataTables::of($etudiants)
            ->addColumn('case_coche', function(Etudiant $etudiants) {
                $html = '<input type="radio"  value="'.$etudiants->id.'" name="cases[]" id="et'.$etudiants->id.'"  onClick="selectEtudiantsExamen('.$etudiants->id.')"/>';
                return $html;
            })
            ->rawColumns(['id','case_coche'])
            ->make(true);
    }
    public function giveEtudiantId(){
        $etudiants =Etudiant::all();
        foreach($etudiants as $etudiant){
            $etudMates=EtudMat::where('NODOS',$etudiant->NODOS)->get();
            foreach ($etudMates as $etudMate){
                $et=EtudMat::find($etudMate->id);
                $et->etudiant_id=$etudiant->id;
                $et->save();
            }
        }
        return response()->json('Done',200);
    }
    public function formAdd()
    {
        return view($this->module.'.add');
    }

    public function add(FamilleRequest $request)
    {
        $famille = new Famille;
        $famille->libelle = $request->libelle;
        $famille->save();
        return response()->json($famille->id,200);
    }
    public function saisirnoteTarakm(Request $request)
    {

        $etat=0;
        $profil= $request->profil;
        $semestre = $request->semestre ;
        $groupe = $request->groupe ;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $s5=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('profil_id',$profil)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('annee_id',$id_annee)->orderBy('etudiant_id')->get()->pluck("etudiant_id");
        $etudiants = Etudiant::whereIn('id',$s5)->get();

     //   $s5=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('ref_semestre_id',$semestre)->where('profil_id',$profil)->where('ref_groupe_id',$groupe)->orderBy('etudiant_id')->get()->pluck("etudiant_id");
     //   $etudiants = Etudiant::whereIn('id',$s5)->get();
        foreach ($etudiants as $etudiant) {
            $this->validate($request, [
                'sem1' . $etudiant->NODOS . '' => 'required',
                'sem2' . $etudiant->NODOS . '' => 'required',
                'sem3' . $etudiant->NODOS . '' => 'required',
                'sem4' . $etudiant->NODOS . '' => 'required',
                'sem5' . $etudiant->NODOS . '' => 'required',
                'sem6' . $etudiant->NODOS . '' => 'required',
            ]);
            $etape_note=20;$ESSnote=10;
            $n1 = $request->input('sem1' . $etudiant->NODOS . '');
            $n2 = $request->input('sem2' . $etudiant->NODOS . '');
            $n3 = $request->input('sem3' . $etudiant->NODOS . '');
            $n4 = $request->input('sem4' . $etudiant->NODOS . '');
            $n5 = $request->input('sem5' . $etudiant->NODOS . '');
            $n6 = $request->input('sem6' . $etudiant->NODOS . '');
            if ($n1 > $etape_note && $n2 > $etape_note && $n3 > $etape_note && $n4 > $etape_note && $n5 > $etape_note && $n6 > $etape_note)
            {return response()->json(['Exists'=>[''.trans('text_me.notesUP').'']],422);}
            if ($n1 < $ESSnote && $n2 < $ESSnote && $n3 < $ESSnote && $n4 < $ESSnote && $n5 < $ESSnote && $n6 < $ESSnote)
            {return response()->json(['Exists'=>[''.trans('text_me.notesUP').'']],422);}
            $Sem1=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',1)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
            $Sem2=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',2)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
            $Sem3=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',3)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
            $Sem4=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',4)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
            $Sem5=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',5)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
            $Sem6=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',6)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
            $groupe=1;
            if ($Sem5){ $groupe = $Sem5->ref_groupe_id; }
            if ($Sem6){ $groupe = $Sem6->ref_groupe_id; }
            if ($Sem1){ $moy1 = number_format($Sem1->note,2);  $this->updateValidSemAn($Sem1->id,$n1); } else { $this->inserValidSemAn($etudiant->id,$profil,$groupe,1,$id_annee,$n1,11);}
            if ($Sem2){ $moy2 = number_format($Sem2->note,2);  $this->updateValidSemAn($Sem2->id,$n2); } else { $this->inserValidSemAn($etudiant->id,$profil,$groupe,2,$id_annee,$n2,11);}
            if ($Sem3){ $moy3 = number_format($Sem3->note,2);  $this->updateValidSemAn($Sem3->id,$n3); } else { $this->inserValidSemAn($etudiant->id,$profil,$groupe,3,$id_annee,$n3,11);}
            if ($Sem4){ $moy4 = number_format($Sem4->note,2);  $this->updateValidSemAn($Sem4->id,$n4); } else { $this->inserValidSemAn($etudiant->id,$profil,$groupe,4,$id_annee,$n4,11);}
            if ($Sem5){ $moy5 = number_format($Sem5->note,2);  $this->updateValidSemAn($Sem5->id,$n5); } else { }
            if ($Sem6){ $moy6 = number_format($Sem6->note,2);  $this->updateValidSemAn($Sem6->id,$n6); } else { }
            $etat=1;
        }

        return response()->json($etat, 200);
    }

    public function inserValidSemAn($id,$profil,$groupe,$semestre,$id_annee,$mye,$val){
        $moySem =new App\Models\MoyennesSemestre();
        $moySem->etudiant_id=$id;
        $moySem->profil_id=$profil;
        $moySem->ref_groupe_id=$groupe;
        $moySem->ref_semestre_id=$semestre;
        $moySem->annee_id=$id_annee;
        $moySem->note=$mye;
        $moySem->decision = 11;
        $moySem->save();
    }

    public function updateValidSemAn($id,$mye){
        $moySem = App\Models\MoyennesSemestre::find($id);
        $moySem->note=$mye;
        $moySem->save();

    }

    public function saisirnote(Request $request)
    {
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etap=Etape::find($request->etape_id)->ref_type_controle_id;
        $etape_note=Etape::find($request->etape_id)->note;
        $niveau=Profil::find($request->profil);
        $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
        if ($ref_niveau_etude_id==5 and $request->semestre==4) {
            $p = 1;
            if ($etap == 1) {
                $etape_note = 20;
            }
        }
        if ($request->sit == 'add') {
            if ($etap==3)
            {
                $etudiants = Etudiant::whereIn('id', App\Models\RelevesNote::where('matiere_id', $request->id_matiere)->where('ref_semestre_id', $request->semestre)
                    ->where('annee_id', $id_annee)->where('ref_groupe_id', $request->groupe)->where('decision', 2)->get()->pluck("etudiant_id"))
                    ->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();
            }
            else{

                $etudiants =  Etudiant::whereIn('id', EtudMat::where('matiere_id', $request->id_matiere)->where('annee_id', $id_annee)
                    ->where('ref_semestre_id', $request->semestre)->get()->pluck("etudiant_id"))->where('groupe', RefGroupe::find($request->groupe)->libelle)->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();
            }
            // dd($etudiants);
            foreach ($etudiants as $etudiant) {
                $this->validate($request, [
                    'note' . $etudiant->id . '' => 'required',
                ]);

                $nn = $request->input('note' . $etudiant->id . '');

                if ($nn > $etape_note)
                    return response()->json(['Exists'=>[''.trans('text_me.notesUP').'']],422);
            }

            foreach ($etudiants as $etudiant) {
                if ($etap==1) {
                    $noteexam = new NoteExamen();
                }
                if ($etap==2) {
                    $noteexam = new NoteDevoir();
                }
                if ($etap==3) {
                    $noteexam = new NoteExamenRt();
                }
                $noteexam->profil_id = $request->profil;
                $noteexam->etudiant_id = $etudiant->id;
                $noteexam->ref_groupe_id = $request->groupe;
                $noteexam->ref_semestre_id = $request->semestre;
                $noteexam->etape_id = $request->etape_id;
                $noteexam->matiere_id = $request->id_matiere;
                $noteexam->note = $request->input('note' . $etudiant->id . '');
                $noteexam->annee_id = $id_annee;
                $noteexam->save();
                $etat = 1;
            }
        }
        if ($request->sit == 'edit') {
            //dd('edit');
            $etudiants =  Etudiant::whereIn('id', EtudMat::where('matiere_id', $request->id_matiere)->where('profil_id', $request->profil)->where('annee_id', $id_annee)
                ->where('ref_semestre_id', $request->semestre)->get()->pluck("etudiant_id"))->where('groupe', RefGroupe::find($request->groupe)->libelle)->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                ->orderBy('NODOS', 'ASC')->get();

            foreach ($etudiants as $etudiant)
            {
                $this->validate($request, [
                    'note' . $etudiant->id . '' => 'required',
                ]);

                //$etape_note=Etape::find($request->etape_id)->note;
                $nn = $request->input('note' . $etudiant->id . '');

                if ($nn > $etape_note)
                    return response()->json(['Exists'=>[''.trans('text_me.notesUP').'']],422);
            }

            $mac=$request->mac;
            // $mac=system("/usr/sbin/arp -a|grep ".$_SERVER['REMOTE_ADDR']."\)|cut -f4 -d\" \"");
            foreach ($etudiants as $etudiant)
            {
                $notemod = new App\Models\NoteModifier();
                $notemod->machine=trim($mac);
                if ($etap==1) {
                    $notemod->etat = 'Examen';
                    $noteexam= NoteExamen::find(NoteExamen::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)->where('annee_id', $id_annee)
                        ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etudiant_id',$etudiant->id)
                        ->where('annee_id',$id_annee)->get()->first()->id);
                }
                if ($etap==2) {
                    $notemod->etat = 'devoir';
                    $noteexam= NoteDevoir::find(NoteExamen::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)->where('annee_id', $id_annee)
                        ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etudiant_id',$etudiant->id)
                        ->where('annee_id',$id_annee)->get()->first()->id);
                }
                if ($etap==3) {
                    $notemod->etat = 'RT';
                    $noteexam= NoteExamenRt::find(NoteExamen::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)->where('annee_id', $id_annee)
                        ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etudiant_id',$etudiant->id)
                        ->where('annee_id',$id_annee)->get()->first()->id);
                }

                $noteexam->profil_id=$request->profil;
                $noteexam->etudiant_id=$etudiant->id;
                $noteexam->ref_groupe_id=$request->groupe;
                $noteexam->ref_semestre_id=$request->semestre;
                $noteexam->etape_id=$request->etape_id;
                $noteexam->matiere_id=$request->id_matiere;
                if ($noteexam->note != $request->input('note' . $etudiant->id . ''))
                {

                    $notemod->profil_id = $request->profil;
                    $notemod->etudiant_id = $etudiant->etudiant_id;
                    $notemod->ref_groupe_id = $request->groupe;
                    $notemod->ref_semestre_id = $request->semestre;
                    $notemod->etape_id = $request->etape_id;
                    $notemod->matiere_id = $request->id_matiere;
                    $notemod->oldnote = $noteexam->note;
                    $notemod->newnote = $request->input('note' . $etudiant->id . '');
                    $notemod->user_id = Auth::user()->id;
                    $notemod->annee_id = $id_annee;
                    $notemod->save();
                }
                $noteexam->note=$request->input('note' . $etudiant->id . '');
                $noteexam->annee_id=$id_annee;
                $noteexam->save();
                $etat=1;
            }
        }
        if ($request->sit == 'save') {

            if ($etap==1){
                $etudiants= NoteExamen::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }
            if ($etap==2){
                $etudiants= NoteDevoir::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }
            if ($etap==3){
                $etudiants= NoteExamenRt::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }

            foreach ($etudiants as $etudiant)
            {

                $this->validate($request, [
                    'note'.$etudiant->id.'' => 'required',
                ]);
                //$etape_note=Etape::find($request->etape_id)->note;
                $nn = $request->input('note' . $etudiant->id . '');

                if ($nn > $etape_note)
                    return response()->json(['Exists'=>[''.trans('text_me.notesUP').'']],422);

            }
            foreach ($etudiants as $etudiant)
            {
                $notemod = new App\Models\NoteModifier();
                $mac=$request->mac;


                $notemod->machine=trim($mac);
                if ($etap==1){
                    $notemod->etat = 'Examen';
                    $noteexam = NoteExamen::find($etudiant->id);
                }
                if ($etap==2){
                    $notemod->etat = 'devoir';
                    $noteexam = NoteDevoir::find($etudiant->id);
                }
                if ($etap==3){
                    $notemod->etat = 'RT';
                    $noteexam = NoteExamenRt::find($etudiant->id);
                }
                if ($noteexam->note != $request->input('note'.$etudiant->id.''))
                {

                    $notemod->profil_id = $request->profil;
                    $notemod->etudiant_id = $etudiant->etudiant_id;
                    $notemod->ref_groupe_id = $request->groupe;
                    $notemod->ref_semestre_id = $request->semestre;
                    $notemod->etape_id = $request->etape_id;
                    $notemod->matiere_id = $request->id_matiere;
                    $notemod->oldnote = $noteexam->note;
                    $notemod->newnote = $request->input('note'.$etudiant->id.'');
                    $notemod->user_id = Auth::user()->id;
                    $notemod->annee_id = $id_annee;
                    $notemod->save();
                }
                $noteexam->note = $request->input('note'.$etudiant->id.'');
                $noteexam->save();
                //dd($noteexam->note);
                $etat=2;
            }
        }
        return response()->json($etat, 200);
    }


    public function validernote(Request $request)
    {
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etap=Etape::find($request->etape_id)->ref_type_controle_id;

        if ($request->sit == 'save') {

            if ($etap==1){
                $etudiants= NoteExamen::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }
            if ($etap==2){
                $etudiants= NoteDevoir::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }
            if ($etap==3){
                $etudiants= NoteExamenRt::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }


            foreach ($etudiants as $etudiant)
            {
                if ($etap==1){
                    $noteexam = NoteExamen::find($etudiant->id);
                }
                if ($etap==2){
                    $noteexam = NoteDevoir::find($etudiant->id);
                }
                if ($etap==3){
                    $noteexam = NoteExamenRt::find($etudiant->id);
                }
                $noteexam->etat = 'a';
                $noteexam->save();
                //dd($noteexam->note);
                $etat=2;
            }
        }
        return response()->json($etat, 200);
    }
 public function devalidernote(Request $request)
    {
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etap=Etape::find($request->etape_id)->ref_type_controle_id;

        if ($request->sit == 'save') {

            if ($etap==1){
                $etudiants= NoteExamen::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }
            if ($etap==2){
                $etudiants= NoteDevoir::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }
            if ($etap==3){
                $etudiants= NoteExamenRt::where('matiere_id',$request->id_matiere)->where('profil_id',$request->profil)
                    ->where('ref_semestre_id',$request->semestre)->where('ref_groupe_id',$request->groupe)->where('etape_id',$request->etape_id)
                    ->where('annee_id',$id_annee)->get();

            }


            foreach ($etudiants as $etudiant)
            {
                if ($etap==1){
                    $noteexam = NoteExamen::find($etudiant->id);
                }
                if ($etap==2){
                    $noteexam = NoteDevoir::find($etudiant->id);
                }
                if ($etap==3){
                    $noteexam = NoteExamenRt::find($etudiant->id);
                }
                $noteexam->etat = '';
                $noteexam->save();
                //dd($noteexam->note);
                $etat=2;
            }
        }
        return response()->json($etat, 200);
    }

    public function saisirnoteIndiv(Request $request)
    {
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        //dd($request->etape);
        $etap=Etape::find($request->etape)->ref_type_controle_id;

        $semestre=$request->semestre;
        $etudiants='';
        if ($semestre == 1 or $semestre== 3 or $semestre ==5 )
        {
            $etudiants = EtudMat::where('etudiant_id',$request->id)->whereIN('ref_semestre_id',[1,3,5])->where('annee_id',$id_annee)->get();
        }
        if ($semestre == 2 or $semestre== 4 or $semestre == 6 )
        {
            $etudiants = EtudMat::where('etudiant_id',$request->id)->whereIN('ref_semestre_id',[2,4,6])->where('annee_id',$id_annee)->get();
        }
        // dd($etudiants);
        foreach ($etudiants as $etudian) {
            $verif='';

            if ($etap==1) {
                $verif = NoteExamen::where('matiere_id', $etudian->matiere_id)->where('etudiant_id',$request->id)
                    ->where('ref_semestre_id', $etudian->ref_semestre_id)->where('etape_id', $request->etape)
                    ->where('annee_id', $id_annee)->get()->first();
            }
            if ($etap==2) {
                $verif = NoteDevoir::where('matiere_id', $etudian->matiere_id)->where('etudiant_id',$request->id)
                    ->where('ref_semestre_id', $etudian->ref_semestre_id)->where('etape_id', $request->etape)
                    ->where('annee_id', $id_annee)->get()->first();
            }
            if ($etap==3) {
                $verif = NoteExamenRt::where('matiere_id', $etudian->matiere_id)->where('etudiant_id',$request->id)
                    ->where('ref_semestre_id', $etudian->ref_semestre_id)->where('etape_id', $request->etape)
                    ->where('annee_id', $id_annee)->get()->first();
            }
            // dd($verif);
            if ($verif)
            {
                $notemod = new App\Models\NoteModifier();

                $mac=$request->mac;


                $notemod->machine=trim($mac);
                if ($etap==1) {  $noteexam= NoteExamen::find($verif->id); $notemod->etat = 'Examen'; }
                if ($etap==2) {  $noteexam= NoteDevoir::find($verif->id);  $notemod->etat = 'devoir'; }
                if ($etap==3) {  $noteexam= NoteExamenRt::find($verif->id);  $notemod->etat = 'RT'; }
                $not=$request->input('note' . $etudian->matiere_id  . '');
                if ($noteexam->note != $not)
                {
                    $notemod->profil_id = $noteexam->profil_id;
                    $notemod->etudiant_id = $noteexam->etudiant_id;
                    $notemod->ref_groupe_id = $noteexam->ref_groupe_id;
                    $notemod->ref_semestre_id = $noteexam->ref_semestre_id;
                    $notemod->etape_id = $noteexam->etape_id;
                    $notemod->matiere_id = $noteexam->matiere_id;
                    $notemod->oldnote = $noteexam->note;
                    $notemod->newnote = $not;
                    $notemod->user_id = Auth::user()->id;
                    $notemod->annee_id = $id_annee;
                    $notemod->save();
                }
            }
            else {

                if ($etap==1) {  $noteexam= new NoteExamen();  }
                if ($etap==2) {  $noteexam= new NoteDevoir(); }
                if ($etap==3) {  $noteexam= new NoteExamenRt(); }
            }
            $not=$request->input('note' . $etudian->matiere_id  . '');
            if (trim($not) !=''){

                $etudiant  = Etudiant::find($request->id);
                $groupe=RefGroupe::where('libelle',$etudiant->groupe)->first()->id;
                $noteexam->profil_id = $etudian->profil_id;
                $noteexam->etudiant_id = $request->id;
                $noteexam->ref_groupe_id = $groupe;
                $noteexam->ref_semestre_id = $etudian->ref_semestre_id;
                $noteexam->etape_id = $request->etape;
                $noteexam->matiere_id = $etudian->matiere_id;
                $noteexam->note = $request->input('note' . $etudian->matiere_id  . '');
                $noteexam->annee_id = $id_annee;
                //dd($request->etape);
                $noteexam->save();
            }
            $etat = 1;
        }

        return response()->json($etat, 200);
    }

    public function saisirnoteIndivan(Request $request)
    {
        $semestre=$request->semestre;
        $profil=$request->profil;
        $id=$request->id;
        $cas=$request->cas;
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $semestreRech=1;
        if ($semestre == 1)
        {
            $semestreRech=1;
        }
        if ($semestre == 3)
        {
            $semestreRech=1;
        }
        if ($semestre == 5)
        {
            $semestreRech=3;
        }
        if ($semestre == 2)
        {
            $semestreRech=2;
        }
        if ($semestre == 4)
        {
            $semestreRech=2;
        }
        if ($semestre == 6)
        {
            $semestreRech=4;
        }
        $id_matiere = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get()->first()->matiere_id;

        $profil_id=Matiere::find($id_matiere)->profil_id;
        if ($cas=='edit')
        {
            $releves=App\Models\NoteExamenFinale::where('etudiant_id', $id)->where('ref_semestre_id', $semestreRech)
                ->where('etape_id', 0)->where('annee_id', $id_annee)->get();
            foreach ($releves as $releve) {
                $notFinal = $request->input('note' . $releve->matiere_id . '');
                $note= App\Models\NoteExamenFinale::find($releve->id);
                $note->note=$notFinal;
                $note->save();
            }
        }
        else {
            $ancienMatiere = Matiere::whereNotIn("id", EtudMat::where('etudiant_id', $id)
                ->where('ref_semestre_id', $semestreRech)->where('annee_id', $id_annee)->get()->pluck("matiere_id"))
                ->where('profil_id', $profil_id)->where('ref_semestre_id', $semestreRech)->get();
            $etudiant = Etudiant::find($id);
            $libGrp = $etudiant->groupe;
            $groupe = RefGroupe::where('libelle_ar', $libGrp)->get()->first()->id;
            foreach ($ancienMatiere as $ancienMatier) {
                $mdulles1 = Matiere::find($ancienMatier->id);
                if ($mdulles1) {
                    $mdulles = $mdulles1->modulle_id;
                    $notFinal = $request->input('note' . $ancienMatier->id . '');
                    $note = new App\Models\NoteExamenFinale();
                    $note->profil_id = $profil;
                    $note->etudiant_id = $id;
                    $note->ref_semestre_id = $semestreRech;
                    $note->matiere_id = $ancienMatier->id;
                    $note->etape_id = 0;
                    $note->note_dev = -3;
                    $note->note_exam = -3;
                    $note->note = $notFinal;
                    $note->annee_id = $id_annee;
                    $note->modulle_id = $mdulles;
                    $note->ref_groupe_id = $groupe;
                    $note->save();
                }
            }
        }
        $etat=1;
        return response()->json($etat, 200);
    }

    public function edit(FamilleRequest $request)
    {
        $famille = Famille::find($request->id);
        $famille->libelle = $request->libelle;
        $famille->save();
        return response()->json('Done',200);
    }

    public function get($id)
    {
        $famille = Famille::find($id);
        $tablink = $this->module.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('text.info') => $tablink.'/1',
            '<i class="fa fa-plus-circle"></i> '.trans('text.elements') => $tablink.'/2',
        ];
        $modal_title = '<b>'.$famille->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab)
    {
        $famille = Famille::find($id);
        switch ($tab) {
            case '1':
                $parametres = ['famille' => $famille];
                break;
            default :
                $parametres = ['famille' => $famille];
                break;
        }
        return view($this->module.'.tabs.tab'.$tab,$parametres);
    }

    public function getalletudiants($id,$profil,$semestre,$groupe)
    {
        $titre = trans('text_me.liste_pr');
        $matiere=Matiere::find($id);

        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>';
        $ip=Profil::find($profil)->ref_niveau_etude_id;
        if ($ip==4 or $ip==5){
            $titre  .='<td align="center" style="width: 15%"><b>الرباعي</b></td>';
        }
        else{
            $titre .='<th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>';
        }
        $titre .=' 
                    <th align="right" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="right" style="width: 35%">'.$profil.'</td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';
        //.
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $html .='<table style="width: 100%" border="1">
                <tr>
                    <th align="right" style="width: 20%">'.trans('text_me.presence').'</th>
                    <th align="right" style="width: 60%">'.trans('text_me.nom_complet').'</th>
                    <th align="right" style="width: 20%">'.trans('text_me.numero').'</th>
                </tr>';
        $etuidants=Etudiant::whereIn('id',EtudMat::where('matiere_id',$id)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->get()->pluck("etudiant_id"))->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        //$etuidants=EtudMat::where('matiere_id',$id)->where('ref_semestre_id',1)->get();
        foreach ($etuidants as $etuidant)
        {
            $html .='<tr>
                    <th align="center" style="width: 20%"><input type="checkbox"></th>
                    <th align="right" style="width: 60%">'.$etuidant->NOMA.' '.$etuidant->NOMF.'</th>
                    <th align="right" style="width: 20%">'.$etuidant->NODOS.'</th>
                </tr>';
        }
        $html .='
                <tr>
                    <th align="center" style="width: 20%">'.count($etuidants).'</th>
                    <th align="right" colspan="2">'.trans("text_me.nbreParticipant").'</th>
                </tr>';
        $html .='</table>';
        $html .='<div style="page-break-after: always"></div>';
        return $html;

    }
    public function getalletudiantsListeEMRG1($id,$profil,$semestre,$groupe,$etape)
    {
        $salless= App\Models\Salle::where('etat1',4)->orderBy('ordre')->get();
        foreach ($salless as $sal)
        {
            $s=App\Models\Salle::find($sal->id);
            $s->etat1=0;
            $s->save();
        }
        $salle= App\Models\Salle::where('etat1',0)->orderBy('ordre')->get();
        $html='';
        foreach ($salle as $sa)
        {
            $html .=$this->getalletudiantsListeEMRG($id,$profil,$semestre,$groupe,$sa->id,$etape);
        }
        return $html;
    }
    public function getalletudiantsListeEMRG2($id,$profil,$semestre,$groupe)
    {
        $salless= App\Models\Salle::where('etat1',4)->orderBy('ordre')->get();
        foreach ($salless as $sal)
        {
            $s=App\Models\Salle::find($sal->id);
            $s->etat1=0;
            $s->save();
        }
        $salle= App\Models\Salle::where('etat1',0)->orderBy('ordre')->get();
        $html='';
        foreach ($salle as $sa)
        {
            $html .=$this->getalletudiantsListeEMRGListe($id,$profil,$semestre,$groupe,$sa->id);
        }
        return $html;
    }
    public function getalletudiantsListeEMRG($id,$profil,$semestre,$groupe,$salle,$etape)
    {
        $type_controle=Etape::find($etape)->ref_type_controle->libelle_ar;
        $titre = trans('text_me.liste_pr').' - '.$type_controle.'<br>'.App\Models\Salle::find($salle)->libelle;
        $matiere=Matiere::find($id);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;

        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="right" style="width: 35%">'.Profil::find($profil)->libelle.' </td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';
        //.
        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $html .='<table style="width: 100%" border="1">
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.presence').'</th>
                    <th align="right" style="width: 60%">'.trans('text_me.nom_complet').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.numero').'</th>
                    <th align="right" style="width: 10%">'.trans('text_me.rang').'</th>
                </tr>';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etuidants=Etudiant::whereIn('id',App\Models\MatiereSalleEtudiant::where('matiere_id',$id)->where('annee_id',$id_annee)->where('groupe_id',$groupe)->where('salle_id',$salle)->get()->pluck("etudiant_id"))->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        //$etuidants=EtudMat::where('matiere_id',$id)->where('ref_semestre_id',1)->get();
        if ($etuidants->count()==0)
            return '';
        $cpt=1;
        foreach ($etuidants as $etuidant)
        {
            $html .='<tr>
                    <th align="center" style="width: 15%"><input type="checkbox"></th>
                    <th align="right" style="width: 60%">';
            if (trim($etuidant->NOMF) == trim($etuidant->NOMA))
                $html .=' '.$etuidant->NOMF;
            else
                $html .=' '.$etuidant->NOMA .' '.$etuidant->NOMF;
            $html .=' </th>
                    <th align="right" style="width: 15%">'.$etuidant->NODOS.'</th> 
                    <th align="right" style="width: 10%">'.$cpt.'</th>
                </tr>';
            $cpt +=1;
        }
        $html .='
                <tr>
                    <th align="center" style="width: 20%">'.count($etuidants).'</th>
                    <th align="right" colspan="2">'.trans("text_me.nbreParticipant").'</th>
                </tr>';
        $html .='</table>';
        $html .='<div style="page-break-after: always"></div>';
        $html .=''.$this->getallPv($id,$profil,$semestre,$groupe,$salle,count($etuidants));
        $html .='<div style="page-break-after: always"></div>';
        return $html;

    }
    public function getalletudiantsListeEMRGListe($id,$profil,$semestre,$groupe,$salle)
    {
        $titre = trans('text_me.liste_emergement1').'<br>'.App\Models\Salle::find($salle)->libelle;
        $matiere=Matiere::find($id);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>';
        $ip=Profil::find($profil)->ref_niveau_etude_id;
        if ($ip==4 or $ip==5){
            $titre  .='<td align="center" style="width: 15%"><b>الرباعي</b></td>';
        }
        else{
         $titre .='<th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>';
        }

                    $titre .='<th align="right" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="right" style="width: 35%">'.Profil::find($profil)->libelle.'</td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';

        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $html .='<table style="width: 100%" border="1">
                <tr>
                    <th align="right" style="width: 75%">'.trans('text_me.nom_complet').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.numero').'</th>
                    <th align="right" style="width: 10%">'.trans('text_me.rang').'</th>
                </tr>';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etuidants=Etudiant::whereIn('id',App\Models\MatiereSalleEtudiant::where('matiere_id',$id)->where('annee_id',$id_annee)->where('groupe_id',$groupe)->where('salle_id',$salle)->get()->pluck("etudiant_id"))->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        //$etuidants=EtudMat::where('matiere_id',$id)->where('ref_semestre_id',1)->get();
        $cpt=1;
        foreach ($etuidants as $etuidant)
        {
            $html .='<tr>
                    <th align="right" style="width: 75%">';
            if (trim($etuidant->NOMF) == trim($etuidant->NOMA))
                $html .=' '.$etuidant->NOMF;
            else
                $html .=' '.$etuidant->NOMA .' '.$etuidant->NOMF;
            $html .=' </th>
                    <th align="right" style="width: 15%">'.$etuidant->NODOS.'</th> 
                    <th align="right" style="width: 10%">'.$cpt.'</th>
                </tr>';
            $cpt +=1;
        }
        $html .='
                <tr>
                    <th align="center" style="width: 20%">'.count($etuidants).'</th>
                    <th align="right" colspan="2">'.trans("text_me.nbreParticipant").'</th>
                </tr>';
        $html .='</table>';
        $html .='<div style="page-break-after: always"></div>';

        return $html;

    }
    function getallPv($id,$profil,$semestre,$groupe,$salle,$nbr){
        $html ='';
        $titre = trans('text_me.pv').'<br>'.App\Models\Salle::find($salle)->libelle;
        $matiere=Matiere::find($id);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="right" style="width: 35%">'.Profil::find($profil)->libelle.'</td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';
        //.
        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $html .='<table border="0" style="width: 100%">';
        $html .='<tr>
        <td  style="width: 80%" align="right">'.$nbr.'</td>
        <td  style="width: 20%" align="right"> عدد المشاركين</td>
        </tr>';
        $html .='<tr>
        <td  style="width: 80%" align="right">.........................................................................................................</td>
        <td  style="width: 20%" align="right"> عدد الحاضرين</td>
        </tr>';
        $html .='<tr><td></td><br><td></td></tr>';
        $html .='<tr>
        <td  style="width: 80%" align="right">.........................................................................................................</td>
        <td  style="width: 20%" align="right"> عدد الغائبين</td>
        </tr>';
        $html .='</table><br><br>';
        $html .='<table border="0" style="width: 100%" >';
        $html .='<tr>';
        $html .='<td align="right">الملاحظة';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='</table><br><br>';
        $html .='<table border="0" style="width: 100%" >';
        $html .='<tr>';
        $html .='<td align="right">لجنة الرقابة';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td align="right">..............................................................................................................';
        $html .='</td>';
        $html .='</tr>';
        $html .='</table><br><br>';

        $html .='';
        return $html;
    }

    public function getalletudiantsAnonymes($id,$profil,$semestre,$groupe,$etape='1')
    {
        $typeControl =Etape::find($etape)->ref_type_controle->libelle_ar;
        $typ=Etape::find($etape)->ref_type_controle_id;
        $titre = trans('text_me.liste_col').'<br>'.$typeControl;
        $matiere=Matiere::find($id);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>';
        $ip=Profil::find($profil)->ref_niveau_etude_id;
        if ($ip==4 or $ip==5){
            $titre  .='<td align="center" style="width: 15%"><b>الرباعي</b></td>';
        }
        else{
            $titre .='<th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>';
        }
        $titre  .=' <th align="center" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td align="center"> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="center" style="width: 35%">'.Profil::find($profil)->libelle.'</td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';
        //.
        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $html .='<div align="right"> <center><table style="width: 100%" border="1" align="right">
                <tr>
                    <th align="center" style="width: 25%">'.trans('text_me.note_ar').'</th>
                    <th align="right" style="width: 40%">'.trans('text_me.nom').'</th>
                    <th align="center" style="width: 25%">'.trans('text_me.numero').'</th>
                 <th align="center" style="width: 10%">'.trans('text_me.rang').'</th>
                </tr>';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();

        //$etuidants=Anonymat::whereIn('etudiant_id',EtudMat::where('matiere_id',$id)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->get()->pluck("etudiant_id"))->orderBy('id')->get();
        if ($typ == 3) {
            $etuidants = Etudiant::whereIn('id', App\Models\RelevesNote::where('matiere_id', $id)->where('ref_semestre_id', $semestre)
                ->where('annee_id', $id_annee)->where('decision', 2)->get()->pluck("etudiant_id"))
                ->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                ->orderBy('NODOS', 'ASC')->get();
        }
        else
        {
            $groupe_libelle=RefGroupe::find($groupe)->libelle;
            //->where('profil_id',$profil)
            $etuidants=Etudiant::whereIn('id',EtudMat::where('matiere_id',$id)
                ->where('annee_id',$id_annee)->where('ref_semestre_id',$semestre)->get()
                ->pluck("etudiant_id"))->where('DECF','1')->where('groupe',$groupe_libelle)
                ->orderByRaw('LENGTH(NODOS)', 'ASC')
                ->orderBy('NODOS', 'ASC')->get();
        }
        // dd($etuidants);
        $i=0;
        foreach ($etuidants as $etuidant)
        {
            $i +=1;
            $html .='<tr>
                    <td align="right" style="width: 25%"><input type="checkbox"></td>
                    <td align="right" style="width: 40%">'.$etuidant->NOMF.'';
            if ($etuidant->NOMF =='_' OR $etuidant->NOMF =='')
            {
                $html .= $etuidant->NOMA.'';
            }
            $html .='</td>
                    <td align="center" style="width: 25%">'.$etuidant->NODOS.'</td>
                    <td align="center" style="width: 10%">'.$i.'</td>
                </tr>';
        }
        $html .='
                <tr>
                    <td align="center" style="width: 25%">'.count($etuidants).'</td>
                    <td align="right" colspan="">'.trans("text_me.nbreParticipant").'</td>
                </tr>
               ';
        $html .='</table><br> <br>
                   <table border="0" style="width: 100%"> 
                   <tr>
                    <td align="center" >امضاء الاستاذ</td>
                </tr></table>
                </center></div>';
        $html .='<div style="page-break-after: always"></div>';
        return $html;

    }

    public function getalletudiantsAnonymesCorrespond($id,$profil,$semestre,$groupe)
    {
        $titre = trans('text_me.liste_colAnonymat');
        $matiere=Matiere::find($id);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="right" style="width: 35%">'.$profil.'</td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';
        //.
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $html .='<div align="right"> <center><table style="width: 100%" border="1" align="right">
                <tr>
                    <th align="right" style="width: 30%">'.trans('text_me.note_ar').'</th>
                    
                    <th align="right" style="width: 30%">'.trans('text_me.anonymat_ar').'</th>
                    <th align="right" style="width: 40%">'.trans('text_me.numero').'</th>
                </tr>';
        $etuidants=Anonymat::whereIn('etudiant_id',EtudMat::where('matiere_id',$id)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->get()->pluck("etudiant_id"))->orderBy('id')->get();
        //$etuidants=EtudMat::where('matiere_id',$id)->where('ref_semestre_id',1)->get();
        foreach ($etuidants as $etuidant)
        {
            $html .='<tr>
                    <td align="right" style="width: 30%"><input type="checkbox"></td>
                    <td align="center" style="width: 30%">'.$etuidant->anonymat.'</td>
                    <td align="right" style="width: 40%">'.$etuidant->nodos.'</td>
                    
                </tr>';
        }
        $html .='
                <tr>
                    <td align="center" style="width: 30%">'.count($etuidants).'</td>
                    <td align="right" colspan="">'.trans("text_me.nbreParticipant").'</td>
                </tr>';
        $html .='</table></center></div>';
        $html .='<div style="page-break-after: always"></div>';
        return $html;

    }
    public  function genererSalles($profil,$groupe,$semestre,$etape,$choix){
        $salle=App\Models\Salle::where('etat1',0)->orderBy('ordre')->get()->first();

        $data=2;
        if ($salle) {
            if ($choix == 'all' or $choix == 'col'){
                $matieres=Matiere::where('profil_id', $profil)
                    ->where('ref_semestre_id', $semestre)->get();

                foreach ($matieres as $matiere) {
                    $data= $this->getGenererMatierExamenColl($matiere->id, $profil, $semestre, $groupe,$etape);
                }
            }
            else{
                $this->getGenererMatierExamen($choix, $profil, $semestre, $groupe);
            }

        }
        return  $data;
    }

    public function getGenererMatierExamenColl($matiere, $profil, $semestre, $groupe,$etape)
    {
        $type_controle=Etape::find($etape)->ref_type_controle_id;
        $prof = Profil::find($profil);
        $dels=App\Models\MatiereSalleEtudiant::where('matiere_id', $matiere)->where('profil_id', $profil)->where('groupe_id', $groupe)->get();
        foreach ($dels as $del)
        {
            $d=App\Models\MatiereSalleEtudiant::find($del->id);
            $d->delete();
        }

        $salless= App\Models\Salle::where('etat1',4)->orderBy('ordre')->get();
        foreach ($salless as $sal)
        {
            $s=App\Models\Salle::find($sal->id);
            $s->etat1=0;
            $s->save();
        }

        if ($prof->ref_niveau_etude_id==1)
        {
            //COMMENTER NON CONNUE
            //$this->getGenererMatierExamenCollPR($matiere, $profil, $semestre, $groupe,$etape);
        }
        $salle2= App\Models\Salle::where('etat1',0)->where('ordre','<>',1)->orderBy('ordre')->get()->first();
        $nbre_cand_att_sall=0;
        $etat_sall=1;
        $data=2;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        // dd($salle);
        $niveau = Profil::find($profil)->ref_niveau_etude_id;
        if ($salle2)
        {
            $data=1;
            //$type_controle=2;
            if ($type_controle==3)
            {
                /*if ($prof->ref_niveau_etude_id==1) {
                    $etuidants = Etudiant::whereIn('id', App\Models\RelevesNote::where('matiere_id', $matiere)->where('ref_semestre_id', $semestre)
                        ->where('annee_id', $id_annee)->where('ref_groupe_id', $groupe)->where('decision', 2)->get()->pluck("etudiant_id"))
                        ->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                        ->orderBy('NODOS', 'ASC')->get();
                }
                else{
					(function ($q) {
    $q->where('decision',2)->orWhere('decision', 0);
})->get()->pluck("etudiant_id"))
					*/
                $etuidants = Etudiant::whereIn('id', App\Models\RelevesNote::where('matiere_id', $matiere)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $id_annee)->where('decision', 2)->get()->pluck("etudiant_id"))->where('groupe', RefGroupe::find($groupe)->libelle)
                    ->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();
                //dd($etuidants);
                // }
            }
            else{
                $etuidants = Etudiant::whereIn('id', EtudMat::where('matiere_id', $matiere)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $id_annee)->get()->pluck("etudiant_id"))->where('groupe', RefGroupe::find($groupe)->libelle)
                    ->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();

            }
            foreach ($etuidants as $etuidant) {
                $salle1 = App\Models\Salle::where('etat1', 0)->where('ordre',1)->orderBy('ordre')->get()->first();
                $testing=1;
                $salle = App\Models\Salle::where('etat1', 0)->where('ordre','<>',1)->orderBy('ordre')->get()->first();

                if ($niveau == 1 or $niveau == 4){
                    //dd('l2'.$salle);

                    $test1 =EtudMat::where('annee_id',$id_annee)->where('etudiant_id',$etuidant->id)
                        ->where('ref_semestre_id',3)->orderBy('ref_groupe_id')->get();
                    if ($test1->count()>0){
                        $testing=0;
                        $mtsall = new App\Models\MatiereSalleEtudiant();
                        $mtsall->salle_id = $salle1->id;
                        $mtsall->matiere_id = $matiere;
                        $mtsall->profil_id = $profil;
                        $mtsall->groupe_id = $groupe;
                        $mtsall->annee_id = $id_annee;
                        $mtsall->etudiant_id = $etuidant->id;
                        $mtsall->save();
                    }


                }
                if ($niveau == 2){
                    //dd('l"'.$salle);
                    $test1 =EtudMat::where('annee_id',$id_annee)->where('etudiant_id',$etuidant->id)
                        ->where('ref_semestre_id',5)->orderBy('ref_groupe_id')->get();

                    //dd($test1);
                    if ($test1->count()>0){
                        //dd('&sall'.$salle1->id.'$matiere'.$matiere.'profil_id'.$profil.'groupe_id'.$groupe.'id_annee'.$id_annee.'$etuidant->id'.$etuidant->id);
                        $testing=0;
                        $mtsall = new App\Models\MatiereSalleEtudiant();
                        $mtsall->salle_id = $salle1->id;
                        $mtsall->matiere_id = $matiere;
                        $mtsall->profil_id = $profil;
                        $mtsall->groupe_id = $groupe;
                        $mtsall->annee_id = $id_annee;
                        $mtsall->etudiant_id = $etuidant->id;
                        $mtsall->save();
                    }

                }

                //dd('cl2'.$salle);

                if ($testing == 1) {
                    $credit=0;

                    $credit=$this->creditAtt($etuidant->id,$semestre,$id_annee);
                    //if ($etuidant->id == 7484 ) { dd($credit);  }

                    if ($credit == 30) {
                        $salle = App\Models\Salle::where('etat1', 0)->where('ordre','<>',1)->orderBy('ordre')->get()->first();
                        $nbre_cand_att_sall = count(App\Models\MatiereSalleEtudiant::where('salle_id', $salle->id)->where('matiere_id', $matiere)
                            ->where('profil_id', $profil)->where('groupe_id', $groupe)->get());
                        if (($nbre_cand_att_sall == $salle->capacite) or ($nbre_cand_att_sall > $salle->capacite)) {
                            $salle = App\Models\Salle::find($salle->id);
                            $salle->etat1 = 4;
                            $salle->save();
                            $etat_sall = 2;
                        }

                        $salle = App\Models\Salle::where('etat1', 0)->where('ordre','<>',1)->orderBy('ordre')->get()->first();
                        if ($profil==(51)){
                        $moySem1=$this->moyenne_semestreFn($etuidant->id,1,$groupe,$id_annee);
                        if ($moySem1>=1){
                            $mtsall = new App\Models\MatiereSalleEtudiant();
                            $mtsall->salle_id = $salle->id;
                            $mtsall->matiere_id = $matiere;
                            $mtsall->profil_id = $profil;
                            $mtsall->groupe_id = $groupe;
                            $mtsall->annee_id = $id_annee;
                            $mtsall->etudiant_id = $etuidant->id;
                            $mtsall->save();
                        }
                        }
                        else{
                        $mtsall = new App\Models\MatiereSalleEtudiant();
                        $mtsall->salle_id = $salle->id;
                        $mtsall->matiere_id = $matiere;
                        $mtsall->profil_id = $profil;
                        $mtsall->groupe_id = $groupe;
                        $mtsall->annee_id = $id_annee;
                        $mtsall->etudiant_id = $etuidant->id;
                        $mtsall->save();
                        }
                    }
                    //dd($credit);
                    if ($credit < 30) {
                        $mtsall = new App\Models\MatiereSalleEtudiant();
                        $mtsall->salle_id = $salle1->id;
                        $mtsall->matiere_id = $matiere;
                        $mtsall->profil_id = $profil;
                        $mtsall->groupe_id = $groupe;
                        $mtsall->annee_id = $id_annee;
                        $mtsall->etudiant_id = $etuidant->id;
                        $mtsall->save();
                    }

                }

            }

        }
        return  $data;
    }

    public function creditAtt($id,$semestre,$id_annee)
    {
        $credit=0;

        $etudiantMat = EtudMat::where('etudiant_id',$id)->where('annee_id',$id_annee)->where('ref_semestre_id',$semestre)->orderBy('ref_semestre_id')->get();
        foreach ($etudiantMat as $etudiant) {
            $matttt = Matiere::find($etudiant->matiere_id);


            if($matttt)
            {
                $credit += $etudiant->matiere->credit;
            }
            else{ //dd($matttt);
            }

        }
        return $credit;
    }
    public function getGenererMatierExamenCollPR($matiere, $profil, $semestre, $groupe,$etape)
    {
        $type_controle=Etape::find($etape)->ref_type_controle_id;
        $salle= App\Models\Salle::where('etat1',0)->where('ordre',1)->get()->first();
        $nbre_cand_att_sall=0;
        $etat_sall=1;
        $data=2;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        // dd($salle);
        if ($salle)
        {
            $data=1;
            if ($type_controle==3)
            {
                $etuidants = Etudiant::whereIn('id', App\Models\RelevesNote::where('matiere_id', $matiere)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $id_annee->where('decision', 2)->get()->pluck("etudiant_id")))
                    ->where('NODOS', '<', 18642)->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();
            }
            else {
                $etuidants = Etudiant::whereIn('id', EtudMat::where('matiere_id', $matiere)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $id_annee)->get()->pluck("etudiant_id"))->where('groupe', RefGroupe::find($groupe)->libelle)
                    ->where('NODOS', '<', 18642)->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();

            }
            foreach ($etuidants as $etuidant) {
                $salle = App\Models\Salle::where('etat1', 0)->where('ordre',1)->orderBy('ordre')->get()->first();
                if ($salle) {
                    // +=1;
                    //$dfg=$salle->id;
                    // $nbre_cand_att_sall = count(App\Models\MatiereSalleEtudiant::where('salle_id', $salle->id)->where('matiere_id', $matiere)->where('profil_id', $profil)->where('groupe_id', $groupe)->get());

                    /* if (($nbre_cand_att_sall == $salle->capacite) or ($nbre_cand_att_sall > $salle->capacite)) {
                         // dd($nbre_cand_att_sall);
                         $salle = App\Models\Salle::find($salle->id);
                         $salle->etat1 = 4;
                       //  $salle->save();
                         $etat_sall = 2;
                     }*/
                    $mtsall = new App\Models\MatiereSalleEtudiant();
                    $mtsall->salle_id = $salle->id;
                    $mtsall->matiere_id = $matiere;
                    $mtsall->profil_id = $profil;
                    $mtsall->groupe_id = $groupe;
                    $mtsall->annee_id = $id_annee;
                    $mtsall->etudiant_id = $etuidant->id;
                    $mtsall->save();
                }
            }
        }

    }
    public function imprimerListeEmergemet($profil,$groupe,$semestre,$etape,$choix)
    {
        $html = '';
        $profil_l = Profil::find($profil)->libelle;
        if ($choix == 'all' or $choix == 'col'){
            $matieres=Matiere::where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->get();
            foreach ($matieres as $matiere) {
                $html .= $this->getalletudiantsListeEMRG1($matiere->id, $profil, $semestre, $groupe,$etape);
            }
        }
        else{
            $html .= $this->getalletudiants($choix, $profil_l, $semestre, $groupe);
        }

        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_pr').'');
        PDF::SetSubject(''.trans('text_me.liste_pr').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }
    public function imprimerListeEmergemet1($profil,$groupe,$semestre,$etape,$choix)
    {
        $html = '';
        $profil_l = Profil::find($profil)->libelle;
        if ($choix == 'all' or $choix == 'col'){
            $matieres=Matiere::where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->get();
            foreach ($matieres as $matiere) {
                $html .= $this->getalletudiantsListeEMRG2($matiere->id, $profil, $semestre, $groupe);
            }
        }
        else{
            $html .= $this->getalletudiants($choix, $profil_l, $semestre, $groupe);
        }

        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_pr').'');
        PDF::SetSubject(''.trans('text_me.liste_pr').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }
    public function imprimerCollectNote($profil,$groupe,$semestre,$etape,$choix)
    {
        $html = '';
        $prof = Profil::find($profil);
        $profil_l=$prof->libelle;
        if ($choix == 'all' or $choix == 'col'){
            /*$matieres = MatieresProfilsEtape::where('profil_id', $profil)
                ->where('etape_id', $etape)->get();*/
            $typeSemestre=App\Models\RefTypeSemestre::where('type',1)->get()->first()->id;
            $sem=$semestre;
            /* if ($typeSemestre==1)
             {
                if ($prof->ref_niveau_etude_id==1)
                {
                    $sem=1;
                }
                 if ($prof->ref_niveau_etude_id==2)
                 {
                     $sem=3;
                 }
                 if ($prof->ref_niveau_etude_id==3)
                 {
                     $sem=5;
                 }
             }
             if ($typeSemestre==2)
             {
                 if ($prof->ref_niveau_etude_id==1)
                 {
                     $sem=2;
                 }
                 if ($prof->ref_niveau_etude_id==2)
                 {
                     $sem=4;
                 }
                 if ($prof->ref_niveau_etude_id==3)
                 {
                     $sem=6;
                 }
             }*/
            $matieres=Matiere::where('profil_id', $profil)
                ->where('ref_semestre_id', $sem)->get();
            foreach ($matieres as $matiere) {
                $html .= $this->getalletudiantsAnonymes($matiere->id, $profil, $semestre, $groupe,$etape);
            }
        }
        else{
            $html .= $this->getalletudiantsAnonymes($choix, $profil_l, $semestre, $groupe);
        }

        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_pr').'');
        PDF::SetSubject(''.trans('text_me.liste_pr').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getMajSemestre($profil,$groupe,$semestre,$etape)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $html = '';

        $s1=$s2=0;

        if ($semestre == 1 or $semestre==2) {$s1=1;$s2=2;}
        if ($semestre == 3 or $semestre==4) {$s1=3;$s2=4;}
        if ($semestre == 5 or $semestre==6) {$s1=5;$s2=6;}
        $niveauL = Profil::find($profil);
        $titre ='<p align="center">   الخمسة الأوائل س '.$semestre.'</p> 
<p align="center">'.$niveauL->libelle.' - '.RefGroupe::find($groupe)->libelle.'</p>';

        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html =$entete;
        $etudiants = App\Models\MoyennesSemestre::where('profil_id',$profil)
            ->where('decision','<>',0)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('annee_id',$id_annee)->orderBy('note', 'DESC')->get();
        $html .='<table style="width: 100%;" border="1">
                    <tr>
                    <td style="width: 15%" align="center">'.trans("text_me.note").'</td>
                     <td style="width: 20%;" align="center">'.trans("text_me.nni").'</td>
                     
                     <td style="width: 46%" align="right">'.trans("text_me.nom").'</td>
                     <td style="width: 12%" align="right">'.trans("text_me.nodos").'</td>
                    <td style="width: 7%" align="right">'.trans("text_me.rang").'</td>
                    </tr>';
        $rang=0;
        foreach ($etudiants as $noteSem)
        {
            $rang +=1;
            if ($rang<6)
            {
                $etudiant =   Etudiant::find($noteSem->etudiant_id);
                $html .='<tr>
                    <td style="width: 15%" align="center">'.$noteSem->note.'</td>
                     <td style="width: 20%;" align="center">'.$etudiant->NNI.'</td>';
                $html .='<td style="width: 46%" align="right">';
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
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.releve_note').'');
        PDF::SetSubject(''.trans('text_me.releve_note').'');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A5');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getbultin15($id_etudiant,$semestre)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $libAnne=Annee::find($id_annee)->libelle;
        $etudiant=Etudiant::find($id_etudiant);
        $etudiant_mat=EtudMat::where('etudiant_id',$id_etudiant)
            ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->first();
        $profil=$etudiant_mat->profil_id;
        $groupe=$etudiant->groupe;
        $html .='<table border="0" style="width: 100%;"><tr><td style="width: 29%;"><b>'.trans("text_me.anne_univ").' '.$libAnne .'</b></td><td align="right" style="width: 70%;"><b >'.trans("text_me.iseri").'</b></td></tr></table><hr>';
        $objet =new editionController();
        $etudiant1 =Etudiant::find($id_etudiant);
        $html .=$objet->infosEtudiant($etudiant1);
       $html .= $this->getBultin($id_etudiant,$profil,$groupe,$semestre,1,$id_annee);
        /*$html .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';
        $niveau = Profil::find($profil)->ref_niveau_etude_id;
        if ($niveau > 3)
            $html .= '' . trans("text_me.MNS");
        else
            $html .= '' . trans("text_me.PC");
        $html .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';*/
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.releve_note').'');
        PDF::SetSubject(''.trans('text_me.releve_note').'');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A5');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }
    public function getBultinImpressionCollect($profil,$groupe,$semestre,$etape)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();

        $etudiants = Etudiant::whereIn('id',EtudMat::where('profil_id',$profil)
            ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->where('groupe',RefGroupe::find($groupe)->libelle)
            ->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        //dd($etudiants->pluck("NODOS"));
        foreach ($etudiants as $etudiant)
        {
            $html .='<table border="0" style="width: 100%;"><tr><td style="width: 29%;"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td><td align="right" style="width: 70%;"><b >'.trans("text_me.iseri").'</b></td></tr></table><hr>';
            $objet =new editionController();
            $etudiant1 =Etudiant::find($etudiant->id);
            $html .=$objet->infosEtudiant($etudiant1);
            $html .= $this->getBultin($etudiant->id,$profil,$groupe,$semestre,$etape,$id_annee);
            $html .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';
            $niveau = Profil::find($profil)->ref_niveau_etude_id;
            if ($niveau > 3)
                $html .= '' . trans("text_me.MNS");
            else
                $html .= '' . trans("text_me.PC");
            $html .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';
            $html .='<div style="page-break-after: always"></div>';
        }
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.releve_note').'');
        PDF::SetSubject(''.trans('text_me.releve_note').'');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A5');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getBultinImpressionIndiRel($nodos,$semestre,$id_annee)
    {
        $html = '';
        $profil=0;$groupe=0;$etape=3;
        $e=Etudiant::where('NODOS',$nodos)->orderBy('id','DESC')->get()->first()->id;
        $etd=EtudMat::where('ref_semestre_id',$semestre)->where('etudiant_id',$e)->where('annee_id',$id_annee)->get()->first();
        $rel=App\Models\RelevesNote::where('ref_semestre_id',$semestre)->where('etudiant_id',$e)->where('annee_id',$id_annee)->get()->first();
        if ($etd){
            $profil=$etd->profil_id;
            $groupe=$rel->ref_groupe_id;
            $etape=3;
        }
       // dd($etd);

        $etudiants = Etudiant::whereIn('id',EtudMat::where('ref_semestre_id',$semestre)->where('etudiant_id',$e)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        $an=Annee::find($id_annee);
        foreach ($etudiants as $etudiant)
        {
            $html .='<table border="0" style="width: 100%;"><tr><td style="width: 29%;"><b>'.trans("text_me.anne_univ").' '.$an->annee.'</b></td><td align="right" style="width: 70%;"><b >'.trans("text_me.iseri").'</b></td></tr></table><hr>';
            $objet =new editionController();
            $etudiant1 =Etudiant::find($etudiant->id);
            $html .=$objet->infosEtudiant($etudiant1,$profil);
            $html .= $this->getBultin($etudiant->id,$profil,$groupe,$semestre,$etape,$id_annee);
            $html .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';
            $niveau = Profil::find($profil)->ref_niveau_etude_id;
            if ($niveau > 3)
                $html .= '' . trans("text_me.MNS");
            else
                $html .= '' . trans("text_me.PC");
            $html .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';
            $html .='<div style="page-break-after: always"></div>';
        }
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.releve_note').'');
        PDF::SetSubject(''.trans('text_me.releve_note').'');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A5');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getBultinImpressionCollect11($profil,$groupe,$semestre,$etape)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $s1=$s2=0;

        if ($semestre == 1 or $semestre==2) {$s1=1;$s2=2;}
        if ($semestre == 3 or $semestre==4) {$s1=3;$s2=4;}
        if ($semestre == 5 or $semestre==6) {$s1=5;$s2=6;}
//fixer les limite etudiants
        $etudiants = Etudiant::whereIn('id',EtudMat::where('profil_id',$profil)
            ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->where('groupe',RefGroupe::find($groupe)->libelle)->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        //the first 100 ->where('NODOS','>','10860')->where('NODOS','<','10692')
        foreach ($etudiants as $etudiant)
        {
           // if ($etudiant->NODOS > '18692' and $etudiant->NODOS < '19383') {
            if ( $etudiant->NODOS < '300000') {
            $html .='<table border="0" style="width: 100%;"><tr><td style="width: 29%;"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td><td align="right" style="width: 70%;"><b >'.trans("text_me.iseri").'</b></td></tr></table><hr>';
            $objet =new editionController();
            $etudiant1 =Etudiant::find($etudiant->id);
            $html .=$objet->infosEtudiant($etudiant1);
            $html .='<table border="0" style="width: 100%;"><tr> ';
            $html .= '<td style="width: 50%;">'.$this->getBultin($etudiant->id,$profil,$groupe,$s1,$etape,$id_annee).'</td>';
            $html .= '<td style="width: 50%;">'.$this->getBultin($etudiant->id,$profil,$groupe,$s2,$etape,$id_annee).'</td>';
            $html .='</tr>
            ';
            $moySem2=$moySem1=0;
            $moySem1=$this->moyenne_semestre($etudiant->id,$s1,$groupe,$id_annee);
            $moySem2=$this->moyenne_semestre($etudiant->id,$s2,$groupe,$id_annee);
            $html .='<tr><td rowspan="2" ><b> ' . trans("text_me.moyenGen") . ' : '.number_format(($moySem1+$moySem2)/2,2).' / 20</b></td></tr>
            </table>';
            $html .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';
            $niveau = Profil::find($profil)->ref_niveau_etude_id;
            if ($niveau > 3)
                $html .= '' . trans("text_me.MNS");
            else
                $html .= '' . trans("text_me.PC");
            $html .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>
            <div style="page-break-after: always"></div>';
        }
    }
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.releve_note').'');
        PDF::SetSubject(''.trans('text_me.releve_note').'');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getBultinImpressionCollect11AN($profil,$groupe,$semestre,$etape)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $s1=$s2=0;

        if ($semestre == 1 or $semestre==2) {$s1=1;$s2=2;}
        if ($semestre == 3 or $semestre==4) {$s1=3;$s2=4;}
        if ($semestre == 5 or $semestre==6) {$s1=5;$s2=6;}
        $niveauL = Profil::find($profil);
        $titre ='<p align="center">لائحة الناجحين</p> <p align="center">'.$niveauL->libelle.'</p>';
        $titre1 ='<p align="center">لائحة غير الناجحين</p> <p align="center">'.$niveauL->libelle.'</p>';

        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html =$entete;
        $entete1 = $this->enteteServiceExamenen($titre1, 'L');
        $html1 =$entete1;
        // $html1 ='<table border="0" style="width: 100%;"><tr><td style="width: 29%;"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td><td align="right" style="width: 40%;"><b >'.$niveauL->libelle.'</b></td><td align="right" style="width: 30%;"><b >'.trans("text_me.iseri").'</b></td></tr></table><hr>';
        $html1 .='<p align="center"> </p>';

        $etudiants = Etudiant::whereIn('id',EtudMat::where('profil_id',$profil)
            ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->where('groupe',RefGroupe::find($groupe)->libelle)
            ->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        $nbrR=$nbraD=0;
        $html .='<table border="1" style="width: 100%;"><tr> ';
        $html .= '<td style="width: 10%;"  align="center">الملاحظة</td>';
        $html .= '<td style="width: 10%;"  align="center">معدل العام</td>';
        $html .= '<td style="width: 10%;"  align="center">م . الارصدة</td>';
        $html .= '<td style="width: 10%;"  align="center"> رصيد س '.$s1.'</td>';
        $html .= '<td style="width: 10%;"  align="center">  رصيد س '.$s2.'</td>';
        $html .= '<td style="width: 40%;"  align="center">الاسم</td>';
        $html .= '<td style="width: 10%;"  align="center">الرقم</td>';
        $html .='</tr>';

        $html1 .='<table border="1" style="width: 100%;"><tr> ';
        $html1 .= '<td style="width: 10%;" align="center">الملاحظة</td>';
        $html1 .= '<td style="width: 10%;" align="center">معدل العام</td>';
        $html1 .= '<td style="width: 10%;" align="center">م. الارصدة</td>';
        $html1 .= '<td style="width: 10%;" align="center">رصيد س '.$s1.'</td>';
        $html1 .= '<td style="width: 10%;" align="center"> رصيد س '.$s2.'</td>';
        $html1 .= '<td style="width: 40%;"  align="center">الاسم</td>';
        $html1 .= '<td style="width: 10%;"  align="center">الرقم</td>';
        $html1 .='</tr>';
        $niveau = Profil::find($profil)->ref_niveau_etude_id;
        foreach ($etudiants as $etudiant)
        {
            $valide1=$valide2=1;

            $credi1=$this->getBultin11($etudiant->id,$profil,$groupe,$s1,$etape,$id_annee);
            $credi2=$this->getBultin11($etudiant->id,$profil,$groupe,$s2,$etape,$id_annee);
            $moySem2=$moySem1=0;
            $moySem1=$this->moyenne_semestre($etudiant->id,$s1,$groupe,$id_annee);
            $moySem2=$this->moyenne_semestre($etudiant->id,$s2,$groupe,$id_annee);
            $moyenFin=0;
            $credif=0;
            $credif=$credi1+$credi2;

            $moyenFin= number_format(($moySem1+$moySem2)/2,2);
            if ($niveau==2)
            {
                $valide1=$credi1=$this->getBultin11validL2($etudiant->id,$profil,$groupe,1,$etape,$id_annee);
                $valide2=$credi1=$this->getBultin11validL2($etudiant->id,$profil,$groupe,2,$etape,$id_annee);
            }
            else{
                $valide1=$valide2=1;
            }
            if ($moyenFin >= 10 and $credif>=39 and $valide1==1 and $valide2==1)
            {

                $nbraD +=1;
                $nom='';
                if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
                    $nom .=' '.$etudiant->NOMF;
                else
                    $nom .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
                $html .= '<tr><td style="width: 10%;">ناجح</td>';
                $html .= '<td style="width: 10%;" align="right">'.$moyenFin.'</td>';
                $html .= '<td style="width: 10%;" align="right">'.$credif.'</td>';
                $html .= '<td style="width: 10%;" align="right">'.$this->getBultin11($etudiant->id,$profil,$groupe,$s1,$etape,$id_annee).'</td>';
                $html .= '<td style="width: 10%;" align="right">'.$this->getBultin11($etudiant->id,$profil,$groupe,$s2,$etape,$id_annee).'</td>';
                $html .= '<td style="width: 40%;" align="right">'.$nom.'</td>';
                $html .= '<td style="width: 10%;" align="right">'.$etudiant->NODOS.'</td>';
                $html .='</tr>
            ';
            }
            else{
                $nbrR +=1;
                $nom='';
                if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
                    $nom .=' '.$etudiant->NOMF;
                else
                    $nom .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
                $html1 .= '<tr><td style="width: 10%;">غير ناجح</td>';
                $html1 .= '<td style="width: 10%;">'.$moyenFin.'</td>';
                $html1 .= '<td style="width: 10%;">'.$credif.'</td>';
                $html1 .= '<td style="width: 10%;">'.$this->getBultin11($etudiant->id,$profil,$groupe,$s1,$etape,$id_annee).'</td>';
                $html1 .= '<td style="width: 10%;">'.$this->getBultin11($etudiant->id,$profil,$groupe,$s2,$etape,$id_annee).'</td>';
                $html1 .= '<td style="width: 40%;">'.$nom.'</td>';
                $html1 .= '<td style="width: 10%;">'.$etudiant->NODOS.'</td>';
                $html1 .='</tr>
            ';
            }
        }
        $html .= '<tr><td colspan="5">'.$nbraD.'</td><td>عدد الناجحين</td>';
        $html .='</tr></table>';
        $niveau = Profil::find($profil)->ref_niveau_etude_id;
        $html .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';
        if ($niveau > 3)
            $html .= '' . trans("text_me.MNS");
        else
            $html .= '' . trans("text_me.PC");
        $html .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';
        $html1.= '<tr><td colspan="5">'.$nbrR.'</td><td>عدد غير الناجحين</td>';
        $html1 .='</tr></table>';

        $html1 .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';

        if ($niveau > 3)
            $html1 .= '' . trans("text_me.MNS");
        else
            $html1 .= '' . trans("text_me.PC");
        $html1 .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';

        $htmlg=$html;
        $htmlg .='<div style="page-break-after: always"></div>';
        $htmlg .=$html1;
        PDF::SetAuthor('unisof');
        PDF::SetTitle('محضر الناجحين');
        PDF::SetSubject('محضر الناجحين');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($htmlg, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getBultinImpressionCollect11AN2($profil,$groupe,$semestre,$etape)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $s1=$s2=0;

        if ($semestre == 1 or $semestre==2) {$s1=1;$s2=2;}
        if ($semestre == 3 or $semestre==4) {$s1=3;$s2=4;}
        if ($semestre == 5 or $semestre==6) {$s1=5;$s2=6;}
        $niveauL = Profil::find($profil);
        $titrest ='<p align="center">احصائيات </p> <p align="center">'.$niveauL->libelle.' - '.RefGroupe::find($groupe)->libelle.'</p>';
        $titre ='<p align="center">لائحة الناجحين</p> <p align="center">'.$niveauL->libelle.' - '.RefGroupe::find($groupe)->libelle.'</p>';
        $titre1 ='<p align="center">لائحة غير الناجحين</p> <p align="center">'.$niveauL->libelle.' - '.RefGroupe::find($groupe)->libelle.'</p>';

        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html =$entete;
        $entete1 = $this->enteteServiceExamenen($titre1, 'L');
        $html1 =$entete1;
        // $html1 ='<table border="0" style="width: 100%;"><tr><td style="width: 29%;"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td><td align="right" style="width: 40%;"><b >'.$niveauL->libelle.'</b></td><td align="right" style="width: 30%;"><b >'.trans("text_me.iseri").'</b></td></tr></table><hr>';
        $html1 .='<p align="center"> </p>';

        $etudiants = Etudiant::whereIn('id',EtudMat::where('profil_id',$profil)
            ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->where('groupe',RefGroupe::find($groupe)->libelle)
            ->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
            ->orderBy('NODOS', 'ASC')->get();
        $nbrR=$nbraD=0;
        $nbretotal= $nbrNongenre=$nbfilles=$nbgarsons=$nbrpresence=$nbnonparticipents=0;
        $html .='<table border="1" style="width: 100%;"><tr> ';
        $html .= '<td style="width: 30%;"  align="center">الملاحظة</td>';
        $html .= '<td style="width: 50%;"  align="center">الاسم</td>';
        $html .= '<td style="width: 20%;"  align="center">الرقم</td>';
        $html .='</tr>';

        $html1 .='<table border="1" style="width: 100%;"><tr> ';
        $html1 .= '<td style="width: 30%;" align="center">الملاحظة</td>';
        $html1 .= '<td style="width: 50%;"  align="center">الاسم</td>';
        $html1 .= '<td style="width: 20%;"  align="center">الرقم</td>';
        $html1 .='</tr>';
        $niveau = Profil::find($profil)->ref_niveau_etude_id;
        foreach ($etudiants as $etudiant)
        {
            $valide1=$valide2=1;
            $etdd=Etudiant::find($etudiant->id);
            if ($etdd->SEXE=='F')
            {
                $nbfilles +=1;
            }
            elseif ($etdd->SEXE=='M')
            {

                $nbgarsons +=1;
            }

            elseif ($etdd->SEXE=='ذكر')
            {
                $nbgarsons +=1;
            }
            elseif ($etdd->SEXE=='انثى' or $etdd->SEXE=='أنثى'  )
            {
                $nbfilles +=1;
            }

            else{
                $nbrNongenre +=1;
            }
            $nbretotal +=1;
            $credi1=$this->getBultin11($etudiant->id,$profil,$groupe,$s1,$etape,$id_annee);
            $credi2=$this->getBultin11($etudiant->id,$profil,$groupe,$s2,$etape,$id_annee);
            $moySem2=$moySem1=0;
            $moySem1=$this->moyenne_semestre($etudiant->id,$s1,$groupe,$id_annee);
            $moySem2=$this->moyenne_semestre($etudiant->id,$s2,$groupe,$id_annee);
            $moyenFin=0;
            $credif=0;
            $credif=$credi1+$credi2;

            $moyenFin= number_format(($moySem1+$moySem2)/2,2);
            if ($niveau==2)
            {
                $valide1=$credi1=$this->getBultin11validL2($etudiant->id,$profil,$groupe,1,$etape,$id_annee);
                $valide2=$credi1=$this->getBultin11validL2($etudiant->id,$profil,$groupe,2,$etape,$id_annee);
            }
            else{
                $valide1=$valide2=1;
            }
            if ($moyenFin >= 10 and $credif>=39 and $valide1==1 and $valide2==1)
            {

                $nbraD +=1;
                $nom='';
                if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
                    $nom .=' '.$etudiant->NOMF;
                else
                    $nom .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
                $html .= '<tr><td style="width: 30%;"></td>';

                $html .= '<td style="width: 50%;" align="right">'.$nom.'</td>';
                $html .= '<td style="width: 20%;" align="right">'.$etudiant->NODOS.'</td>';
                $html .='</tr>
            ';
            }
            else{
                if ($moyenFin==0)
                {
                    $nbnonparticipents +=1;
                }
                $nbrR +=1;
                $nom='';
                if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
                    $nom .=' '.$etudiant->NOMF;
                else
                    $nom .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
                $html1 .= '<tr><td style="width: 30%;"></td>';
                $html1 .= '<td style="width: 50%;" align="right">'.$nom.'</td>';
                $html1 .= '<td style="width: 20%;" align="right">'.$etudiant->NODOS.'</td>';
                $html1 .='</tr>
            ';
            }
        }
        $html .= '<tr><td colspan="5">'.$nbraD.'</td><td>عدد الناجحين</td>';
        $html .='</tr></table>';
        $niveau = Profil::find($profil)->ref_niveau_etude_id;
        $html .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';
        if ($niveau>3)
            $html .= '' . trans("text_me.MNS");
        else
            $html .= '' . trans("text_me.PC");
        $html .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';
        $html1.= '<tr><td colspan="5">'.$nbrR.'</td><td>عدد غير الناجحين</td>';
        $html1 .='</tr></table>';

        $html1 .='<table border="0" style="width: 100%"><tr>
                     <td align="center" style="width: 33%">' . trans("text_me.sg") . '</td>
                     <td align="center" style="width: 33%">';

        if ($niveau > 3)
            $html1 .= '' . trans("text_me.MNS");
        else
            $html1 .= '' . trans("text_me.PC");
        $html1 .= ' </td><td align="center" style="width: 34%">' . trans("text_me.sex") . '</td>
            </tr></table>';

        $htmlg=$html;
        $htmlg .='<div style="page-break-after: always"></div>';
        $htmlg .=$html1;
        $htmlg .='<div style="page-break-after: always"></div>';
        $htmlg .=$this->functionallstatiatique($nbretotal,$nbraD,$nbrR,$nbfilles,$nbgarsons,$nbrNongenre,$nbnonparticipents,$titrest);
        PDF::SetAuthor('unisof');
        PDF::SetTitle('محضر الناجحين');
        PDF::SetSubject('محضر الناجحين');
        PDF::SetMargins(2, 2, 2);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 9);
        PDF::writeHTML($htmlg, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function functionallstatiatique($nbretotal,$nbraD,$nbrR,$nbfilles,$nbgarsons,$nbrNongenre,$nbnonparticipents,$titrest)
    {
        //$html='';

        $html =$titrest;
        $html.='<table border="1">';
        $html.='<tr>';
        $html.='<td align="center">عدد المسجلين</td>';
        $html.='<td align="center">الجنس غير محدد</td>';
        $html.='<td align="center">عدد الاناث</td>';
        $html.='<td align="center">عدد الذكور</td>';
        $html.='<td align="center">عدد الغائبين</td>';
        $html.='<td align="center">عدد الراسبين</td>';
        $html.='<td align="center">عدد الناجحين</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<td align="center">'.$nbretotal.'</td>';
        $html.='<td align="center">'.$nbrNongenre.'</td>';
        $html.='<td align="center">'.$nbfilles.'</td>';
        $html.='<td align="center">'.$nbgarsons.'</td>';
        $html.='<td align="center">'.$nbnonparticipents.'</td>';
        $html.='<td align="center">'.$nbrR.'</td>';
        $html.='<td align="center">'.$nbraD.'</td>';
        $html.='</tr>';
        $html.='</table>';
        return $html;
    }
    public function getBultin($id,$profil,$groupe,$semestre,$etape,$id_annee)
    {
        $moyen_sem=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
        $etudiant =Etudiant::find($id);
        $html ='';
       // dd('j');
        $releves66as = App\Models\RelevesNote::where('profil_id',$profil)
            ->where('ref_semestre_id', $semestre)->where('note','>', 1)->where('etudiant_id', $id)
            ->where('annee_id', $id_annee)->orderBy('matiere_id','DESC')->orderBy('id','DESC')->get();
        if ($releves66as->count()>0){
            //->orderBy('matiere_id','DESC')->orderBy('id','DESC')
        $rupM=0;
            $niveau=Profil::find($profil);
            $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
            //dd($ref_niveau_etude_id);
            if ($ref_niveau_etude_id !=15 and $semestre==8){
        foreach ($releves66as as $releves66) {
            if ($rupM != $releves66->matiere_id) {
                //$html .='mat'.$releves66->matiere_id .' id'.$releves66->id.'<br>' ;
                $rupM = $releves66->matiere_id;
            } else {
                $f = App\Models\RelevesNote::find($releves66->id);
                $f->delete();
            }
        }
            }
        }
        $html .='<table border="0" style="width: 100%">
                <tr>
                    <td style="width: 100%" align="center"><b>'.trans("text_me.releve_note").' '.RefSemestre::find($semestre)->libelle.'</b></td>
                </tr>
                <tr>
                    <td style="width: 100%" align="center"></td>
                </tr>
                </table><br><br>';
        $releves = App\Models\RelevesNote::where('profil_id',$profil)
            ->where('ref_semestre_id', $semestre)->where('note','>', 1)->where('etudiant_id', $id)
            ->where('annee_id', $id_annee)->orderBy('modulle_id','DESC')->get();

        $moyenne_module=0;
        $html1 ='';

        if ($releves->count()>0) {
            $html .= '<table border="1" style="width: 100%">
                <tr>
                    <th style="width: 10%" align="right"><b>' . trans("text_me.decision") . '</b></th>
                    <th style="width: 7%" align="right"><b>' . trans("text_me.notef") . '</b></th>
                    <th style="width: 10%" align="right"><b>' . trans("text_me.rt") . '</b></th>
                    <th style="width: 13%" align="right"><b>' . trans("text_me.cf") . '</b></th>
                    <th style="width: 12%" align="right"><b>' . trans("text_me.cc") . '</b></th>
                    <th style="width: 8%" align="right"><b>' . trans("text_me.credit") . '</b></th>
                    <th style="width: 40%" align="right"><b>' . trans("text_me.matiere1") . '</b></th>

                </tr>';
            $rupture_module = $releves->first()->modulle_id;
            $moyenne_module = $this->moyenne_module($id, $semestre, $groupe, $id_annee, $rupture_module);
            $validM = 1;
            $vsem = 1;
            $noteModule = $releves->first()->noteModule;
            //dd($releves->first()->modulle_id);
                $libelle_modulle = $releves->first()->modulle->libelle;
            foreach ($releves as $releve) {
                if ($rupture_module != $releve->modulle_id) {
                    $moyenne_module = $this->moyenne_module($id, $semestre, $groupe, $id_annee, $rupture_module);
                    $html .= '<tr><td style="width: 10%" align="right"><b>';
                    if ($validM == 2) {
                        $html .= '' . trans("text_me.rattrapage");
                    }
                    if ($validM == 0) {
                        $html .= '' . trans("text_me.novalidee");
                    }
                    if ($validM == 1) {
                        $html .= '' . trans("text_me.validee");
                    }

                    $html .= ' </b></td>
                    <td colspan="5" align="center"><b>' . number_format($noteModule, 2) . '&nbsp;&nbsp;</b></td>
                    <td style="width: 40%;background-color: #5a5c69" align="center"><b>' . trans("text_me.modulle") . ' : ' . $libelle_modulle . ' </b></td>

                </tr>';
                    $html .= $html1;
                    $html1 = '';
                    $rupture_module = $releve->modulle_id;
                    $noteModule = $releve->noteModule;
                    $libelle_modulle = $releve->modulle->libelle;
                    $validM = 1;
                }
                $html1 .= '<tr><td style="width: 10%" align="right"><b>';
                if ($releve->decision == 2) {
                    $html1 .= '' . trans("text_me.novalide");
                    // $html1 .= '' . trans("text_me.rattrapage");
                    //$validM = 2;
                    $vsem = 0;
                    $validM = 0;
                }
                if ($releve->decision == 0) {
                    $html1 .= '' . trans("text_me.novalide");
                    $validM = 0;
                    $vsem = 0;
                }
                if ($releve->decision == 1 or $releve->decision == 11) {
                    $html1 .= '' . trans("text_me.valide");
                }
                $rtnt = $releve->note_rt;
                if ($releve->note_rt == -1 or $releve->note_rt == -3) {
                    $rtnt = '';
                }
                $mttt = Matiere::find($releve->matiere_id);
                //$mttt=Matiere::find($releve->matiere_id);
                $rnote_exam=$releve->note_exam;
                $rnote_dev=$releve->note_dev;
if ($releve->note_exam == -1 or $releve->note_exam==-3){  $rnote_exam=''; }
if ($releve->note_dev == -1 or $releve->note_dev ==-3){ $rnote_dev =''; }
                if ($releve->note_exam == -1 or $releve->note_exam==-3){  $rnote_exam=''; }
                $html1 .= ' </b></td>
                    <td style="width: 7%" align="center"><b>' . $releve->note . '</b></td>
                    <td style="width: 10%" align="right"><b>' . $rtnt . '</b></td>
                    <td style="width: 13%" align="center"><b>' . $rnote_exam . '</b></td>
                    <td style="width: 12%" align="center"><b>' . $rnote_dev . '</b></td>
                    <td style="width: 8%" align="center"><b>';
                if ($mttt) {
                    $html1 .= $releve->matiere->coaf;
                }

                $html1 .= '</b></td>
                    <td style="width: 40%" align="right"><b>';
                if ($mttt) {
                    $html1 .= '' . $releve->matiere->libelle . '';
                }
                $html1 .= '</b></td>

                </tr>';
            }

            $html .= '<tr><td style="width: 10%" align="right"><b>';
            if ($validM == 2) {
                // $html .= '' . trans("text_me.rattrapage");
                $html .= '' . trans("text_me.novalidee");
            }
            if ($validM == 0) {
                $html .= '' . trans("text_me.novalidee");
            }
            if ($validM == 1) {
                $html .= '' . trans("text_me.validee");
            }

            $html .= ' </b></td>
                    <td colspan="5" align="center"><b>' . number_format($noteModule, 2) . '&nbsp;&nbsp;</b></td>
                    <td style="width: 40%;background-color: #5a5c69" align="center"><b>' . trans("text_me.modulle") . ' : ' . $libelle_modulle . ' </b></td>

                </tr>';
            $html .= $html1;
            $html .= '<tr><td style="width: 10%" align="right"><b>';

            if ($moyen_sem >= 10 && $vsem == 1) {
                $html .= '' . trans("text_me.valide");
            } else {
                // $html .= '' . trans("text_me.rattrapage");
                $html .= '' . trans("text_me.novalidee");
            }

            $html .= ' </b></td>
                    <td style="width: 7%" align="center"><b>' . number_format($moyen_sem, 2) . '</b></td>
                    <td colspan="4" align="right"></td>
                    <td style="width: 40%" align="right"><b>' . trans("text_me.moyenneSem") . '</b></td>

                </tr>';
            $niveau = Profil::find($profil)->ref_niveau_etude_id;
            $html .= ' </table><br><br>

 ';
        }
        return $html;
    }

    public function getBultin11validL2($id,$profil,$groupe,$semestre,$etape,$id_annee)
    {
        $credits=0;
        $releves = App\Models\RelevesNote::where('ref_semestre_id', $semestre)->where('etudiant_id', $id)
            ->where('annee_id', $id_annee)->orderBy('modulle_id','DESC')->get();
        $valide =1;
        foreach ($releves as $releve) {
            if ($releve->decision == 1 or $releve->decision == 11) {


            }
            else{
                $valide=0;
            }

        }
        return $valide;
    }
    public function getBultin11($id,$profil,$groupe,$semestre,$etape,$id_annee)
    {
        $credits=0;
        $releves = App\Models\RelevesNote::where('profil_id',$profil)
            ->where('ref_semestre_id', $semestre)->where('etudiant_id', $id)
            ->where('annee_id', $id_annee)->orderBy('modulle_id','DESC')->get();
        $html1 ='';
        foreach ($releves as $releve) {
            if ($releve->decision == 1 or $releve->decision == 11) {
                $matiere=Matiere::find($releve->matiere_id);
                if ($matiere)
                {
                    if ($matiere->credit!=0 and $matiere->credit!= null)
                    {
                        $credits +=$matiere->credit;
                    }
                    else
                    {
                        $credits +=$matiere->coaf;
                    }
                }


            }


        }
        return $credits;
    }
    public function imprimerPVNotes($profil,$groupe,$semestre,$etape,$choix)
    {
        $html = '';
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        if ($choix == 1)
        {
            $html .=''. $this->getPVmatieres($profil,$groupe,$semestre,$etape,$choix,$id_annee);
        }
        if ($choix == 3)
        {
            $html .=''.$this->entetPv($profil,$groupe,$semestre,$etape,$choix,$id_annee);
            $modules = App\Models\Modulle::where('profil_id', $profil)->where('ref_semestre_id', $semestre)->orderBy('id')->get();
            //dd($matieres);
            $html .='<table style="width:100%" border="1" ><tr>';
            $html .='<td align="center" rowspan="3" style="width: 6%">الملاحظة</td>';
            $html .='<td align="center" rowspan="3" style="width: 6%">م.العام</td>';
            $cpt=0;
            $cptMOD=0;

            foreach ($modules as $module)
            {

                $nbmatieresMod=Matiere::where('modulle_id',$module->id)->get();
                if ($nbmatieresMod->count()>0){
                    $cptMOD +=1;
                       }

            }
            $largCol=68/$cptMOD;
            foreach ($modules as $module)
            {

                $nbmatieresMod=Matiere::where('modulle_id',$module->id)->get();
                if ($nbmatieresMod->count()>0){
                    $cpt=$nbmatieresMod->count()*3;
                    $html .='<td  align="center" colspan="'.$cpt.'" style="width: '.$largCol.'%;"><b> '.$module->libelle_ar.'</b></td>';
                }

            }
            $html .='<td align="center" rowspan="3" colspan="2" style="width: 20%;"></td>';
            $html .='</tr>';
            //Ligne des matieres
            $html .='<tr>';

            $cptmatiers=0;
            foreach ($modules as $module)
            {
                $matiers=Matiere::where('modulle_id',$module->id)->orderBy('id')->get();
                if ($matiers->count()>0)
                {
                    $cptmatiers=$largCol/$matiers->count();
                    foreach ($matiers as $matier)
                    {
                        $html .='<td align="center" colspan="3" style="width: '.$cptmatiers.'%;"><b> '.$matier->libelle_ar.'</b></td>';

                    }
                }


            }
           // $html .='<td align="center"  style="width: 20%;"></td>';
            $html .='</tr>';

            //CC TD TP
            $html .='<tr>';

            $cptmatiers=0;
            foreach ($modules as $module)
            {
                $matiers=Matiere::where('modulle_id',$module->id)->orderBy('id')->get();
                if ($matiers->count()>0)
                {
                    $cptmatiers=$largCol/$matiers->count();
                    foreach ($matiers as $matier)
                    {
                        $html .='<td align="center" colspan=""  ><b>مع.</b></td>';
                        $html .='<td align="center" colspan=""  ><b>ن ام</b></td>';
                        $html .='<td align="center" colspan=""  ><b>ن اخ</b></td>';
                    }
                }


            }
           // $html .='<td align="center"  style="width: 20%;"></td>';
            $html .='</tr>';



            $listes=$this->listeInscrits($profil,$groupe);

            foreach ($listes as $liste)
            {
                $html .='<tr>';
                $html .=''. $this->getPVSemestre($profil,$groupe,$semestre,$etape,$modules,$id_annee,$liste,$largCol);
                $html .='</tr>';
            }

            $html .='</table>';
        }
           /*
            $html .= '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';
            $html .= '<style>
.th { vertical-align: bottom; text-align: center; } th span { -ms-writing-mode: tb-rl; -webkit-
    writing-mode: vertical-tb; writing-mode: vertical-rl; transform: rotate(0deg); white-space: nowrap; }
	</style>
	<table>
<tr>
  <th><span>Project Sponsor</span></th>
</tr>
</table>
<style>

div{
  writing-mode: vertical-lr;
  border: solid black 1px;
  display: inline-block;
  height: 350px;
  width: 300px;
  margin: 5px;
}
div.a {
  text-orientation: mixed;
}

div.b {
  text-orientation: upright;
}
</style>
</head>
<body>

<h1>The text-orientation Property</h1>
<p>Left box has text-orientation: mixed. Right box has text-orientation: upright.</p>
<div class="a">
<h3>text-orientation: mixed</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam semper diam at erat pulvinar, at pulvinar felis blandit.</p>
</div>

<div class="b">
<h3>text-orientation: upright</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam semper diam at erat pulvinar, at pulvinar felis blandit.</p>
</div>';
            $html .='  <table border="1">
                    <tr>
                        <th >First</th>
                        <th class="titre">Second</th>
                        <th class="verticalTableHeader">Third</th>
                    </tr>
                    <tr>
                        <td class="verticalTableHeader"><div class="vtext">
    <b>Number of joints /</b>
    <p>
      <b>Number of bolts per joint</b>
    </p>
  </div></td>
                        <td>foo</td>
                        <td>foo</td>
                    </tr>
                    <tr>
                        <td>foo</td>
                        <td>foo</td>
                        <td>foo</td>
                    </tr>
                    <tr>
                        <td>foo</td>
                        <td>foo</td>
                        <td>foo</td>
                    </tr>
                </table>';*/

            // Create the mPDF document

       PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.pvMatiere').'');
        PDF::SetSubject(''.trans('text_me.pvMatiere').'');
        PDF::SetMargins(10, 10, 10);

      //  $html =PDF::Text(70, 96, 'Rotate');
       /*$pdf->StartTransform();
        $pdf->Rotate(90);
        $pdf->Cell(30, 0, 'Value 1', 1, 0);
        $pdf->StopTransform();*/
       // TextWithRotation(50,65,'Hello',45,-45);
        PDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 056', PDF_HEADER_STRING);

        PDF::setFooterData(array(0,64,0), array(0,64,128));
        PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        if ($choix==3)
            PDF::AddPage('L', 'A4');
        else
             PDF::AddPage('P', 'A4');

        PDF::SetFont('aefurat', '', 8);
        PDF::writeHTML($html, true, false, true, false, '');
        /*PDF::StartTransform();
        PDF::Rotate(90);
        PDF::Text(90,0,'Rotate');
        PDF::StopTransform();
        $header = array('nom et prénom', 'Genre', 'Situation Fam', 'Date de naissance', 'lieu de naissance', 'Type contrat', 'Function', 'Service');
//$this->liste_employes($header,'');
        PDF::SetFillColor(976, 245, 458);
        PDF::StartTransform();
        PDF::Rotate(90);
        PDF::Cell(30, 0, 'yrg', 1, 0, '', 1);
        PDF::StopTransform();*/
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
     //   require_once('tcpdf_include.php');

    }
    public function listeInscrits($profil, $groupe){
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


        $etudiants = Etudiant::whereIn('id',$fils)->where('DECF','1')->get();

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
            $etudiants = Etudiant::whereIn('id',$fils)->where('DECF','1')->get();
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
            $etudiants = Etudiant::whereIn('id',$fils)->where('DECF','1')->get();
        }
        return $etudiants;
    }
    public function liste_employes($header, $data)
    {
        // Colors, line width and bold font

        PDF::SetFillColor(976, 245, 458);
        PDF::StartTransform();
        PDF::Rotate(90);
        PDF::Cell(30, 0, 'yrg', 1, 0, '', 1);
        PDF::StopTransform();
        /*PDF::SetTextColor(0);
        PDF::SetDrawColor(0, 0, 0);
        PDF::SetLineWidth(0.3);
        PDF::SetFont('', 'B');*/
        // Header
        $w = array(55, 25, 30, 40, 40, 30, 30, 30);
        $num_headers = count($header);
        //for ($i = 0; $i < $num_headers; ++$i) {


       // }
       // PDF::Ln();
        // Color and font restoration
        //PDF::SetFillColor(224, 235, 255);
       // PDF::SetTextColor(0);
      //  PDF::SetFont('');
        // Data
        $fill = 0;
        $st_f = '';
        $lieu_nais = '';
        $type_contrat = '';

       /* foreach ($data as $row) {
            PDF::Cell($w[0], 6, $row->prenom . ' ' . $row->nom, 'LR', 0, 'L', $fill);
            PDF::Cell($w[1], 6, ($row->ref_genre) ? $row->ref_genre->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[2], 6, ($row->ref_situation_familliale) ? $row->ref_situation_familliale->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[3], 6, $row->date_naissance, 'LR', 0, 'L', $fill);
            PDF::Cell($w[4], 6, ($row->commune) ? $row->commune->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[5], 6, ($row->ref_types_contrat) ? $row->ref_types_contrat->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[6], 6, ($row->ref_fonction) ? $row->ref_fonction->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[7], 6, ($row->service) ? $row->service->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Ln();
            $fill = !$fill;
        }*/
        PDF::Cell(array_sum($w), 0, '', 'T');
    }

    public function entetPv($profil,$groupe,$semestre,$etape,$choix,$id_annee){
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre ='<table style="width:100%" ><tr>
                        <td style="width: 100%" align="center"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td>
                    </tr></table>';
    $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>
                    <th align="center" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%" rowspan="2">محضر السداسي</th>
                </tr>';
    $titre .='<tr>
                    <td align="center"> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="center" style="width: 35%">'.Profil::find($profil)->libelle.'</td>
                    
                </tr>
               
                </table>
                ';
    $entete = $this->enteteServiceExamenen($titre, 'L');
    $html=$entete;
   /* $html .= '<table style="width:100%;border: black;" border="1" >
                <thead><tr>
                <td style="width:10%; " align="center">'.trans('text_me.decision').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.notef').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.note_RT').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.note_exam').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.note_dev').'</td>
                <td style="width:40%" align="right">'.trans('text_me.nom').'</td>
                <td style="width:10%" align="right">'.trans('text_me.numero').'</td>
               </tr></thead><tbody>';*/
   return $html;
}
    public function getPVSemestre($profil,$groupe,$semestre,$etape,$modules,$id_annee,$liste,$largCol)
    {
        $html1 =''; $html ='';
        $moyen_sem=$this->moyenne_semestre($liste->id,$semestre,$groupe,$id_annee);

        $vsem = 1;
        foreach ($modules as $module)
        {
            $matiers=Matiere::where('modulle_id',$module->id)->orderBy('id')->get();
            if ($matiers->count()>0)
            {
                $cptmatiers=$largCol/$matiers->count();
                foreach ($matiers as $matier)
                {
                    $releves = App\Models\RelevesNote::where('ref_semestre_id', $semestre)->where('profil_id', $profil)
                      ->where('matiere_id', $matier->id)->where('etudiant_id', $liste->id)
                        ->where('annee_id', $id_annee)->where('note_dev', '<>',-3)->get();

                    $html1 .='<td align="center" ><b> ';
                    if ($releves->count()>0)
                    {
                        $html1 .='';
                        $html1 .=$releves->first()->note_dev.'';
                    }
                    $html1 .='</b></td>';
                    $html1 .='<td align="center"  ><b> ';
                    if ($releves->count()>0)
                    {
                        $html1 .=$releves->first()->note_exam ;
                    }
                    $html1 .='</b></td>';
                    $html1 .='<td align="center" ><b>';
                    if ($releves->count()>0)
                    {
                        $html1 .=$releves->first()->note ;
                        if ($releves->first()->decision == 0) {
                            $html1 .= ' غ ' ;
                            $vsem = 0;
                        }
                        if ($releves->first()->decision == 2) {
                            $html1 .= ' ك ' ;
                            $vsem = 0;
                        }
                        if ($releves->first()->decision == 1 or $releves->first()->decision == 11) {
                            $html1 .= 'ا';
                        }
                    }
                    $html1 .='</b></td>';
                        /*$html .='<td align="center" ><b> '.$releves->first()->decision.'</b></td>';*/

                    }

                }
            }

        $html1 .='<td align="center" style="width: 15%"><b> '.$liste->NOMA.' </b></td>
                  <td align="center" style="width: 5%"><b>  '.$liste->NODOS.'</b></td>';
        $decision='';
        if ($moyen_sem >= 10 && $vsem == 1) {
            $decision .= '' . trans("text_me.valide");
        } else {
            // $html .= '' . trans("text_me.rattrapage");
            $decision .= '' . trans("text_me.novalidee");
        }
        $html .='<td align="center" ><b> '.$decision.'</b></td>';
        $html .='<td align="center" ><b> '.number_format($moyen_sem, 2).'</b></td>';
        $html .=''.$html1;
        return $html;
    }

    public function getPVmatierel2($profil,$semestre,$id_annee,$liste,$nivProg,$moyen_sem,$profilOrientations)
    {
        //dd($liste);
        $html1 ='';
        $html ='<tr>';
        $groupe=RefGroupe::where('libelle',$liste->groupe)->get()->first()->id;
        //$moyen_sem=$this->moyenne_semestre($liste->id,$semestre,$groupe,$id_annee);
        $decision='';
        $vsem = 1;
       // $html1 .='<td align="center" >'.$temps->first()->moyenne.'</td>';
       // $html1 .='<td align="center" >'.$moyen_sem.'</td>';
        //$profilOrientations=App\Models\ProfilOrientation::where('proil_cible',$profil)->orderBy('id')->get();
        $decision=Profil::find($nivProg)->libelle;
        foreach ($profilOrientations as $profilOrientatio)
        {
            $temps=App\Models\TmpOrientation::where('etudiant_id',$liste->id)->where('profil_id',$profilOrientatio->profil_id)->orderBy('id','DESC')->get();
           if ($temps->count()>0)
           {
               $prof=Profil::find($profilOrientatio->profil_id);
               $html1 .='<td align="center" >'.$temps->first()->moyenne.'</td>';

           }
           else{
               $html1 .='<td align="center" >D</td>';
           }

        }
            $matiers=Matiere::where('profil_id',$profil)->where('ref_semestre_id',2)->orderBy('id')->get();
            if ($matiers->count()>0)
            {

                foreach ($matiers as $matier)
                {
                    $releves = App\Models\RelevesNote::where('ref_semestre_id', $semestre)->where('profil_id', $profil)
                      ->where('matiere_id', $matier->id)->where('etudiant_id', $liste->id)
                        ->where('annee_id', $id_annee)->where('note', '>',-3)->get();


                    $html1 .='<td align="center" ><b>';
                    if ($releves->count()>0)
                    {
                        $html1 .=$releves->first()->note ;


                    }
                    else{ $html1 .=' '; }
                    $html1 .='</b></td>';


                    }

                }

        $html1 .='<td align="center" ><b> '.$liste->NOMA.' </b></td>
                  <td align="center" ><b>  '.$liste->NODOS.'</b></td>';


        $html .='<td align="center" >'.$decision.'</td>';
        $html .='<td align="center" >'.number_format($moyen_sem, 2).'</td>';
        $html .=''.$html1.'</tr>';
        return $html;
    }

    public function getPVmatieres($profil,$groupe,$semestre,$etape,$choix,$id_annee)
    {
        $html ='';
        $matieres = Matiere::where('profil_id', $profil)->where('ref_semestre_id', $semestre)->get();
        //dd($matieres);
        foreach ($matieres as $matiere)
        {
            $html .=$this->imprimerPVMatier($profil,$groupe,$semestre,$id_annee,$matiere->id,$etape);
        }
        return $html;
    }
    public function imprimerPVMatier($profil,$groupe,$semestre,$id_annee,$matiere_id,$etape)
    {
        $type_controle=Etape::find($etape)->ref_type_controle_id;
        if ($type_controle ==3 )
        {
            $releves = App\Models\RelevesNote::where('ref_semestre_id', $semestre)->where('matiere_id', $matiere_id)
                ->where('annee_id', $id_annee)->where('note_dev', '<>',-3)->where('note_rt', '<>',-1)->get();
        }
        else
        {
            $releves = App\Models\RelevesNote::where('ref_semestre_id', $semestre)->where('matiere_id', $matiere_id)
                ->where('annee_id', $id_annee)->where('note_dev', '<>',-3)->get();
        }
        $matiere=Matiere::find($matiere_id);

        // $libelle_profil=Profil::find($profil);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre ='<table style="width:100%" ><tr>
                    <td style="width: 100%" align="center"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td>
                </tr></table>';
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>
                    <th align="center" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.modulle').'</th>
                </tr>';
        $titre .='<tr>
                    <td align="center"> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="center" style="width: 35%">'.Profil::find($profil)->libelle.'</td>
                    <td align="right" style="width: 35%">'.$matiere->modulle->libelle.'</td>
                </tr>
                <tr>

                    <td align="center" style="width: 50%" colspan="3"> '.trans("text_me.matiere1").' : '.$matiere->libelle.'</td>
                    <td align="right" style="width: 50%">'.trans('text_me.coaf').' : '.$matiere->coaf.'</td>
                </tr>
                </table>
                ';
        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $html .= '<table style="width:100%;border: black;" border="1" >
                <thead><tr>
                <td style="width:10%; " align="center">'.trans('text_me.decision').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.notef').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.note_RT').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.note_exam').'</td>
                <td style="width:10%; " align="center">'.trans('text_me.note_dev').'</td>
                <td style="width:40%" align="right">'.trans('text_me.nom').'</td>
                <td style="width:10%" align="right">'.trans('text_me.numero').'</td>
               </tr></thead><tbody>';
        $i=0;
        foreach ($releves as $releve) {
            $et = EtudMat::where('annee_id',$id_annee)->where('matiere_id',$releve->matiere_id)->where('etudiant_id',$releve->etudiant_id)->get();
            if(count($et)>0){
                $i += 1;

                $html .= '<tr>
                        <td style="width:10%; " align="center">';
                $type_controle = Etape::find($etape)->ref_type_controle_id;
                if ($type_controle == 3) {

                    if ($releve->decision == 1 or $releve->decision == 11) {
                        $html .= '' . trans("text_me.valide");
                    } else {
                        $html .= '' . trans("text_me.novalide");
                    }
                } else {
                    if ($releve->decision == 0) {
                        $html .= '' . trans("text_me.novalide");
                    }
                    if ($releve->decision == 2) {
                        $html .= '' . trans("text_me.rattrapage");
                    }
                    if ($releve->decision == 1 or $releve->decision == 11) {
                        $html .= '' . trans("text_me.valide");
                    }
                }

                $html .= '</td><td style="width:10%; " align="center">' . $releve->note . '</td>
                        <td style="width:10%; " align="center">';
                if ($releve->note_rt == -1) {
                    $html .= '';
                } else {
                    $html .= '' . $releve->note_rt;
                }
                $html .= '</td>
                        <td style="width:10%; " align="center">' . $releve->note_exam . '</td>
                        <td style="width:10%; " align="center">' . $releve->note_dev . '</td>
                        <td style="width:40%" align="right">';
                if (trim($releve->etudiant->NOMF) == trim($releve->etudiant->NOMA))
                    $html .= ' ' . $releve->etudiant->NOMF;
                else
                    $html .= ' ' . $releve->etudiant->NOMA . ' ' . $releve->etudiant->NOMF;

                $html .= '</td>
                        <td style="width:10%" align="right">' . $releve->etudiant->NODOS . '</td>
                        </tr>';
            }
        }

        $html .='</tbody></table>';
        $html .='<table border="0" style="width: 100%"><tr>
                 <td align="center" style="width: 33%">'.trans("text_me.pf").'</td>
                 <td align="center" style="width: 33%"></td>
                 <td align="center" style="width: 34%">'.trans("text_me.sex").'</td>
                </tr></table>';
        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }
    public function imprimerCorrespontNote($profil,$groupe,$semestre,$etape,$choix)
    {
        $html = '';
        $profil_l = Profil::find($profil)->libelle;
        if ($choix == 'all' or $choix == 'col'){
            $matieres = MatieresProfilsEtape::where('profil_id', $profil)
                ->where('etape_id', $etape)->get();
            foreach ($matieres as $matiere) {
                $html .= $this->getalletudiantsAnonymesCorrespond($matiere->matiere_id, $profil_l, $semestre, $groupe);
            }
        }
        else
        {
            $html .= $this->getalletudiantsAnonymesCorrespond($choix, $profil_l, $semestre, $groupe);
        }

        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_pr').'');
        PDF::SetSubject(''.trans('text_me.liste_pr').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('dejavusans', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function getImpressionCollect()
    {
        return view($this->module.'.ajax.getImpressionCollect');
    }

    public function getPVImpressionCollect()
    {
        return view($this->module.'.ajax.getPVImpressionCollect');
    }
    public function getImpressionCorrespond()
    {
        return view($this->module.'.ajax.getImpressionCorrespond');
    }

    public function getImpression()
    {
        return view($this->module.'.ajax.getImpression');
    }

    public function getImpression1()
    {
        return view($this->module.'.ajax.getImpression1');
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

    public function getmatiers_profil($id_profil,$etape)
    {
        $matieres=MatieresProfilsEtape::where('profil_id',$id_profil)
            ->where('etape_id',$etape)->with('matiere')->get();
        return $matieres;
    }

    public function genererAnonymats($id_profil,$etape)
    {
        $etudiants =Etudiant::where('profil_id',$id_profil)->where('DECF','1')->get();
        $plage=Plage::where('profil_id',$id_profil)->get()->first();
        $verificateur=0;
        $anonymat='';
        $anonymats= Anonymat::where('profil_id',$id_profil)->get()->first();
        if ($anonymats){
            $verificateur=2;
        }
        else{
            if ($plage){
                foreach ($etudiants as $etudiant) {
                    $anonymat = random_int($plage->debut, $plage->fin);
                    $anonym=new Anonymat();
                    $anonym->profil_id=$id_profil;
                    $anonym->anonymat=$anonymat;
                    $anonym->etudiant_id=$etudiant->id;
                    $anonym->nodos=$etudiant->NODOS;
                    $anonym->etape_id=$etape;
                    $anonym->save();
                }
                $verificateur=1;
            }
            else{
                $verificateur=0;
            }
        }
        return $verificateur;
    }
    public function entete($titre, $or = 'P', $id = false)
    {
        if ($id == false)
            // $id = env('APP_Faculte');
            $id = 1;
        $class = '';
        if ($or != 'P')
            $class = '_g';
        $etablissement = Faculte::find($id);
        $entete = EnteteEtablissement::where('faculte_id', $id)->first();

        $titre1 = $entete->titre1;
        $titre2 = $entete->titre2;
        $titre3 = $entete->titre3;

        $titre1_ar = $entete->titre1_ar;
        $titre2_ar = $entete->titre2_ar;
        $titre3_ar = $entete->titre3_ar;
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';

        //dd($html);
        $entet_fr = '<table><tr><td class="titre' . $class . '">' . $titre1 . '</td></tr><tr><td class="titre2' . $class . '">' . $titre2 . '</td></tr><tr><td class="titre3' . $class . '">' . $titre3 . '</td></tr></table>';
        $entet_ar = '<table ><tr><td class="titre_ar' . $class . '">' . $titre1_ar . '</td></tr><tr><td class="titre2_ar' . $class . '">' . $titre2_ar . '</td></tr><tr><td class="titre3_ar' . $class . '">' . $titre3_ar . '</td></tr></table>';
        $logo = '<table  ><tr ><td align="center"><input><img src="' . $entete->logo . '" alt="avatar"  /></td></tr></table>';
        $table = '<table><tr><td class="t_left' . $class . '">' . $entet_fr . '</td><td class="t_center' . $class . '">' . $logo . '</td><td class="t_right' . $class . '">' . $entet_ar . '</td></tr></table>';
        $html .= $table;

        $titre_entete = '<br><h4 class="titre_entete">' . $titre . '<br><br></h4>';
        $html .= $titre_entete;
        return $html;
    }

    public function enteteServiceExamenen($titre, $or = 'P', $id = false)
    {
        if ($id == false)
            // $id = env('APP_Faculte');
            $id = 1;
        $class = '';
        if ($or != 'P')
            $class = '_g';
        $etablissement = Faculte::find($id);
        $entete = EnteteEtablissement::where('faculte_id', $id)->first();

        $titre1 = $entete->titre1;
        $titre2 = $entete->titre2;
        $titre3 = $entete->titre3;

        $titre1_ar = $entete->titre1_ar;
        $titre2_ar = $entete->titre2_ar;
        $titre3_ar = 'مصلحة الإمتحانات';
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';

        //dd($html);
        $entet_fr = '<table><tr><td class="titre' . $class . '">' . $titre1 . '</td></tr><tr><td class="titre2' . $class . '">' . $titre2 . '</td></tr><tr><td class="titre3' . $class . '">' . $titre3 . '</td></tr></table>';
        $entet_ar = '<table style="width: 100%" border="0"><tr><td class="titre_ar' . $class . '">' . $titre1_ar . '</td></tr><tr><td class="titre2_ar' . $class . '">' . $titre2_ar . '</td></tr><tr><td class="titre3_ar' . $class . '">' . $titre3_ar . '</td></tr></table>';
        $logo = '<table  ><tr ><td align="center"><input><img src="' . $entete->logo . '" alt="avatar"  /></td></tr></table>';
        $table = '<table><tr><td  class="t_left' . $class . '">' . $entet_fr . '</td><td class="t_center' . $class . '">' . $logo . '</td><td class="t_right' . $class . '">' . $entet_ar . '</td></tr></table>';
        $html .= $table;

        $titre_entete = '<br><h4 class="titre_entete">' . $titre . '<br><br></h4>';
        $html .= $titre_entete;
        return $html;
    }

    public function getNotes($profil,$semestre,$etape)
    {

        $matieres = Matiere::where('profil_id', $profil)
            ->where('ref_semestre_id', $semestre)->where('tp', '<>',11)->get();
        return view($this->module.'.ajax.getNotes',['matieres'=>$matieres]);
    }
    public function releveNoteIndiv()
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
       $annees=Annee::where('etat','<>',null)->get();
       $semestres=RefSemestre::all();
        return view($this->module.'.ajax.releveNoteIndiv',['semestres'=>$semestres,'annees'=>$annees,'id_annee'=>$id_annee]);
    }

    public function attEntet(){

        $html ='<table style="width: 100%" border="0">
                <tr>
                    <td style="width: 40%">ﺷرف ـ إﺧﺎء ـ ﻋدل</td>
                    <td style="width: 60%" align="right">الجمهورية الإسلامية الموريتانية</td>
                </tr>
                <tr>
                    <td style="width: 40%">العام الجامعي 2022-2023</td>
                    <td style="width: 60%" align="right">وزارة الشؤون الإسلامية والتعليم الأصلي</td>
                </tr>
                <tr>
                    <td style="width: 40%"></td>
                    <td style="width: 60%" align="right">المعهد العالي للدراسات واالبحوث الإسلامية</td>
                </tr>
        </table>';
        return $html;
    }
    public function inserSortantAttestation($etudiant,$moyAn,$niveau,$annee_id)
    {
        $ets=App\Models\MoyennesSortant::where('etudiant_id',$etudiant->id)->where('profil_id',$niveau->id)
                            ->where('annee_id',$annee_id)->get();
        foreach ($ets as $et)
        {
            $ddd=App\Models\MoyennesSortant::find($et->id);
            $ddd->Delete();
        }
        $ms=new App\Models\MoyennesSortant();
        $ms->etudiant_id=$etudiant->id;
        $ms->profil_id=$niveau->id;
        $ms->annee_id=$annee_id;
        $ms->note=$moyAn;
        $ms->save();
    }
    public function imprimerAttestation($etudiant,$moyAn,$niveau)
    {

        $html=$this->attEntet();
        $html .='<table style="width: 30%" ><tr> <td align="center" style="width: 100%"> انواكشوط بتاريخ '.date("Y-m-d").'</td></tr></table>';
        $html .= '<table style="width: 15%" >
                    
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
                </table>';
        $html .='<p align="center"> <h1><b>'.trans("text_me.attS6").'</b></h1></p><br><br>';
        $html .='<table style="width: 100%" border="0">
                    <tr>
                        <td colspan="2" style="width: 100%" align="right">يفيد المدير العام للمعهد العالي للدراسات والبحوث الإسلامية أن الطالب (ة)
                         <br></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 100%" align="right"><b> ';
        if ($etudiant->NOMF =='_' or $etudiant->NOMF=='')
        {
            $html .= $etudiant->NOMA.'';
        }
        else if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
            $html .=' '.$etudiant->NOMF;
        else
            $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
        $html .=' </b>
 <br></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right">المولود(ة) بتاريخ '.$etudiant->DATN.' في '.$etudiant->LIEUNA.'
                         <br></td>
                    </tr>';
        $html .=' <tr>
             <td colspan="2"style="width: 100%" align="right"><b>  الرقم الوطني : 
             '.$etudiant->NNI.' 
                            </b> <br> </td>   </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right"> <b> '.$etudiant->NODOS.' </b> المسجل (ة) تحت الرقم 
                         <br></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right"><b>قد حصل (ت) على لصانص الدراسات الأساسية 
                         </b><br></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right"><b>في '.$niveau->libelle_ar.' 
                         </b><br></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2"  style="width: 100%" align="right"><b>بتقدير ';
        if ($moyAn >= 10 and $moyAn< 12) {  $html .='  مقبول ';}
        if ($moyAn >= 12 and $moyAn< 14) {  $html .=' مستحسن ';}
        if ($moyAn >= 14 and $moyAn< 16) {  $html .=' حسن ';}
        if ($moyAn >= 16 and $moyAn< 18) {  $html .=' حسن جدا ';}
        if ($moyAn >= 18 and $moyAn< 20) {  $html .=' ممتاز ';}
        $html .='('.$moyAn.')';
        $html .='بعد استيفاء السداسيات المقررة';
        $html .=' <br></b></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right">وقد سلمت له (ا) هذه الوثيقة للإدلاء بها عند الحاجة.</td>
                    </tr>';
        $html .='  </table>';
        $html .='<br><br><br><br><p align=""> '.trans("text_me.dg").'</p>';
        $html .='
                <table style="width: 100%" border="0">
                <tr>
                    <td style="width: 100%" align="right"><br><br><br><br><br><br><br><br><br><br><br><br>
                    ملاحظة :لا تسلم من هذه الوثيقة سوى نسخة واحدة
                    </td>
                </tr>
                 <tr>
                    <td style="width: 100%;vertical-align: bottom" align="center">
                    <hr></td>
                </tr>
                <tr>
                    <td style="width: 100%;vertical-align: top" align="center" >ISERI
                    </td>
                </tr>
                </table>
';
        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }

    public function imprimerAttestationMaster($etudiant,$moyAn,$niveau)
    {

        $html=$this->attEntet();
        $html .='<table style="width: 30%" ><tr> <td align="center" style="width: 100%"> انواكشوط بتاريخ '.date("Y-m-d").'</td></tr></table>';
        $html .= '<table style="width: 15%" >
                    
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
                </table>';
        $html .='<p align="center"> <h1><b>'.trans("text_me.attS6").'</b></h1></p><br><br>';
        $html .='<table style="width: 100%" border="0">
                    <tr>
                        <td colspan="2" style="width: 100%" align="right">يفيد المدير العام للمعهد العالي للدراسات والبحوث الإسلامية أن الطالب (ة)
                         <br></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 100%" align="right"><b> ';
        if ($etudiant->NOMF =='_' or $etudiant->NOMF=='')
        {
            $html .= $etudiant->NOMA.'';
        }
        else if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
            $html .=' '.$etudiant->NOMF;
        else
            $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
        $html .=' </b>
 <br></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right">المولود(ة) بتاريخ '.$etudiant->DATN.' في '.$etudiant->LIEUNA.'
                         <br></td>
                    </tr>';
        $html .=' <tr>
             <td colspan="2"style="width: 100%" align="right"><b>  الرقم الوطني : 
             '.$etudiant->NNI.' 
                            </b> <br> </td>   </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right"> <b> '.$etudiant->NODOS.' </b> المسجل (ة) تحت الرقم 
                         <br></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right"><b>في '.$niveau->libelle_ar.'</b><b>قد حصل (ت) على ماستر</b><br></td>
                    </tr>';
        /*$html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right"><br></td>
                    </tr>';*/
        $html .=' <tr>
                        <td colspan="2"  style="width: 100%" align="right"><b>بتقدير ';
        if ($moyAn >= 10 and $moyAn< 12) {  $html .='  مقبول ';}
        if ($moyAn >= 12 and $moyAn< 14) {  $html .=' مستحسن ';}
        if ($moyAn >= 14 and $moyAn< 16) {  $html .=' حسن ';}
        if ($moyAn >= 16 and $moyAn< 18) {  $html .=' حسن جدا ';}
        if ($moyAn >= 18 and $moyAn< 20) {  $html .=' ممتاز ';}
        $html .='('.$moyAn.')';
        $html .='بعد استيفاء الرباعيات الأربعة المقررة';
        $html .=' <br></b></td>
                    </tr>';
        $html .=' <tr>
                        <td colspan="2" style="width: 100%" align="right">وقد سلمت له (ا) هذه الوثيقة للإدلاء بها عند الحاجة.</td>
                    </tr>';
        $html .='  </table>';
        $html .='<br><br><br><br><p align=""> '.trans("text_me.dg").'</p>';
        $html .='
                <table style="width: 100%" border="0">
                <tr>
                    <td style="width: 100%" align="right"><br><br><br><br><br><br><br><br><br><br><br><br>
                    ملاحظة :لا تسلم من هذه الوثيقة سوى نسخة واحدة
                    </td>
                </tr>
                 <tr>
                    <td style="width: 100%;vertical-align: bottom" align="center">
                    <hr></td>
                </tr>
                <tr>
                    <td style="width: 100%;vertical-align: top" align="center" >ISERI
                    </td>
                </tr>
                </table>
';
        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }

    public function imprimerAttestationAtt($etudiant,$moyAn,$niveau,$semestre)
    {
        $Ssem1='الأول والثاني';
        if ($semestre== 3 or $semestre==4) {  $Ssem1='الثالث و الرابع'; }
        if ($semestre== 5 or $semestre==6) {  $Ssem1='الخامس و السادس'; }
        $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
        $libAnnee=''; $l='';
        if ($ref_niveau_etude_id ==1 ) { $libAnnee='الأولى '; $l='ل1'; }
        if ($ref_niveau_etude_id ==2 ) { $libAnnee='الثانية '; $l='ل2'; }
        if ($ref_niveau_etude_id ==3) { $libAnnee='الثالثة '; $l='ل3'; }
        if ($ref_niveau_etude_id ==4) { $libAnnee='الاولى ماستر '; $l='ماستر 1'; }
        if ($ref_niveau_etude_id ==5) { $libAnnee='الثانية ماستر '; $l='ماستر 2'; }
        $html=$this->attEntet();
        $html .='<br><p align="left" style="width: 30%"> انواكشوط بتاريخ '.date("Y-m-d").'</p> ';
        $html .='<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>';
        $html .='<p align="center"> <h1><b>'.trans("text_me.attValid").' '.$libAnnee .' </b></h1></p>';
        $html .='<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>';
        $html .='<table style="width: 100%" border="0">
                    <tr>
                        <td style="width: 100%" align="right">تفيد إدارة المعهد العالي للدراسات والبحوث الإسلامية  بناء  على  محاضر الامتحانات 
أن  الطالب (ة)
                         <br></td>
                    </tr>
                    <tr>
                        <td style="width: 100%" align="right"><b>';
        if ($etudiant->NOMF =='_' or $etudiant->NOMF=='')
        {
            $html .= $etudiant->NOMA.'';
        }
        else if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
            $html .=' '.$etudiant->NOMF;
        else
            $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF.'';
        $html .='&nbsp;&nbsp;';
        $html .=' الرقم  الوطنى : '.$etudiant->NNI .'';
        $html .='&nbsp;&nbsp;';
        $html .='  رقم التسجيل :'.$etudiant->NODOS .'';
        $html .='</b> <br></td>
                    </tr>';
        /* $html .=' <tr>
                         <td style="width: 100%" align="right">المولود(ة) بتاريخ '.$etudiant->DATN.' في '.$etudiant->LIEUNA.'
                          <br></td>
                     </tr>';*/

        if ($ref_niveau_etude_id ==4 or $ref_niveau_etude_id ==5) {

            $html .=' <tr>
                        <td style="width: 100%" align="right"><b>قد  استوفى (ت)  المتطلبات  الدراسية  للرباعي
                         ';
            $html .=''.$Ssem1;
            $html .=' من ';
            $html .='  '.$niveau->libelle_ar;
            $html .='  برسم  العام الجامعى 2020- 2021';
            $html .='</b><br></td>
                    </tr>';
            $html .=' <tr>
                        <td style="width: 100%" align="right"><b>وفد سلمت له  هذه الإفادة بناء على طلبه
                         </b><br></td>
                    </tr>';
        }
        else {
            $html .=' <tr>
                        <td style="width: 100%" align="right"><b>قد  استوفى (ت)  المتطلبات  الدراسية  للسداسي
                         ';
            $html .=''.$Ssem1;
            $html .=' من ';
            $html .='  '.$niveau->libelle_ar;
            $html .='  لرسم  العام الجامعى 2020- 2021';
            $html .='</b> <br> </td>
                    </tr>';
            $html .=' <tr>
                        <td style="width: 100%" align="right"><b>وفد سلمت له  هذه الإفادة بناء على طلبه
                         </b><br></td>
                    </tr>';
        }

        /*$html .=' <tr>
                        <td style="width: 100%" align="right"><b>بتقدير ';
                        if ($moyAn >= 10 and $moyAn< 12) {  $html .=' مقبول';}
                        if ($moyAn >= 12 and $moyAn< 14) {  $html .=' جيد';}
                        if ($moyAn >= 14 and $moyAn< 16) {  $html .='جيد جدا';}
                        if ($moyAn >= 16 and $moyAn< 17) {  $html .='ممتاز';}
                        $html .='('.$moyAn.')';
                        $html .='بعد استفاء السداسيات المقررة';
                        $html .=' <br></b></td>
                    </tr>';
        $html .=' <tr>
                        <td style="width: 100%" align="right">وقد سلمت له (ا) هذه الوثيقة الإدلاء بها عند الحاجة.</td>
                    </tr>';*/
        $html .=' <tr>
                        <td style="width: 100%" align="right"> <br> <br> </td>
                    </tr>';
        $html .='  </table>';
        $html .='<br><br><br><br><table style="width: 100%" ><tr>
                    
                    <td align="" style="width: 35%"> '.trans("text_me.sg").'</td>
                    <td align="center" style="width: 30%"> '.trans("text_me.sex").'</td> ';
        if ($ref_niveau_etude_id ==4 or $ref_niveau_etude_id ==5)
        { $html .='   <td align="right" style="width: 35%"> '.trans("text_me.MNS").'</td>'; }
        else
        { $html .='   <td align="right" style="width: 35%"> '.trans("text_me.PC").'</td>'; }
        $html .=' </tr></table>';

        $html .='';
        $html .='
                <table style="width: 100%" border="0">
                <tr>
                    <td style="width: 100%" align="right"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    ملاحظة :لا تسلم من هذه الوثيقة سوى نسخة واحدة
                    </td>
                </tr>
                 <tr>
                    <td style="width: 100%;vertical-align: bottom" align="center">
                    <hr></td>
                </tr>
                <tr>
                    <td style="width: 100%;vertical-align: top" align="center" >ISERI
                    </td>
                </tr>
                </table>
';
        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }

    public function imprimerSemestreAN($profil,$groupe,$semestre)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $niveau=Profil::find($profil);
        $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
        if ($ref_niveau_etude_id == 3)
        {
            $s5=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('profil_id',$profil)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->orderBy('etudiant_id')->get()->pluck("etudiant_id");
            $etudiants = Etudiant::whereIn('id',$s5)->get();

            $html='';
            $mooo=0;
            $etud=$etudhtml='';
            foreach ($etudiants as $etudiant)
            {
                $moy1=$moy2=$moy3=$moy4=$moy5=$moy6='';
                $Sem1=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',1)->orderBy('created_at','DESC')->get()->first();
                $Sem2=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',2)->orderBy('created_at','DESC')->get()->first();
                $Sem3=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',3)->orderBy('created_at','DESC')->get()->first();
                $Sem4=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',4)->orderBy('created_at','DESC')->get()->first();
                $Sem5=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',5)->orderBy('created_at','DESC')->get()->first();
                $Sem6=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',6)->orderBy('created_at','DESC')->get()->first();
                $val5=$val6=0;
                if ($Sem1){ $moy1 = number_format($Sem1->note,2); }
                if ($Sem2){ $moy2 = number_format($Sem2->note,2); }
                if ($Sem3){ $moy3 = number_format($Sem3->note,2); }
                if ($Sem4){ $moy4 = number_format($Sem4->note,2); }
                if ($Sem5){ $moy5 = number_format($Sem5->note,2); $val5=$Sem5->decision; }
                if ($Sem6){ $moy6 = number_format($Sem6->note,2); $val6=$Sem6->decision; }
                if ($moy1 >9.9 && $moy2 >9.9  && $moy3 >9.9  && $moy4 >9.9  && $moy5 >9.9 && $moy6 >9.9 && ($val5 ==1 or $val5==11)&& ($val6 ==1 or $val6==11))
                {
                    $moyAn=number_format((($moy1 + $moy2 + $moy3 + $moy4 + $moy5 + $moy6)/6),2);
                    $moyAn1=number_format((($moy1 + $moy2 + $moy3 + $moy4 + $moy5 + $moy6)/6),2);
                    $html .= $this->imprimerAttestation($etudiant,$moyAn,$niveau);
                    $this->inserSortantAttestation($etudiant,$moyAn,$niveau,$id_annee);
                    if ($moyAn1 >$mooo)
                    {
                        $mooo = $moyAn1;
                        $etud =''.$etudiant->NODOS.'- nni'.$etudiant->NNI.'-nom '.$etudiant->NOMF.' -moy'.$mooo;
                    }
                    $etudhtml .='<br>'.$etudiant->NODOS.'- nni'.$etudiant->NNI.'-nom '.$etudiant->NOMF.' -moy'.$moyAn1;
                }



            }
            //$html .=$etud .'<br><br>';
            // $html .=$etudhtml;
        }
        if ($ref_niveau_etude_id == 5){
            $s5=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('ref_semestre_id',$semestre)->where('profil_id',$profil)->where('ref_groupe_id',$groupe)->orderBy('etudiant_id')->get()->pluck("etudiant_id");
            $etudiants = Etudiant::whereIn('id',$s5)->get();

            $html='';
            $mooo=0;
            $etud=$etudhtml='';
            foreach ($etudiants as $etudiant)
            {
                $moy1=$moy2=$moy3=$moy4=$moy5=$moy6='';
                $Sem1=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',1)->orderBy('created_at','DESC')->get()->first();
                $Sem2=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',2)->orderBy('created_at','DESC')->get()->first();
                $Sem3=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',3)->orderBy('created_at','DESC')->get()->first();
                $Sem4=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',4)->orderBy('created_at','DESC')->get()->first();
                $val5=$val6=0;
                if ($Sem1){ $moy1 = number_format($Sem1->note,2); }
                if ($Sem2){ $moy2 = number_format($Sem2->note,2); }
                if ($Sem3){ $moy3 = number_format($Sem3->note,2); $val5=$Sem3->decision;}
                if ($Sem4){ $moy4 = number_format($Sem4->note,2); $val6=$Sem4->decision;}
                if ($moy1 >9.9 && $moy2 >9.9  && $moy3 >9.9  && $moy4 >9.9   && ($val5 ==1 or $val5==11)&& ($val6 ==1 or $val6==11))
                {
                    $moyAn=number_format((($moy1 + $moy2 + $moy3 + $moy4)/4),2);
                    $moyAn1=number_format((($moy1 + $moy2 + $moy3 + $moy4)/4),2);
                    $html .= $this->imprimerAttestationMaster($etudiant,$moyAn,$niveau);
                    $this->inserSortantAttestation($etudiant,$moyAn,$niveau,$id_annee);
                    if ($moyAn1 >$mooo)
                    {
                        $mooo = $moyAn1;
                        $etud =''.$etudiant->NODOS.'- nni'.$etudiant->NNI.'-nom '.$etudiant->NOMF.' -moy'.$mooo;
                    }
                    $etudhtml .='<br>'.$etudiant->NODOS.'- nni'.$etudiant->NNI.'-nom '.$etudiant->NOMF.' -moy'.$moyAn1;
                }



            }
        }
        $majors=App\Models\MoyennesSortant::where('profil_id',$niveau->id)
            ->where('annee_id',$id_annee)->orderBy('note','DESC')->get();
        if ($majors->count()>0){
            $html .='<div style="page-break-after: always"></div>';
            $titre ='<table style="width:100%" ><tr>
                    <td style="width: 100%" align="center"><b>لائحة الخرجين حسب ترتيب المعدل التراكمي للطالب</b></td>
                </tr></table>';
            $titre ='<table style="width:100%" ><tr>
                    <td style="width: 100%" align="center"><b>'.$niveau->libelle.' </b></td>
                </tr></table>';
$titre ='<table style="width:100%" ><tr>
                    <td style="width: 100%" align="center"><b>'.trans("text_me.anne_univ").' 2022-2023</b></td>
                </tr></table>';


            $entete = $this->enteteServiceExamenen($titre, 'L');
            $html .=$entete;
            $html .='<table style="width: 100%" border="1">
                        <tr>
                            <td style="width: 20%" align="right">المعدل </td>
                            <td style="width: 60%" align="right">'.trans("text_me.nom").' </td>
                            <td style="width: 20%" align="right">'.trans("text_me.numero").' </td>
                            
                            
                        </tr>';
            foreach ($majors as $major){
                $html .='<tr>
                            <td style="width: 20%" align="right">'.$major->note.' </td>
                            <td style="width: 60%" align="right">'.$major->etudiant->NOMF.' </td>
                            <td style="width: 20%" align="right">'.$major->etudiant->NODOS.' </td>
                            
                            
                            
                        </tr>';
        }
            $html .='</table>';

}
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.attS6').'');
        PDF::SetSubject(''.trans('text_me.attS6').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.attS6').''.'.pdf');
    }

    public function imprimerAtteAN($profil,$groupe,$semestre)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $niveau=Profil::find($profil);
        $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
        $Ssem1=1;$Ssem2=2;
        if ($semestre== 3 or $semestre==4) {  $Ssem1=3;$Ssem2=4; }
        if ($semestre== 5 or $semestre==6) {  $Ssem1=5;$Ssem2=6; }
        if ($niveau)
        {
            $s5=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('annee_id',$id_annee)->orderBy('etudiant_id')->get()->pluck("etudiant_id");
            $etudiants = Etudiant::where('profil_id',$profil)->whereIn('id',$s5)->get();

            $html='';
            foreach ($etudiants as $etudiant)
            {
                $moy1=$moy2='';
                $val1=$val2=0;
                $Sem1=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',$Ssem1)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
                $Sem2=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',$Ssem2)->where('annee_id',$id_annee)->orderBy('created_at','DESC')->get()->first();
                if ($Sem1){ $moy1 = number_format($Sem1->note,2); $val1=$Sem1->decision;}
                if ($Sem2){ $moy2 = number_format($Sem2->note,2); $val2=$Sem2->decision; }
                if ($moy1 >9.9 && $moy2 >9.9 && ($val1==1 or $val1==11) && ($val2==1 or $val2==11))
                {
                    $moyAn=number_format(($moy1 + $moy2 )/2);
                    $html .= $this->imprimerAttestationAtt($etudiant,$moyAn,$niveau ,$semestre);
                }

            }
        }
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.attValid').'');
        PDF::SetSubject(''.trans('text_me.attValid').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.attS6').''.'.pdf');
    }

    public function saisieNotesSemestreAN($profil,$groupe,$semestre)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $html='<input type="hidden" value="'.$profil.'" name="profil">
        <input type="hidden" value="'.$semestre.'" name="semestre">
        <input type="hidden" value="'.$groupe.'" name="groupe">';
        $niveau=Profil::find($profil);
        $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
        if ($ref_niveau_etude_id == 3)
        {
            $html .='<table style="width: 100%" border="1">
                        <tr>
                            <td style="width: 10%">'.trans("text_me.numero").' </td>
                            <td style="width: 30%">'.trans("text_me.nom").' </td>
                            <td style="width: 10%">'.trans("text_me.semestre").' 1</td>
                            <td style="width: 10%">'.trans("text_me.semestre").' 2</td>
                            <td style="width: 10%">'.trans("text_me.semestre").' 3</td>
                            <td style="width: 10%">'.trans("text_me.semestre").' 4</td>
                            <td style="width: 10%">'.trans("text_me.semestre").' 5</td>
                            <td style="width: 10%">'.trans("text_me.semestre").' 6</td>
                        </tr>';
            $etape_note=20;
            // $s6=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('ref_semestre_id',6)->where('annee_id',$id_annee)->orderBy('etudiant_id')->get()->pluck("etudiant_id");//->where('annee_id',$id_annee)
            $s5=App\Models\MoyennesSemestre::where('note','>',9.9)->where('decision',1)->where('ref_semestre_id',$semestre)->where('profil_id',$profil)->where('annee_id',$id_annee)->where('ref_groupe_id',$groupe)->orderBy('etudiant_id')->get()->pluck("etudiant_id");
            $etudiants = Etudiant::whereIn('id',$s5)->orderBy('NODOS', 'ASC')->get();

            foreach ($etudiants as $etudiant)
            {
                $moy1=$moy2=$moy3=$moy4=$moy5=$moy6='';//
                $Sem1=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',1)->orderBy('created_at','DESC')->get()->first();
                $Sem2=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',2)->orderBy('created_at','DESC')->get()->first();
                $Sem3=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',3)->orderBy('created_at','DESC')->get()->first();
                $Sem4=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',4)->orderBy('created_at','DESC')->get()->first();
                $Sem5=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',5)->orderBy('created_at','DESC')->get()->first();
                $Sem6=App\Models\MoyennesSemestre::where('etudiant_id',$etudiant->id)->where('ref_semestre_id',6)->orderBy('created_at','DESC')->get()->first();
                if ($Sem1){ $moy1 = number_format($Sem1->note,2); }
                if ($Sem2){ $moy2 = number_format($Sem2->note,2); }
                if ($Sem3){ $moy3 = number_format($Sem3->note,2); }
                if ($Sem4){ $moy4 = number_format($Sem4->note,2); }
                if ($Sem5){ $moy5 = number_format($Sem5->note,2); }
                if ($Sem6){ $moy6 = number_format($Sem6->note,2); }
                $html .=' <tr>
                          <td style="width: 10%">'.$etudiant->NODOS.' </td>
                          <td style="width: 30%">'.$etudiant->NOMF.' </td>
                          <td style="width: 10%" align="center"> <input type="number" name="sem1'.$etudiant->NODOS.'" value="'.$moy1.'" size="4" min="0" max="20" maxlength="4" onchange="test(this.value,'.$etape_note.');"> </td>
                          <td style="width: 10%" align="center"> <input type="number" name="sem2'.$etudiant->NODOS.'" value="'.$moy2.'" size="4" min="0" max="20" maxlength="4" onchange="test(this.value,'.$etape_note.');"> </td>
                          <td style="width: 10%" align="center"> <input type="number" name="sem3'.$etudiant->NODOS.'" value="'.$moy3.'" size="4" min="0" max="20" maxlength="4" onchange="test(this.value,'.$etape_note.');"> </td>
                          <td style="width: 10%" align="center"> <input type="number" name="sem4'.$etudiant->NODOS.'" value="'.$moy4.'" size="4" min="0" max="20" maxlength="4" onchange="test(this.value,'.$etape_note.');"> </td>
                          <td style="width: 10%" align="center"> <input type="number" name="sem5'.$etudiant->NODOS.'" value="'.$moy5.'" size="4" min="0" max="20" maxlength="4" onchange="test(this.value,'.$etape_note.');"> </td>
                          <td style="width: 10%" align="center"> <input type="number" name="sem6'.$etudiant->NODOS.'" value="'.$moy6.'" size="4" min="0" max="20" maxlength="4" onchange="test(this.value,'.$etape_note.');"> </td>
                          </tr>';
            }
            $html .='</table>';
            return view($this->module.'.ajax.saisieNotesSemestreAN',['profil'=>$profil,'niveau'=>$niveau,'html'=>$html,]);
        }

    }

    public function annulercalculerNotes($profil,$semestre,$groupe,$etape)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();

        $verif=App\Models\VerifCalculeNote::where('ref_semestre_id',$semestre)
            ->where('groupe_id',$groupe)->where('profil_id',$profil)->where('annee_id',$id_annee)->get();
        if (count($verif)>0)
        {
            foreach ($verif as $v)
            {
                $d=App\Models\VerifCalculeNote::find($v->id);
                $d->Delete();
            }
            $notesFinales = App\Models\NoteExamenFinale::where('profil_id',$profil)
                ->where('ref_semestre_id', $semestre) ->where('etape_id', null)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
            foreach ($notesFinales as $notes)
            {
                $n=App\Models\NoteExamenFinale::find($notes->id);
                $n->delete();
            }
            $releves = App\Models\RelevesNote::where('profil_id',$profil)
                ->where('ref_semestre_id', $semestre)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
            foreach ($releves as $releve)
            {
                $releve=App\Models\RelevesNote::find($releve->id);
                $releve->delete();
            }
            $semestre = App\Models\MoyennesSemestre::where('profil_id',$profil)
                ->where('ref_semestre_id', $semestre)
                ->where('annee_id', $id_annee)->get();
            foreach ($semestre as $semt)
            {
                $ss=App\Models\MoyennesSemestre::find($semt->id);
                $ss->delete();
            }
        }
    }

    public function calculerNote($profil,$semestre,$groupe,$etape,$id)
    {
        $data=1;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etudiant =Etudiant::find($id);
        $semestreRech=1;
        if ($semestre == 1)
        {
            $semestreRech=1;
        }
        if ($semestre == 3)
        {
            $semestreRech=1;
        }
        if ($semestre == 5)
        {
            $semestreRech=3;
        }
        if ($semestre == 2)
        {
            $semestreRech=2;
        }
        if ($semestre == 4)
        {
            $semestreRech=2;
        }
        if ($semestre == 6)
        {
            $semestreRech=4;
        }
        $id_matiere = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get()->first()->matiere_id;

        $profil_id=Matiere::find($id_matiere)->profil_id;
        $profil =$profil_id;
        $verif = App\Models\VerifCalculeNoteIndiv::where('ref_semestre_id',$semestreRech)
            ->where('etudant_id',$id)->where('annee_id',$id_annee)->get();

        if (count($verif)>0)
        {
            $data=2;
        }
        else{
            $verifNot =new App\Models\VerifCalculeNoteIndiv();
            $verifNot->etudant_id=$id;
            $verifNot->ref_semestre_id=$semestreRech;
            $verifNot->groupe_id=$groupe;
            $verifNot->profil_id=$profil;
            $verifNot->annee_id=$id_annee;
            $verifNot->save();
            //dd($semestreRech);->where('etape_id', null)

            $moyen_sem=$moyenne_module=0;
            $notesFinales1 = App\Models\NoteExamenFinale::where('etudiant_id',$id)
                ->where('ref_semestre_id', $semestreRech)->where('etape_id', null)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
            if ($notesFinales1->count()>0)
            {
                foreach ($notesFinales1 as $notesF)
                {
                    $nt = App\Models\NoteExamenFinale::find($notesF->id);
                    $nt->delete();
                }
            }
            $matieres_etudiants = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get();
            foreach ($matieres_etudiants as $matiere)
            {
                $noteE=$notedev=0;
                $notFinal=0;
                $noteExam = NoteExamen::where('matiere_id', $matiere->matiere_id)->where('etudiant_id',$id)
                    ->where('ref_semestre_id', $matiere->ref_semestre_id)
                    ->where('annee_id', $id_annee)->get()->first();

                $noteDevs = NoteDevoir::where('matiere_id', $matiere->matiere_id)
                    ->where('ref_semestre_id', $matiere->ref_semestre_id)->where('etudiant_id',$id)
                    ->where('annee_id', $id_annee)->get()->first();
                if ($noteExam){ $noteE = $noteExam->note; }
                if ($noteDevs){ $notedev = $noteDevs->note; }
                $notFinal= $noteE + $notedev;
                $this->insertNotFinal($id,$matiere->matiere_id,$matiere->ref_semestre_id,$groupe,$id_annee,$profil,$noteE,$notedev,$notFinal);
            }
            $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
                ->where('ref_semestre_id', $semestreRech)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();

            $moyen_sem=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
            $this->insertSem_semestre($id,$semestre,$groupe,$id_annee,$profil);
            $essey = env('ESSEY');
            foreach ($notesFinales as $notesF)
            {
                $moyenne_module=0;
                $val=0;
                //val=2 rt =1 valide =0 non valide
                $moyenne_module=$this->moyenne_module($id,$semestreRech,$groupe,$id_annee,$notesF->modulle_id);
                $valide_module=$this->valide_module($id,$semestreRech,$groupe,$id_annee,$notesF->modulle_id);
                if ($notesF->etape_id == 0 and  $notesF->note_dev == -3)
                {
                    $val=11;
                }
                elseif ($notesF->note >= 10)
                {
                    $val=1;
                }
                elseif ($notesF->note >= $essey and $valide_module ==1  )
                {
                    $val=1;
                }
                elseif ($valide_module ==0 and $moyenne_module >= 5)
                {
                    $val=2;
                }
                else
                {
                    $val=0;
                }
                $this->insertReleves($id,$notesF->matiere_id,$semestreRech,$groupe,$notesF->modulle_id,$id_annee,$profil,$notesF->note_dev,$notesF->note_exam,$notesF->note,$moyenne_module,$val,null);
            }
        }
        return $data;
    }

    public function annulercalculerNoteAn($profil,$semestre,$groupe,$etape,$id)
    {
        $data=1;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etudiant =Etudiant::find($id);
        $semestreRech=1;
        if ($semestre == 1)
        {
            $semestreRech=1;
        }
        if ($semestre == 3)
        {
            $semestreRech=1;
        }
        if ($semestre == 5)
        {
            $semestreRech=3;
        }
        if ($semestre == 2)
        {
            $semestreRech=2;
        }
        if ($semestre == 4)
        {
            $semestreRech=2;
        }
        if ($semestre == 6)
        {
            $semestreRech=4;
        }
        $id_matiere = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get()->first()->matiere_id;

        $profil_id=Matiere::find($id_matiere)->profil_id;
        $profil =$profil_id;
        $verif=App\Models\VerifCalculeNoteIndiv::where('ref_semestre_id',$semestreRech)
            ->where('etudant_id',$id)->where('annee_id',$id_annee)->get();

        foreach ($verif as $v)
        {
            $b= App\Models\VerifCalculeNoteIndiv::find($v->id);
            $b->delete();
        }

        $notesFinales = App\Models\RelevesNote::where('etudiant_id',$id)
            ->where('ref_semestre_id', $semestreRech)
            ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
        //dd($semestreRech .' 1'.$id.' 2 '.$groupe .''.$notesFinales);
        foreach ($notesFinales as $notes)
        {
            $nn=App\Models\RelevesNote::find($notes->id);
            $nn->delete();
        }
        $semes =App\Models\MoyennesSemestre::where('etudiant_id',$id)->where('annee_id',$id_annee)->get();
        foreach ($semes as $se)
        {
            $ss=App\Models\MoyennesSemestre::find($se->id);
            $ss->delete();
        }

        return $data;
    }

    public function calculerNoteNow($profil,$semestre,$groupe,$etape,$id)
    {
        $data=1;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etudiant =Etudiant::find($id);

        $id_matiere = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->first()->matiere_id;

        $profil_id=$profil;
        $profil =$profil_id;
        $verif=App\Models\VerifCalculeNoteIndiv::where('ref_semestre_id',$semestre)
            ->where('etudant_id',$id)->where('annee_id',$id_annee)->get();

        if (count($verif)>0)
        {
            foreach ($verif as $v)
            {
                $b= App\Models\VerifCalculeNoteIndiv::find($v->id);
                $b->delete();
            }
        }
        $type_controle=Etape::find($etape)->ref_type_controle_id;
        if ($type_controle==1){
            $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
                ->where('ref_semestre_id', $semestre)->where('etape_id', null)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
            if ($notesFinales->count()>0)
            {
                foreach ($notesFinales as $notesF)
                {
                    $nt = App\Models\NoteExamenFinale::find($notesF->id);
                    $nt->delete();
                }
            }
            $verifNot =new App\Models\VerifCalculeNoteIndiv();
            $verifNot->etudant_id=$id;
            $verifNot->ref_semestre_id=$semestre;
            $verifNot->groupe_id=$groupe;
            $verifNot->profil_id=$profil;
            $verifNot->annee_id=$id_annee;
            $verifNot->save();
            //dd($semestreRech);
            $moyen_sem=$moyenne_module=0;


            $moyen_sem=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
            $this->insertSem_semestre($id,$semestre,$groupe,$id_annee,$profil);
            $essey = env('ESSEY');
            $matieres_etudiants = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get();
            foreach ($matieres_etudiants as $matiere)
            {
                $noteE=$notedev=0;
                $notFinal=0;
                $noteExam = NoteExamen::where('matiere_id', $matiere->matiere_id)->where('etudiant_id',$id)
                    ->where('ref_semestre_id', $matiere->ref_semestre_id)
                    ->where('annee_id', $id_annee)->get()->first();

                $noteDevs = NoteDevoir::where('matiere_id', $matiere->matiere_id)
                    ->where('ref_semestre_id', $matiere->ref_semestre_id)->where('etudiant_id',$id)
                    ->where('annee_id', $id_annee)->get()->first();
                if ($noteExam){ $noteE = $noteExam->note; }
                if ($noteDevs){ $notedev = $noteDevs->note; }
                $notFinal= $noteE + $notedev;
                $this->insertNotFinal($id,$matiere->matiere_id,$matiere->ref_semestre_id,$groupe,$id_annee,$profil,$noteE,$notedev,$notFinal);
            }
            $moyen_sem=$moyenne_module=0;
            $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
                ->where('ref_semestre_id', $semestre)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
            $moyen_sem=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
            $this->insertSem_semestre($id,$semestre,$groupe,$id_annee,$profil);
            $essey = env('ESSEY');
            foreach ($notesFinales as $notesF)
            {
                $moyenne_module=0;
                $val=0;
                //val=2 rt =1 valide =0 non valide
                $moyenne_module=$this->moyenne_module($id,$semestre,$groupe,$id_annee,$notesF->modulle_id);
                $valide_module=$this->valide_module($id,$semestre,$groupe,$id_annee,$notesF->modulle_id);
                if ($notesF->note >= 10)
                {
                    $val=1;
                }
                elseif ($notesF->note >= $essey and $valide_module ==1  )
                {
                    $val=1;
                }
                elseif ($notesF->note == $essey and $valide_module ==1  )
                {
                    $val=1;
                }
                elseif ($valide_module ==0 and $moyenne_module >= 5)
                {
                    /* if ($etudian->id == '6027' ){
                          dd('M'.$valide_module.' moy'.$moyenne_module.' n'.$notesF->note.'');
                     }*/
                    $val=2;
                }
                else
                {
                    $val=0;
                }
                $this->insertReleves($id,$notesF->matiere_id,$semestre,$groupe,$notesF->modulle_id,$id_annee,$profil,$notesF->note_dev,$notesF->note_exam,$notesF->note,$moyenne_module,$val);
            }
        }
        if ($type_controle==3){
            $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
                ->where('ref_semestre_id', $semestre)->where('etape_id', null)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();

            $verifNot =new App\Models\VerifCalculeNoteIndiv();
            $verifNot->etudant_id=$id;
            $verifNot->ref_semestre_id=$semestre;
            $verifNot->groupe_id=$groupe;
            $verifNot->profil_id=$profil;
            $verifNot->annee_id=$id_annee;
            $verifNot->save();
            //dd($semestreRech);
            $moyen_sem=$moyenne_module=0;


            $moyen_sem=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
            $this->insertSem_semestre($id,$semestre,$groupe,$id_annee,$profil);
            $essey = env('ESSEY');
            $matieres_etudiants = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get();
            foreach ($matieres_etudiants as $matiere)
            {
                $noteE=$notedev=0;
                $notFinal=0;
                $noteExam = NoteExamenRt::where('matiere_id', $matiere->matiere_id)->where('etudiant_id',$id)
                    ->where('ref_semestre_id', $matiere->ref_semestre_id)
                    ->where('annee_id', $id_annee)->get()->first();

                $noteDevs = NoteDevoir::where('matiere_id', $matiere->matiere_id)
                    ->where('ref_semestre_id', $matiere->ref_semestre_id)->where('etudiant_id',$id)
                    ->where('annee_id', $id_annee)->get()->first();
                if ($noteExam){ $noteE = $noteExam->note; }
                if ($noteDevs){ $notedev = $noteDevs->note; }
                $notFinal= $noteE + $notedev;
                // $this->insertNotFinal($id,$matiere->matiere_id,$matiere->ref_semestre_id,$groupe,$id_annee,$profil,$noteE,$notedev,$notFinal);
            }
            $moyen_sem=$moyenne_module=0;
            $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
                ->where('ref_semestre_id', $semestre)
                ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
            $moyen_sem=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
            $this->insertSem_semestre($id,$semestre,$groupe,$id_annee,$profil);
            $essey = env('ESSEY');
            foreach ($notesFinales as $notesF)
            {
                $moyenne_module=0;
                $val=0;
                //val=2 rt =1 valide =0 non valide
                $moyenne_module=$this->moyenne_module($id,$semestre,$groupe,$id_annee,$notesF->modulle_id);
                $valide_module=$this->valide_module($id,$semestre,$groupe,$id_annee,$notesF->modulle_id);
                if ($notesF->note >= 10)
                {
                    $val=1;
                }
                elseif ($notesF->note >= $essey and $valide_module ==1  )
                {
                    $val=1;
                }
                elseif ($notesF->note == $essey and $valide_module ==1  )
                {
                    $val=1;
                }
                elseif ($valide_module ==0 and $moyenne_module >= 5)
                {
                    /* if ($etudian->id == '6027' ){
                          dd('M'.$valide_module.' moy'.$moyenne_module.' n'.$notesF->note.'');
                     }*/
                    $val=2;
                }
                else
                {
                    $val=0;
                }
                $this->insertReleves($id,$notesF->matiere_id,$semestre,$groupe,$notesF->modulle_id,$id_annee,$profil,$notesF->note_dev,$notesF->note_exam,$notesF->note,$moyenne_module,$val,$notesF->note_rt);
            }
        }
        return $data;
    }


    public function calculerNotes($profil,$semestre,$groupe,$etape)
    {
        $data=1;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $verif=App\Models\VerifCalculeNote::where('ref_semestre_id',$semestre)
            ->where('groupe_id',$groupe)->where('profil_id',$profil)->where('annee_id',$id_annee)->get();
        if (count($verif)>0)
        {
            $data=2;
        }
        else{

            $annee2=Annee::where('etat',2)->get()->first()->id;

            $verifNot =new App\Models\VerifCalculeNote();
            $verifNot->ref_semestre_id=$semestre;
            $verifNot->groupe_id=$groupe;
            $verifNot->profil_id=$profil;
            $verifNot->annee_id=$id_annee;
            $verifNot->save();
            $groupesEtudiants = Etudiant::whereIn('id',EtudMat::where('profil_id',$profil)
                ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->where('groupe',RefGroupe::find($groupe)->libelle)->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                ->orderBy('NODOS', 'ASC')->get();
            foreach ($groupesEtudiants as $etudian) {
                $niveau = Profil::find($profil)->ref_niveau_etude_id;
                $matieres_etudiants = EtudMat::where('etudiant_id',$etudian->id)->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get();
                $releves = App\Models\RelevesNote::where('etudiant_id', $etudian->id)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $annee2)->where('decision', 1)->get();
                $releves1 = App\Models\RelevesNote::where('etudiant_id', $etudian->id)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $annee2)->where('decision', 11)->get();
                foreach ($releves as $relev)
                {
                    $this->insertNotRel($relev->id,$groupe,$id_annee);

                }
                foreach ($releves1 as $relev)
                {
                    $this->insertNotRel($relev->id,$groupe,$id_annee);

                }
                foreach ($matieres_etudiants as $matiere)
                {
                    $noteE=$notedev=0;
                    $notFinal=0;
                    $noteExam = NoteExamen::where('matiere_id', $matiere->matiere_id)->where('etudiant_id',$etudian->id)
                        ->where('ref_semestre_id', $matiere->ref_semestre_id)
                        ->where('annee_id', $id_annee)->get()->first();

                    $noteDevs = NoteDevoir::where('matiere_id', $matiere->matiere_id)
                        ->where('ref_semestre_id', $matiere->ref_semestre_id)->where('etudiant_id',$etudian->id)
                        ->where('annee_id', $id_annee)->get()->first();
                    if ($noteExam){ $noteE = $noteExam->note; }
                    if ($noteDevs){ $notedev = $noteDevs->note; }
                    $notFinal= $noteE + $notedev;
                    $this->insertNotFinal($etudian->id,$matiere->matiere_id,$matiere->ref_semestre_id,$groupe,$id_annee,$profil,$noteE,$notedev,$notFinal);
                }

            }

            foreach ($groupesEtudiants as $etudian)
            {
                $moyen_sem=$moyenne_module=0;
                $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$etudian->id)
                    ->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
                $moyen_sem=$this->moyenne_semestre($etudian->id,$semestre,$groupe,$id_annee);
                $this->insertSem_semestre($etudian->id,$semestre,$groupe,$id_annee,$profil);
                $essey = env('ESSEY');
                foreach ($notesFinales as $notesF)
                {
                    $moyenne_module=0;
                    $val=0;
                    //val=2 rt =1 valide =0 non valide
                    $moyenne_module=$this->moyenne_module($etudian->id,$semestre,$groupe,$id_annee,$notesF->modulle_id);
                    $valide_module=$this->valide_module($etudian->id,$semestre,$groupe,$id_annee,$notesF->modulle_id);
                    if ($notesF->note >= 10)
                    {
                        $val=1;
                    }
                    elseif ($notesF->note >= $essey and $valide_module ==1  )
                    {
                        $val=1;
                    }
                    elseif ($notesF->note == $essey and $valide_module ==1  )
                    {
                        $val=1;
                    }
                    elseif ($valide_module ==0 and $moyenne_module >= 5)
                    {
                        /* if ($etudian->id == '6027' ){
                              dd('M'.$valide_module.' moy'.$moyenne_module.' n'.$notesF->note.'');
                         }*/
                        $type_controle=Etape::find($etape)->ref_type_controle_id;
                        if ($type_controle==3){
                            $val=0;
                        }
                        else {
                            $val=2;
                        }

                    }
                    else
                    {
                        $val=0;
                    }
                    $this->insertReleves($etudian->id,$notesF->matiere_id,$semestre,$groupe,$notesF->modulle_id,$id_annee,$profil,$notesF->note_dev,$notesF->note_exam,$notesF->note,$moyenne_module,$val,$notesF->note_rt);
                }

            }

        }
        return $data;
    }

    public function moyenne_module($id,$semestre,$groupe,$id_annee,$modulle_id)
    {
        $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
            ->where('ref_semestre_id', $semestre)
            ->where('annee_id', $id_annee)->where('modulle_id', $modulle_id)->orderBy('matiere_id')->get();
        $cpt = 0;
        $total=$mye=0;
        foreach ($notesFinales as $notes)
        {
            $mttt=Matiere::find($notes->matiere_id);
            if($mttt){
                $coef=$notes->matiere->coaf;
                $cpt +=$coef;
                $total += ($notes->note*$coef);
            }
        }
        if ($cpt!=0)
        {
            $mye = $total/$cpt;
        }

        return $mye;
    }

    public function valide_module($id,$semestre,$groupe,$id_annee,$modulle_id)
    {
        $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
            ->where('ref_semestre_id', $semestre)
            ->where('annee_id', $id_annee)->where('modulle_id', $modulle_id)->orderBy('matiere_id')->get();
        $cpt = 0;
        $t=1;
        $total=$mye=$valide_module=0;
        $valide=1;
        foreach ($notesFinales as $notes)
        {
            $mttt=Matiere::find($notes->matiere_id);
            if($mttt){
                $coef=$notes->matiere->coaf;
                if ($notes->note ==5)
                { $t=0;}
                if ($notes->note < 5)
                {
                    $valide=0;
                }
                $cpt +=$coef;
                $total += ($notes->note*$coef);
            }
        }
        $mye = $total/$cpt;
        $moyenne_semstre=$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
        $valide_M_S=$this->valide_MODULLES_semestre($id,$semestre,$groupe,$id_annee);
        if ($mye>=10 && $valide==1){ $valide_module =1;  }
        elseif ($mye>10 && $valide_M_S==1 && $valide==1){ $valide_module =1; }
        elseif ($mye>=7 && $valide_M_S==1 && $valide==1){ $valide_module =1; }
        else { $valide_module =0;
        }
        return $valide_module;
    }

    public function moyenne_semestre($id,$semestre,$groupe,$id_annee)
    {
        $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
            ->where('ref_semestre_id', $semestre)
            ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
        $cpt = 0;
        $total=$mye=0;
        foreach ($notesFinales as $notes)
        {
            $mttt=Matiere::find($notes->matiere_id);
            if($mttt){
                $coef=$notes->matiere->coaf;
                $cpt +=$coef;
                $total += ($notes->note*$coef);
            }

        }
        if ($cpt !=0)
            $mye = $total/$cpt;
        else  $mye=0.000;
        return $mye;
    }
    public function moyenne_semestreFn($id,$semestre,$groupe,$id_annee)
    {
        $notesFinales = App\Models\MoyennesSemestre::where('etudiant_id',$id)
            ->where('note', '>',1)->where('ref_semestre_id', $semestre)
            ->where('annee_id', $id_annee)->get();
        $cpt = 0;
        $total=$mye=0;
       if ($notesFinales->count()>0){
           $mye=$notesFinales->first()->note;
       }

        return $mye;
    }
    public function insertSem_semestre($id,$semestre,$groupe,$id_annee,$profil)
    {
        $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
            ->where('ref_semestre_id', $semestre)
            ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
        $cpt = 0;
        $total=$mye=0;
        foreach ($notesFinales as $notes)
        {
            $mttt=Matiere::find($notes->matiere_id);
            if($mttt){
                $coef=$notes->matiere->coaf;
                $cpt +=$coef;
                $total += ($notes->note*$coef);
            }
        }
        $mye = $total/$cpt;
        $val=1;
        $v=$this->valide_MODULLES_semestre($id,$semestre,$groupe,$id_annee);
        if ($v==1 and $mye >=10)
            $val=1;
        else
            $val=0;
        $moySem =new App\Models\MoyennesSemestre();
        $moySem->etudiant_id=$id;
        $moySem->profil_id=$profil;
        $moySem->ref_groupe_id=$groupe;
        $moySem->ref_semestre_id=$semestre;
        $moySem->annee_id=$id_annee;
        $moySem->note=$mye;
        $moySem->decision = $val;
        $moySem->save();
    }

    public function valide_MODULLES_semestre($id,$semestre,$groupe,$id_annee)
    {
        $moyenSem =$this->moyenne_semestre($id,$semestre,$groupe,$id_annee);
        $notesFinales = App\Models\NoteExamenFinale::where('etudiant_id',$id)
            ->where('ref_semestre_id', $semestre)
            ->where('annee_id', $id_annee)->orderBy('matiere_id')->get();
        $cpt = 0;
        $total=$mye=0;
        $valide=1;
        foreach ($notesFinales as $notes)
        {
            $mye=$this->moyenne_module($notes->etudiant_id,$semestre,$groupe,$id_annee,$notes->modulle_id);
            if ($mye<7 )
                $valide=0;
        }
        if ($valide ==1 and $moyenSem < 10)
        {
            $valide=0;
        }
        return $valide;
    }

    public function insertNotFinal($id,$matiere_id,$ref_semestre_id,$groupe,$id_annee,$profil,$noteE,$notedev,$notFinal){
        $mdulles1 = Matiere::find($matiere_id);
        $noteExamRT = NoteExamenRt::where('matiere_id', $matiere_id)->where('etudiant_id',$id)
            ->where('ref_semestre_id', $ref_semestre_id)
            ->where('annee_id', $id_annee)->get();
        $noteRT=-1;
        if($noteExamRT->count()>0){
            $noteRT=$noteExamRT->first()->note;
        }
        if ($noteRT>$noteE)
        {
            $notFinal=$noteRT;
            $notFinal = $notedev + $noteRT;
        }
        if($mdulles1){ $mdulles =$mdulles1->modulle_id;
            $note=new App\Models\NoteExamenFinale();
            $note->profil_id=$profil;
            $note->etudiant_id=$id;
            $note->ref_semestre_id=$ref_semestre_id;
            $note->matiere_id=$matiere_id;
            $note->note_dev=$notedev;
            $note->note_exam=$noteE;
            $note->note_rt=$noteRT;
            $note->note=$notFinal;
            $note->annee_id=$id_annee;
            $note->modulle_id=$mdulles;
            $note->ref_groupe_id=$groupe;
            $note->save();
        }
    }

    public function insertReleves($id,$matiere_id,$ref_semestre_id,$groupe,$modulle_id,$id_annee,$profil,$notedev,$noteE,$notFinal,$moyenne_module,$val,$note_rt){
        $note=new App\Models\RelevesNote();
        $note->profil_id=$profil;
        $note->etudiant_id=$id;
        $note->ref_semestre_id=$ref_semestre_id;
        $note->matiere_id=$matiere_id;
        $note->note_dev=$notedev;
        $note->note_exam=$noteE;
        $note->note_rt=$note_rt;
        $note->note=$notFinal;
        $note->annee_id=$id_annee;
        $note->modulle_id=$modulle_id;
        $note->ref_groupe_id=$groupe;
        $note->noteModule=$moyenne_module;
        $note->decision=$val;
        $note->save();
    }


    public function insertNotRel($id,$groupe,$id_annee){
        $releve=App\Models\RelevesNote::find($id);
        $note=new App\Models\NoteExamenFinale();
        $note->profil_id=$releve->profil_id;
        $note->etudiant_id=$releve->etudiant_id;
        $note->ref_semestre_id=$releve->ref_semestre_id;
        $note->matiere_id=$releve->matiere_id;
        $note->note_dev=$releve->note_dev;
        $note->note_exam=$releve->note_exam;
        $note->note_rt=$releve->note_rt;
        $note->note=$releve->note;
        $note->annee_id=$id_annee;
        $note->anonymat_id=$releve->annee_id;
        $note->modulle_id=$releve->modulle_id;
        $note->ref_groupe_id=$groupe;
        $note->save();

        /*$note=new App\Models\RelevesNote();
        $note->profil_id=$releve->profil_id;
        $note->etudiant_id=$releve->etudiant_id;
        $note->ref_semestre_id=$releve->ref_semestre_id;
        $note->matiere_id=$releve->matiere_id;
        $note->note_dev=$releve->note_dev;
        $note->note_exam=$releve->note_exam;
        $note->note_rt=$releve->note_rt;
        $note->note=$releve->note;
        $note->annee_id=$id_annee;
        $note->modulle_id=$releve->modulle_id;
        $note->ref_groupe_id=$groupe;
        $note->noteModule=$releve->noteModule;
        $note->decision=$releve->decision;
        $note->save();*/
    }

    public function getNoteIndivModifier($id,$semestre,$etape)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etape_note=Etape::find($etape)->note;
        $type_controle=Etape::find($etape)->ref_type_controle_id;
        $titre=' تفاصيل النتائج المغيرة ';
        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $html .='<input type="hidden" value="'.$semestre.'" name="semestre">';
        $html .='<input type="hidden" value="'.$id.'" name="id">';
        $html .='<input type="hidden" value="'.$etape.'" name="etape">';
        $etudiant =Etudiant::find($id);
        $html.=''.$etudiant->NODOS .'-->';
        if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
            $html .=' '.$etudiant->NOMF;
        else
            $html .=' <div align="right">'.$etudiant->NOMA .' '.$etudiant->NOMF.'</div>';
        $html .='<br>';
        $adm=1;
        $html .='<table border="1" style="width: 100%">
                <thead>
                <tr>
                    <th>'.trans("text_me.machine").'</th>
                    <th>'.trans("text_me.type").'</th>
                   <th>'.trans("text_me.ancienNote").'</th>
                    <th>'.trans("text_me.noveauNote").'</th>
                    <th>'.trans("text_me.user").'</th>
                    <th>'.trans("text_me.date").'</th>
                    <th style="width: 30%">'.trans("text_me.matiere").'</th>
                </tr>
                </thead>';
        $etudiants='';
        $etudiants = App\Models\NoteModifier::where('etudiant_id',$id)->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get();
        foreach ($etudiants as $etudian) {
            $etat='الاختبار';
            if ($etudian->etat=='Examen'){
                $etat='الامتحان';
            }
            if ($etudian->etat=='RT'){
                $etat='استدراك';
            }
            $html .= '<tr >';
            $html .= '<td >'.$etudian->machine.'</td>';
            $html .= '<td >'.$etat.'</td>';
            $html .= '<td >'.$etudian->oldnote.'</td>';
            $html .= '<td >'.$etudian->newnote.'</td>';
            $html .= '<td>'.$etudian->user->name.'<br>'.$etudian->user->username.'</td>';
            $html .= '<td >'.$etudian->updated_at.'</td>';
            $html .= '<td style="width: 30%">'.$etudian->matiere->libelle.'</td>';
            $html .= '</tr >';
        }
        $html .='</table>';
        $html .='<br><br>تاريخ السحب '.date('Y-m-d');
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_pr').'');
        PDF::SetSubject(''.trans('text_me.liste_pr').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }


    public function getNoteIndiv($id,$semestre,$etape)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etape_note=Etape::find($etape)->note;
        $type_controle=Etape::find($etape)->ref_type_controle_id;
        $html='<input type="hidden" value="'.$semestre.'" name="semestre">';
        $html .='<input type="hidden" value="'.$id.'" name="id">';
        $html .='<input type="hidden" value="'.$etape.'" name="etape">';
        $etudiant =Etudiant::find($id);
        $html.=''.$etudiant->NODOS .'-->';
        if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
            $html .=' '.$etudiant->NOMF;
        else
            $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
        $html .='<br>';
        $adm=1;
        $html .='<table border="1" style="width: 100%">
                <thead>
                <tr>

                    <th style="width: 80%">'.trans("text_me.matiere").'</th>
                    <th>'.trans("text_me.note").'</th>
                </tr>
                </thead>';
        $etudiants='';
        if ($semestre == 1 or $semestre== 3 or $semestre ==5 )
        {
            $etudiants = EtudMat::where('etudiant_id',$id)->whereIN('ref_semestre_id',[1,3,5])->where('annee_id',$id_annee)->get();
        }
        if ($semestre == 2 or $semestre== 4 or $semestre == 6 )
        {
            $etudiants = EtudMat::where('etudiant_id',$id)->whereIN('ref_semestre_id',[2,4,6])->where('annee_id',$id_annee)->get();
        }
        //$verif=array();
        foreach ($etudiants as $etudian) {
            $html .= '<tr >';
            if (Matiere::find($etudian->matiere_id))
            {
                $html .= '<td>'.$etudian->matiere->libelle.' -> '.$etudian->ref_semestre->libelle.'</td>' ;
            }
            else{
                $html .= '<td></td>' ;
            }
            $verif='';
            if ($type_controle==1) {
                $verif = NoteExamen::where('matiere_id', $etudian->matiere_id)->where('etudiant_id',$id)
                    ->where('ref_semestre_id', $etudian->ref_semestre_id)->where('etape_id', $etape)
                    ->where('annee_id', $id_annee)->get()->first();
            }
            if ($type_controle==2) {
                $verif = NoteDevoir::where('matiere_id', $etudian->matiere_id)
                    ->where('ref_semestre_id', $etudian->ref_semestre_id)->where('etape_id', $etape)->where('etudiant_id',$id)
                    ->where('annee_id', $id_annee)->get()->first();
            }
            if ($type_controle==3) {
                $verif = NoteExamenRt::where('matiere_id', $etudian->matiere_id)
                    ->where('ref_semestre_id', $etudian->ref_semestre_id)->where('etape_id', $etape)->where('etudiant_id',$id)
                    ->where('annee_id', $id_annee)->get()->first();
            }
            // dd($verif);
            if ($verif)
            {
                $html .= '<td align="right"><input type="number" value="' . $verif->note. '" name="note' . $etudian->matiere_id . '"  id="note' . $etudian->matiere_id . '" required size="8" onchange="test(this.value,' . $etape_note . ');" min="0" max="' . $etape_note . '" step="0.001"></td>';
            }
            else {
                $html .= '<td align="right"><input type="number" name="note' . $etudian->matiere_id . '"  id="note' . $etudian->matiere_id . '" required size="8" onchange="test(this.value,' . $etape_note . ');" min="0" max="' . $etape_note . '" step="0.001"></td>';
            }
            $html .= '</tr >';
        }
        $html .='</table>';

        return view($this->module.'.ajax.getNoteIndiv',['html'=>$html,'adm'=>$adm]);
    }

    public function getNoteIndivan($id,$semestre,$etape,$profil)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();

        $html='<input type="hidden" value="'.$semestre.'" name="semestre">';
        $html .='<input type="hidden" value="'.$id.'" name="id">';
        $html .='<input type="hidden" value="'.$etape.'" name="etape">';
        $etudiant =Etudiant::find($id);
        $html.=''.$etudiant->NODOS .'-->';
        if (trim($etudiant->NOMF) == trim($etudiant->NOMA))
            $html .=' '.$etudiant->NOMF;
        else
            $html .=' '.$etudiant->NOMA .' '.$etudiant->NOMF;
        $html .='<br>';
        $adm=1;
        $html .='<table border="1" style="width: 100%">
                <thead>
                <tr>
                    <th style="width: 80%">'.trans("text_me.matiere").'</th>
                    <th>'.trans("text_me.note").'</th>
                </tr>
                </thead>';
        $etudiants ='';
        $etape_note=20;
        $semestreRech=1;
        if ($semestre == 1)
        {
            $semestreRech=1;
        }
        if ($semestre == 3)
        {
            $semestreRech=1;
        }
        if ($semestre == 5)
        {
            $semestreRech=3;
        }
        if ($semestre == 2)
        {
            $semestreRech=2;
        }
        if ($semestre == 4)
        {
            $semestreRech=2;
        }
        if ($semestre == 6)
        {
            $semestreRech=4;
        }
        // dd($semestreRech);
        $matiere = EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get()->first();

        //$id_matiere=$matiere->matiere_id;

        $profil_id=$profil;//Matiere::find($id_matiere)->profil_id;

        $groupesEtudiants =EtudMat::where('etudiant_id',$id)->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get();
        $etudiant = Etudiant::find($id);
        $libGrp = $etudiant->groupe;
        $groupe = RefGroupe::where('libelle_ar', $libGrp)->get()->first()->id;
        foreach ($groupesEtudiants as $matiere)
        {
            $noteE=$notedev=0;
            $notFinal=0;
            $noteExam = NoteExamen::where('matiere_id', $matiere->matiere_id)->where('etudiant_id',$id)
                ->where('ref_semestre_id', $matiere->ref_semestre_id)
                ->where('annee_id', $id_annee)->get()->first();

            $noteDevs = NoteDevoir::where('matiere_id', $matiere->matiere_id)
                ->where('ref_semestre_id', $matiere->ref_semestre_id)->where('etudiant_id',$id)
                ->where('annee_id', $id_annee)->get()->first();
            if ($noteExam){ $noteE = $noteExam->note; }
            if ($noteDevs){ $notedev = $noteDevs->note; }
            $notFinal= $noteE + $notedev;
            $releves1=App\Models\NoteExamenFinale::where('etudiant_id', $id)->where('ref_semestre_id', $semestreRech)
                ->where('annee_id', $id_annee)->where('matiere_id', $matiere->matiere_id)->get();
            if ($releves1->count()>0)
            { }
            else{
                $this->insertNotFinal($id,$matiere->matiere_id,$matiere->ref_semestre_id,$groupe,$id_annee,$profil_id,$noteE,$notedev,$notFinal);
            }

        }

        $html .='<input type="hidden" value="'.$profil_id.'" name="profil">';
        $releves=App\Models\NoteExamenFinale::where('etudiant_id', $id)->where('ref_semestre_id', $semestreRech)
            ->where('annee_id', $id_annee)->where('etape_id', 0)->get();

        if ($releves->count()>0)
        {
            $html .='<input type="hidden" value="edit" name="cas">';
            foreach ($releves as $releve) {
                $html .= '<tr >';
                $html .= '<td>' . $releve->matiere->libelle . ' -> ' . $releve->ref_semestre->libelle . '</td>';
                $html .= '<td align="right"><input type="number" value="' . $releve->note. '" name="note' . $releve->matiere_id . '"  id="note' . $releve->matiere_id . '" required size="8" onchange="test(this.value,' . $etape_note . ');" min="0" max="' . $etape_note . '" step="0.001"></td>';
                $html .= '</tr >';
            }
        }
        else{
            $html .='<input type="hidden" value="add" name="cas">';
            $ancienMatiere= Matiere::whereNotIn("id", EtudMat::where('etudiant_id',$id)
                ->where('ref_semestre_id',$semestreRech)->where('annee_id',$id_annee)->get()->pluck("matiere_id"))
                ->where('profil_id',$profil_id)->where('ref_semestre_id',$semestreRech)->get();
            foreach ($ancienMatiere as $ancienMatier) {
                $html .= '<tr >';
                $html .= '<td>'.$ancienMatier->libelle.' -> '.$ancienMatier->ref_semestre->libelle.'</td>' ;
                $html .= '<td align="right"><input type="number" name="note' . $ancienMatier->id . '"  id="note' .  $ancienMatier->id . '" required size="8" onchange="test(this.value,' . $etape_note . ');" min="0" max="' . $etape_note . '" step="0.001"></td>';
                $html .= '</tr >';
            }
        }

        $html .='</table>';

        return view($this->module.'.ajax.getNoteIndivan',['html'=>$html,'adm'=>$adm]);
    }

    public function getNoEtudiants($id_matiere,$etape_id,$profil,$semestre,$groupe)
    {
        $adm=1;
        $val='';
        $niveau=Profil::find($profil);
        $ref_niveau_etude_id =$niveau->ref_niveau_etude_id;
        $p=0;

        if (Auth::user()->code !=null)
        {
            $cod=App\Models\Departement::find(Auth::user()->code);
            if ($cod){
                $adm=$cod->etat_saisi;
                if ($cod->etat_saisi==1){  $val='v';}
            }
        }

        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etape_note=Etape::find($etape_id)->note;
        $type_controle=Etape::find($etape_id)->ref_type_controle_id;
        if ($ref_niveau_etude_id==5 and $semestre==4){
            $p=1;
            if ($type_controle==1 ) {
                $etape_note=20;
            }
            else{
                $semestre=333;
            }

        }

        $verif=array();
        if ($type_controle==1 ) {
            $verif = NoteExamen::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        if ($type_controle==2 and $p==0) {
            $verif = NoteDevoir::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        if ($type_controle==3 and $p==0) {

            $verif = NoteExamenRt::where('matiere_id', $id_matiere)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        $imp='';

        $html = '<table style="width:100%">
                <tr><td style="width:80%">'.trans('text_me.nodos').'</td><td style="width:20%; " align="center">'.trans('text_me.note').'/'.$etape_note.'</td></tr>';
        if (count($verif)>0)
        {

            /*if (Auth::user()->hasAccess([1]))
                $adm=1;
            else
                $adm=2;*/
            $imp .='<div  id="formstpdf" name="formstpdf" class=""  method="get">
                    <div class="col-md-12 form-row">
                    <div class="col-md-8 text-center form-group"><a href="#" onclick="get_NoteImp()"  class="social-icon linkedin"><i class="fa fa-print"></i></a></div>';

            if ($etape_id)
                $html .='<input type="hidden" name="sit" id="sit" value="save"> ';
            if ($type_controle==1)
            {
                $etudiants= NoteExamen::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
            }
            if ($type_controle==2 and $p==0)
            {
                $etudiants= NoteDevoir::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
            }
            if ($type_controle==3 and $p==0)
            {
                $etudiants= NoteExamenRt::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
            }
            $disab='';
            if ($etudiants->first()->etat == 'a')
            {
                $val ='a';  $disab='disabled';
                if (Auth::user()->hasAccess([6,3]) and Auth::user()->code ==null)
                {
                    $val ='';
                    $disab='disabled';
                }
                if (Auth::user()->hasAccess([1]))
                {
                     $val ='a';
                   }
            }
            else{
                if (Auth::user()->hasAccess([1]))
                {
                    if ($disab=='disabled')
                    { $val ='a'; }
                    $imp .='<div class="col-md-3 text-center form-group"><a href="#" onclick="Delete_Note()"  class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></div>';
                    $imp .='<div class="col-md-3 text-center form-group"><a href="#" onclick="Delete_NoteDoublon()"  class="btn btn-sm btn-info"><i class="fa fa-trash-alt"></i></a></div>';
                }

            }
            $imp .=' </div></div>';
            foreach ($etudiants as $etudian) {
                if (Etudiant::find($etudian->etudiant_id))
                {
                    $html .= '<tr><td>'.$etudian->etudiant->NODOS.'</td>' ;
                    $html .= '<td align="right"><input '.$disab.' type="number" value="'.$etudian->note.'" name="note'.$etudian->id.'" id="note'.$etudian->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001" ></td></tr>' ;

                }
            }
        }
        else{

            $html .='<input type="hidden" name="sit" id="sit" value="add"> ';
            $adm=1;
            $val='';
            if ($type_controle==3 and $p==0) {
                $etudiants = Etudiant::whereIn('id', App\Models\RelevesNote::where('matiere_id', $id_matiere)->where('ref_semestre_id', $semestre)
                    ->where('annee_id', $id_annee)->where('decision', 2)->get()->pluck("etudiant_id"))
                    ->where('DECF', '1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();
                //dd($etuidants);
            }
            else  {
                $etudiants = Etudiant::whereIn('id',EtudMat::where('matiere_id',$id_matiere)
                    ->where('ref_semestre_id',$semestre)->where('annee_id',$id_annee)->get()->pluck("etudiant_id"))->where('groupe',RefGroupe::find($groupe)->libelle)->where('DECF','1')->orderByRaw('LENGTH(NODOS)', 'ASC')
                    ->orderBy('NODOS', 'ASC')->get();
            }
            foreach ($etudiants as $etudian) {
                $html .= '<tr ><td onmouseover="bgChange(this.style.backgroundColor)"  onmouseout="bgChange(\'transparent\')">'.$etudian->NODOS.'</td>' ;
                $html .= '<td align="right"><input type="number" name="note'.$etudian->id.'"  id="note'.$etudian->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001"></td></tr>' ;
            }
//dd($etudiants);
        }
        /*if (count($verif)>0)
        {
            $html .='<input type="hidden" name="sit" id="sit" value="save"> ';
            $etudiants= NoteExamen::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id',$etape_id)
                ->where('annee_id',$id_annee)->get();
            foreach ($etudiants as $etudiant) {
                $html .= '<tr><td>'.$etudiant->anonymat->anonymat.'</td>' ;
                $html .= '<td align="right"><input type="number" value="'.$etudiant->note.'" name="note'.$etudiant->id.'" id="note'.$etudiant->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001"></td></tr>' ;
            }
        }
        else{
            $html .='<input type="hidden" name="sit" id="sit" value="add"> ';
            $etudiants = Anonymat::whereIn('etudiant_id',EtudMat::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->get()->pluck("etudiant_id"))->get();
            foreach ($etudiants as $etudian) {
                $html .= '<tr><td>'.$etudian->etudiant->NODOS.'</td>' ;
                $html .= '<td align="right"><input type="number" name="note'.$etudian->id.'"  id="note'.$etudian->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001"></td></tr>' ;
            }

        }*/
        //dd(count(EtudMat::where('matiere_id',$id_matiere)->get()->pluck("etudiant_id")));
        $html .='</table>';

        $html .='<input type="hidden" name="groupe" value="'.$groupe.'"> ';
        $html .='<input type="hidden" name="semestre" value="'.$semestre.'"> ';
        $html .='<input type="hidden" name="profil" value="'.$profil.'"> ';
        $html .='<input type="hidden" name="etape_id" value="'.$etape_id.'"> ';
        $html .='<input type="hidden" name="id_matiere" value="'.$id_matiere.'"> ';
        return view($this->module.'.ajax.getEtudiants',['html'=>$html,'imp'=>$imp,'adm'=>$adm,'val'=>$val,'semestre'=>$semestre]);
    }

    public function get_NoteImp($id_matiere,$etape_id,$profil,$semestre,$groupe)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etape_note=Etape::find($etape_id)->note;
        $type_controle=Etape::find($etape_id)->ref_type_controle_id;
        $typeControl =Etape::find($etape_id)->ref_type_controle->libelle_ar;
        $titre = trans('text_me.liste_col').'<br>'.$typeControl;
        $matiere=Matiere::find($id_matiere);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 15%">'.trans('text_me.groupe_ar').'</th>
                    <th align="right" style="width: 15%">'.trans('text_me.semestre_ar').'</th>
                    <th align="center" style="width: 35%">'.trans('text_me.profil_ar').'</th>
                    <th align="right" style="width: 35%">'.trans('text_me.matiere_ar').'</th>
                </tr>';
        $titre .='<tr>
                    <td align="center"> '.$groupe_libelle.'</td>
                    <td align="center" style="width: 15%">'.$semestre_libelle.'</td>
                    <td align="center" style="width: 35%">'.Profil::find($profil)->libelle.'</td>
                    <td align="right" style="width: 35%">'.$matiere->libelle.'</td>
                </tr>
                </table>
                ';
        $entete = $this->enteteServiceExamenen($titre, 'L');
        $html=$entete;
        $verif=array();
        if ($type_controle==1) {
            $verif = NoteExamen::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        if ($type_controle==2) {
            $verif = NoteDevoir::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        if ($type_controle==3) {
            $verif = NoteExamenRt::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        $imp='';
        $html .= '<table style="width:100%" border="1">
                <thead><tr><td style="width:20%; " align="center">'.trans('text_me.note_ar').'/'.$etape_note.'</td>
                <td style="width:60%" align="right">'.trans('text_me.nom').'</td>
                <td style="width:10%" align="right">'.trans('text_me.numero').'</td>
                <td style="width:10%" align="right">'.trans('text_me.rang').'</td></tr></thead>';
        if (count($verif)>0)
        {
            if ($etape_id)
                $html .='<input type="hidden" name="sit" id="sit" value="save"> ';
            if ($type_controle==1)
            {
                $etudiants= NoteExamen::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
            }
            if ($type_controle==2)
            {
                $etudiants= NoteDevoir::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
            }
            if ($type_controle==3)
            {
                $etudiants= NoteExamenRt::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
            }
            $html .='<tbody>';
            $i=0;
            foreach ($etudiants as $etudian) {
                $i +=1;
                $nom='';
                $etud=Etudiant::find($etudian->etudiant_id);
                if ($etud)
                {
                    $html .= '<tr>' ;
                    $html .= '<td align="center" style="width:20%">'.$etudian->note.'</td>';
                    if (trim($etudian->etudiant->NOMF) == trim($etudian->etudiant->NOMA))
                        $nom=' '.$etudian->etudiant->NOMF;
                    else
                        $nom .=' '.$etudian->etudiant->NOMA .' '.$etudian->etudiant->NOMF;
                    $html .= ' <td align="right" style="width:60%">'.$nom.'</td>';
                    $html .= ' <td align="right" style="width:10%">'.$etudian->etudiant->NODOS.'</td>
                            <td align="right" style="width:10%">'.$i.'</td>
                      </tr>' ;
                }
            }
        }
        $html .='</tbody></table>';

        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_pr').'');
        PDF::SetSubject(''.trans('text_me.liste_pr').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
        // return view($this->module.'.ajax.getEtudiants',['html'=>$html,'imp'=>$imp]);
    }

    public function Delete_NoteDoublon($id_matiere,$etape_id,$profil,$semestre,$groupe)
    {
        $verifff=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etape_note=Etape::find($etape_id)->note;
        $type_controle=Etape::find($etape_id)->ref_type_controle_id;
        $typeControl =Etape::find($etape_id)->ref_type_controle->libelle_ar;
        $titre = trans('text_me.liste_col').'<br>'.$typeControl;
        $matiere=Matiere::find($id_matiere);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;

        $verif=array();
        if ($type_controle==1) {
            $verif = NoteExamen::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->orderBy('id','DESC')->get()->pluck("etudiant_id");
        }
        if ($type_controle==2) {
            $verif = NoteDevoir::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->orderBy('id','DESC')->get()->pluck("etudiant_id");
        }
        if ($type_controle==3) {
            $verif = NoteExamenRt::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->orderBy('id','DESC')->get()->pluck("etudiant_id");
        }
        $imp='';
        $cpt=0;
        $sup='d';
        if (count($verif)>0)
        {

            if ($type_controle==1)
            {

                $etudiants= NoteExamen::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->orderBy('etudiant_id','DESC')->get();
               // $verifff=$etudiants->first()->etudiant_id;

                foreach ($etudiants as $etudian)
                {
                    $cpt +=1;
                    //if ($verifff== $etudian->etudiant_id and  $sup=='d') { $sup='a'; }
                    //if (trim($verifff) == trim($etudian->etudiant_id) and $sup=='a') { dd($sup.'h'); }
                   /* if ((trim($verifff) == trim($etudian->etudiant_id)) and ($cpt>2))
                    {
                        $sup='sup';

                        //dd($verifff.'v '.$etudian->etudiant_id.' cp'.$cpt);
                    }*/
                    if ($verifff==$etudian->etudiant_id)
                    {

                        $etud= NoteExamen::find($etudian->id);
                        $etud->Delete();
                    }
                    $verifff=$etudian->etudiant_id;

                }

                return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
            }
            if ($type_controle==2)
            {
                $etudiants= NoteDevoir::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->orderBy('etudiant_id','DESC')->get();
                $verifff=$etudiants->first()->etudiant_id;
                foreach ($etudiants as $etudian)
                {
                    if ($verifff==$etudian->etudiant_id){
                        $etud= NoteDevoir::find($etudian->id);
                        $etud->Delete();
                    }
                    $verifff=$etudian->etudiant_id;
                }
                return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
            }
            if ($type_controle==3)
            {
                $etudiants= NoteExamenRt::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->orderBy('etudiant_id','DESC')->get();
                $verifff=$etudiants->first()->etudiant_id;
                foreach ($etudiants as $etudian)
                {
                    if ($verifff==$etudian->etudiant_id){
                        $etud= NoteExamenRt::find($etudian->id);
                        $etud->Delete();
                    }
                    $verifff=$etudian->etudiant_id;
                }
                return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
            }

        }

    }
    public function Delete_Note($id_matiere,$etape_id,$profil,$semestre,$groupe)
    {
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();
        $etape_note=Etape::find($etape_id)->note;
        $type_controle=Etape::find($etape_id)->ref_type_controle_id;
        $typeControl =Etape::find($etape_id)->ref_type_controle->libelle_ar;
        $titre = trans('text_me.liste_col').'<br>'.$typeControl;
        $matiere=Matiere::find($id_matiere);
        $groupe_libelle=RefGroupe::find($groupe)->libelle;
        $semestre_libelle=RefSemestre::find($semestre)->libelle;

        $verif=array();
        if ($type_controle==1) {
            $verif = NoteExamen::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        if ($type_controle==2) {
            $verif = NoteDevoir::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        if ($type_controle==3) {
            $verif = NoteExamenRt::where('matiere_id', $id_matiere)->where('profil_id', $profil)
                ->where('ref_semestre_id', $semestre)->where('etape_id', $etape_id)
                ->where('annee_id', $id_annee)->get()->pluck("etudiant_id");
        }
        $imp='';
        if (count($verif)>0)
        {

            if ($type_controle==1)
            {
                $etudiants= NoteExamen::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
                foreach ($etudiants as $etudian)
                {
                    $etud= NoteExamen::find($etudian->id);
                    $etud->Delete();
                }

                return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
            }
            if ($type_controle==2)
            {
                $etudiants= NoteDevoir::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
                foreach ($etudiants as $etudian)
                {
                    $etud= NoteDevoir::find($etudian->id);
                    $etud->Delete();
                }
                return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
            }
            if ($type_controle==3)
            {
                $etudiants= NoteExamenRt::where('matiere_id',$id_matiere)->where('profil_id',$profil)
                    ->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->where('etape_id', $etape_id)
                    ->where('annee_id',$id_annee)->get();
                foreach ($etudiants as $etudian)
                {
                    $etud= NoteExamenRt::find($etudian->id);
                    $etud->Delete();
                }
                return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
            }

        }

    }
}
