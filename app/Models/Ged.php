<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ged
 * 
 * @property int $id
 * @property string|null $libelle
 * @property string $emplacement
 * @property int $objet_id
 * @property int $type
 * @property string $extension
 * @property int $ref_types_document_id
 * @property string|null $commentaire
 * @property int|null $taille
 * @property int|null $type_ged
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property RefTypesDocument $ref_types_document
 *
 * @package App\Models
 */
class Ged extends Model
{
	use SoftDeletes;
	protected $table = 'ged';

	protected $casts = [
		'objet_id' => 'int',
		'type' => 'int',
		'ref_types_document_id' => 'int',
		'taille' => 'int',
		'type_ged' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'emplacement',
		'objet_id',
		'type',
		'extension',
		'ref_types_document_id',
		'commentaire',
		'taille',
		'type_ged',
		'ordre'
	];

	public function ref_types_document()
	{
		return $this->belongsTo(RefTypesDocument::class);
	}
}
