<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $username
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $sys_types_user_id
 * @property int $etat
 * @property string|null $phone
 * @property string|null $code
 * @property int $confirm
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property SysTypesUser $sys_types_user
 * @property Collection|SysProfile[] $sys_profiles
 *
 * @package App\Models
 */
class User extends Model
{
	use SoftDeletes;
	protected $table = 'users';

	protected $casts = [
		'sys_types_user_id' => 'int',
		'etat' => 'int',
		'confirm' => 'int'
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
		'confirm'
	];

	public function sys_types_user()
	{
		return $this->belongsTo(SysTypesUser::class);
	}

	public function sys_profiles()
	{
		return $this->belongsToMany(SysProfile::class, 'sys_profiles_users')
					->withPivot('id', 'commune_id', 'ordre', 'deleted_at')
					->withTimestamps();
	}
}
