<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Etudiant;
use App\Models\Bachelier;
use App\Models\EtudMat;
use App\Models\RefSemestre;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Profil;
use Auth;
use Illuminate\Support\Facades\File;
class HomeController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth');
    }
 public function dashboard1($nni='')
    {
        $html='';
    if ($nni!=''){
        $annee=Annee::where('etat',1)->get()->first();
		$bachelier = Bachelier::where('nni', $nni)->first();
		if($bachelier->etat==3){
			 $etudiant = Etudiant::where('NNI',$nni)->get()->first();
		}
       elseif($bachelier->etat==2){
		   
	   }
	   elseif($bachelier->etat==1){
		   
	   }
        $etudiant = Etudiant::join('etud_mats', 'etudiants.id', '=', 'etud_mats.etudiant_id')
    ->where('etudiants.NODOS', $nodos)
    ->where('etud_mats.annee_id', $annee->id)
    ->orderBy('etudiants.id', 'DESC')
    ->select('etudiants.*')
    ->first();
        $test1 =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->orderBy('ref_semestre_id')->get();
        $html='<div class="col-md-12 text-center form-group " align="center">'.$etudiant->NODOS.' <br>'.$etudiant->NOMF.' /** '.$etudiant->NOMA.'</div>';
        if ($test1->count()>0){
			    //  $html .='<div class="col-md-12 text-center form-group " align="center"><button type="button" class="btn btn-sm btn-success mb-3 btn-block" onClick="exporteattestationPDF('.$etudiant->id.')"  title="'.trans('text_me.exporter').'"><i class=" fa fa-file-pdf">'.trans('text_me.exporter').'</i></button></div>';
                  $html .='<div class="col-md-12 text-center form-group " align="center"><button type="button" class="btn btn-sm btn-success mb-3 btn-block" onclick="exporteattestationPDF('.$etudiant->id.')"  ><i class="fa fa-file-pdf"> '.trans('text_me.exporter').' </i></button></div>';
                
            $semestres=RefSemestre::where('etat',1)->get();
            foreach ($semestres as $semestre)
            {
                $verif=EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->where('ref_semestre_id',$semestre->id)->get();
                if ($verif->count()>0)
                {
                    $html .='<div class="col-md-12 text-center form-group " align="center"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getbultin('.$etudiant->id.','.$semestre->id.')"  class="btn btn-sm btn-danger"><i class="fa fa-file-pdf"> '.$semestre->libelle.' </i></button></div>';
                }
            }

        }
        else{

        }
        return view('dashboard1',['html'=>$html]);
    }
      else{
          return redirect('login');
      }
    }
    public function dashboard($nodos='')
    {
        $html='';
    if ($nodos!=''){
        $annee=Annee::where('etat',1)->get()->first();
        //$etudiant = Etudiant::where('NODOS',$nodos)->orderBy('id','DESC')->get()->first();
        $etudiant = Etudiant::join('etud_mats', 'etudiants.id', '=', 'etud_mats.etudiant_id')
    ->where('etudiants.NODOS', $nodos)
    ->where('etud_mats.annee_id', $annee->id)
    ->orderBy('etudiants.id', 'DESC')
    ->select('etudiants.*')
    ->first();
        $test1 =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->orderBy('ref_semestre_id')->get();
        $html='<div class="col-md-12 text-center form-group " align="center">'.$etudiant->NODOS.' <br>'.$etudiant->NOMF.' /** '.$etudiant->NOMA.'</div>';
        if ($test1->count()>0){
			    //  $html .='<div class="col-md-12 text-center form-group " align="center"><button type="button" class="btn btn-sm btn-success mb-3 btn-block" onClick="exporteattestationPDF('.$etudiant->id.')"  title="'.trans('text_me.exporter').'"><i class=" fa fa-file-pdf">'.trans('text_me.exporter').'</i></button></div>';
                  $html .='<div class="col-md-12 text-center form-group " align="center"><button type="button" class="btn btn-sm btn-success mb-3 btn-block" onclick="exporteattestationPDF('.$etudiant->id.')"  ><i class="fa fa-file-pdf"> '.trans('text_me.exporter').' </i></button></div>';
                
            $semestres=RefSemestre::where('etat',1)->get();
            foreach ($semestres as $semestre)
            {
                $verif=EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->where('ref_semestre_id',$semestre->id)->get();
                if ($verif->count()>0)
                {
                    $html .='<div class="col-md-12 text-center form-group " align="center"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getbultin('.$etudiant->id.','.$semestre->id.')"  class="btn btn-sm btn-danger"><i class="fa fa-file-pdf"> '.$semestre->libelle.' </i></button></div>';
                }
            }

        }
        else{

        }
        return view('dashboard',['html'=>$html]);
    }
      else{
          return redirect('login');
      }
    }
public function sorties($html)
    {
        return view('logout',['html'=>$html]);
    }

    public function selectModule($module_id)
    {
        $module = Module::find($module_id);
        if(!Auth::user()->hasAccess($module->sys_groupes_traitement_id))
          return redirect('dashboard');
        else {
          if(!$module->is_externe)
            session()->put('module', $module);
          return redirect($module->lien);
        }
    }
	
