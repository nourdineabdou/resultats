<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tewjih
 * 
 * @property int $id
 * @property string|null $etudiant
 * @property string|null $profil_id
 * @property string|null $groupe
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Tewjih extends Model
{
	use SoftDeletes;
	protected $table = 'tewjihs';

	protected $fillable = [
		'etudiant',
		'profil_id',
		'groupe'
	];
}
