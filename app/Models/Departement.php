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
 * Class Departement
 * 
 * @property int $id
 * @property int $faculte_id
 * @property string $code
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Faculte $faculte
 * @property Collection|Profil[] $profils
 *
 * @package App\Models
 */
class Departement extends Model
{
	use SoftDeletes;
	protected $table = 'departements';

	protected $casts = [
		'faculte_id' => 'int'
	];

	protected $fillable = [
		'faculte_id',
		'code',
		'libelle',
		'libelle_ar'
	];

	public function faculte()
	{
		return $this->belongsTo(Faculte::class);
	}

	public function profils()
	{
		return $this->hasMany(Profil::class);
	}
}
