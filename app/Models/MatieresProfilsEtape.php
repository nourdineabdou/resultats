<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MatieresProfilsEtape
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $etape_id
 * @property int $matiere_id
 * @property int $ref_semestre_id
 * @property float|null $coef
 * @property int|null $optionnelle
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Etape $etape
 * @property Matiere $matiere
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 *
 * @package App\Models
 */
class MatieresProfilsEtape extends Model
{
	use SoftDeletes;
	protected $table = 'matieres_profils_etapes';

	protected $casts = [
		'profil_id' => 'int',
		'etape_id' => 'int',
		'matiere_id' => 'int',
		'ref_semestre_id' => 'int',
		'coef' => 'float',
		'optionnelle' => 'int'
	];

	protected $fillable = [
		'profil_id',
		'etape_id',
		'matiere_id',
		'ref_semestre_id',
		'coef',
		'optionnelle'
	];

	public function etape()
	{
		return $this->belongsTo(Etape::class);
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
}
