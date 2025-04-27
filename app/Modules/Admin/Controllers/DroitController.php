<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Modules\Admin\Requests\DroitRequest;
use App\Modules\Admin\Models\SysProfile;
use App\Modules\Admin\Models\SysProfilesUser;
use App\Modules\Admin\Models\SysProfilesSysDroit;
use App\Modules\Admin\Models\SysDroit;
use App\Modules\Admin\Models\SysGroupesTraitement;
use App\Models\SysTypesUser;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Schema;

class DroitController extends Controller
{
    private $views = 'Admin::droits';
    private $link = 'admin/droits';

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
            $droits = SysDroit::orderByRaw('id = ? desc', [$selected]);
        else
            $droits = SysDroit::all();
        return DataTables::of($droits)
            ->addColumn('actions', function(SysDroit $droit) {
                $html = '<div class="btn-group float-right">';
                $html .=' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal('.$droit->id.',\'admin/droits\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.visualiser').'"><i class="fa fa-fw fa-eye"></i></button> ';
                if(Auth::user()->hasAccess(1)){
                    $html .=' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\''.url($this->link.'/delete/'.$droit->id).'\',\''.trans('Admin::admin.confirm_suppression').'\')" data-toggle="tooltip" data-placement="top" title="'.trans('Admin::admin.supprimer').'"><i class="fas fa-trash"></i></button> ';
                }
                $html .='</div>';
                return $html;
            })
            ->setRowClass(function ($droit) use ($selected) {
                return $droit->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id','actions'])
            ->make(true);
    }

    public function formAdd()
    {
        return view($this->views.'.add',[
            'last_order' => SysDroit::max('ordre') + 1,
            'sys_groupes_traitements' => SysGroupesTraitement::all(),
        ]);
    }

    public function add(DroitRequest $request)
    {
        $droit = new SysDroit;
        $droit->libelle = $request->libelle;
        $droit->sys_groupes_traitement_id = $request->sys_groupes_traitement_id;
        $droit->type_acces = $request->type_acces;
        $droit->ordre = ($request->ordre) ? $request->ordre : 1;
        if (SysDroit::where(['sys_groupes_traitement_id'=>$request->sys_groupes_traitement_id,'type_acces'=>$request->type_acces])->exists()) {
            return response()->json(['errormsg'=>[trans('Admin::admin.droit_exist')]],422);
        } 
        $droit->save();
        return response()->json($droit->id,200);
    }

    public function edit(DroitRequest $request)
    {
        $droit = SysDroit::find($request->id);
        if(
            $droit->libelle != $request->libelle ||
            $droit->sys_groupes_traitement_id != $request->sys_groupes_traitement_id ||
            $droit->type_acces != $request->type_acces ||
            $droit->ordre != $request->ordre
            )
        {             
            $droit->libelle = $request->libelle;
            $droit->sys_groupes_traitement_id = $request->sys_groupes_traitement_id;
            $droit->type_acces = $request->type_acces;
            $droit->ordre = ($request->ordre) ? $request->ordre : 1;
            $droit->save();
            return response()->json('Done',200);
        }
        else{
            return response()->json(['error'=>[trans('Admin::admin.aucune_modification')]],422);
        }
    }

    public function get($id)
    {
        $droit = SysDroit::find($id);
        $tablink = $this->link.'/getTab/'.$id;
        $tabs = [
            '<i class="fa fa-info-circle"></i> '.trans('Admin::admin.info') => $tablink.'/1',
        ];
        $modal_title = '<b>'.$droit->libelle.'</b>';
        return view('tabs',['tabs'=>$tabs,'modal_title'=>$modal_title]);
    }

    public function getTab($id,$tab, $selected = null)
    {
        $droit = SysDroit::find($id);
        switch ($tab) {
            case '1':
                $parametres = ['droit' => $droit, 'sys_groupes_traitements' => SysGroupesTraitement::all()];
                break;
            default :
                $parametres = ['droit' => $droit];
                break;
        }
        return view($this->views.'.tabs.tab'.$tab,$parametres);
    }

    public function delete($id)
    {
        $droit = SysDroit::find($id);
        if ($droit->sys_droits->count())
            return response()->json(['success'=>'false', 'msg'=>trans('Admin::admin.droit_cant_be_del_bcuz_of_droits')],200);
        else {
            $droit->delete();
            return response()->json(['success'=>'true', 'msg'=>trans('Admin::admin.element_well_deleted')],200);
        }
    }
}
