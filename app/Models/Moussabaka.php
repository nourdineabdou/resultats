<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Moussabaka
 * 
 * @property string|null $date
 * @property string|null $nom
 * @property string|null $nni
 * @property string|null $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Moussabaka extends Model
{
	use SoftDeletes;
	protected $table = 'moussabakas';
	public $incrementing = false;

	protected $fillable = [
		'date',
		'nom',
		'nni',
		'id'
	];
}
