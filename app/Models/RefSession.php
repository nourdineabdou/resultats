<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RefSession
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class RefSession extends Model
{
	use SoftDeletes;
	protected $table = 'ref_sessions';

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];
}
