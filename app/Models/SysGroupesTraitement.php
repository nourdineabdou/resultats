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
 * Class SysGroupesTraitement
 * 
 * @property int $id
 * @property string $libelle
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $supprimer
 * 
 * @property Collection|SysDroit[] $sys_droits
 *
 * @package App\Models
 */
class SysGroupesTraitement extends Model
{
	use SoftDeletes;
	protected $table = 'sys_groupes_traitements';

	protected $casts = [
		'ordre' => 'int',
		'supprimer' => 'int'
	];

	protected $fillable = [
		'libelle',
		'ordre',
		'supprimer'
	];

	public function sys_droits()
	{
		return $this->hasMany(SysDroit::class);
	}
}
