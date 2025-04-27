<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ResultatGlobal
 * 
 * @property int $id
 * @property int $profil_id
 * @property int $ref_groupe_id
 * @property int $ref_semestre_id
 * @property int $etape_id
 * @property int $matiere_id
 * @property float $note_cc
 * @property float $note_final
 * @property float $note_rt
 * @property float $note_total
 * @property int $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Annee $annee
 * @property Etape $etape
 * @property Matiere $matiere
 * @property Profil $profil
 * @property RefGroupe $ref_groupe
 *
 * @package App\Models
 */
class ResultatGlobal extends Model
{
	use SoftDeletes;
	protected $table = 'resultat_globals';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'profil_id' => 'int',
		'ref_groupe_id' => 'int',
		'ref_semestre_id' => 'int',
		'etape_id' => 'int',
		'matiere_id' => 'int',
		'note_cc' => 'float',
		'note_final' => 'float',
		'note_rt' => 'float',
		'note_total' => 'float',
		'annee_id' => 'int'
	];

	protected $fillable = [
		'profil_id',
		'ref_groupe_id',
		'ref_semestre_id',
		'etape_id',
		'matiere_id',
		'note_cc',
		'note_final',
		'note_rt',
		'note_total',
		'annee_id'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function etape()
	{
		return $this->belongsTo(Etape::class);
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class);
	}
}
