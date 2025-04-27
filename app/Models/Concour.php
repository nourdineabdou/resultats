<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Concour
 * 
 * @property int $id
 * @property string|null $libelle
 * @property int|null $nbre_admis
 * @property int|null $nbre_attent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Concour extends Model
{
	use SoftDeletes;
	protected $table = 'concours';

	protected $casts = [
		'nbre_admis' => 'int',
		'nbre_attent' => 'int'
	];

	protected $fillable = [
		'libelle',
		'nbre_admis',
		'nbre_attent'
	];
}
