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
 * Class Matiere
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $modulle_id
 * @property int $ref_semestre_id
 * @property int $ref_langue_id
 * @property string|null $libelle
 * @property string|null $libelle_ar
 * @property string|null $libelle_court
 * @property string|null $libelle_court_ar
 * @property string $code
 * @property float $coaf
 * @property float $credit
 * @property Carbon|null $date
 * @property int|null $existe
 * @property int|null $tp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Profil $profil
 * @property RefLangue $ref_langue
 * @property Modulle $modulle
 * @property RefSemestre $ref_semestre
 * @property Collection|EtudMat[] $etud_mats
 * @property Collection|Etudiant[] $etudiants
 * @property Collection|Salle[] $salles
 * @property Collection|Etape[] $etapes
 * @property Collection|Profil[] $profils
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|RelevesNote[] $releves_notes
 * @property Collection|ResultatGlobal[] $resultat_globals
 * @property Collection|TempMatiere[] $temp_matieres
 *
 * @package App\Models
 */
class Matiere extends Model
{
	use SoftDeletes;
	protected $table = 'matieres';

	protected $casts = [
		'profil_id' => 'int',
		'modulle_id' => 'int',
		'ref_semestre_id' => 'int',
		'ref_langue_id' => 'int',
		'coaf' => 'float',
		'credit' => 'float',
		'existe' => 'int',
		'tp' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'profil_id',
		'modulle_id',
		'ref_semestre_id',
		'ref_langue_id',
		'libelle',
		'libelle_ar',
		'libelle_court',
		'libelle_court_ar',
		'code',
		'coaf',
		'credit',
		'date',
		'existe',
		'tp'
	];

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function ref_langue()
	{
		return $this->belongsTo(RefLangue::class);
	}

	public function modulle()
	{
		return $this->belongsTo(Modulle::class);
	}

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}

	public function etud_mats()
	{
		return $this->hasMany(EtudMat::class);
	}

	public function etudiants()
	{
		return $this->belongsToMany(Etudiant::class, 'matiere_salle_etudiants')
					->withPivot('id', 'salle_id', 'annee_id', 'deleted_at', 'etud_mat_id', 'profil_id', 'groupe_id')
					->withTimestamps();
	}

	public function salles()
	{
		return $this->belongsToMany(Salle::class, 'matiere_salle_etudiants')
					->withPivot('id', 'annee_id', 'deleted_at', 'etudiant_id', 'etud_mat_id', 'profil_id', 'groupe_id')
					->withTimestamps();
	}

	public function etapes()
	{
		return $this->belongsToMany(Etape::class, 'matieres_profils_etapes')
					->withPivot('id', 'profil_id', 'ref_semestre_id', 'coef', 'optionnelle', 'deleted_at')
					->withTimestamps();
	}

	public function profils()
	{
		return $this->belongsToMany(Profil::class, 'matieres_profils_etapes')
					->withPivot('id', 'etape_id', 'ref_semestre_id', 'coef', 'optionnelle', 'deleted_at')
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

	public function releves_notes()
	{
		return $this->hasMany(RelevesNote::class);
	}

	public function resultat_globals()
	{
		return $this->hasMany(ResultatGlobal::class);
	}

	public function temp_matieres()
	{
		return $this->hasMany(TempMatiere::class, 'id_matiere');
	}
}
