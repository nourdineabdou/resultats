<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'sys_types_user_id' => 'int',
        'etat' => 'int',
        'confim' => 'int'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'remember_token',
        'sys_types_user_id',
        'etat',
        'phone',
        'code',
        'confim'
    ];

    public function sysProfiles()
    {
      return $this->belongsToMany('\App\Modules\Admin\Models\SysProfile','sys_profiles_users','user_id','sys_profile_id');
    }

    public function hasAccessForCommune($groupes, $commune_id = null, $type = 0)
    {
        return $this->hasAccess($groupes, $type, $commune_id);
    }

    public function hasAccess($groupes, $type = 0, $commune_id = null)
    {
        if($type==0)
            $type = [0,1,2,3,4,5];
        else
            $type = (is_array($type)) ? $type : [0,$type];
        $groupes = (is_array($groupes)) ? $groupes : [$groupes];
        $profiles = $this->sysProfiles;
        if ($commune_id) {
            $agences = (is_array($commune_id)) ? $commune_id : [$commune_id];
            $profiles = $profiles->whereIn('id', $this->sys_profiles_users->whereIn('commune_id',$agences)->pluck('sys_profile_id'));
        }
        foreach($profiles as $profile){
            if($profile->sys_droits()->whereIn('sys_groupes_traitement_id',$groupes)->whereIn('type_acces',$type)->exists())
                return true;
        }
        return false;
    }

    public function sys_types_user()
    {
        return $this->belongsTo(\App\Models\SysTypesUser::class);
    }

    public function sys_profiles()
    {
        return $this->belongsToMany(\App\Modules\Admin\Models\SysProfile::class, 'sys_profiles_users')
            ->withPivot('id', 'commune_id', 'ordre', 'deleted_at')
            ->withTimestamps();
    }

    public function sys_profiles_users()
    {
        return $this->hasMany(\App\Modules\Admin\Models\SysProfilesUser::class);
    }
}
