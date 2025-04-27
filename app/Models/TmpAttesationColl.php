<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TmpAttesationColl
 * 
 * @property int $id
 * @property int $etudiant_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_ad
 *
 * @package App\Models
 */
class TmpAttesationColl extends Model
{
	protected $table = 'tmp_attesation_colls';

	protected $casts = [
		'etudiant_id' => 'int'
	];

	protected $dates = [
		'deleted_ad'
	];

	protected $fillable = [
		'etudiant_id',
		'deleted_ad'
	];
}
