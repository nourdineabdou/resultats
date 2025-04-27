<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EtudAnneeCredit
 * 
 * @property int $id
 * @property int|null $etudiant_id
 * @property int|null $libelle
 * @property int|null $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EtudAnneeCredit extends Model
{
	use SoftDeletes;
	protected $table = 'etud_annee_credits';

	protected $casts = [
		'etudiant_id' => 'int',
		'libelle' => 'int',
		'annee_id' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'libelle',
		'annee_id'
	];
}
