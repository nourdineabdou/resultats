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
 * Class Etudiant
 * 
 * @property int $id
 * @property string $NODOS
 * @property string|null $NOMF
 * @property string|null $NOMA
 * @property string|null $AD1
 * @property string|null $AD2
 * @property string|null $AD3
 * @property string|null $SITF
 * @property string|null $DATN
 * @property string|null $LIEUNA
 * @property string|null $LIEUNF
 * @property int $ref_nationnalite_id
 * @property string|null $DIPL
 * @property string|null $DDIPL
 * @property string|null $BVP
 * @property string|null $DVP
 * @property string|null $BAC
 * @property string|null $TEL
 * @property string|null $NOBAC
 * @property string|null $DBAC
 * @property float|null $MOYB
 * @property string|null $DATEU
 * @property bool|null $ACTIF
 * @property string|null $DERRONO
 * @property string|null $NOGPE
 * @property int $profil_id
 * @property string|null $LGUE
 * @property string|null $SEXE
 * @property string|null $SITH
 * @property string|null $SITB
 * @property string|null $NODECB
 * @property string|null $REGETUD
 * @property string|null $NOANO
 * @property string|null $RNOANO
 * @property string|null $INDR
 * @property string|null $RINDR
 * @property string|null $SINDR
 * @property Carbon|null $DATIX
 * @property string|null $NOHSALLE
 * @property float|null $NOPLACE
 * @property float|null $MCC
 * @property float|null $ME1
 * @property float|null $MAD1
 * @property float|null $MO1
 * @property float|null $MF1
 * @property float|null $ME2
 * @property float|null $MAD2
 * @property float|null $MO2
 * @property float|null $MF2
 * @property float|null $MMEM
 * @property float|null $MANN
 * @property string|null $DECF
 * @property string|null $DECF_1
 * @property Carbon|null $DATCRE
 * @property Carbon|null $DATMAJ
 * @property bool|null $INDINSB
 * @property string|null $NORECB
 * @property string|null $INDPB
 * @property int|null $faculte_id
 * @property string|null $nodep
 * @property string|null $Annee
 * @property bool|null $indanpv
 * @property string|null $Ment
 * @property string|null $mentA
 * @property string|null $Session
 * @property string|null $SessionA
 * @property bool|null $Transferer
 * @property string|null $Option
 * @property int|null $S1
 * @property int|null $S2
 * @property int|null $CRD
 * @property string|null $SALLE
 * @property string|null $Hr
 * @property bool|null $Abs
 * @property string|null $LG
 * @property string|null $PAYS
 * @property string|null $NNI
 * @property string|null $photo
 * @property string|null $groupe
 * @property string|null $email
 * @property string|null $adress
 * @property string|null $whatsapp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|EtudMat[] $etud_mats
 * @property Collection|EtudSemestre[] $etud_semestres
 * @property Collection|Matiere[] $matieres
 * @property Collection|Salle[] $salles
 * @property Collection|NoteDevoir[] $note_devoirs
 * @property Collection|NoteExamenFinale[] $note_examen_finales
 * @property Collection|NoteExamenRt[] $note_examen_rts
 * @property Collection|RelevesNote[] $releves_notes
 *
 * @package App\Models
 */
class Etudiant extends Model
{
	use SoftDeletes;
	protected $table = 'etudiants';

	protected $casts = [
		'ref_nationnalite_id' => 'int',
		'MOYB' => 'float',
		'ACTIF' => 'bool',
		'profil_id' => 'int',
		'NOPLACE' => 'float',
		'MCC' => 'float',
		'ME1' => 'float',
		'MAD1' => 'float',
		'MO1' => 'float',
		'MF1' => 'float',
		'ME2' => 'float',
		'MAD2' => 'float',
		'MO2' => 'float',
		'MF2' => 'float',
		'MMEM' => 'float',
		'MANN' => 'float',
		'INDINSB' => 'bool',
		'faculte_id' => 'int',
		'indanpv' => 'bool',
		'Transferer' => 'bool',
		'S1' => 'int',
		'S2' => 'int',
		'CRD' => 'int',
		'Abs' => 'bool'
	];

	protected $dates = [
		'DATIX',
		'DATCRE',
		'DATMAJ'
	];

	protected $fillable = [
		'NODOS',
		'NOMF',
		'NOMA',
		'AD1',
		'AD2',
		'AD3',
		'SITF',
		'DATN',
		'LIEUNA',
		'LIEUNF',
		'ref_nationnalite_id',
		'DIPL',
		'DDIPL',
		'BVP',
		'DVP',
		'BAC',
		'TEL',
		'NOBAC',
		'DBAC',
		'MOYB',
		'DATEU',
		'ACTIF',
		'DERRONO',
		'NOGPE',
		'profil_id',
		'LGUE',
		'SEXE',
		'SITH',
		'SITB',
		'NODECB',
		'REGETUD',
		'NOANO',
		'RNOANO',
		'INDR',
		'RINDR',
		'SINDR',
		'DATIX',
		'NOHSALLE',
		'NOPLACE',
		'MCC',
		'ME1',
		'MAD1',
		'MO1',
		'MF1',
		'ME2',
		'MAD2',
		'MO2',
		'MF2',
		'MMEM',
		'MANN',
		'DECF',
		'DECF_1',
		'DATCRE',
		'DATMAJ',
		'INDINSB',
		'NORECB',
		'INDPB',
		'faculte_id',
		'nodep',
		'Annee',
		'indanpv',
		'Ment',
		'mentA',
		'Session',
		'SessionA',
		'Transferer',
		'Option',
		'S1',
		'S2',
		'CRD',
		'SALLE',
		'Hr',
		'Abs',
		'LG',
		'PAYS',
		'NNI',
		'photo',
		'groupe',
		'email',
		'adress',
		'whatsapp'
	];

	public function etud_mats()
	{
		return $this->hasMany(EtudMat::class);
	}

	public function etud_semestres()
	{
		return $this->hasMany(EtudSemestre::class);
	}

	public function matieres()
	{
		return $this->belongsToMany(Matiere::class, 'matiere_salle_etudiants')
					->withPivot('id', 'salle_id', 'annee_id', 'deleted_at', 'etud_mat_id', 'profil_id', 'groupe_id')
					->withTimestamps();
	}

	public function salles()
	{
		return $this->belongsToMany(Salle::class, 'matiere_salle_etudiants')
					->withPivot('id', 'matiere_id', 'annee_id', 'deleted_at', 'etud_mat_id', 'profil_id', 'groupe_id')
					->withTimestamps();
	}

	public function note_devoirs()
	{
		return $this->hasMany(NoteDevoir::class);
	}

	public function note_examen_finales()
	{
		return $this->hasMany(NoteExamenFinale::class);
	}

	public function note_examen_rts()
	{
		return $this->hasMany(NoteExamenRt::class);
	}

	public function releves_notes()
	{
		return $this->hasMany(RelevesNote::class);
	}
}
