<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Election
 * 
 * @property int $id
 * @property string|null $centre
 * @property int|null $ref_type_election
 * @property int|null $centre_id
 * @property string|null $bureau
 * @property int|null $bureau_id
 * @property string|null $parti
 * @property int|null $inscrits
 * @property int|null $votants
 * @property int|null $cartenuls
 * @property int|null $suffragesexprimés
 * @property int|null $nbvoixobtenues
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $commune
 * @property int|null $commune_id
 * @property string|null $moughataa
 * @property int|null $moughataa_id
 *
 * @package App\Models
 */
class Election extends Model
{
	use SoftDeletes;
	protected $table = 'elections';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'ref_type_election' => 'int',
		'centre_id' => 'int',
		'bureau_id' => 'int',
		'inscrits' => 'int',
		'votants' => 'int',
		'cartenuls' => 'int',
		'suffragesexprimés' => 'int',
		'nbvoixobtenues' => 'int',
		'commune_id' => 'int',
		'moughataa_id' => 'int'
	];

	protected $fillable = [
		'centre',
		'ref_type_election',
		'centre_id',
		'bureau',
		'bureau_id',
		'parti',
		'inscrits',
		'votants',
		'cartenuls',
		'suffragesexprimés',
		'nbvoixobtenues',
		'commune',
		'commune_id',
		'moughataa',
		'moughataa_id'
	];
}
