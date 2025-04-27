<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Modules\Admin\Requests\ProfileRequest;
use App\Modules\Admin\Models\SysProfile;
use App\Modules\Admin\Models\SysProfilesUser;
use App\Modules\Admin\Models\SysProfilesSysDroit;
use App\Modules\Admin\Models\SysDroit;
use App\Models\SysTypesUser;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Schema;

class ProfileController extends Controller
{
    private $views = 'Admin::profiles';
    private $link = 'admin/profiles';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view($this->views.'.index');
    }

    public function getDT($selected = 'none')
    {
        if ($selected != 'none')
            $profiles = SysProfile::orderByRaw('id = ? desc', [$selected]);
        else
            $profiles = SysProfile::all();
        return DataTables::of($profiles)
            ->addColumn('actions', function(SysProfile $profile) {
                $html = '<div class="btn-group float-right">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$profile->id.',\'admin/profiles\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                if(Auth::user()->hasAccess(1)){
                    $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->link.'/delete/'.$profile->id).'\',\''.trans('Admin::admin.confirm_suppression').'\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.supprimer').'"><i class="fas fa-trash"></i></button> ';
                }
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($profile) use ($selected) {
                return $profile->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        return view($this->views.'.add');
    }

    public function add(ProfileRequest $request)
    {
        $profile = new SysProfile;
        $profile->libelle = $request->libelle;
        $profile->save();
        return response()->json($profile->id,200);
    }

    public function edit(ProfileRequest $request)
    {
        $profile = SysProfile::find($request->id);if(
            $profile->libelle != $request->libelle
            )
        {
            $profile->libelle = $request->libelle;
            $profile->save();
            return response()->json('Done',200);
        }
        else{
            return response()->json(['error'=>[trans('Admin::admin.aucune_modification')]],422);
        }
    }

    public function get($id)
    {
        $profile = SysProfile::find($id);
        $tablink = $this->link.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('Admin::admin.info') => $tablink.'/1',
            '<i class="fa fa-id-card"></i> '.trans('Admin::admin.droits') => $tablink.'/2',
        ];
        $modal_title = '<b>'.$profile->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab, $selected = null)
    {
        $profile = SysProfile::find($id);
        switch ($tab) {
            case '1':
                $parametres = ['profile' => $profile];
                break;
            default :
                $parametres = ['profile' => $profile];
                break;
        }
        return view($this->views.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $profile = SysProfile::find($id);
        if ($profile->sys_droits->count())
            return response()->json(['success'=>'false', 'msg'=>trans('Admin::admin.profile_cant_be_del_bcuz_of_droits')],200);
        else {
            $profile->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('Admin::admin.element_well_deleted')],200);
        }
    }

    public function getDroitsDT($id)
    {
        $sys_profile = SysProfile::find($id);
        $droits =SysDroit::whereNotIn('id',SysProfilesSysDroit::where('sys_profile_id',$sys_profile->id)->pluck('sys_droit_id'))->get();
        return Datatables::of($droits)
                ->editColumn('libelle', function($droit){
                    return $droit->libelle;
                })
                ->addColumn('actions', function($droit) {
                    $html = '<div class="btn-group float-right">';
                    $html .='<button type="button" idelt="'.$droit->id.'" libelle="'.$droit->libelle.'" data-toggle="tooltip" data-placement="top" title="Ajouter a ce profil" class="btn btn-light" onClick="updateGroupeElements(this)"><i class="fa fa-fw fa-arrow-right"></i></button>';
                    return $html.'</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
    }
    public function updateGrouping($list, $id)
    {
        SysProfilesSysDroit::where(['sys_profile_id'=>$id])->ForceDelete();
        if ($list) {
            $list = explode(',',$list);
            foreach ($list as $qst_id) {
                $grp_qst = new SysProfilesSysDroit;
                $grp_qst->sys_profile_id = $id;
                $grp_qst->sys_droit_id = $qst_id;
                $grp_qst->save();
            }
        }
        $res = "le groupe a été bien mise a jour";
        return response()->json($list ,200);
    }
}
