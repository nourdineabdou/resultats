<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bureau
 * 
 * @property int $id
 * @property string|null $libelle
 * @property string|null $libelle_ar
 * @property int|null $centre_id
 * @property int|null $commune_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Bureau extends Model
{
	use SoftDeletes;
	protected $table = 'bureaus';

	protected $casts = [
		'centre_id' => 'int',
		'commune_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'centre_id',
		'commune_id'
	];
}
