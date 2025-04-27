<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Candidat
 * 
 * @property int $id
 * @property string|null $tel
 * @property string|null $nompl
 * @property Carbon|null $datn
 * @property string|null $lieu
 * @property int|null $ref_genre_id
 * @property int|null $ref_nationnalite_id
 * @property string|null $serie
 * @property string|null $noetec
 * @property string|null $ville
 * @property string|null $inde
 * @property string|null $nni
 * @property int|null $annee_id
 * @property int|null $salle_id
 * @property int $etat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Annee $annee
 * @property RefGenre $ref_genre
 * @property RefNationnalite $ref_nationnalite
 * @property Salle $salle
 * @property Collection|Anonymatsconcour[] $anonymatsconcours
 * @property Collection|NoteConcour[] $note_concours
 * @property Collection|NoteConcoursFinale[] $note_concours_finales
 *
 * @package App\Models
 */
class Candidat extends Model
{
	use SoftDeletes;
	protected $table = 'candidats';

	protected $casts = [
		'ref_genre_id' => 'int',
		'ref_nationnalite_id' => 'int',
		'annee_id' => 'int',
		'salle_id' => 'int',
		'etat' => 'int'
	];

	protected $dates = [
		'datn'
	];

	protected $fillable = [
		'tel',
		'nompl',
		'datn',
		'lieu',
		'ref_genre_id',
		'ref_nationnalite_id',
		'serie',
		'noetec',
		'ville',
		'inde',
		'nni',
		'annee_id',
		'salle_id',
		'etat'
	];

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}

	public function ref_genre()
	{
		return $this->belongsTo(RefGenre::class);
	}

	public function ref_nationnalite()
	{
		return $this->belongsTo(RefNationnalite::class);
	}

	public function salle()
	{
		return $this->belongsTo(Salle::class);
	}

	public function anonymatsconcours()
	{
		return $this->hasMany(Anonymatsconcour::class);
	}

	public function note_concours()
	{
		return $this->hasMany(NoteConcour::class);
	}

	public function note_concours_finales()
	{
		return $this->hasMany(NoteConcoursFinale::class);
	}
}
