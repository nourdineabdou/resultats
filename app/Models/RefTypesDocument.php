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
 * Class RefTypesDocument
 * 
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Ged[] $geds
 *
 * @package App\Models
 */
class RefTypesDocument extends Model
{
	use SoftDeletes;
	protected $table = 'ref_types_documents';

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre'
	];

	public function geds()
	{
		return $this->hasMany(Ged::class);
	}
}
