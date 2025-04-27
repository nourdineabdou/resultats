<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Plage
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $ref_semestre_id
 * @property int $etape_id
 * @property int|null $nb
 * @property int|null $debut
 * @property int|null $fin
 * @property int|null $valide
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Plage extends Model
{
	use SoftDeletes;
	protected $table = 'plages';

	protected $casts = [
		'profil_id' => 'int',
		'ref_semestre_id' => 'int',
		'etape_id' => 'int',
		'nb' => 'int',
		'debut' => 'int',
		'fin' => 'int',
		'valide' => 'int'
	];

	protected $fillable = [
		'profil_id',
		'ref_semestre_id',
		'etape_id',
		'nb',
		'debut',
		'fin',
		'valide'
	];
}
