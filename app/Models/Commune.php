<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Commune
 * 
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property string|null $adresse_GPS
 * @property string|null $contour_gps
 * @property int $nbr_habitans
 * @property int $classe_population
 * @property int $moughataa_id
 * @property string $code
 * @property string $nom_Maire
 * @property string $nom_SG
 * @property int $surface
 * @property int|null $nbr_villages_localites
 * @property string|null $decret_de_creation
 * @property int|null $nbr_conseillers_municipaux
 * @property int|null $nbr_employes_municipaux_permanents
 * @property int|null $nbr_employes_municipaux_temporaires
 * @property int|null $secretaire_generale
 * @property bool|null $pnidelle
 * @property bool|null $organisations_internationale
 * @property bool|null $recettes_impots
 * @property bool|null $eclairage_public
 * @property string|null $path_carte
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Commune extends Model
{
	use SoftDeletes;
	protected $table = 'communes';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'nbr_habitans' => 'int',
		'classe_population' => 'int',
		'moughataa_id' => 'int',
		'surface' => 'int',
		'nbr_villages_localites' => 'int',
		'nbr_conseillers_municipaux' => 'int',
		'nbr_employes_municipaux_permanents' => 'int',
		'nbr_employes_municipaux_temporaires' => 'int',
		'secretaire_generale' => 'int',
		'pnidelle' => 'bool',
		'organisations_internationale' => 'bool',
		'recettes_impots' => 'bool',
		'eclairage_public' => 'bool'
	];

	protected $hidden = [
		'secretaire_generale'
	];

	protected $fillable = [
		'id',
		'libelle',
		'libelle_ar',
		'adresse_GPS',
		'contour_gps',
		'nbr_habitans',
		'classe_population',
		'moughataa_id',
		'code',
		'nom_Maire',
		'nom_SG',
		'surface',
		'nbr_villages_localites',
		'decret_de_creation',
		'nbr_conseillers_municipaux',
		'nbr_employes_municipaux_permanents',
		'nbr_employes_municipaux_temporaires',
		'secretaire_generale',
		'pnidelle',
		'organisations_internationale',
		'recettes_impots',
		'eclairage_public',
		'path_carte'
	];
}
