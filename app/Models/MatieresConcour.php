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
 * Class MatieresConcour
 * 
 * @property int $id
 * @property string|null $libelle
 * @property string|null $libelle_ar
 * @property string|null $libelle_court
 * @property string|null $libelle_court_ar
 * @property string $code
 * @property float $note
 * @property float $coaf
 * @property float $credit
 * @property Carbon|null $date
 * @property int|null $existe
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|NoteConcour[] $note_concours
 *
 * @package App\Models
 */
class MatieresConcour extends Model
{
	use SoftDeletes;
	protected $table = 'matieres_concours';

	protected $casts = [
		'note' => 'float',
		'coaf' => 'float',
		'credit' => 'float',
		'existe' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'libelle_court',
		'libelle_court_ar',
		'code',
		'note',
		'coaf',
		'credit',
		'date',
		'existe'
	];

	public function note_concours()
	{
		return $this->hasMany(NoteConcour::class);
	}
}
