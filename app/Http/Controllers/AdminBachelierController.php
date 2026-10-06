<?php

namespace App\Http\Controllers;

use App\Models\Bachelier;
use App\Models\Etudiant;
use App\Models\Annee;
use App\Models\Faculte;
use App\Models\Profil;
use App\Models\Matiere;
use App\Models\TempMatiere;
use App\Models\EtudMat;
use App\Models\EtudSemestre;
use App\Models\RefGroupe;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminBachelierController extends Controller
{
    /**
     * Tableau de bord et liste des bacheliers
     */
    public function index(Request $request)
    {
		$bachelierConnecte = Bachelier::find(
        session('bachelier_id')
    );

    if (!$bachelierConnecte || $bachelierConnecte->etat != 5) {
        session()->forget([
            'bachelier_id',
            'bachelier_nni',
            'bachelier_nom',
            'bachelier_prenom',
            'bachelier_etat',
        ]);

        return redirect()->route('authentification2');
    }
        $query = Bachelier::where('etat','<>', 5);

        // Filtre par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        $bacheliers = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Statistiques générales
        $total = $query->count();

        $brouillons = Bachelier::where('etat', 1)->count();

        $demandes = Bachelier::where('etat', 2)->count();

        $inscrits = Bachelier::where('etat', 3)->count();

        return view('admin.bacheliers.index', compact(
            'bacheliers',
            'total',
            'brouillons',
            'demandes', 
			'bachelierConnecte',
            'inscrits'
        ));
    }

    /**
     * Afficher le dossier complet d'un bachelier
     */
    public function show($id)
    {
        $bachelier = Bachelier::findOrFail($id);

        return view('admin.bacheliers.show', compact('bachelier'));
    }

    /**
     * Valider l'inscription
     */
   public function valider($id)
{
   // DB::transaction(function () use ($id)  {

        // Récupérer le bachelier
        $bachelier = Bachelier::find($id);

        // Vérifier que le dossier est en attente
        if ($bachelier->etat != 2) {
            throw new \Exception('Ce dossier ne peut pas être validé.');
        }

        // Vérifier si le candidat existe déjà comme étudiant
        $existant = Etudiant::where('NNI', $bachelier->nni)->first();

        if ($existant) {
            throw new \Exception('Cet étudiant existe déjà dans la base des étudiants.');
        }

        // Récupérer l'année universitaire active
        $annee = Annee::where('etat', 1)->first();

        // Récupérer la faculté active
        $faculte = Faculte::where('etat', 1)->first();

        // Récupérer le profil du bachelier
        $profil = Profil::find($bachelier->nat);

        $ref_niveau_etude_id = 1;

        // Génération du numéro NODOS
        if ($ref_niveau_etude_id == 1) {

            $nodos = $faculte->code . ($annee->numero + 1);

            $annee->numero = $annee->numero + 1;

        } elseif ($ref_niveau_etude_id == 4) {

            $nodos = 'M00' . ($annee->numeroMst + 1);

            $annee->numeroMst = $annee->numeroMst + 1;

        } else {
            throw new \Exception('Niveau d’étude non reconnu.');
        }

        $annee->save();

        // Détermination du groupe
        $groupe = 'ا';

        $dernierEtudiant = Etudiant::orderByDesc('id')->first();

        if ($dernierEtudiant && $dernierEtudiant->groupe) {

            $groupeActuel = RefGroupe::where(
                'libelle',
                $dernierEtudiant->groupe
            )->first();

            if ($groupeActuel) {

                $groupeSuivant = RefGroupe::where(
                    'id',
                    '>',
                    $groupeActuel->id
                )->orderBy('id')->first();

                if ($groupeSuivant) {
                    $groupe = $groupeSuivant->libelle;
                }
            }
        }

        // Création de l'étudiant à partir du bachelier
        $etudiant = new Etudiant();

        $etudiant->NODOS = $nodos;
        $etudiant->DECF = 1;

        $etudiant->NNI = $bachelier->nni;
        $etudiant->NOMF = $bachelier->nompl;
        $etudiant->NOMA = $bachelier->nompa;
        $etudiant->NOBAC = $bachelier->nobac;

        $etudiant->DATN = $bachelier->datn;
        $etudiant->LIEUNA = $bachelier->lieuna;
        $etudiant->profil_id = $bachelier->nat;

        $etudiant->groupe = '';
		$etudiant->photo = $bachelier->photo_personnelle;
        $etudiant->SEXE = $bachelier->sexe;
        $etudiant->whatsapp = $bachelier->tel;

        // À adapter selon le format de nationalite dans bacheliers
        $etudiant->ref_nationnalite_id = $bachelier->nationalite;

        $etudiant->save();

        // Affectation des matières
        $matieres = Matiere::where('profil_id',$bachelier->nat)->get();

        foreach ($matieres as $matiere) {

            $m = Matiere::find($matiere->id);

            if (!$m) {
                continue;
            }

            $etudMat = new EtudMat();

            $etudMat->etudiant_id = $etudiant->id;
            $etudMat->profil_id = $bachelier->nat;
            $etudMat->NODOS = $etudiant->NODOS;

            $etudMat->Code = $m->modulle_id;
            $etudMat->NOMAT = $matiere->code;
            $etudMat->matiere_id = $matiere->id;
            $etudMat->ref_semestre_id = $m->ref_semestre_id;

            $etudMat->annee_id = $annee->id;

            $etudMat->save();
        }

        // Création du semestre 1
        /* $sem1 = new EtudSemestre();

        $sem1->NODOS = $etudiant->NODOS;
        $sem1->etudiant_id = $etudiant->id;
        $sem1->profil_id = $bachelier->nat;
        $sem1->ref_semestre_id = 1;
        $sem1->annee_id = $annee->id;

        $sem1->save();

        // Création du semestre 2
        $sem2 = new EtudSemestre();

        $sem2->NODOS = $etudiant->NODOS;
        $sem2->etudiant_id = $etudiant->id;
        $sem2->profil_id = $bachelier->nat;
        $sem2->ref_semestre_id = 2;
        $sem2->annee_id = $annee->id;

        $sem2->save(); */

        // Mise à jour du bachelier après création complète
        $bachelier->etat = 3;
        $bachelier->noprfl = null;
        $bachelier->save();
   // });

    return redirect()
        ->route('admin.bacheliers.show', $id)
        ->with('success', 'Le candidat a été inscrit avec succès. Son compte étudiant a été créé.');
}

    /**
     * Demander des compléments au candidat
     */
    public function complement(Request $request, $id)
    {
        $request->validate([
            'noprfl' => 'required|string|max:2000',
        ], [
            'noprfl.required' => 'Veuillez saisir une observation.',
        ]);

        $bachelier = Bachelier::findOrFail($id);

        $bachelier->update([
            'noprfl' => $request->noprfl,

            // Retour à l'état brouillon / dossier incomplet
            'etat' => 1,
        ]);

        return redirect()
            ->route('admin.bacheliers.show', $bachelier->id)
            ->with(
                'success',
                'L’observation a été envoyée au candidat.'
            );
    }
}