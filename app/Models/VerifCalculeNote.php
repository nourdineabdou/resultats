<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VerifCalculeNote
 * 
 * @property int $id
 * @property int $ref_semestre_id
 * @property int $annee_id
 * @property int $profil_id
 * @property int $groupe_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Annee $annee
 * @property RefGroupe $ref_groupe
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 *
 * @package App\Models
 */
class VerifCalculeNote extends Model
{
	use SoftDeletes;
	protected $table = 'verif_calcule_notes';

	protected $casts = [
		'ref_semestre_id' => 'int',
		'annee_id' => 'int',
		'profil_id' => 'int',
		'groupe_id' => 'int'
	];

	protected $fillable = [
		'ref_semestre_id',
		'annee_id',
		'profil_id',
		'groupe_id'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class, 'groupe_id');
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}
}
