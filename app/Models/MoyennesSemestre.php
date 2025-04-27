<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MoyennesSemestre
 * 
 * @property int $id
 * @property int $etudiant_id
 * @property int $profil_id
 * @property int $ref_groupe_id
 * @property int $ref_semestre_id
 * @property float $note
 * @property int $annee_id
 * @property int|null $decision
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MoyennesSemestre extends Model
{
	use SoftDeletes;
	protected $table = 'moyennes_semestres';

	protected $casts = [
		'etudiant_id' => 'int',
		'profil_id' => 'int',
		'ref_groupe_id' => 'int',
		'ref_semestre_id' => 'int',
		'note' => 'float',
		'annee_id' => 'int',
		'decision' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'profil_id',
		'ref_groupe_id',
		'ref_semestre_id',
		'note',
		'annee_id',
		'decision'
	];
}
