<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoteExamenRt
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $ref_groupe_id
 * @property int $ref_semestre_id
 * @property int $etape_id
 * @property int $matiere_id
 * @property float $note
 * @property string|null $etat
 * @property int $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $etudiant_id
 * @property int|null $anonymat_id
 * 
 * @property Annee $annee
 * @property Matiere $matiere
 * @property Etape $etape
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 * @property RefGroupe $ref_groupe
 * @property Anonymat $anonymat
 * @property Etudiant $etudiant
 *
 * @package App\Models
 */
class NoteExamenRt extends Model
{
	use SoftDeletes;
	protected $table = 'note_examen_rts';

	protected $casts = [
		'profil_id' => 'int',
		'ref_groupe_id' => 'int',
		'ref_semestre_id' => 'int',
		'etape_id' => 'int',
		'matiere_id' => 'int',
		'note' => 'float',
		'annee_id' => 'int',
		'etudiant_id' => 'int',
		'anonymat_id' => 'int'
	];

	protected $fillable = [
		'profil_id',
		'ref_groupe_id',
		'ref_semestre_id',
		'etape_id',
		'matiere_id',
		'note',
		'etat',
		'annee_id',
		'etudiant_id',
		'anonymat_id'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}

	public function etape()
	{
		return $this->belongsTo(Etape::class);
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class);
	}

	public function anonymat()
	{
		return $this->belongsTo(Anonymat::class);
	}

	public function etudiant()
	{
		return $this->belongsTo(Etudiant::class);
	}
}
