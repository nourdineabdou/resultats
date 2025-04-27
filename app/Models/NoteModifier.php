<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoteModifier
 *
 * @property int $id
 * @property int $etudiant_id
 * @property int $profil_id
 * @property int $user_id
 * @property int $ref_groupe_id
 * @property int $ref_semestre_id
 * @property int $etape_id
 * @property int $matiere_id
 * @property float $oldnote
 * @property float $newnote
 * @property int $annee_id
 * @property string|null $etat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Annee $annee
 * @property Etape $etape
 * @property Matiere $matiere
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 * @property RefGroupe $ref_groupe
 * @property Etudiant $etudiant
 * @property User $user
 *
 * @package App\Models
 */
class NoteModifier extends Model
{
	use SoftDeletes;
	protected $table = 'note_modifier';

	protected $casts = [
		'etudiant_id' => 'int',
		'profil_id' => 'int',
		'user_id' => 'int',
		'ref_groupe_id' => 'int',
		'ref_semestre_id' => 'int',
		'etape_id' => 'int',
		'matiere_id' => 'int',
		'oldnote' => 'float',
		'newnote' => 'float',
		'annee_id' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'profil_id',
		'user_id',
		'ref_groupe_id',
		'ref_semestre_id',
		'etape_id',
		'matiere_id',
		'oldnote',
		'newnote',
		'annee_id',
        'machine',
		'etat'
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

	public function ref_semestre()
	{
		return $this->belongsTo(RefSemestre::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class);
	}

	public function etudiant()
	{
		return $this->belongsTo(Etudiant::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
