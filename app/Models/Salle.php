<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Salle
 * 
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property int $capacite
 * @property int $ordre
 * @property int $etat
 * @property int|null $etat1
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Candidat[] $candidats
 * @property Collection|Etudiant[] $etudiants
 * @property Collection|Matiere[] $matieres
 * @property Collection|NoteConcour[] $note_concours
 * @property Collection|NoteConcoursFinale[] $note_concours_finales
 *
 * @package App\Models
 */
class Salle extends Model
{
	use SoftDeletes;
	protected $table = 'salles';

	protected $casts = [
		'capacite' => 'int',
		'ordre' => 'int',
		'etat' => 'int',
		'etat1' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'capacite',
		'ordre',
		'etat',
		'etat1'
	];

	public function candidats()
	{
		return $this->hasMany(Candidat::class);
	}

	public function etudiants()
	{
		return $this->belongsToMany(Etudiant::class, 'matiere_salle_etudiants')
					->withPivot('id', 'matiere_id', 'annee_id', 'deleted_at', 'etud_mat_id', 'profil_id', 'groupe_id')
					->withTimestamps();
	}

	public function matieres()
	{
		return $this->belongsToMany(Matiere::class, 'matiere_salle_etudiants')
					->withPivot('id', 'annee_id', 'deleted_at', 'etudiant_id', 'etud_mat_id', 'profil_id', 'groupe_id')
					->withTimestamps();
	}

	public function note_concours()
	{
		return $this->hasMany(NoteConcour::class);
	}

	public function note_concours_finales()
	{
		return $this->hasMany(NoteConcoursFinale::class);
	}
}