public function store(Request $request)
{
	//dd($request->nni);
    $request->validate([
        'nni' => 'required|string',
        'tel' => 'required|string|max:20',
        'carte_identite' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'bac_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'photo_personnelle' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $bachelier = Bachelier::where('nni', $request->nni)->first();

    $dossier = public_path('bacheliers/' . $bachelier->nni);

    if (!File::exists($dossier)) {
        File::makeDirectory($dossier, 0755, true);
    }

    $carteIdentiteName = 'carte_identite_' . time() . '.' .
        $request->file('carte_identite')->getClientOriginalExtension();

    $bacDocumentName = 'bac_' . time() . '.' .
        $request->file('bac_document')->getClientOriginalExtension();

    $photoPersonnelleName = 'photo_' . time() . '.' .
        $request->file('photo_personnelle')->getClientOriginalExtension();

    $request->file('carte_identite')->move(
        $dossier,
        $carteIdentiteName
    );

    $request->file('bac_document')->move(
        $dossier,
        $bacDocumentName
    );

    $request->file('photo_personnelle')->move(
        $dossier,
        $photoPersonnelleName
    );
	
//  'specialite_id' => $request->specialite_id,
    $bachelier->update([
        'tel' => $request->tel,
      

        'carte_identite' =>
            'bacheliers/' . $bachelier->nni . '/' . $carteIdentiteName,

        'bac_document' =>
            'bacheliers/' . $bachelier->nni . '/' . $bacDocumentName,

        'photo_personnelle' =>
            'bacheliers/' . $bachelier->nni . '/' . $photoPersonnelleName,

        // Exemple : candidature envoyée, en attente de validation
        'etat' => 2,
    ]);

    return redirect()
    ->route('login')
    ->with('success', 'تم إرسال طلب ترشحكم بنجاح.');
}

	 public function authenticate2(Request $request)
    {
      $nni = $request->nni;
$nobac=$request->numero_bac;

    // Vérifier l'année active
    $annee = Annee::where('etat', 1)->first();

    if (!$annee) {
        return back()->with('error', 'Aucune année universitaire active.');
    }

    // Vérifier si le NNI existe chez les bacheliers
    $bachelier = Bachelier::where('nni', $nni)->where('nobac', $nobac)->first();

    if (!$bachelier) {
        return back()->with(
            'error',
            'البيانات غير صحيحة'
        );
    }

    // Vérifier l'état du bachelier
    if ($bachelier->etat == 3) {

        // Inscription confirmée
        $etudiant = Etudiant::where('NNI', $nni)->first();

        if (!$etudiant) {
            return back()->with(
                'error',
                'تم تأكيد تسجيل الطالب، ولكن تعذر العثور على معلوماته.'
            );
        }

return $this->dashboard($etudiant->NODOS);
        /* return view('candidature.attestation', compact(
            'etudiant',
            'bachelier',
            'annee'
        )); */

    } elseif ($bachelier->etat == 2) {
		//dd($bachelier); 
		$profils  =Profil::where('ref_niveau_etude_id',1)->get();
/*         return view('candidature.attente', compact(
            'bachelier',
            'annee'
        )); */
		
		 return view('candidature.attente1', compact(
            'bachelier',
            'annee'
        ));

    } elseif ($bachelier->etat == 1) {
$profils  =Profil::where('id',$bachelier->nat)->get();
        // Nouveau bachelier : formulaire à compléter
        return view('candidature.formulaire', compact(
            'bachelier',
            'annee','profils'
        ));

    }
elseif ($bachelier->etat == 5) {
	 session([
        'bachelier_id' => $bachelier->id,
        'bachelier_nni' => $bachelier->nni,
        'bachelier_nom' => $bachelier->nompl,
        'bachelier_prenom' => $bachelier->nompa,
        'bachelier_etat' => $bachelier->etat,
    ]);
		return redirect()->route('admin.bacheliers.index');

    }
	else {

        return back()->with(
            'error',
            'État du dossier inconnu. Veuillez contacter l’administration.'
        );
    }
	   
	   
	   
       /* if (Bachelier::where(['nni'=>$request->nni, 'nobac'=>$request->numero_bac])->exists()) {
            return $this->dashboard1($request->nni);
        }
        else {
            if (Bachelier::where(['nni'=>$request->nni])->exists())
                return $this->sorties('رقم التسجيل غير صحيح');
            else if (Bachelier::where(['nobac'=>$request->numero_bac])->exists())
                return $this->sorties('رقم  الباكالوريا غير صحيح');
            else return $this->sorties('البيانات غير صحيحة');
        }*/
    }
    public function authenticate1(Request $request)
    {
      // dd($request->nodos);
        if (Etudiant::where(['NNI'=>$request->nni, 'NODOS'=>$request->nodos])->exists()) {
            return $this->dashboard($request->nodos);
        }
        else {
            if (Etudiant::where(['NNI'=>$request->nni])->exists())
                return $this->sorties('رقم التسجيل غير صحيح');
            else if (Etudiant::where( ['NODOS'=>$request->nodos])->exists())
                return $this->sorties('الرقم الوطني  غير صحيح');
            else return $this->sorties('البيانات غير صحيحة');
        }
    }
}
