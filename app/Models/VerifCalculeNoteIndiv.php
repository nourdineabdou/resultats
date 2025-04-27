<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VerifCalculeNoteIndiv
 * 
 * @property int $id
 * @property int $etudant_id
 * @property int $ref_semestre_id
 * @property int $annee_id
 * @property int $profil_id
 * @property int $groupe_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class VerifCalculeNoteIndiv extends Model
{
	use SoftDeletes;
	protected $table = 'verif_calcule_note_indivs';

	protected $casts = [
		'etudant_id' => 'int',
		'ref_semestre_id' => 'int',
		'annee_id' => 'int',
		'profil_id' => 'int',
		'groupe_id' => 'int'
	];

	protected $fillable = [
		'etudant_id',
		'ref_semestre_id',
		'annee_id',
		'profil_id',
		'groupe_id'
	];
}
