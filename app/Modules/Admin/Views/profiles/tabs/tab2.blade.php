<div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <b>{{ trans('Admin::admin.add_profil_droit') }}</b> 
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <table link="{{url('admin/profiles/getDroitsDT/'.$profile->id)}}" colonnes="libelle,actions" class="table table-bordered datatableshow2">
                <thead>
                  <tr>
                    <th>{{ trans('Admin::admin.droit') }}</th>
                    <th width="40px">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header">
            <b>{{ trans('Admin::admin.droits_profil') }} :</b> "{{$profile->libelle}}"
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <ul class="group-elements sortable list-group" lien="admin/profiles/updatedroits" count="0" datatable-source=".datatableshow2" idgroup="{{ $profile->id }}">
                @foreach($profile->sys_profiles_sys_droits as $sys_profiles_sys_droit)
                  <li id="{{ $sys_profiles_sys_droit->b_droits_acces->id }}" class="list-group-item">
                    {{ $sys_profiles_sys_droit->b_droits_acces->libelle }}
                    <button type="button"
                            idelt="{{ $sys_profiles_sys_droit->b_droits_acces->id }}"
                            libelle="{{ $sys_profiles_sys_droit->b_droits_acces->libelle}}"
                            class="close"
                            aria-hidden="true"
                            onclick="updateGroupeElements(this)"
                            >&times;</button>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>