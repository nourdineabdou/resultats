<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EtudSemestre
 * 
 * @property int $id
 * @property int $etudiant_id
 * @property string|null $NODOS
 * @property string|null $NOMF
 * @property string|null $NOMA
 * @property int $profil_id
 * @property int $ref_semestre_id
 * @property string|null $NOANO
 * @property string|null $RNOANO
 * @property string|null $INDR
 * @property string|null $RINDR
 * @property string|null $NOHSALLE
 * @property float|null $NOPLACE
 * @property string|null $SALLE
 * @property string|null $Hr
 * @property bool|null $Abs
 * @property string|null $Lg
 * @property int $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Etudiant $etudiant
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 *
 * @package App\Models
 */
class EtudSemestre extends Model
{
	use SoftDeletes;
	protected $table = 'etud_semestres';

	protected $casts = [
		'etudiant_id' => 'int',
		'profil_id' => 'int',
		'ref_semestre_id' => 'int',
		'NOPLACE' => 'float',
		'Abs' => 'bool',
		'annee_id' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'NODOS',
		'NOMF',
		'NOMA',
		'profil_id',
		'ref_semestre_id',
		'NOANO',
		'RNOANO',
		'INDR',
		'RINDR',
		'NOHSALLE',
		'NOPLACE',
		'SALLE',
		'Hr',
		'Abs',
		'Lg',
		'annee_id'
	];

	public function etudiant()
	{
		return $this->belongsTo(Etudiant::class);
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
