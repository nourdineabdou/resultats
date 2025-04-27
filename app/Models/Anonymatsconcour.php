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
 * Class Anonymatsconcour
 * 
 * @property int $id
 * @property string $anonymat
 * @property int $candidat_id
 * @property int|null $pacquet
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Candidat $candidat
 * @property Collection|NoteConcour[] $note_concours
 * @property Collection|NoteConcoursFinale[] $note_concours_finales
 *
 * @package App\Models
 */
class Anonymatsconcour extends Model
{
	use SoftDeletes;
	protected $table = 'anonymatsconcours';

	protected $casts = [
		'candidat_id' => 'int',
		'pacquet' => 'int'
	];

	protected $fillable = [
		'anonymat',
		'candidat_id',
		'pacquet'
	];

	public function candidat()
	{
		return $this->belongsTo(Candidat::class);
	}

	public function note_concours()
	{
		return $this->hasMany(NoteConcour::class, 'anonymat_id');
	}

	public function note_concours_finales()
	{
		return $this->hasMany(NoteConcoursFinale::class, 'anonymat_id');
	}
}
