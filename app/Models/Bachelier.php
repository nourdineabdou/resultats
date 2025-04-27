<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bachelier
 * 
 * @property int $id
 * @property string|null $nobac
 * @property string|null $nodoss
 * @property string|null $nompl
 * @property string|null $nompa
 * @property Carbon|null $datn
 * @property string|null $lieu
 * @property string|null $lieuna
 * @property string|null $sexe
 * @property string|null $nat
 * @property string|null $serie
 * @property string|null $noetec
 * @property string|null $ville
 * @property string|null $inde
 * @property float|null $moyg1
 * @property float|null $moyg2
 * @property string|null $session
 * @property string|null $noreg
 * @property string|null $noprfl
 * @property string|null $nni
 * @property string|null $annee
 * @property int $etat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Bachelier extends Model
{
	use SoftDeletes;
	protected $table = 'bacheliers';

	protected $casts = [
		'moyg1' => 'float',
		'moyg2' => 'float',
		'etat' => 'int'
	];

	protected $dates = [
		'datn'=>'date::d-m-Y',
	];

	protected $fillable = [
		'nobac',
		'nodoss',
		'nompl',
		'nompa',
		'datn',
		'lieu',
		'lieuna',
		'sexe',
		'nat',
		'serie',
		'noetec',
		'ville',
		'inde',
		'moyg1',
		'moyg2',
		'session',
		'noreg',
		'noprfl',
		'nni',
		'annee',
		'etat'
	];
}
