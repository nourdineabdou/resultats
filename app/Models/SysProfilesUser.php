<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SysProfilesUser
 * 
 * @property int $id
 * @property int $sys_profile_id
 * @property int $user_id
 * @property int|null $commune_id
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property SysProfile $sys_profile
 * @property User $user
 *
 * @package App\Models
 */
class SysProfilesUser extends Model
{
	use SoftDeletes;
	protected $table = 'sys_profiles_users';

	protected $casts = [
		'sys_profile_id' => 'int',
		'user_id' => 'int',
		'commune_id' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'sys_profile_id',
		'user_id',
		'commune_id',
		'ordre'
	];

	public function sys_profile()
	{
		return $this->belongsTo(SysProfile::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
