<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MoyennesSortant
 * 
 * @property int $id
 * @property int|null $etudiant_id
 * @property int $profil_id
 * @property int|null $ref_groupe_id
 * @property int|null $niveau
 * @property float|null $note
 * @property int|null $annee_id
 * @property int|null $decision
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Annee $annee
 * @property Profil $profil
 * @property Etudiant $etudiant
 *
 * @package App\Models
 */
class MoyennesSortant extends Model
{
	use SoftDeletes;
	protected $table = 'moyennes_sortants';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'etudiant_id' => 'int',
		'profil_id' => 'int',
		'ref_groupe_id' => 'int',
		'niveau' => 'int',
		'note' => 'float',
		'annee_id' => 'int',
		'decision' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'profil_id',
		'ref_groupe_id',
		'niveau',
		'note',
		'annee_id',
		'decision'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function etudiant()
	{
		return $this->belongsTo(Etudiant::class);
	}
}
