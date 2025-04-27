<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoteConcoursFinale
 * 
 * @property int $id
 * @property int|null $salle_id
 * @property int $pacquet
 * @property float|null $note
 * @property int|null $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $candidat_id
 * @property int $anonymat_id
 * @property int|null $elimine
 * @property int|null $etat
 * 
 * @property Annee $annee
 * @property Anonymatsconcour $anonymatsconcour
 * @property Candidat $candidat
 * @property Salle $salle
 *
 * @package App\Models
 */
class NoteConcoursFinale extends Model
{
	use SoftDeletes;
	protected $table = 'note_concours_finales';

	protected $casts = [
		'salle_id' => 'int',
		'pacquet' => 'int',
		'note' => 'float',
		'annee_id' => 'int',
		'candidat_id' => 'int',
		'anonymat_id' => 'int',
		'elimine' => 'int',
		'etat' => 'int'
	];

	protected $fillable = [
		'salle_id',
		'pacquet',
		'note',
		'annee_id',
		'candidat_id',
		'anonymat_id',
		'elimine',
		'etat'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function anonymatsconcour()
	{
		return $this->belongsTo(Anonymatsconcour::class, 'anonymat_id');
	}

	public function candidat()
	{
		return $this->belongsTo(Candidat::class);
	}

	public function salle()
	{
		return $this->belongsTo(Salle::class);
	}
}
