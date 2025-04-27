<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoteExamenFinale
 * 
 * @property int $id
 * @property int $etudiant_id
 * @property int|null $anonymat_id
 * @property int $profil_id
 * @property int $ref_groupe_id
 * @property int $ref_semestre_id
 * @property int|null $etape_id
 * @property int $matiere_id
 * @property float|null $note_dev
 * @property float|null $note_exam
 * @property float|null $note_rt
 * @property float $note
 * @property int $annee_id
 * @property int $modulle_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Etudiant $etudiant
 * @property Matiere $matiere
 * @property Profil $profil
 * @property Annee $annee
 * @property RefGroupe $ref_groupe
 * @property RefSemestre $ref_semestre
 * @property Modulle $modulle
 *
 * @package App\Models
 */
class NoteExamenFinale extends Model
{
	use SoftDeletes;
	protected $table = 'note_examen_finales';

	protected $casts = [
		'etudiant_id' => 'int',
		'anonymat_id' => 'int',
		'profil_id' => 'int',
		'ref_groupe_id' => 'int',
		'ref_semestre_id' => 'int',
		'etape_id' => 'int',
		'matiere_id' => 'int',
		'note_dev' => 'float',
		'note_exam' => 'float',
		'note_rt' => 'float',
		'note' => 'float',
		'annee_id' => 'int',
		'modulle_id' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'anonymat_id',
		'profil_id',
		'ref_groupe_id',
		'ref_semestre_id',
		'etape_id',
		'matiere_id',
		'note_dev',
		'note_exam',
		'note_rt',
		'note',
		'annee_id',
		'modulle_id'
	];

	public function etudiant()
	{
		return $this->belongsTo(Etudiant::class);
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class);
	}

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}

	public function modulle()
	{
		return $this->belongsTo(Modulle::class);
	}
}
