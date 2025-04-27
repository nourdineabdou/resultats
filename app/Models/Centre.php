<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Centre
 * 
 * @property int $id
 * @property string|null $libelle
 * @property string|null $libelle_ar
 * @property int|null $comunne_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Centre extends Model
{
	use SoftDeletes;
	protected $table = 'centres';

	protected $casts = [
		'comunne_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'comunne_id'
	];
}
