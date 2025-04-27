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
 * Class RefGroupe
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|EtudMat[] $etud_mats
 * @property Collection|MatiereSalleEtudiant[] $matiere_salle_etudiants
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|ProfilGroupeAnnee[] $profil_groupe_annees
 * @property Collection|ResultatGlobal[] $resultat_globals
 * @property Collection|VerifCalculeNote[] $verif_calcule_notes
 *
 * @package App\Models
 */
class RefGroupe extends Model
{
	use SoftDeletes;
	protected $table = 'ref_groupes';

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre'
	];

	public function etud_mats()
	{
		return $this->hasMany(EtudMat::class);
	}

	public function matiere_salle_etudiants()
	{
		return $this->hasMany(MatiereSalleEtudiant::class, 'groupe_id');
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

	public function profil_groupe_annees()
	{
		return $this->hasMany(ProfilGroupeAnnee::class, 'groupe_id');
	}

	public function resultat_globals()
	{
		return $this->hasMany(ResultatGlobal::class);
	}

	public function verif_calcule_notes()
	{
		return $this->hasMany(VerifCalculeNote::class, 'groupe_id');
	}
}
