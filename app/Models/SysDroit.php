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
 * Class SysDroit
 * 
 * @property int $id
 * @property string $libelle
 * @property int $type_acces
 * @property int $sys_groupes_traitement_id
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $supprimer
 * 
 * @property SysGroupesTraitement $sys_groupes_traitement
 * @property Collection|SysProfile[] $sys_profiles
 *
 * @package App\Models
 */
class SysDroit extends Model
{
	use SoftDeletes;
	protected $table = 'sys_droits';

	protected $casts = [
		'type_acces' => 'int',
		'sys_groupes_traitement_id' => 'int',
		'ordre' => 'int',
		'supprimer' => 'int'
	];

	protected $fillable = [
		'libelle',
		'type_acces',
		'sys_groupes_traitement_id',
		'ordre',
		'supprimer'
	];

	public function sys_groupes_traitement()
	{
		return $this->belongsTo(SysGroupesTraitement::class);
	}

	public function sys_profiles()
	{
		return $this->belongsToMany(SysProfile::class, 'sys_profiles_sys_droits')
					->withPivot('id', 'ordre', 'deleted_at')
					->withTimestamps();
	}
}
