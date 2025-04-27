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
 * Class Modulle
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $faculte_id
 * @property int $ref_semestre_id
 * @property string $code
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property int|null $nbre
 * @property float|null $coaf
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property RefSemestre $ref_semestre
 * @property Collection|Matiere[] $matieres
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|RelevesNote[] $releves_notes
 *
 * @package App\Models
 */
class Modulle extends Model
{
	use SoftDeletes;
	protected $table = 'modulles';

	protected $casts = [
		'profil_id' => 'int',
		'faculte_id' => 'int',
		'ref_semestre_id' => 'int',
		'nbre' => 'int',
		'coaf' => 'float'
	];

	protected $fillable = [
		'profil_id',
		'faculte_id',
		'ref_semestre_id',
		'code',
		'libelle',
		'libelle_ar',
		'nbre',
		'coaf'
	];

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}

	public function matieres()
	{
		return $this->hasMany(Matiere::class);
	}

	public function note_examen_finales()
	{
		return $this->hasMany(NoteExamenFinale::class);
	}

	public function releves_notes()
	{
		return $this->hasMany(RelevesNote::class);
	}
}
