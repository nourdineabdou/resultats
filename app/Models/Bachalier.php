<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bachalier
 * 
 * @property int $id
 * @property int|null $imported_id
 * @property string|null $nobac
 * @property string|null $nni
 * @property string|null $nompl
 * @property Carbon|null $datn
 * @property string|null $lieu
 * @property string|null $nat
 * @property string|null $annee
 *
 * @package App\Models
 */
class Bachalier extends Model
{
	protected $table = 'bachalier';
	public $timestamps = false;

	protected $casts = [
		'imported_id' => 'int'
	];

	protected $dates = [
		'datn'=>'date::d-m-Y',
	];

	protected $fillable = [
		'imported_id',
		'nobac',
		'nni',
		'nompl',
		'datn',
		'lieu',
		'nat',
		'annee'
	];
}
