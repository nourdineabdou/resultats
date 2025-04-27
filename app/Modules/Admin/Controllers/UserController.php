<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Departement;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Modules\Admin\Requests\UserRequest;
use App\Modules\Admin\Requests\ResetPasswordRequest;
use App\Modules\Admin\Requests\AddProfileRequest ;
use App\Modules\Admin\Models\SysProfile;
use App\Modules\Admin\Models\SysProfilesUser;
use App\Modules\Admin\Models\SysProfilesSysDroit;
use App\Modules\Admin\Models\SysDroit;
use App\Models\SysTypesUser;
use App\Models\Commune;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use DataTables;
use Crypt;
use DB;
use Schema;

class UserController extends Controller
{
    private $views = 'Admin::users';
    private $link = 'admin/users';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view($this->views.'.index');
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
    public function getDT($etat, $selected = 'none')
    {
        $users = User::where('etat',$etat);
        if ($selected != 'none')
            $users = $users->orderByRaw('id = ? desc', [$selected]);
        return DataTables::of($users)
            ->addColumn('actions', function(User $user) {
                $html = '<div class="btn-group float-right">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$user->id.',\'admin/users\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                if(Auth::user()->hasAccess(1)){
                    $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="openInModal(\''.url('admin/users/resetpassword/'.$user->id).'\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.reset_password').'"><i class="fas fa-key"></i></button> ';
                    // $html .= '<a href="'.url("users/resetpassword/".Crypt::encrypt($user->id)).'" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Réinitialiser le mot de passe"><i class="fa fa-key"></i></a> ';
                    if (Auth::user()->id != $user->id) {
                        /*if ($user->etat==1)
                            $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url('admin/users/disableUser/'.Crypt::encrypt($user->id)).'\',\''.trans('Admin::admin.confirm_suspension_user').'\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.suspendre').'"><i class="fas fa-user-times"></i></button> ';
                        else
                            $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url('admin/users/enableUser/'.Crypt::encrypt($user->id)).'\',\''.trans('Admin::admin.confirm_reactivation_user').'\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.reactiver').'"><i class="fas fa-heck-circle-o"></i></button> ';
                        */
                        $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->link.'/delete/'.$user->id).'\',\''.trans('Admin::admin.confirm_suppression').'\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.supprimer').'"><i class="fas fa-trash"></i></button> ';
                    }
                }
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($user) use ($selected) {
                return $user->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        $departements=Departement::all();
        return view($this->views.'.add', ['sys_types_users' => SysTypesUser::all(),'departements'=>$departements]);
    }

    public function add(UserRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->username = $request->username;
        // $user->sys_types_user_id = $request->sys_types_user_id;
        $user->confirm = 1;
       $user->code = $request->departement;
        $user->etat = 1;
        $user->save();
        return response()->json($user->id,200);
    }

    public function edit(UserRequest $request)
    {
        $user = User::find($request->id);if( $user->name != $request->name ||
        $user->username != $request->username ||
        $user->sys_types_user_id != $request->sys_types_user_id ||
        $user->email != $request->email
    )
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        // $user->sys_types_user_id = $request->sys_types_user_id;
        $user->save();
        return response()->json('Done',200);
    }
    else{
        return response()->json(['error'=>[trans('Admin::admin.aucune_modification')]],422);
    }
    }

    public function get($id)
    {
        $user = User::find($id);
        $tablink = $this->link.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('Admin::admin.info') => $tablink.'/1',
            '<i class="fa fa-id-card"></i> '.trans('Admin::admin.profiles') => $tablink.'/2',
        ];
        $modal_title = '<b>'.$user->name.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab, $selected = null)
    {
        $user = User::find($id);
        switch ($tab) {
            case '1':
                $parametres = ['user' => $user, 'sys_types_users' => SysTypesUser::all()];
                break;
            case '2':
                $parametres = ['user'=>$user, 'selected'=>$selected];
                break;
            default :
                $parametres = ['user' => $user];
                break;
        }
        return view($this->views.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user->sysProfiles->count())
            return response()->json(['success'=>'false', 'msg'=>trans('text.user_cant_be_del')],200);
        else {
            $user->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('Admin::admin.element_well_deleted')],200);
        }
    }

    public function formAddProfileToUser($id)
    {
        $user = User::find($id);
        $communes = null;
        if (Auth::user()->hasAccess(1)){
            $communes = Commune::all();
            $profiles = SysProfile::whereIn('id',SysProfilesSysDroit::pluck('sys_profile_id'))->get();
        }
        else{
            // $etablissements = BEtablissement::whereIn('id', Auth::user()->get_etablissements(2, 0))->get();
            $profiles = SysProfile::whereNotIn('id',SysProfilesSysDroit::whereIn('sys_droit_id',SysDroit::whereIn('sys_groupes_traitement_id',[1])->pluck('id'))->pluck('sys_profile_id'))->get();
        }
        $params = ['user'=>$user,'communes'=>$communes,'profiles'=>$profiles];
        return view('Admin::users.ajax.addProfileToUser',$params);
    }

    public function addProfileToUser(AddProfileRequest $request)
    {
        $communes = $request->communes;
        $cpt = 0; // to be used later for errors
        $err = 0; // to be used later for errors
        $params = ['sys_profile_id'=>$request->profile, 'user_id'=>$request->id];
        foreach ($communes as $commune) {
            $params['commune_id'] = $commune;
            if(!SysProfilesUser::where($params)->exists()){
                $profileuser = new SysProfilesUser;
                $profileuser->sys_profile_id = $request->profile;
                $profileuser->user_id = $request->id;
                $profileuser->commune_id = $commune;
                $profileuser->save();
            }
            else
                $err++;
            $cpt++;
        }
        if($err)
            return response()->json(['Exists'=>['Cet utilisateur a deja un profil avec ces même parametres!']],422);
        return response()->json($request->id,200);
    }

    public function deleteProfileFromUser($id)
    {
        $profileuser = SysProfilesUser::find($id);
        $profileuser->forceDelete();
        return response()->json(['success'=>'true', 'msg'=>trans('Admin::admin.element_well_deleted')],200);
    }

    public function ShowFormResetPasswordUser($id)
    {
        $user = User::find($id);
        return view('Admin::users.ajax.resetPassword',['user'=>$user]);
    }

    public function resetPasswordUser(ResetPasswordRequest $request)
    {
        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json('Done',200);
    }

    public function ShowFormResetMyPasswordUser()
    {
        return view('Admin::users.ajax.resetMyPassword');
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
}
