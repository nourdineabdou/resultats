<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EnteteEtablissement
 * 
 * @property int $id
 * @property int $faculte_id
 * @property string $titre1
 * @property string $titre1_ar
 * @property string $titre2
 * @property string $titre2_ar
 * @property string $titre3
 * @property string $titre3_ar
 * @property string $logo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EnteteEtablissement extends Model
{
	use SoftDeletes;
	protected $table = 'entete_etablissements';

	protected $casts = [
		'faculte_id' => 'int'
	];

	protected $fillable = [
		'faculte_id',
		'titre1',
		'titre1_ar',
		'titre2',
		'titre2_ar',
		'titre3',
		'titre3_ar',
		'logo'
	];
}
