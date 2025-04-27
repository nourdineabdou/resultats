<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoteConcour
 * 
 * @property int $id
 * @property int|null $salle_id
 * @property int $pacquet
 * @property int $matieres_concour_id
 * @property float|null $note1
 * @property float|null $note2
 * @property float|null $note3
 * @property float|null $note
 * @property int|null $etat_note3
 * @property int|null $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $candidat_id
 * @property int $anonymat_id
 * @property int|null $pacquet3
 * 
 * @property Annee $annee
 * @property Anonymatsconcour $anonymatsconcour
 * @property Candidat $candidat
 * @property MatieresConcour $matieres_concour
 * @property Salle $salle
 *
 * @package App\Models
 */
class NoteConcour extends Model
{
	use SoftDeletes;
	protected $table = 'note_concours';

	protected $casts = [
		'salle_id' => 'int',
		'pacquet' => 'int',
		'matieres_concour_id' => 'int',
		'note1' => 'float',
		'note2' => 'float',
		'note3' => 'float',
		'note' => 'float',
		'etat_note3' => 'int',
		'annee_id' => 'int',
		'candidat_id' => 'int',
		'anonymat_id' => 'int',
		'pacquet3' => 'int'
	];

	protected $fillable = [
		'salle_id',
		'pacquet',
		'matieres_concour_id',
		'note1',
		'note2',
		'note3',
		'note',
		'etat_note3',
		'annee_id',
		'candidat_id',
		'anonymat_id',
		'pacquet3'
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

	public function matieres_concour()
	{
		return $this->belongsTo(MatieresConcour::class);
	}

	public function salle()
	{
		return $this->belongsTo(Salle::class);
	}
}
