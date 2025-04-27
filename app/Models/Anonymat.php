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
 * Class Anonymat
 * 
 * @property int $id
 * @property string $anonymat
 * @property int $profil_id
 * @property int $etudiant_id
 * @property string $nodos
 * @property int $etape_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenRt[] $note_examen_rts
 *
 * @package App\Models
 */
class Anonymat extends Model
{
	use SoftDeletes;
	protected $table = 'anonymats';

	protected $casts = [
		'profil_id' => 'int',
		'etudiant_id' => 'int',
		'etape_id' => 'int'
	];

	protected $fillable = [
		'anonymat',
		'profil_id',
		'etudiant_id',
		'nodos',
		'etape_id'
	];

	public function note_devoirs()
	{
		return $this->hasMany(NoteDevoir::class);
	}

	public function note_examen_rts()
	{
		return $this->hasMany(NoteExamenRt::class);
	}
}
