<?php
namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Profil;
use App\Models\NoteConcour;
use App\Models\Anonymatsconcour;
use App\Models\NoteExamen;
use App\Models\RefSemestre;
use Illuminate\Http\Request;
use App\Http\Requests\FamilleRequest;
use App\Models\Famille;
use App\Models\RefTypesFamille;;
use App\Models\Candidat;
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
use App\Models\NoteDevoir;
use App\Models\NoteExamenRt;
use App\Models\MatieresConcour;
use function GuzzleHttp\Promise\all;
use App\Models\NoteConcoursFinale;
//use Auth;

class ExamenCONController extends Controller
{
    private $module = 'examensCns';
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {

        /*for($i=0;$i<10;$i++){
            $j=random_int ( 1000 ,  1200 );
          echo  $j.'<br>';
    }*/
        $matieres=MatieresConcour::where('id','<>',3)->get();
        return view($this->module.'.index',['matieres'=> $matieres]);
    }

    public function getDT($profil='all')
    {

            $etudiants = Etudiant::where('profil_id',$profil);
        return DataTables::of($etudiants)
            ->addColumn('actions', function(Etudiant $etudiants) {
                $html = '<div class="btn-group">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$etudiants->id.',\''.$this->module.'\')" data-toggle="tooltip" data-placement="top" title="'.trans('text.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                 $html .='</div>';
                return $html;
            })

            ->rawColumns(['id','actions'])
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
    public function saisirnote(Request $request)
    {
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();


        if ($request->sit == 'add') {
            $candidats = Anonymatsconcour::where('pacquet', $request->pacquet)->orderby('anonymat')->get();
            foreach ($candidats as $candidat) {
                $this->validate($request, [
                    'note' . $candidat->id . '' => 'required',
                ]);
            }
            foreach ($candidats as $candidat) {
                $noteecnc = new NoteConcour();
                $noteecnc->pacquet = $request->pacquet;
                $noteecnc->matieres_concour_id = $request->matiere;
                $noteecnc->annee_id = $id_annee;
                $noteecnc->anonymat_id = $candidat->id;
                $noteecnc->candidat_id = $candidat->candidat_id;
                if ($request->correction== 1)
                    $noteecnc->note1 = $request->input('note' . $candidat->id . '');
                if ($request->correction== 2)
                    $noteecnc->note2 = $request->input('note' . $candidat->id . '');
                $noteecnc->save();
                $etat = 1;
            }
        }
        if ($request->sit == 'edit') {
            $candidats = Anonymatsconcour::where('pacquet', $request->pacquet)->orderby('anonymat')->get();
            foreach ($candidats as $candidat)
            {
                $this->validate($request, [
                    'note' . $candidat->id . '' => 'required',
                ]);
            }
            foreach ($candidats as $candidat)
            {
                $noteecnc= NoteConcour::find(NoteConcour::where('matieres_concour_id',$request->matiere)->where('pacquet',$request->pacquet)->where('annee_id',$id_annee)->get()->first()->id);
                if ($request->correction== 1)
                    $noteecnc->note1 = $request->input('note' . $candidat->id . '');
                if ($request->correction== 2)
                    $noteecnc->note2 = $request->input('note' . $candidat->id . '');
                $noteecnc->save();
                $etat=1;
            }
        }
        if ($request->sit == 'save') {
            $candidats=NoteConcour::where('matieres_concour_id',$request->matiere)->where('pacquet',$request->pacquet)->where('annee_id',$id_annee)->get();
            foreach ($candidats as $candidat)
            {
                $this->validate($request, [
                    'note' . $candidat->id . '' => 'required',
                ]);
            }
            foreach ($candidats as $candidat)
            {

                $noteexam= NoteConcour::find($candidat->id);
                if ($request->correction== 1)
                    $noteexam->note1=$request->input('note' . $candidat->id . '');
                if ($request->correction== 2)
                    $noteexam->note2=$request->input('note' . $candidat->id . '');
                $noteexam->save();
                $etat=2;
            }
        }
        return response()->json($etat, 200);
    }

    public function saisirnote3(Request $request)
    {
        $etat=0;
        $inscip=new InscriptionController();
        $id_annee=$inscip->annee_id();

            $candidats=NoteConcour::where('etat_note3',3)->where('matieres_concour_id',$request->matiere)->where('pacquet3',$request->pacquet)->where('annee_id',$id_annee)->get();
            foreach ($candidats as $candidat)
            {
                $this->validate($request, [
                    'note' . $candidat->id . '' => 'required',
                ]);
            }
            foreach ($candidats as $candidat)
            {
                $noteexam= NoteConcour::find($candidat->id);
                $noteexam->note3=$request->input('note' . $candidat->id . '');
                $noteexam->save();
                $etat=2;
            }

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
        $html .='<table style="width: 100%" border="1">
                <tr>
                    <th align="right" style="width: 20%">'.trans('text_me.presence').'</th>
                    <th align="right" style="width: 60%">'.trans('text_me.nom_complet').'</th>
                    <th align="right" style="width: 20%">'.trans('text_me.numero').'</th>
                </tr>';
        $etuidants=Etudiant::whereIn('id',EtudMat::where('matiere_id',$id)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->get()->pluck("etudiant_id"))->orderBy('nodos')->get();
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

    public function getalletudiantsAnonymes($id,$profil,$semestre,$groupe)
    {
        $titre = trans('text_me.liste_col');
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
                    <th align="right" style="width: 40%">'.trans('text_me.numero').'</th>
                    <th align="right" style="width: 30%">'.trans('text_me.anonymat_ar').'</th>
                </tr>';
        $etuidants=Anonymat::whereIn('etudiant_id',EtudMat::where('matiere_id',$id)->where('ref_semestre_id',$semestre)->where('ref_groupe_id',$groupe)->get()->pluck("etudiant_id"))->orderBy('id')->get();
        //$etuidants=EtudMat::where('matiere_id',$id)->where('ref_semestre_id',1)->get();
        foreach ($etuidants as $etuidant)
        {
            $html .='<tr>
                    <td align="right" style="width: 30%"><input type="checkbox"></td>
                    <td align="right" style="width: 40%"><input type="checkbox"></td>
                    <td align="center" style="width: 30%">'.$etuidant->anonymat.'</td>
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

    public function getalletudiantsMactierConcours($matiere,$salle)
    {
        $salle_obj=Salle::find($salle);

        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">'.trans('text_me.liste_colCNC').' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">'.$salle_obj->libelle.' </td>
                </tr>
                </table>
                ';

        //.
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $html .='<div align="right"> <center><table style="width: 100%" border="1" align="right">
                <tr>

                    <th align="right" style="width: 50%">'.trans('text_me.nom').'</th>
                    <th align="right" style="width: 20%">الرقم الوطني</th>
                     <th align="right" style="width: 20%">رقم الملف</th>
                     <th align="right" style="width: 10%">الرقم التسلسلي</th>
                </tr>';
        /*<th align="right" style="width: 30%">'.trans('text_me.signature').'</th>*/
        $cadidats=Candidat::where('salle_id',$salle)->get();
        $i=1;
        foreach ($cadidats as $cadidat)
        {
           /* <!-- <td align="right" style="width: 30%"></td>-->*/
            $html .='<tr>

                    <td align="right" style="width: 50%">'.$cadidat->nompl.'</td>
                    <td align="right" style="width: 20%">'.$cadidat->nni.'</td>
                    <td align="right" style="width: 20%">'.$cadidat->id.'</td>
                    <td align="center" style="width: 10%">'.$i.'</td>
                </tr>';
            $i +=1;
        }
        $html .='
                <tr>
                    <td align="center" style="width: 30%">'.count($cadidats).'</td>
                    <td align="right" colspan="">'.trans("text_me.nbreParticipant").'</td>
                </tr>';
        $html .='</table></center></div>';
        $html .='<div style="page-break-after: always"></div>';

        return $html;

    }

    public function getalletudiantsMactierConcours1($matiere,$salle)
    {
        $salle_obj=Salle::find($salle);
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">'.trans('text_me.liste_colCNC').' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">'.$salle_obj->libelle.' </td>
                </tr>
                </table>
                ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $html .='<div align="right"> <center><table style="width: 100%" border="1" align="right">
                <tr>

                    <th align="right" style="width: 15%">'.trans('text_me.signature').'</th>
                    <th align="right" style="width: 42%">'.trans('text_me.nom').'</th>
                    <th align="right" style="width: 18%">الرقم الوطني</th>
                     <th align="right" style="width: 15%">رقم الملف</th>
                     <th align="right" style="width: 10%">الرقم التسلسلي</th>
                </tr>';
        /*<th align="right" style="width: 30%">'.trans('text_me.signature').'</th>*/
        $cadidats=Candidat::where('salle_id',$salle)->get();
        $i=1;
        foreach ($cadidats as $cadidat)
        {
           /* <!-- <td align="right" style="width: 30%"></td>-->*/
            $html .='<tr>

                    <td align="right" style="width: 15%"></td>
                    <td align="right" style="width: 42%">'.trim($cadidat->nompl).'</td>
                    <td align="right" style="width: 18%">'.$cadidat->nni.'</td>
                    <td align="right" style="width: 15%">'.$cadidat->id.'</td>
                    <td align="center" style="width: 10%">'.$i.'</td>
                </tr>';
            $i +=1;
        }
        $html .='
                <tr>
                    <td align="center" style="width: 30%">'.count($cadidats).'</td>
                    <td align="right" colspan="">'.trans("text_me.nbreParticipant").'</td>
                </tr>';
        $html .='</table></center></div>';
        $html .='<div style="page-break-after: always"></div>';
        $html .=$this->getallPv($matiere,$salle,count($cadidats));
        return $html;

    }

    function getallPv($matiere,$salle,$nbr){
        $html ='';
        $salle_obj=Salle::find($salle);
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">'.trans('text_me.pv').' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">'.$salle_obj->libelle.' </td>
                </tr>
                </table>
                ';

        $entete = $this->entete($titre, 'L');
        $html .=$entete;
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

        $html .='<div style="page-break-after: always"></div>';
        return $html;
    }
    public function getalletudiantsMactierConcoursAnonymat($matiere,$salle)
    {
        $salle_obj=Salle::find($salle);
        $titre = trans('text_me.liste_note');
        $titre .='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 50%">'. $salle_obj->libelle.' : '. trans('text_me.salle').'</th>

                    <td align="right" style="width: 50%">'.MatieresConcour::find($matiere)->libelle.' : '. trans('text_me.matiere').'</td>
                </tr>
                </table>
                ';
        //.
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $html .='<div align="right"> <center><table style="width: 100%" border="1" align="right">
                <tr>
                    <th align="right" style="width: 30%">'.trans('text_me.note').'</th>
                   <th align="right" style="width: 60%">'.trans('text_me.note').'</th>

                    <th align="right" style="width: 10%">'.trans('text_me.anonymat').'</th>
                </tr>';
        $cadidats=Anonymatsconcour::whereIn('candidat_id',Candidat::where('salle_id',$salle)->get()->pluck("id"))->orderby('anonymat')->get();

        foreach ($cadidats as $cadidat)
        {
            $html .='<tr>
                        <td align="right" style="width: 30%"></td>
                        <td align="right" style="width: 60%">'.$cadidat->candidat_id.'</td>
                        <td align="center" style="width: 10%">'.$cadidat->anonymat.'</td>
                    </tr>';
        }
        $html .='
                <tr>
                    <td align="center" style="width: 30%">'.count($cadidats).'</td>
                    <td align="right" colspan="">'.trans("text_me.nbreParticipant").'</td>
                </tr>';
        $html .='</table></center></div>';
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

    public function imprimerListeEmergemet($profil,$groupe,$semestre,$etape,$choix)
    {
        //dd($profil.'>gr'.$groupe.'>sem'.$semestre.'>eta'.$etape);
        $html = '';
        $profil_l = Profil::find($profil)->libelle;
        //dd($profil_l);
        if ($choix == 'all' or $choix == 'col'){
            $matieres = MatieresProfilsEtape::where('profil_id', $profil)
                ->where('etape_id', $etape)->get();
            foreach ($matieres as $matiere) {
                $html .= $this->getalletudiants($matiere->matiere_id, $profil_l, $semestre, $groupe);
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
        PDF::SetFont('dejavusans', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
    }

    public function imprimerCollectNote($profil,$groupe,$semestre,$etape,$choix)
    {
        $html = '';
        $profil_l = Profil::find($profil)->libelle;
        if ($choix == 'all' or $choix == 'col'){
            $matieres = MatieresProfilsEtape::where('profil_id', $profil)
                ->where('etape_id', $etape)->get();
            foreach ($matieres as $matiere) {
                $html .= $this->getalletudiantsAnonymes($matiere->matiere_id, $profil_l, $semestre, $groupe);
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
        PDF::SetFont('dejavusans', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_pr').''.'.pdf');
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
        else{
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

    public function imprimerListeEmargementParSalle($matiere)
    {
        $html = '';
        $salles= Salle::orderBy('ordre')->get();
        foreach ($salles as $salle)
        {
            if (Candidat::where('salle_id',$salle->id)->get()->count()>0)
                $html .= $this->getalletudiantsMactierConcours($matiere,$salle->id);
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
    public function imprimerListeEmargementParSalle1($matiere)
    {
        $html = '';
        $salles= Salle::orderBy('ordre')->get();
        foreach ($salles as $salle)
        {
            if (Candidat::where('salle_id',$salle->id)->get()->count()>0)
                $html .= $this->getalletudiantsMactierConcours1($matiere,$salle->id);
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

    public function imprimerListeEmargementTroisiemeCorrection($matiere)
    {
        $html = '';
        $p=NoteConcour::where('etat_note3',3)->where('matieres_concour_id',$matiere)->select('pacquet3');
        $pacquets = $p->groupBy('pacquet3')->get();
        //dd($pacquets);
        /*foreach ($pacquets as $pacquet)
        {*/
                //$html .= $this->getImprimerAnnymatCorrection3($matiere,$pacquet);
                $html .= $this->getImprimerAnnymatCorrection3Liste($matiere);
       // }
        //dd($html);
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

    public function imprimerCollectNoteParSalle($matiere)
    {
        $html = '';
        //ancien code pour genere les liste par salle d examen
        /*$salles= Salle::orderBy('ordre')->get();
        foreach ($salles as $salle)
        {
            if (Candidat::where('salle_id',$salle->id)->get()->count()>0)
                $html .= $this->getalletudiantsMactierConcoursAnonymat($matiere,$salle->id);
        }*/
        $html = '';
        $candidats = Anonymatsconcour::orderBy('anonymat')->get();
        $i=0;
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">محضر النقاط </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف 1 </td>
                </tr>

                </table>
                ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=1;
        $html .='<table border="1">
                    <tr>
                      <th align="center" style="width: 30%"><b> نتيجة التصحيح الثاني </b></th>
                     <th align="center" style="width: 30%"><b> نتيجة التصحيح الاول </b></th>
                     <th align="center" style="width: 40%"><b>التوهيم</b></th>
                     </tr>';
        $i=0;
        foreach ($candidats as $candidat)
        {
            $i +=1;
            if ($i <= 50)
            {
                $html .='<tr>
                        <td align="center"><b></b></td>
                        <td align="center"><b></b></td>
                        <td align="center"><b>'.$candidat->anonymat.'</b></td>
                    </tr>';
                /*$pacquet = Anonymatsconcour::find($candidat->id);
                $pacquet->pacquet = $nbre;
                $pacquet->save();*/
            }
            else
            {
                $i=1;
                $nbre +=1;
                $html .='</table>';
                $html .='<br><br> <div align="right">
                    امضاء
                    <br>-1<br>
                    <br>-2<br>
                    <br>-3<br>
                    <br>-4<br>
                    <br><br><font size="8"><b>
                    ملاحظة :يجب ان يكون المحضر خاليا من اي شطب او تفخيم اما في
                    <br>
                    حالة غياب المتسابق يجب ان تترك الخانة المناسبة فارغة
                    </b></font>
                    </div>';
                $html .='<div style="page-break-after: always"></div>';
                $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">محضر النقاط </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف '.$nbre.' </td>
                </tr>
                </table>
                ';
                $entete = $this->entete($titre, 'L');
                $html.=$entete;
                $html .='<table border="1">
                    <tr>
                     <th align="center" style="width: 30%"><b> نتيجة التصحيح الثاني </b></th>
                     <th align="center" style="width: 30%"><b> نتيجة التصحيح الاول </b></th>
                     <th align="center" style="width: 40%"><b>التوهيم</b></th>
                     </tr>';
                $html .='<tr>
                        <td align="center"><b></b></td>
                        <td align="center"><b></b></td>
                        <td align="center"><b>'.$candidat->anonymat.'</b></td>
                    </tr>';
                /*$pacquet = Anonymatsconcour::find($candidat->id);
                $pacquet->pacquet = $nbre;
                $pacquet->save();*/
            }
        }
        $html .='</table><br><br> <div align="right">
                    امضاء
                    <br>-1<br>
                    <br>-2<br>
                    <br>-3<br>
                    <br>-4<br>
                    <br><br><font size="8"><b>
                    ملاحظة :يجب ان يكون المحضر خاليا من اي شطب او تفخيم اما في
                    <br>
                    حالة غياب المتسابق يجب ان تترك الخانة المناسبة فارغة
                    </b></font>
                    </div>';
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_note').'');
        PDF::SetSubject(''.trans('text_me.liste_note').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }

    public function imprimerCollectNoteCand()
    {

        $html = '';
        $candidats = NoteConcoursFinale::orderBy('note', 'DESC')->get();
        $i=0;
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">النتائج النهائية </td>
                </tr>
                </table>
               ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=1;
        $html .=' <table style="width: 100%" border="1" align="right">
                <tr>
                    <th align="right" style="width: 10%">ملاحظة </th>
                    <th align="right" style="width: 10%">النتيجة </th>
                    <th align="right" style="width: 50%">الاسم </th>
                    <th align="right" style="width: 20%">الرقم الوطني</th>
                     <th align="right" style="width: 10%">رقم الملف</th>
                </tr>';
        $i=0;
        foreach ($candidats as $candidat)
        {
            $html .='<tr>';
            if ($candidat->elimine==0)
              $html .='<td style="width: 10%"></td>';
            if ($candidat->elimine==1)
                $html .='<td style="width: 10%">مقصي</td>';
            $html .='<td align="right" style="width: 10%">'.$candidat->note.' </td>
                    <td align="right" style="width: 50%">'.$candidat->candidat->nompl.' </td>
                    <td align="right" style="width: 20%">'.$candidat->candidat->nni.'  </td>
                     <td align="right" style="width: 10%">'.$candidat->candidat_id.' </td>
            </tr>';
        }
        $html .='</table></div>';
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_note').'');
        PDF::SetSubject(''.trans('text_me.liste_note').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }

   public function imprimerCollectNoteCandFinal()
    {
//where('candidat_id',1037)->
        $html = '';
        $candidats = NoteConcoursFinale::orderBy('note', 'DESC')->get();
        $i=0;
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">النتائج النهائية </td>
                </tr>
                </table>
               ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=0;
        $html .=' <table style="width: 100%" border="1" align="right">
                <thead>
                <tr>
                    <th align="right" rowspan="2" style="width: 5%">ملاحظة </th>
                    <th align="right" rowspan="2" style="width: 5%"> النتيجة النهائية </th>';
        $matieres =MatieresConcour::where('id','<>',3)->get();
                foreach ($matieres as $matiere)
                {
                    $html .='<th colspan="4" style="width: 16%">'.$matiere->libelle.' <br>( الضارب '.$matiere->coaf.')</th>';
                }
        $html .=' <th rowspan="2" align="right" style="width: 20%">الاسم </th>
                    <th rowspan="2" align="right" style="width: 8%">الرقم الوطني</th>
                    <th rowspan="2" align="right" style="width: 5%">التوهيم</th>
                    <th rowspan="2" align="right" style="width: 5%">رقم الملف</th>
                    <th rowspan="2" align="right" style="width: 4%">الرقم التسلسلي </th>
                </tr>';

        $html .='<tr>';
        foreach ($matieres as $matiere)
        {
            $html .='<th>النتيجة النهائية</th>';
            $html .='<th>التصحيح الثالث</th>';
            $html .='<th>التصحيح الثاني</th>';
            $html .='<th>التصحيح الاول</th>';
        }
        $html  .='</tr>
        </thead>';
        $i=$verifadd=$verifatt=0;
        $admis=1;
        $cnc=App\Models\Concour::find(1);
        $verifadd=$cnc->nbre_admis;
        $verifatt=$cnc->nbre_attent;
        foreach ($candidats as $candidat)
        {
            $nbre +=1;
            $html .='<tr>';
            if ($candidat->elimine==1){
                $html .='<td style="width: 5%">مقصي</td>';
            }

            if ($candidat->elimine==0 and ($admis <= $verifadd))
            {
                $html .='<td style="width: 5%">ناجح</td>';
                $admis +=1;
                $cad=NoteConcoursFinale::find($candidat->id);
                $cad->etat=1;
                $cad->save();
            }
            else if ($candidat->elimine==0 and ($admis <= ($verifadd + $verifatt)))
            {
                $cad=NoteConcoursFinale::find($candidat->id);
                $cad->etat=2;
                $cad->save();
                $html .='<td style="width: 5%"> الانتظار</td>';
                $admis +=1;
            }
            else if ($candidat->elimine==0 and ($admis > ($verifadd + $verifatt)))
            {
                $cad=NoteConcoursFinale::find($candidat->id);
                $cad->etat=3;
                $cad->save();
                $html .='<td style="width: 5%">راسب</td>';
                $admis +=1;
            }

            $html .='<td align="right" style="width: 5%">'.number_format($candidat->note,2).' </td>';
            $matieres =MatieresConcour::where('id','<>',3)->get();
            foreach ($matieres as $matiere)
            {
                $html .=$this->matiere_notes($candidat->candidat_id,$matiere->id);
            }
            $html .=' <td align="right" style="width: 20%">'.$candidat->candidat->nompl.' </td>
                    <td align="right" style="width: 8%">'.$candidat->candidat->nni.'  </td>
                    <td align="right" style="width: 5%">'.$candidat->anonymatsconcour->anonymat.' </td>
                    <td align="right" style="width: 5%">'.$candidat->candidat_id.' </td>
                    <td align="right" style="width: 4%">'.$nbre.' </td>
            </tr>';
        }
        $html .='</table></div>';
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_note').'');
        PDF::SetSubject(''.trans('text_me.liste_note').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('aefurat', '', 8);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }
   public function imprimerCollectNoteCandFinalAdmis()
    {
//where('candidat_id',1037)->
        $html = '';
        $candidats = NoteConcoursFinale::where('etat',1)->orderBy('note', 'DESC')->get();
        $i=0;
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 1%"></th>
                    <td align="center" style="width: 99%">  بعد انتهاء التصحيح اقرت اللجنة المشرفة على الامتحان النتائج التالية:</td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الناجحين  </td>
                </tr>
                </table>
               ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=0;
        $html .=' <table style="width: 100%" border="1" align="right">
                <thead>
                <tr>
                    <th align="right" rowspan="2" style="width: 5%">ملاحظة </th>
                    <th align="right" rowspan="2" style="width: 5%"> النتيجة النهائية </th>';
        $matieres =MatieresConcour::where('id','<>',3)->get();
                foreach ($matieres as $matiere)
                {
                    $html .='<th colspan="4" style="width: 16%">'.$matiere->libelle.' <br>( الضارب '.$matiere->coaf.')</th>';
                }
        $html .=' <th rowspan="2" align="right" style="width: 20%">الاسم </th>
                    <th rowspan="2" align="right" style="width: 8%">الرقم الوطني</th>
                    <th rowspan="2" align="right" style="width: 5%">التوهيم</th>
                    <th rowspan="2" align="right" style="width: 5%">رقم الملف</th>
                    <th rowspan="2" align="right" style="width: 4%">الرقم التسلسلي </th>
                </tr>';

        $html .='<tr>';
        foreach ($matieres as $matiere)
        {
            $html .='<th>النتيجة النهائية</th>';
            $html .='<th>التصحيح الثالث</th>';
            $html .='<th>التصحيح الثاني</th>';
            $html .='<th>التصحيح الاول</th>';
        }
        $html  .='</tr>
        </thead>';
        $i=$verifadd=$verifatt=0;
       $notef=0;
       $nbre=0;
        foreach ($candidats as $candidat)
        {
            $nbre +=1;

            $html .='<tr>';
            $notef=$candidat->note;
            $html .='<td style="width: 5%">ناجح</td>';
            if ($candidat->note<10)
            {
                $notef=10;
            }
            $html .='<td align="right" style="width: 5%">'.number_format($notef,2).' </td>';
            $matieres =MatieresConcour::where('id','<>',3)->get();
            foreach ($matieres as $matiere)
            {
                $html .=$this->matiere_notes($candidat->candidat_id,$matiere->id);
            }
            $html .=' <td align="right" style="width: 20%">'.$candidat->candidat->nompl.' </td>
                    <td align="right" style="width: 8%">'.$candidat->candidat->nni.'  </td>
                    <td align="right" style="width: 5%">'.$candidat->anonymatsconcour->anonymat.' </td>
                    <td align="right" style="width: 5%">'.$candidat->candidat_id.' </td>
                    <td align="right" style="width: 4%">'.$nbre.' </td>
            </tr>';
        }
        $html .='</table><br><br>
<table>
<tr><td align="right"><b>اعضاء اللجنة</b></td></tr>
</table>
</div>';
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_note').'');
        PDF::SetSubject(''.trans('text_me.liste_note').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('aefurat', '', 8);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }

    public function matiere_notes($candidat,$matiere){
        $html = '';
        $matiereNote=NoteConcour::where('matieres_concour_id',$matiere)->where('candidat_id',$candidat)->get()->first();
       // dd($matiereNote);
        $html .= '<td style="width: 4%">'.number_format($matiereNote->note,2).'</td>';
        $html .= '<td style="width: 4%">'.number_format($matiereNote->note3,2).'</td>';
        $html .= '<td style="width: 4%">'.number_format($matiereNote->note2,2).'</td>';
        $html .= '<td style="width: 4%">'.number_format($matiereNote->note1,2).'</td>';
   return $html;
    }
    public function getImprimerAnnymatParDosier($matiere)
    {
        $html = '';
        $candidats = Anonymatsconcour::orderBy('anonymat')->get();
        $i=0;
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">التوهيم </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف 1 </td>
                </tr>

                </table>
                ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=1;
        $html .='<table border="1">
                    <tr>
                     <th align="center" style="width: 30%"><b>القاعة</b></th>
                     <th align="center" style="width: 40%"><b>التوهيم</b></th>
                     <th align="center" style="width: 30%"><b>رقم الملف</b></th>
                     </tr>';
        $i=0;
        foreach ($candidats as $candidat)
        {
            $i +=1;
            if ($i <= 50)
            {
                $html .='<tr>
                        <td align="center"><b>'.$candidat->candidat->salle->libelle.'</b></td>
                        <td align="center"><b>'.$candidat->anonymat.'</b></td>
                        <td align="center"><b>'.$candidat->candidat_id.'</b></td>
                    </tr>';
            }
            else
            {
                $i=1;
                $nbre +=1;
                $html .='</table>';
                $html .='';
                $html .='<div style="page-break-after: always"></div>';
                $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">التوهيم </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف '.$nbre.' </td>
                </tr>
                </table>
                ';
                $entete = $this->entete($titre, 'L');
                $html.=$entete;
                $html .='<table border="1">
                    <tr>
                     <th align="center" style="width: 30%"><b>القاعة</b></th>
                     <th align="center" style="width: 40%"><b>التوهيم</b></th>
                     <th align="center" style="width: 30%"><b>رقم الملف</b></th>
                     </tr>';
                $html .='<tr>
                        <td align="center"><b>'.$candidat->candidat->salle->libelle.'</b></td>
                        <td align="center"><b>'.$candidat->anonymat.'</b></td>
                        <td align="center"><b>'.$candidat->candidat_id.'</b></td>
                    </tr>';
            }
        }
        $html .='</table>';
        PDF::SetAuthor('unisof');
        PDF::SetTitle(''.trans('text_me.liste_note').'');
        PDF::SetSubject(''.trans('text_me.liste_note').'');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('aefurat', '', 12);
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output(uniqid().''.''.trans('text_me.liste_note').''.'.pdf');
    }

    public function getImprimerAnnymatCorrection3Liste($matiere)
    {
        $html = '';
        $candidats = NoteConcour::where('matieres_concour_id',$matiere)->where('etat_note3',3)->orderBy('pacquet3')->get();
        $i=0;
        //dd($candidats);
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">التصحيح الثالث </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف 1 </td>
                </tr>

                </table>
                ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=1;
        $html .='<table border="1">
                    <tr>
                    <th align="center" style="width: 40%"><b>نتيجة التصحيح</b></th>
                     <th align="center" style="width: 30%"><b>الغلاف</b></th>
                     <th align="center" style="width: 30%"><b>التوهيم</b></th>
                     </tr>';
        $i=0;
        foreach ($candidats as $candidat)
        {
            $i +=1;
            if ($i <= 50)
            {
                $html .='<tr>
                        <td align="center"><b></b></td>
                        <td align="center"><b>'.$candidat->pacquet.'</b></td>
                        <td align="center"><b>'.$candidat->anonymatsconcour->anonymat.'</b></td>
                    </tr>';
            }
            else
            {
                $i=1;
                $nbre +=1;
                $html .='</table>';
                $html .='';
                $html .='<div style="page-break-after: always"></div>';
                $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">التصحيح الثالث </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف '.$nbre.' </td>
                </tr>
                </table>
                ';
                $entete = $this->entete($titre, 'L');
                $html.=$entete;
                $html .='<table border="1">
                    <tr>
                     <th align="center" style="width: 40%"><b>نتيجة التصحيح</b></th>
                     <th align="center" style="width: 30%"><b>الغلاف</b></th>
                     <th align="center" style="width: 30%"><b>التوهيم</b></th>
                     </tr>';
                $html .='<tr>
                       <td align="center"><b></b></td>
                       <td align="center"><b>'.$candidat->pacquet.'</b></td>
                        <td align="center"><b>'.$candidat->anonymatsconcour->anonymat.'</b></td>
                    </tr>';
            }
        }
        $html .='</table>';
return $html;
    }

    public function getImprimerAnnymatCorrection3($matiere,$pacquet)
    {
        $html = '';
        $candidats = NoteConcour::where('matieres_concour_id',$matiere)->where('pacquet3',$pacquet)->orderBy('pacquet3')->get();
        $i=0;
        dd($candidats);
        // $html .='<div style="page-break-after: always"></div>';
        $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">التوهيم </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف 1 </td>
                </tr>

                </table>
                ';
        $entete = $this->entete($titre, 'L');
        $html=$entete;
        $nbre=1;
        $html .='<table border="1">
                    <tr>
                     <th align="center" style="width: 50%"><b>الغلاف</b></th>
                      <th align="center" style="width: 50%"><b>التوهيم </b></th>
                     </tr>';
        $i=0;
        foreach ($candidats as $candidat)
        {
            $i +=1;
            if ($i <= 50)
            {
                $html .='<tr>
                        <td align="center"><b>'.$candidat->pacquet.'</b></td>
                        <td align="center"><b>'.$candidat->anonymat->anonymat.'</b></td>
                    </tr>';
            }
            else
            {
                $i=1;
                $nbre +=1;
                $html .='</table>';
                $html .='';
                $html .='<div style="page-break-after: always"></div>';
                $titre ='<br><table style="width:100%" >
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find(3)->libelle.' </td>
                </tr>

                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">التوهيم </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="right" style="width: 95%">'.MatieresConcour::find($matiere)->libelle.' </td>
                </tr>
                <tr>
                    <th align="right" style="width: 5%"></th>
                    <td align="center" style="width: 95%">الغلاف '.$nbre.' </td>
                </tr>
                </table>
                ';
                $entete = $this->entete($titre, 'L');
                $html.=$entete;
                $html .='<table border="1">
                    <tr>
                     <th align="center" style="width: 50%"><b>الغلاف</b></th>
                     <th align="center" style="width: 50%"><b>التوهيم</b></th
                     </tr>';
                $html .='<tr>
                       <td align="center"><b>'.$candidat->pacquet.'</b></td>
                        <td align="center"><b>'.$candidat->anonymat->anonymat.'</b></td>
                    </tr>';
            }
        }
        $html .='</table>';

    }

    public function getImpressionCollect()
    {
        return view($this->module.'.ajax.getImpressionCollect');
    }
    public function getImpressionCorrespond()
        {
            return view($this->module.'.ajax.getImpressionCorrespond');
        }

   public function getImpression()
    {
        return view($this->module.'.ajax.getImpression');
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

    public  function genererSalles(){
        $salle=Salle::where('etat','<>',2)->orderBy('ordre')->get()->first();
        $nbre_cand_att_sall=0;
        $etat_sall=1;
        $data=2;
        if ($salle) {
            $data=1;
            $candidats = Candidat::where('salle_id', null)->get();
            foreach ($candidats as $candidat) {
            if ($etat_sall==2 ){
                $salle=Salle::where('etat','<>',2)->orderBy('ordre')->get()->first();
            }
            $cand=Candidat::find($candidat->id);
            $cand->salle_id = $salle->id;
            $cand->save();
            $nbre_cand_att_sall=count(Candidat::where('salle_id', $salle->id)->get());
            if ($nbre_cand_att_sall == $salle->capacite)
            {
                $sal=Salle::find($salle->id);
                $sal->etat=2;
                $sal->save();
                $etat_sall=2;
            }
            }
        }
        return  $data;
    }

    public function calculer3correction()
    {
        $notes=NoteConcour::where('etat_note3',3)->orderby('matieres_concour_id')->get();
        $dif=$i=0;$nbre=1;;
        foreach ($notes as $not)
        {
            $pbjetNote = NoteConcour::find($not->id);
            $not->note = ($not->note1 + $not->note2 + $not->note3)/3;
            $not->save();
        }
        return 1;
    }

    public function calculerNoteEtud()
    {
        $candidats=Candidat::all();
        foreach ($candidats as $candidat)
        {
            $this->noteCandidat($candidat);
        }
        return 1;
    }

    public  function noteCandidat($candidat)
    {
        $notes = NoteConcour::where('candidat_id',$candidat->id)->get();
        $coefs=$elimine=0;
        $notMat=$noteFinale=0;
        foreach ($notes as $note1)
        {
            $matiere =MatieresConcour::find($note1->matieres_concour_id);
            $coefs += $matiere->coaf;
            $notMat += ($note1->note * $matiere->coaf);
            $pacquet = $note1->pacquet;
            $anonymat_id = $note1->anonymat_id;
            if ($note1->note == 0)
            {
                $elimine=1;
            }
        }
        $noteFinale=($notMat/$coefs);
        $verif=NoteConcoursFinale::where('candidat_id',$candidat->id)->get();
        if ($verif->count()>0)
        {
            $noteecnc = NoteConcoursFinale($verif->first()->id);
            $noteecnc->note = $noteFinale;
            $noteecnc->elimine = $elimine;
            $noteecnc->save();
        }
        else
        {
            $noteecnc =new NoteConcoursFinale();
            $noteecnc->pacquet = $pacquet;
            $noteecnc->annee_id = 2;
            $noteecnc->anonymat_id = $anonymat_id;
            $noteecnc->candidat_id = $candidat->id;
            $noteecnc->note = $noteFinale;
            $noteecnc->elimine = $elimine;
            $noteecnc->save();
        }
    }
    public function generer3correction($matiere)
    {
        $notes=NoteConcour::where('matieres_concour_id',$matiere)->get();
        $essay = env('APP_CORECTION');

        $dif=$i=0;$nbre=1;;
        foreach ($notes as $not)
        {
            $pbjetNote = NoteConcour::find($not->id);
            if($not->note1 > $not->note2){  $dif=$not->note1 - $not->note2; }
            else { $dif=$not->note2 - $not->note1; }

            if ($dif > $essay)
            {
                $i +=1;
                if ($i <= 50) {

                }
                else{
                    $i =1;
                    $nbre +=1;
                }
                $not->etat_note3 =3;
                $not->pacquet3 =$nbre;
            }
            else {
                $not->note = ($not->note1 + $not->note2)/2;
            }
            $not->save();
        }
        return 1;
    }

    public function genererAnonymats()
    {
      $verificateur=0;
      $anonymats= Anonymatsconcour::all();
     // dd($anonymats);
      if ($anonymats->count()){
          $verificateur=2;
      }
      else{
          $debut='10000';
          $fin='99999';
          $candidats=Candidat::all();
          foreach ($candidats as $candidat) {
              $anonymat = random_int($debut, $fin);
              if (Anonymatsconcour::where('anonymat',$anonymat)->get()->count()==0)
              {
                  $anonym=new Anonymatsconcour();
                  $anonym->anonymat=$anonymat;
                  $anonym->candidat_id=$candidat->id;
                  $anonym->save();
              }
              else{

                  $f=0;
                  while($f==0)
                  {
                      $anonymat = random_int($debut, $fin);
                      if (Anonymatsconcour::where('anonymat',$anonymat)->get()->count()==0)
                      {
                          $anonym=new Anonymatsconcour();
                          $anonym->anonymat=$anonymat;
                          $anonym->candidat_id=$candidat->id;
                          $anonym->save();
                          if ($anonym->id)
                          {
                              $f=1;
                          }
                      }

                  }
              }
          }
          $verificateur=1;
      }
    return $verificateur;
    }
     public function entete($titre, $or = 'P', $id = false ,$etudiant='')
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
        $html = '';
        $num='';

if ($etudiant) {
    $nunbr=($etudiant->AD3+1);
    $etudi=Etudiant::find($etudiant->id);
    $etudi->AD3=($etudi->AD3+1);
    $etudi->save();
    $num='   النسخة رقم :'.$nunbr; }
        //dd($html);
        $entet_fr = '<table width="100%" ><tr><td "> ' . $num . '</td></tr><tr><td align="right">' . $titre2 . '</td></tr><tr><td align="right">' . $titre3 . '</td></tr></table>';
        $entet_ar = '<table width="100%" style="font-size: 16px;"><tr><td align="right">' . $titre1_ar . '</td></tr><tr><td align="right">' . $titre2_ar . '</td></tr><tr><td align="right">' . $titre3_ar . '</td></tr></table>';
        $logo = '<table  width="100%"><tr ><td align="center"><img src="'.asset('img/logoRim.jpg').'"alt="avatar" style="width:80px; height: 80px;"  /></td></tr></table>';
        $table = '<table width="100%"><tr><td align="right">' . $entet_fr . '</td><td >' . $logo . '</td><td align="right">' . $entet_ar . '</td></tr></table>';
        $html .= $table;

        $titre_entete = '<br><p align="center"><h4 align="center">' . $titre . '<br><br></h4></p>';
        $html .= $titre_entete;
        return $html;
    }

    public function optionTroisiemeConcours($matiere)
    {
        //$p=Anonymatsconcour::where('pacquet3','<>',)->select('pacquet');
       // $pacquets = $p->groupBy('pacquet')->get();
        return view($this->module.'.ajax.optionTroisiemeConcours',['matiere'=>$matiere]);
    }

    public function getNotes($matiere)
    {
        $salles = Salle::all();
        $p=Anonymatsconcour::select('pacquet');
        $pacquets = $p->groupBy('pacquet')->get();
        return view($this->module.'.ajax.getNotes',['matiere'=>$matiere,'pacquets'=>$pacquets]);
    }


    public function getNotesCorr3($matiere)
    {
        $p=NoteConcour::where('etat_note3',3)->where('matieres_concour_id',$matiere)->select('pacquet3');
        $pacquets = $p->groupBy('pacquet3')->get();
        return view($this->module.'.ajax.getNotesCorr3',['matiere'=>$matiere,'pacquets'=>$pacquets]);
    }

    public function getCandidatsSalle($matiere,$pacquet,$correction)
    {
        $html ='<table width="100%" align="right" border="1">
                <tr align="right">

                <td  align="right" width="50%">'.trans("text_me.anonymat").'</td>
                <td align="center" width="50%">'.trans("text_me.note").'</td>
                </tr>';
        $annee_id=2;
        $etape_note=20;
            $verif =NoteConcour::where('matieres_concour_id',$matiere)->where('pacquet',$pacquet)->where('annee_id',$annee_id)->get();
        if (count($verif)>0)
        {
            $html .='<input type="hidden" name="sit" id="sit" value="save"> ';
            $candidats= NoteConcour::where('matieres_concour_id',$matiere)->where('pacquet',$pacquet)->where('annee_id',$annee_id)->get();
            foreach ($candidats as $candidat) {
                if ($correction == 1)
                    $note=$candidat->note1;
                if ($correction == 2)
                    $note=$candidat->note2;
                $html .= '<tr>
                    <td align="right" width="50%">'.$candidat->anonymatsconcour->anonymat.'</td>
                    <td align="center" width="50%"><input class="form-control text-right " type="number" value="'.$note.'" name="note'.$candidat->id.'" id="note'.$candidat->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001"></td>' ;
                $html .= '</tr>' ;
                  }
        }
        else{
            $html .='<input type="hidden" name="sit" id="sit" value="add"> ';
            $candidats = Anonymatsconcour::where('pacquet',$pacquet)->orderby('anonymat')->get();
            foreach ($candidats as $candidat) {
                $html .= '<tr>
                 <td align="right" width="50%"> '.$candidat->anonymat.'</td>
                 <td align="center" width="50%"><input class="form-control text-right" type="number" name="note'.$candidat->id.'"  id="note'.$candidat->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001" align="right"></td>' ;
                $html .= '</tr>' ;
            }

        }
        $html .='</table>';
        $html .='<input type="hidden" name="matiere" value="'.$matiere.'"> ';
        $html .='<input type="hidden" name="pacquet" value="'.$pacquet.'"> ';
        $html .='<input type="hidden" name="correction" value="'.$correction.'"> ';
        return view($this->module.'.ajax.getEtudiants',['html'=>$html]);
    }

    public function getCandidatCorr3($matiere,$pacquet,$correction)
    {
        $html ='<table width="100%" align="right" border="1">
                <tr align="right">

                <td  align="right" width="50%">'.trans("text_me.anonymat").'</td>
                <td align="center" width="50%">'.trans("text_me.note").'</td>
                </tr>';
        $annee_id=2;
        $etape_note=20;
        $candidats =NoteConcour::where('etat_note3',3)->where('matieres_concour_id',$matiere)->where('pacquet3',$pacquet)->get();
        if (count($candidats)>0)
        {
            foreach ($candidats as $candidat) {
                    $note=$candidat->note3;
                $html .= '<tr>
                    <td align="right" width="50%">'.$candidat->anonymatsconcour->anonymat.'</td>
                    <td align="center" width="50%"><input class="form-control text-right " type="number" value="'.$note.'" name="note'.$candidat->id.'" id="note'.$candidat->id.'" required size="8" onchange="test(this.value,'.$etape_note.');" min="0" max="'.$etape_note.'" step="0.001"></td>' ;
                $html .= '</tr>' ;
            }
        }
        $html .='</table>';
        $html .='<input type="hidden" name="matiere" value="'.$matiere.'"> ';
        $html .='<input type="hidden" name="pacquet" value="'.$pacquet.'"> ';
        $html .='<input type="hidden" name="correction" value="3"> ';
        return view($this->module.'.ajax.getEtudiantsCorr3',['html'=>$html]);
    }
}
