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
 * Class RefLangue
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $ordre
 * 
 * @property Collection|Matiere[] $matieres
 *
 * @package App\Models
 */
class RefLangue extends Model
{
	use SoftDeletes;
	protected $table = 'ref_langues';

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre'
	];

	public function matieres()
	{
		return $this->hasMany(Matiere::class);
	}
}
