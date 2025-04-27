<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RefTypeSemestre
 * 
 * @property int $id
 * @property string|null $libelle
 * @property string|null $libelle_ar
 * @property int|null $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class RefTypeSemestre extends Model
{
	use SoftDeletes;
	protected $table = 'ref_type_semestres';

	protected $casts = [
		'type' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'type'
	];
}
