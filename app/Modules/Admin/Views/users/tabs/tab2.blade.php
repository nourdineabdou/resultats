<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-primary float-right mb-3" onClick="addProfileToUser('{{$user->id}}')" data-toggle="tooltip" data-target="top">
            <i class="fa fa-plus"></i> {{ trans('Admin::admin.add_profile') }}
        </button>
        <div class="clearfix"></div>
        @if(count($user->sys_profiles))
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th> {{trans('Admin::admin.profil')}} </th>
                            <th class="text-right" width="40px"> {{trans('Admin::admin.actions')}} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->sys_profiles_users as $pofile_user)
                            <tr class="dlt-element {{($selected && ($selected == $pofile_user->id)) ? 'bg-success':''}}">
                                <td> {{ $pofile_user->sys_profile->libelle }}</td>
                                <td>
                                @if(Auth::user()->hasAccess(1))
                                    <a href="#" onClick="deleteProfileFromUser('{{url('admin/users/deleteProfileFromUser/'.$pofile_user->id)}}','{{trans('Admin::admin.confirm_suppression')}}')" class="btn btn-secondary float-right" msg="{{trans('Admin::admin.confirm_del_profile')}}" data-toggle="tooltip" data-placement="top" title="Supprimer"><i class="fa fa-trash"></i></a>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="alert alert-warning"><i class="fa fa-warning"></i> {{trans('Admin::admin.no_profile_for_this_user')}}</div>
        @endif
    </div>
</div>
