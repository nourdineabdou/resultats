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
 * Class Profil
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
 * @property Departement $departement
 * @property Etape $etape
 * @property RefNiveauEtude $ref_niveau_etude
 * @property RefSemestre $ref_semestre
 * @property Faculte $faculte
 * @property Collection|EtudMat[] $etud_mats
 * @property Collection|EtudSemestre[] $etud_semestres
 * @property Collection|MatiereSalleEtudiant[] $matiere_salle_etudiants
 * @property Collection|Matiere[] $matieres
 * @property Collection|Etape[] $etapes
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|Annee[] $annees
 * @property Collection|RelevesNote[] $releves_notes
 * @property Collection|ResultatGlobal[] $resultat_globals
 * @property Collection|VerifCalculeNote[] $verif_calcule_notes
 *
 * @package App\Models
 */
class Profil extends Model
{
	use SoftDeletes;
	protected $table = 'profils';

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

	public function departement()
	{
		return $this->belongsTo(Departement::class);
	}

	public function etape()
	{
		return $this->belongsTo(Etape::class);
	}

	public function ref_niveau_etude()
	{
		return $this->belongsTo(RefNiveauEtude::class);
	}

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}

	public function faculte()
	{
		return $this->belongsTo(Faculte::class);
	}

	public function etud_mats()
	{
		return $this->hasMany(EtudMat::class);
	}

	public function etud_semestres()
	{
		return $this->hasMany(EtudSemestre::class);
	}

	public function matiere_salle_etudiants()
	{
		return $this->hasMany(MatiereSalleEtudiant::class);
	}

	public function matieres()
	{
		return $this->belongsToMany(Matiere::class, 'matieres_profils_etapes')
					->withPivot('id', 'etape_id', 'ref_semestre_id', 'coef', 'optionnelle', 'deleted_at')
					->withTimestamps();
	}

	public function etapes()
	{
		return $this->belongsToMany(Etape::class, 'matieres_profils_etapes')
					->withPivot('id', 'matiere_id', 'ref_semestre_id', 'coef', 'optionnelle', 'deleted_at')
					->withTimestamps();
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

	public function annees()
	{
		return $this->belongsToMany(Annee::class, 'profil_groupe_annees')
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
