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
 * Class Etape
 * 
 * @property int $id
 * @property int $faculte_id
 * @property int $ref_diplome_id
 * @property int $ref_mode_saisie_id
 * @property int|null $ref_type_controle_id
 * @property string $code
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property float|null $coaf
 * @property int $note
 * @property int|null $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property RefDiplome $ref_diplome
 * @property RefModeSaisy $ref_mode_saisy
 * @property Faculte $faculte
 * @property RefTypeControle $ref_type_controle
 * @property Collection|Matiere[] $matieres
 * @property Collection|Profil[] $profils
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|ResultatGlobal[] $resultat_globals
 *
 * @package App\Models
 */
class Etape extends Model
{
	use SoftDeletes;
	protected $table = 'etapes';

	protected $casts = [
		'faculte_id' => 'int',
		'ref_diplome_id' => 'int',
		'ref_mode_saisie_id' => 'int',
		'ref_type_controle_id' => 'int',
		'coaf' => 'float',
		'note' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'faculte_id',
		'ref_diplome_id',
		'ref_mode_saisie_id',
		'ref_type_controle_id',
		'code',
		'libelle',
		'libelle_ar',
		'coaf',
		'note',
		'ordre'
	];

	public function ref_diplome()
	{
		return $this->belongsTo(RefDiplome::class);
	}

	public function ref_mode_saisy()
	{
		return $this->belongsTo(RefModeSaisy::class, 'ref_mode_saisie_id');
	}

	public function faculte()
	{
		return $this->belongsTo(Faculte::class);
	}

	public function ref_type_controle()
	{
		return $this->belongsTo(RefTypeControle::class);
	}

	public function matieres()
	{
		return $this->belongsToMany(Matiere::class, 'matieres_profils_etapes')
					->withPivot('id', 'profil_id', 'ref_semestre_id', 'coef', 'optionnelle', 'deleted_at')
					->withTimestamps();
	}

	public function profils()
	{
		return $this->hasMany(Profil::class);
	}

	public function note_devoirs()
	{
		return $this->hasMany(NoteDevoir::class);
	}

	public function note_examen_rts()
	{
		return $this->hasMany(NoteExamenRt::class);
	}

	public function resultat_globals()
	{
		return $this->hasMany(ResultatGlobal::class);
	}
}
