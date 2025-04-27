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
 * Class RefSemestre
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|EtudMat[] $etud_mats
 * @property Collection|EtudSemestre[] $etud_semestres
 * @property Collection|Matiere[] $matieres
 * @property Collection|MatieresProfilsEtape[] $matieres_profils_etapes
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|Profil[] $profils
 * @property Collection|RelevesNote[] $releves_notes
 * @property Collection|VerifCalculeNote[] $verif_calcule_notes
 *
 * @package App\Models
 */
class RefSemestre extends Model
{
	use SoftDeletes;
	protected $table = 'ref_semestres';

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];

	public function etud_mats()
	{
		return $this->hasMany(EtudMat::class);
	}

	public function etud_semestres()
	{
		return $this->hasMany(EtudSemestre::class);
	}

	public function matieres()
	{
		return $this->hasMany(Matiere::class);
	}

	public function matieres_profils_etapes()
	{
		return $this->hasMany(MatieresProfilsEtape::class);
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

	public function profils()
	{
		return $this->hasMany(Profil::class);
	}

	public function releves_notes()
	{
		return $this->hasMany(RelevesNote::class);
	}

	public function verif_calcule_notes()
	{
		return $this->hasMany(VerifCalculeNote::class);
	}
}
