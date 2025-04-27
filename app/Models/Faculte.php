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
 * Class Faculte
 * 
 * @property int $id
 * @property string $code
 * @property string|null $libelle
 * @property string $libelle_ar
 * @property string|null $libelle_court
 * @property string|null $libelle_court_ar
 * @property string|null $nom_resp
 * @property string|null $nom_resp_ar
 * @property int|null $etat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Departement[] $departements
 * @property Collection|Etape[] $etapes
 * @property Collection|Profil[] $profils
 *
 * @package App\Models
 */
class Faculte extends Model
{
	use SoftDeletes;
	protected $table = 'facultes';

	protected $casts = [
		'etat' => 'int'
	];

	protected $fillable = [
		'code',
		'libelle',
		'libelle_ar',
		'libelle_court',
		'libelle_court_ar',
		'nom_resp',
		'nom_resp_ar',
		'etat'
	];

	public function departements()
	{
		return $this->hasMany(Departement::class);
	}

	public function etapes()
	{
		return $this->hasMany(Etape::class);
	}

	public function profils()
	{
		return $this->hasMany(Profil::class);
	}
}
