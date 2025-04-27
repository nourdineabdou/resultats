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
 * Class RefTypeControle
 * 
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property int|null $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Etape[] $etapes
 *
 * @package App\Models
 */
class RefTypeControle extends Model
{
	use SoftDeletes;
	protected $table = 'ref_type_controles';

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre'
	];

	public function etapes()
	{
		return $this->hasMany(Etape::class);
	}
}
