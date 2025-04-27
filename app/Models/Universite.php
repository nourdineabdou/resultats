<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Universite
 * 
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property string|null $nom_resp
 * @property string|null $nom_resp_ar
 * @property string $code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Universite extends Model
{
	use SoftDeletes;
	protected $table = 'universites';

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'nom_resp',
		'nom_resp_ar',
		'code'
	];
}
