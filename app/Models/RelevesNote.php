<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RelevesNote
 * 
 * @property int $id
 * @property int $etudiant_id
 * @property int|null $anonymat_id
 * @property int $profil_id
 * @property int $ref_groupe_id
 * @property int $ref_semestre_id
 * @property int|null $etape_id
 * @property int $matiere_id
 * @property float $note_dev
 * @property float $note_exam
 * @property float|null $note_rt
 * @property int $modulle_id
 * @property float $note
 * @property float $noteModule
 * @property int $annee_id
 * @property int|null $decision
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Annee $annee
 * @property Etudiant $etudiant
 * @property Matiere $matiere
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 * @property Modulle $modulle
 *
 * @package App\Models
 */
class RelevesNote extends Model
{
	use SoftDeletes;
	protected $table = 'releves_notes';

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
		'modulle_id' => 'int',
		'note' => 'float',
		'noteModule' => 'float',
		'annee_id' => 'int',
		'decision' => 'int'
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
		'modulle_id',
		'note',
		'noteModule',
		'annee_id',
		'decision'
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

	public function profil()
	{
		return $this->belongsTo(Profil::class);
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
