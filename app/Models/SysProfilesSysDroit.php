<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SysProfilesSysDroit
 * 
 * @property int $id
 * @property int $sys_profile_id
 * @property int $sys_droit_id
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property SysDroit $sys_droit
 * @property SysProfile $sys_profile
 *
 * @package App\Models
 */
class SysProfilesSysDroit extends Model
{
	use SoftDeletes;
	protected $table = 'sys_profiles_sys_droits';

	protected $casts = [
		'sys_profile_id' => 'int',
		'sys_droit_id' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'sys_profile_id',
		'sys_droit_id',
		'ordre'
	];

	public function sys_droit()
	{
		return $this->belongsTo(SysDroit::class);
	}

	public function sys_profile()
	{
		return $this->belongsTo(SysProfile::class);
	}
}
