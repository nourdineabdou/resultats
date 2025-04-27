<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\userRequest;
use App\Models\SysProfile;
use App\Models\SysProfilesUser;
use App\Models\SysProfilesSysDroit;
use App\Models\SysDroit;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use DataTables;
use Crypt;
use DB;
use Schema;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $profiles = SysProfile::all();
        return view('Admin::users.index',['profiles'=>$profiles]);
    }

    public function getUsersDT($etat)
    {
        return DataTables::of(User::where(['etat'=>$etat])->get())
                ->addColumn('actions', function(User $user) {
                    $html = '<a href="#" onClick="openUserModal(\''.$user->id.'\')" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Visualiser"><i class="fa fa-edit"></i></a> ';
                    if(Auth::user()->hasAccess(0,1)){
                        $html .= '<a href="#" onClick="openPasswordModal(\''.$user->id.'\')" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Réinitialiser le mot de passe"><i class="fa fa-key"></i></a> ';
                        // $html .= '<a href="'.url("users/resetpassword/".Crypt::encrypt($user->id)).'" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Réinitialiser le mot de passe"><i class="fa fa-key"></i></a> ';
                        if (Auth::user()->id != $user->id) {
                            if ($user->etat==1)
                                $html .= '<a href="'.url("users/disableUser/".Crypt::encrypt($user->id)).'" onClick="return confirm(\' Etes vous sûr de vouloir suspendre '.$user->name.'? \')" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Suspendre"><i class="fa fa-user-times"></i></a>';
                            else
                                $html .= '<a href="'.url("users/enableUser/".Crypt::encrypt($user->id)).'" onClick="return confirm(\' Etes vous sûr de vouloir réactiver '.$user->name.'? \')" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Réactiver"><i class="fa fa-check-circle-o"></i></a>';
                            $html .= ' <a href="'.url("users/deleteUser/".Crypt::encrypt($user->id)).'" onClick="return confirm(\' Etes vous sûr de vouloir supprimer '.$user->name.'? \')" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Supprimer"><i class="fa fa-trash-o"></i></a>';
                        }
                    }
                    return $html;
                })
                ->rawColumns(['actions','droits'])
                ->make(true);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        $tabs = [
            '<i class="fa fa-info"></i> Info'=>'users/getUserTab/'.$id.'/1',
            '<i class="fa fa-user"></i> Profiles'=>'users/getUserTab/'.$id.'/2'
        ];
        $modal_title = 'Détail de l\'utilisateur';
        return view('Admin::tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getUserTab($id, $tab, $selected = null)
    {
        $user = User::find($id);
        switch ($tab) {
            case '1': {
                $userProfilesId = $user->sysProfiles()->pluck('sys_profiles.id')->toArray();
                $profiles = SysProfile::all();
                $params = ['user'=>$user,'profiles'=>$profiles,'userProfilesId'=>$userProfilesId];
            } break;
            case '2': {
                // $profs = SysProfilesUser::where('users_id',$id)->get();
                // $array =$profs->groupBy('b_strictures_id')->toArray() + $activities->groupBy('niveau_objet')->toArray();
                $userProfiles = $user->sysProfilesWithStructures;
                // $recs = new \Illuminate\Database\Eloquent\Collection($userProfiles);
                // $grouped = $recs->groupBy('b_strictures_id')->transform(function($item, $k) {
                //     return $item->groupBy('niveau_objet');
                // });
                // var_dump($profs);
                $profiles = SysProfile::whereIn('id',SysProfilesUser::pluck('sys_profiles_id'))->get();
                $params = ['user'=>$user, 'userProfiles'=>$userProfiles, 'selected'=>$selected];
            } break;

            default: $params = ['user'=>$user]; break;
        }
        return view('Admin::users.users.tabs.tab'.$tab,$params);
    }
    public function formAddProfileToUser($id)
    {
        $user = User::find($id);
        if (Auth::user()->hasAccess(0,[1])){
            $etablissements = BEtablissement::all();
            $profiles = SysProfile::whereIn('id',SysProfilesSysDroit::pluck('sys_profiles_id'))->get();
        }
        else{
            // $etablissements = BEtablissement::whereIn('id', Auth::user()->get_etablissements(2, 0))->get();
            $profiles = SysProfile::whereNotIn('id',SysProfilesSysDroit::whereIn('sys_droits_id',SysDroit::whereIn('sys_groupes_traitements_id',[1,2])->pluck('id'))->pluck('sys_profiles_id'))->get();
        }
        // $params = ['user'=>$user,'profiles'=>$profiles];
        $params = ['user'=>$user,'etablissements'=>$etablissements,'profiles'=>$profiles];
        return view('Admin::users.users.addProfileToUser',$params);
    }
    public function addProfileToUser(Request $request)
    {
        $this->validate($request, [
            'profile' => 'required',
            'etablissements' => 'required_if:is_admin_etab,1',
        ]);
        $etablissements = $request->etablissements;
        $cpt = 0; // to be used later for errors
        $err = 0; // to be used later for errors
        $params = ['sys_profiles_id'=>$request->profile, 'users_id'=>$request->id];
        if($request->is_admin_etab==1){
            foreach ($etablissements as $etablissement) {
                    $params['b_etablissements_id'] = $etablissement;
                    if(!SysProfilesUser::where($params)->exists()){
                        $profileuser = new SysProfilesUser;
                        $profileuser->sys_profiles_id = $request->profile;
                        $profileuser->users_id = $request->id;
                        // $profileuser->admin_id = Auth::user()->id;
                        $profileuser->b_etablissements_id = $etablissement;
                        $profileuser->save();
                    }
                    else
                        $err++;
                    $cpt++;
            }
        } elseif(!SysProfilesUser::where($params)->exists()){
            $profileuser = new SysProfilesUser;
            $profileuser->sys_profiles_id = $request->profile;
            $profileuser->users_id = $request->id;
            // $profileuser->admin_id = Auth::user()->id;
            $profileuser->b_etablissements_id = null;
            $profileuser->save();
        }
        else
            return response()->json(['Exists'=>['Cet utilisateur a deja un profil avec ces même parametres!']],422);
        return response()->json($request->id,200);
    }

    public function deleteProfileFromUser($id)
    {
        $profileuser = SysProfilesUser::find($id);
        $profileuser->forceDelete();
        $msg="Le profil a été bien supprimé!!";
        $res = array('success' => 'true','msg' => $msg);
        return response()->json($res,200);
    }

    public function disabled()
    {
        $users = User::where(['etat'=>'0'])->get();
        return view('Admin::users.disabled',['users'=>$users]);
    }

    public function showUser($id,$editable=false)
    {
        $id = Crypt::decrypt($id);
        $users = sys_utilisateurs::find($id);
        if($editable)
            return view('Admin::users.userEdit',['users'=>$users]);
        else
            return view('Admin::users.userShow',['users'=>$users]);
    }

    public function  showFormAddUser()
    {
        return view('Admin::users.userAdd');
    }

    public function addUser(userRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->nom_ar = $request->nom_ar;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->fonction = $request->fonction;
        $user->fonction_ar = $request->fonction_ar;
        $user->username = $request->username;
        $user->nom_ar = "";
        $user->fonction_ar = "";
        $user->etat = 1;
        $user->profile_id = 1;
        $user->save();
        // $user->roles()->sync($request->get('droits'));
        $link = url("redirectto/users/".$user->id);
        return response()->json($link,200);
    }

    public function ShowFormEditUser($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        return view('Admin::users.userEdit',['user'=>$user,'roles'=>$roles]);
    }

    public function editUser(userRequest $request)
    {
        /**
         *  name      : editUser
         * parametres : Request (toutes les information de l'utilisateur a modifier)
         * return     : message
         * Descrption :
         */
        $user = User::find($request->id);
        // $link = ($user->etat) ? 'users' : 'users/disabled';
        // $user_roles = $user->Roles->pluck('id')->toArray();
        // $droits = $request->get('droits');
        if( $user->name != $request->name ||
            $user->nom_ar != $request->nom_ar ||
            $user->fonction != $request->fonction ||
            $user->fonction_ar != $request->fonction_ar ||
            $user->username != $request->username ||
            $user->email != $request->email
            )
        {
            $user->name = $request->name;
            $user->nom_ar = $request->nom_ar;
            $user->fonction = $request->fonction;
            $user->fonction_ar = $request->fonction_ar;
            $user->email = $request->email;
            $user->username = $request->username;
            // $user->roles()->sync($request->get('droits'));
            $user->save();
            return response()->json('Done',200);
        }
        else{
            return response()->json(['error'=>[trans('text_mdp.aucune_midification')]],422);
        }
    }

    public function ShowFormResetPasswordUser($id)
    {
        // $id = Crypt::decrypt($id);
        $user = User::find($id);
        return view('Admin::users.userResetPassword',['user'=>$user]);
    }

    public function resetPasswordUser(Request $request)
    {
        $this->validate($request, [
         'password' => 'required|min:4|confirmed',
        ]);
        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();
        $link = ($user->etat) ? 'users' : 'users/disabled';
        return response()->json('Done',200);
        // return redirect($link)->with('successmsg', 'Le mot de passe de l\'utilisateur "'.$user->name.'" a été bien réinitialisé');
    }

    public function ShowFormResetMyPasswordUser()
    {
        return view('Admin::users.userResetMyPassword');
    }

    public function resetMyPasswordUser(Request $request)
    {
        $this->validate($request, [
         'old_password' => 'required|is_user_psw',
         'password' => 'required|min:4|confirmed',
        ]);
        $user = User::find(Auth::user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        $msg = "Votre mot de passe de utilisateur a été bien modifié";
        $back_type = "success";
        $back_txt = "";
        $back_link = "";
        return view('Admin::info',['msg' => $msg, 'back_type' => $back_type, 'back_txt' => $back_txt, 'back_link' => $back_link]);
    }

    public function disableUser($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        $user->etat = 0;
        $user->save();
        return back()->with('successmsg', 'L\'utilisateur "'.$user->name.'" a été bien suspendu');
    }

    public function enableUser($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        $user->etat = 1;
        $user->save();
        return back()->with('successmsg', 'L\'utilisateur "'.$user->name.'" n\'est plus suspendu');
    }

    public function deleteUser($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        $user->supprimer = 1;
        $user->save();
        return back()->with('successmsg', 'L\'utilisateur "'.$user->name.'" a été bien supprimé');
    }

    public function authenticate(Request $request)
    {
        dd('d');
        if (Etudiant::where(['NNI'=>$request->nni, 'NODOS'=>$request->nodos,'DECF'=>0])->exists()) {
                $link =  url('dashboard');

            return response()->json($link,200);
        }
        else {
            if (Etudiant::where(['NNI'=>$request->nni, 'NODOS'=>$request->nodos])->exists())
                return response()->json(['login'=>['Cette utilisateur est suspendu']],422);
            else
                return response()->json(['login'=>['NNI ou NUMERO incorrect']],422);
        }
    }
}
