<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Etudiant;
use App\Models\EtudMat;
use App\Models\RefSemestre;
use Illuminate\Http\Request;
use App\Models\Module;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth');
    }

    public function dashboard($nodos='')
    {
        $html='';
    if ($nodos!=''){
        $annee=Annee::where('etat',1)->get()->first();
        $etudiant = Etudiant::where('NODOS',$nodos)->orderBy('NODOS','DESC')->get()->first();
        $test1 =EtudMat::where('annee_id',$annee->id)->where('etudiant_id',$etudiant->id)->orderBy('ref_semestre_id')->get();
        $html='<div class="col-md-12 text-center form-group " align="center">'.$etudiant->NODOS.' <br>'.$etudiant->NOMF.' / '.$etudiant->NOMA.'</div>';
        if ($test1->count()>0){
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
    public function authenticate1(Request $request)
    {
       // dd('dff');
        if (Etudiant::where(['NNI'=>$request->nni, 'NODOS'=>$request->nodos])->exists()) {
            return $this->dashboard($request->nodos);
        }
        else {
            if (Etudiant::where(['NNI'=>$request->nni])->exists())
                return $this->sorties('رقم التسجيل غير صحيح');
            else if (Etudiant::where( ['NODOS'=>$request->nodos])->exists())
                return $this->sorties('الرقم الوطني  غير صحيح');
            else return $this->sorties('البيانات غير الصحيحة');
        }
    }
}
