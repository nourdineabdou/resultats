<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FamilleRequest;
use App\Http\Requests\refRequest;

use App\Models\Famille;

use App\Models\Domaine;
use App\Models\RefTypesFormation;
use App\Models\RefTypesExperience;
use App\Models\RefSituationProfessionnel;
use App\Models\RefNiveauxCompetence;
use App\Models\RefNiveauEtude;
use App\Models\RefLangue;
use App\Models\RefTypesProgramme;
use App\Models\RefTypesEtablissement;
use App\Models\RefFonctionsAgent;

use DataTables;
use App\User;
use App;
use Auth;
use DB;
use Crypt;
use Schema;
use Carbon\Carbon;

class refController extends Controller
{
    private $module = 'familles';

    //private $viewLink = 'backend.'.$this->module;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function listes($ref)
    {
        /**
         * name        : listes
         * parametres  : Nom du model
         * return      : liste des d'objets de reference
         * Description :
         */
        $name = "App\\Models\\" . $ref;
        $liste = $name::get();
        // dd($liste);
        $param = $this->get_title($ref);
        $tableau = explode(';', $param);
        $title = $tableau[0];
        $title_pop_up = $tableau[1];
        $lastorder = $name::max('ordre') + 1;
        return view('referentiels.referentiels', ['selected' => "none", 'title_pop_up' => $title_pop_up, "liste" => $liste, "model" => $ref, "lastorder" => $lastorder, "title" => $title]);
    }

