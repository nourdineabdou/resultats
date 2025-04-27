<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TmpOrientation
 * 
 * @property int $id
 * @property int|null $profil_id
 * @property float|null $moyenne
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $etudiant_id
 *
 * @package App\Models
 */
class TmpOrientation extends Model
{
	use SoftDeletes;
	protected $table = 'tmp_orientations';

	protected $casts = [
		'profil_id' => 'int',
		'moyenne' => 'float',
		'etudiant_id' => 'int'
	];

	protected $fillable = [
		'profil_id',
		'moyenne',
		'etudiant_id'
	];
}
