<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Orientation
 * 
 * @property int $id
 * @property int|null $matier_id
 * @property int|null $profil_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Orientation extends Model
{
	use SoftDeletes;
	protected $table = 'orientations';

	protected $casts = [
		'matier_id' => 'int',
		'profil_id' => 'int'
	];

	protected $fillable = [
		'matier_id',
		'profil_id'
	];
}
