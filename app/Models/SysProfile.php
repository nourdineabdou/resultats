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
 * Class SysProfile
 * 
 * @property int $id
 * @property string $libelle
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|SysDroit[] $sys_droits
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class SysProfile extends Model
{
	use SoftDeletes;
	protected $table = 'sys_profiles';

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'ordre'
	];

	public function sys_droits()
	{
		return $this->belongsToMany(SysDroit::class, 'sys_profiles_sys_droits')
					->withPivot('id', 'ordre', 'deleted_at')
					->withTimestamps();
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'sys_profiles_users')
					->withPivot('id', 'commune_id', 'ordre', 'deleted_at')
					->withTimestamps();
	}
}