    public function getRefsDT($ref, $selected)
    {
        $name = "App\\Models\\" . $ref;
        $listes = $name::query();

        if ($selected != 'none')
            $listes = $listes->orderByRaw('id = ? desc', [$selected]);

        return Datatables::of($listes)
            ->addColumn('actions', function ($obj) use ($ref) {
                $actions = '<div class="btn-group"><button type="button" class="btn btn-sm btn-dark" onClick="openEditRefModal(\'' . $ref . ',' . $obj->id . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fa fa-fw fa-eye"></i></button>';
                // if (Auth::user()->hasAccess(5,3))
                $actions .= ' <a href="' . url("delete/$ref/" . Crypt::encrypt($obj->id)) . '" onClick="return confirm(\'' . trans('text_my.bien_confirme_supprimer') . ' : ' . $obj->libelle . '? \')" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></a>';

                $actions . '</div>';
                return $actions;
            })
            ->setRowClass(function ($ref) use ($selected) {
                return $ref->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function get_title($ref)
    {
        $title = "";
        switch ($ref) {
            case 'Domaine':
                $title = trans('text_my.domaines');
                $title_modif = trans('text_my.modification_domaine');
                $title_nouveau = trans('text_my.new_domaine');
                break;
            case 'RefTypesFormation':
                $title = trans('text_my.types_formation');
                $title_modif = trans('text_my.modification_type_formation');
                $title_nouveau = trans('text_my.new_type_form');
                break;
            case 'RefTypesExperience':
                $title = trans('text_my.types_experiance');
                $title_modif = trans('text_my.modification_type_experiance');
                $title_nouveau = trans('text_my.new_type_experiance');
                break;
            case 'RefSituationProfessionnel':
                $title = trans('text_my.sitaut_professionel');
                $title_modif = trans('text_my.modification_sitaut_professionel');
                $title_nouveau = trans('text_my.new_sit_profes');
                break;
            case 'RefNiveauxCompetence':
                $title = trans('text_my.niveau_competance');
                $title_modif = trans('text_my.modification_niveau_competance');
                $title_nouveau = trans('text_my.new_niveau_competance');
                break;
            case 'RefNiveauEtude':
                $title = trans('text_my.niveau_etude');
                $title_modif = trans('text_my.modification_niveau_etude');
                $title_nouveau = trans('text_my.new_niv_etude');
                break;
            case 'RefLangue':
                $title = trans('text_my.langues');
                $title_modif = trans('text_my.modification_langue');
                $title_nouveau = trans('text_my.new_langue');
                break;
            case 'RefTypesProgramme':
                $title = trans('text_my.types_programme');
                $title_modif = trans('text_my.modification_types_programme');
                $title_nouveau = trans('text_my.new_type_programme');
                break;
            case 'RefTypesEtablissement':
                $title = trans('text_my.types_etablissement');
                $title_modif = trans('text_my.modification_types_etab');
                $title_nouveau = trans('text_my.new_type_etab');
                break;
            case 'RefFonctionsAgent':
                $title = trans('text_my.fonction_agent');
                $title_modif = trans('text_my.modification_fonction_agent');
                $title_nouveau = trans('text_my.new_fonction_agent');
                break;
            case 'Specialite':
                $title = trans('text_hb.specialites');
                $title_modif = trans('text_hb.modification_specialite');
                $title_nouveau = trans('text_hb.new_specialite');
                break;
            case 'Service':
                $title = trans('text_hb.services');
                $title_modif = trans('text_hb.modification_service');
                $title_nouveau = trans('text_hb.new_service');
                break;
            case 'RefAppreciationsHierarchy':
                $title = trans('text_hb.Appreciation_heirarchie');
                $title_modif = trans('text_hb.modification_appreciation_heirarchie');
                $title_nouveau = trans('text_hb.new_appreciation_heirarchie');
                break;
            case 'RefTypesContrat':
                $title = trans('text_hb.type_contrat');
                $title_modif = trans('text_hb.modification_type_contrat');
                $title_nouveau = trans('text_hb.new_type_contrat');
                break;
            case 'RefSituationFamilliale':
                $title = trans('text_hb.sit_fam');
                $title_modif = trans('text_hb.modification_tsit_fam');
                $title_nouveau = trans('text_hb.new_sit_fam');
                break;

            default:
                # code...
                break;
        }
        return $title . ';' . $title_nouveau . ';' . $title_modif;
    }

    public function get_msg($type, $id, $msg)
    {
        $name = "App\\Models\\" . $type;
        $ref = "/ref/" . $type;
        // $msg ='';
        switch ($type) {
            //begin referentiel
            case  'Domaine':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.la_domaine') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefTypesFormation':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.le_type_form') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefTypesExperience':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.le_type_experiance') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefSituationProfessionnel':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.la_situatio_profes') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefNiveauxCompetence':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.le_niveau_compe') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefNiveauEtude':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.le_niveau_etude') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefLangue':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.la_langue') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefTypesProgramme':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.le_type_programme') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefTypesEtablissement':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.le_type_etab') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefFonctionsAgent':
                $object = $name::withTrashed()->find($id);
                return trans('text_my.la_fonction_agent') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'Specialite':
                $object = $name::withTrashed()->find($id);

                return trans('text_hb.la_specialite') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'Service':
                $object = $name::withTrashed()->find($id);
                return trans('text_hb.le_service') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefAppreciationsHierarchy':
                $object = $name::withTrashed()->find($id);
                return trans('text_hb.le_service') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefTypesContrat':
                $object = $name::withTrashed()->find($id);
                return trans('text_hb.le_type_contrat') . ' "' . $object->libelle . '"  ' . $msg;
                break;
            case  'RefSituationFamilliale':
                $object = $name::withTrashed()->find($id);
                return trans('text_hb.la_sit_fam') . ' "' . $object->libelle . '"  ' . $msg;
                break;

        }
    }

    public function add_ref(refRequest $request)
    {
        /**
         * name        : add_ref
         * parametres  : Les info du referentiel
         * return      : json
         * Description : permet l'ajout d'une nouvelle referentiel
         */
        $model = $request->model;
        $name = "App\\Models\\" . $model;
        $param = $name::where("libelle", $request->libelle)->count();
        if ($param == 0) {
            $referentiel = new $name;
            $referentiel->libelle = $request->libelle;
            $referentiel->libelle_ar = $request->libelle_ar;
            $referentiel->ordre = $request->ordre;
            if ($model == 'Service')
                $referentiel->commune_id = env('APP_COMMUNE');
            $referentiel->save();
            // $link = url("/ref/$model");
            $link = url("redirectto/$model/" . $referentiel->id);
            return response()->json($link, 200);
        } else {
            return response()->json(['libelle' => [trans('text_my.libelle_existe')]], 422);
        }
    }

    public function edit_ref($ref, $id)
    {
        /**
         *  name      : edit_ref
         * parametres : le nom du model et l'ID de l'objet a modifier
         * return     :
         * Descrption :
         */
        $name = "App\\Models\\" . $ref;
        $listes = $name::find($id);
        $param = $this->get_title($ref);
        $tableau = explode(';', $param);
        $title = $tableau[0];
        // $title_pop_up=$tableau[1];
        $title_modif = $tableau[2];
        // return view('referentiel.editRef',['title_modif'=>$title_modif,"listes"=>$listes,"model"=>$ref,"title"=>$title]);
        return view('referentiels.edit_ref', ['title_modif' => $title_modif, "listes" => $listes, "model" => $ref, "title" => $title]);
    }

    public function update_ref(refRequest $request)
    {
        /**
         *  name      : update_ref
         * parametres :Request (toutes les information du reference a modifier)
         * return     :
         * Descrption :
         */
        // dd($request);
        $id = $request->id;
        $model = $request->model;
        $name = "App\\Models\\" . $model;
        $param = $name::whereNotIn("id", [$id])->where("libelle", $request->libelle)->count();
        if (!$param) {
            $referentiel = $name::find($id);
            $referentiel->libelle = $request->libelle;
            $referentiel->libelle_ar = $request->libelle_ar;
            $referentiel->ordre = $request->ordre;
            $referentiel->save();
            /*$link = url("redirectto/$model/".$referentiel->id);
            return response()->json($link,200);*/
            // $message = $this->get_msg($model,$referentiel->id, trans('text_a.bien_modifier'));
            // return redirect('/ref/'.$model)->with('successmsg',$message);
            return response()->json('Done', 200);

        } else {
            // return back()->with('errormsg',trans('text.libelle_existe'));
            return response()->json(['libelle' => [trans('text_my.libelle_existe')]], 422);
        }
    }

    public function delete_ref($ref, $id)
    {
        /**
         *  name      : delete_ref
         * parametres : Request (toutes les information du reference a supprimer)
         * return     : message
         * Descrption :
         */
        $id = Crypt::decrypt($id);
        $model = $ref;
        $name = "App\\Models\\" . $ref;
        $referentiel = $name::find($id);
        $referentiel->delete();
        $message = $this->get_msg($model, $referentiel->id, trans('text_my.bien_supprimer'));
        return redirect('/ref/' . $model)->with('successmsg', $message);
    }


    public function redirectTo($type, $id)
    {
        $name = "App\\Models\\" . $type;
        $ref = "/ref/" . $type;
        switch ($type) {
            //begin referentiel
            case  'Domaine':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.la_domaine') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefTypesFormation':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.le_type_form') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefTypesExperience':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.le_type_experiance') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefSituationProfessionnel':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.la_situatio_profes') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefNiveauxCompetence':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.le_niveau_compe') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefNiveauEtude':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.le_niveau_etude') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefLangue':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.la_langue') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefTypesProgramme':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.le_type_programme') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefTypesEtablissement':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.le_type_etab') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefFonctionsAgent':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_my.la_fonction_agent') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'Specialite':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_hb.la_specialite') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);

                break;
            case  'Service':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_hb.le_service') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefAppreciationsHierarchy':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_hb.la_appreciation_heirarchie') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefTypesContrat':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_hb.le_type_contrat') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;
            case  'RefSituationFamilliale':
                $object = $name::find($id);
                return redirect($ref)->with(['successmsg' => trans('text_hb.la_sit_fam') . ' "' . $object->libelle . '" a été bien ajouté', 'selected' => $object->id]);
                break;

        }

    }

}
