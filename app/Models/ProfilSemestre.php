<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProfilSemestre
 * 
 * @property int $id
 * @property string $code
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $departement_id
 * @property int $ref_niveau_etude_id
 * @property int|null $profil_progression
 * @property int $ref_semestre_id
 * @property int $etape_id
 * @property int $faculte_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ProfilSemestre extends Model
{
	use SoftDeletes;
	protected $table = 'profil_semestres';

	protected $casts = [
		'departement_id' => 'int',
		'ref_niveau_etude_id' => 'int',
		'profil_progression' => 'int',
		'ref_semestre_id' => 'int',
		'etape_id' => 'int',
		'faculte_id' => 'int'
	];

	protected $fillable = [
		'code',
		'libelle',
		'libelle_ar',
		'departement_id',
		'ref_niveau_etude_id',
		'profil_progression',
		'ref_semestre_id',
		'etape_id',
		'faculte_id'
	];
}
