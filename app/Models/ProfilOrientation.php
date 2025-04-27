<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProfilOrientation
 * 
 * @property int $id
 * @property int|null $profil_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ProfilOrientation extends Model
{
	use SoftDeletes;
	protected $table = 'profil_orientations';

	protected $casts = [
		'profil_id' => 'int'
	];

	protected $fillable = [
		'profil_id'
	];
}
