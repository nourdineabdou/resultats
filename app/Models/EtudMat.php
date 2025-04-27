<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EtudMat
 * 
 * @property int $id
 * @property int $etudiant_id
 * @property int|null $ref_groupe_id
 * @property string|null $NODOS
 * @property int $profil_id
 * @property string|null $Code
 * @property string|null $NOMAT
 * @property int $ref_semestre_id
 * @property bool|null $AB
 * @property float|null $CRD
 * @property float|null $credit
 * @property float|null $Nfe
 * @property int $matiere_id
 * @property int|null $annee_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Etudiant $etudiant
 * @property Profil $profil
 * @property RefSemestre $ref_semestre
 * @property Matiere $matiere
 * @property RefGroupe $ref_groupe
 * @property Annee $annee
 *
 * @package App\Models
 */
class EtudMat extends Model
{
	use SoftDeletes;
	protected $table = 'etud_mats';

	protected $casts = [
		'etudiant_id' => 'int',
		'ref_groupe_id' => 'int',
		'profil_id' => 'int',
		'ref_semestre_id' => 'int',
		'AB' => 'bool',
		'CRD' => 'float',
		'credit' => 'float',
		'Nfe' => 'float',
		'matiere_id' => 'int',
		'annee_id' => 'int'
	];

	protected $fillable = [
		'etudiant_id',
		'ref_groupe_id',
		'NODOS',
		'profil_id',
		'Code',
		'NOMAT',
		'ref_semestre_id',
		'AB',
		'CRD',
		'credit',
		'Nfe',
		'matiere_id',
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

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}

	public function ref_groupe()
	{
		return $this->belongsTo(RefGroupe::class);
	}

	public function annee()
	{
		return $this->belongsTo(Annee::class);
	}
}
