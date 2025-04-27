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
 * Class Annee
 * 
 * @property int $id
 * @property string $annee
 * @property string|null $code
 * @property int|null $numero
 * @property int|null $numeroMst
 * @property int|null $etat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Candidat[] $candidats
 * @property Collection|EtudMat[] $etud_mats
 * @property Collection|MatiereSalleEtudiant[] $matiere_salle_etudiants
 * @property Collection|MoyennesSemestre[] $moyennes_semestres
 * @property Collection|MoyennesSortant[] $moyennes_sortants
 * @property Collection|NoteConcour[] $note_concours
 * @property Collection|NoteConcoursFinale[] $note_concours_finales
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|NoteModifier[] $note_modifiers
 * @property Collection|Profil[] $profils
 * @property Collection|RelevesNote[] $releves_notes
 * @property Collection|ResultatGlobal[] $resultat_globals
 * @property Collection|VerifCalculeNote[] $verif_calcule_notes
 *
 * @package App\Models
 */
class Annee extends Model
{
	use SoftDeletes;
	protected $table = 'annees';

	protected $casts = [
		'numero' => 'int',
		'numeroMst' => 'int',
		'etat' => 'int'
	];

	protected $fillable = [
		'annee',
		'code',
		'numero',
		'numeroMst',
		'etat'
	];

	public function candidats()
	{
		return $this->hasMany(Candidat::class);
	}

	public function etud_mats()
	{
		return $this->hasMany(EtudMat::class);
	}

	public function matiere_salle_etudiants()
	{
		return $this->hasMany(MatiereSalleEtudiant::class);
	}

	public function moyennes_semestres()
	{
		return $this->hasMany(MoyennesSemestre::class);
	}

	public function moyennes_sortants()
	{
		return $this->hasMany(MoyennesSortant::class);
	}

	public function note_concours()
	{
		return $this->hasMany(NoteConcour::class);
	}

	public function note_concours_finales()
	{
		return $this->hasMany(NoteConcoursFinale::class);
	}

	public function note_devoirs()
	{
		return $this->hasMany(NoteDevoir::class);
	}

	public function note_examen_finales()
	{
		return $this->hasMany(NoteExamenFinale::class);
	}

	public function note_examen_rts()
	{
		return $this->hasMany(NoteExamenRt::class);
	}

	public function note_modifiers()
	{
		return $this->hasMany(NoteModifier::class);
	}

	public function profils()
	{
		return $this->belongsToMany(Profil::class, 'profil_groupe_annees')
					->withPivot('id', 'groupe_id', 'deleted_at')
					->withTimestamps();
	}

	public function releves_notes()
	{
		return $this->hasMany(RelevesNote::class);
	}

	public function resultat_globals()
	{
		return $this->hasMany(ResultatGlobal::class);
	}

	public function verif_calcule_notes()
	{
		return $this->hasMany(VerifCalculeNote::class);
	}
}
