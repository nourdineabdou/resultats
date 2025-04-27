<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProfilGroupeAnnee
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $groupe_id
 * @property int $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Annee $annee
 * @property RefGroupe $ref_groupe
 * @property Profil $profil
 *
 * @package App\Models
 */
class ProfilGroupeAnnee extends Model
{
	use SoftDeletes;
	protected $table = 'profil_groupe_annees';

	protected $casts = [
		'profil_id' => 'int',
		'groupe_id' => 'int',
		'annee_id' => 'int'
	];

	protected $fillable = [
		'profil_id',
		'groupe_id',
		'annee_id'
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
}
