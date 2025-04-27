<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MatiereSalleEtudiant
 * 
 * @property int $id
 * @property int|null $salle_id
 * @property int $matiere_id
 * @property int|null $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $etudiant_id
 * @property int|null $etud_mat_id
 * @property int|null $profil_id
 * @property int|null $groupe_id
 * 
 * @property Annee $annee
 * @property Etudiant $etudiant
 * @property Matiere $matiere
 * @property Salle $salle
 * @property Profil $profil
 * @property RefGroupe $ref_groupe
 *
 * @package App\Models
 */
class MatiereSalleEtudiant extends Model
{
	use SoftDeletes;
	protected $table = 'matiere_salle_etudiants';

	protected $casts = [
		'salle_id' => 'int',
		'matiere_id' => 'int',
		'annee_id' => 'int',
		'etudiant_id' => 'int',
		'etud_mat_id' => 'int',
		'profil_id' => 'int',
		'groupe_id' => 'int'
	];

	protected $fillable = [
		'salle_id',
		'matiere_id',
		'annee_id',
		'etudiant_id',
		'etud_mat_id',
		'profil_id',
		'groupe_id'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function etudiant()
	{
		return $this->belongsTo(Etudiant::class);
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}

	public function salle()
	{
		return $this->belongsTo(Salle::class);
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class, 'groupe_id');
	}
}
