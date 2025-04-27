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
 * Class RefModeSaisy
 * 
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Etape[] $etapes
 *
 * @package App\Models
 */
class RefModeSaisy extends Model
{
	use SoftDeletes;
	protected $table = 'ref_mode_saisies';

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];

	public function etapes()
	{
		return $this->hasMany(Etape::class, 'ref_mode_saisie_id');
	}
}
