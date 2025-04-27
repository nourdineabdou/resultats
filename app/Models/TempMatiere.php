<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TempMatiere
 * 
 * @property int $id
 * @property int $id_matiere
 * @property string $libelle
 * @property string|null $code
 * @property float $credit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Matiere $matiere
 *
 * @package App\Models
 */
class TempMatiere extends Model
{
	use SoftDeletes;
	protected $table = 'temp_matieres';

	protected $casts = [
		'id_matiere' => 'int',
		'credit' => 'float'
	];

	protected $fillable = [
		'id_matiere',
		'libelle',
		'code',
		'credit'
	];

	public function matiere()
	{
		return $this->belongsTo(Matiere::class, 'id_matiere');
	}
}
