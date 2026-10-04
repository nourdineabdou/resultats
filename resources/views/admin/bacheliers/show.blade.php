<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dossier du bachelier</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;

            background: #f4f6fb;

            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        }

        h1,
        h2 {
            color: #29346f;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .item {
            padding: 15px;
            background: #f7f8fc;
            border-radius: 8px;
        }

        .label {
            display: block;
            color: #777;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .value {
            font-weight: bold;
            color: #29346f;
        }

        .documents {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .document {
            text-align: center;
            padding: 20px;
            background: #f7f8fc;
            border-radius: 12px;
        }

        .document img {
            width: 100%;
            max-height: 220px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            color: white;
            background: #29346f;
        }

        .btn-success {
            background: #198754;
        }

        .btn-danger {
            background: #c62828;
        }

        textarea {
            width: 100%;
            min-height: 130px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
        }

        .alert {
            padding: 15px;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 700px) {
            .grid,
            .documents {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <a href="{{ route('admin.bacheliers.index') }}">
        ← Retour à la liste
    </a>

    <h1>Dossier du bachelier</h1>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Informations personnelles -->
    <div class="card">

        <h2>Informations personnelles</h2>

        <div class="grid">

            <div class="item">
                <span class="label">NNI</span>
                <span class="value">
                    {{ $bachelier->nni ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Numéro du bac</span>
                <span class="value">
                    {{ $bachelier->nobac ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Nom français</span>
                <span class="value">
                    {{ $bachelier->nompl ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Nom arabe</span>
                <span class="value">
                    {{ $bachelier->nompa ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Date de naissance</span>
                <span class="value">
                    {{ $bachelier->datn ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Lieu de naissance</span>
                <span class="value">
                    {{ $bachelier->lieu ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Série du bac</span>
                <span class="value">
                    {{ $bachelier->serie ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Année du bac</span>
                <span class="value">
                    {{ $bachelier->annee ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Téléphone</span>
                <span class="value">
                    {{ $bachelier->tel ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Email</span>
                <span class="value">
                    {{ $bachelier->email ?? '-' }}
                </span>
            </div>

            <div class="item">
                <span class="label">État actuel</span>
                <span class="value">

                    @if($bachelier->etat == 1)
                        Brouillon
                    @elseif($bachelier->etat == 2)
                        Demande d'inscription
                    @elseif($bachelier->etat == 3)
                        Inscrit
                    @endif

                </span>
            </div>

            <div class="item">
                <span class="label">Date de candidature</span>
                <span class="value">
                    {{ $bachelier->date_candidature ?? '-' }}
                </span>
            </div>

        </div>

    </div>

    <!-- Documents -->
    <div class="card">

        <h2>Documents du candidat</h2>

        <div class="documents">

            <!-- Carte d'identité -->
            <div class="document">

                <h3>Carte d'identité</h3>

                @if($bachelier->carte_identite)

                    @php
                        $extension = strtolower(
                            pathinfo($bachelier->carte_identite, PATHINFO_EXTENSION)
                        );
                    @endphp

                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                        <img
                            src="{{ asset($bachelier->carte_identite) }}"
                            alt="Carte d'identité"
                        >
                    @endif

                    <a
                        href="{{ asset($bachelier->carte_identite) }}"
                        target="_blank"
                        class="btn"
                    >
                        Ouvrir le document
                    </a>

                @else
                    <p>Document non fourni.</p>
                @endif

            </div>

            <!-- Bac -->
            <div class="document">

                <h3>Document du baccalauréat</h3>

                @if($bachelier->bac_document)

                    @php
                        $extension = strtolower(
                            pathinfo($bachelier->bac_document, PATHINFO_EXTENSION)
                        );
                    @endphp

                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                        <img
                            src="{{ asset($bachelier->bac_document) }}"
                            alt="Document du bac"
                        >
                    @endif

                    <a
                        href="{{ asset($bachelier->bac_document) }}"
                        target="_blank"
                        class="btn"
                    >
                        Ouvrir le document
                    </a>

                @else
                    <p>Document non fourni.</p>
                @endif

            </div>

            <!-- Photo personnelle -->
            <div class="document">

                <h3>Photo personnelle</h3>

                @if($bachelier->photo_personnelle)

                    <img
                        src="{{ asset($bachelier->photo_personnelle) }}"
                        alt="Photo personnelle"
                    >

                    <a
                        href="{{ asset($bachelier->photo_personnelle) }}"
                        target="_blank"
                        class="btn"
                    >
                        Ouvrir la photo
                    </a>

                @else
                    <p>Photo non fournie.</p>
                @endif

            </div>

        </div>

    </div>

    <!-- Observation existante -->
    @if($bachelier->noprfl)

        <div class="card">

            <h2>Observation actuelle</h2>

            <p>
                {{ $bachelier->noprfl }}
            </p>

        </div>

    @endif

    <!-- Actions administratives -->
    @if($bachelier->etat == 2)

        <div class="card">

            <h2>Décision administrative</h2>

            <p>
                Vérifiez les informations et les documents avant de prendre une décision.
            </p>

            <!-- Valider -->
            <form
                action="{{ route('admin.bacheliers.valider', $bachelier->id) }}"
                method="POST"
                style="display:inline-block;"
            >
                @csrf

                <button type="submit" class="btn btn-success">
                    Valider l'inscription
                </button>
            </form>

            <!-- Demander des compléments -->
            <h3>Demander des compléments</h3>

            <form
                action="{{ route('admin.bacheliers.complement', $bachelier->id) }}"
                method="POST"
            >
                @csrf

                <textarea
                    name="noprfl"
                    placeholder="Saisissez les documents ou informations manquants..."
                    required
                ></textarea>

                <br><br>

                <button type="submit" class="btn btn-danger">
                    Envoyer l'observation au candidat
                </button>
            </form>

        </div>

    @elseif($bachelier->etat == 3)

        <div class="card">

            <h2>Inscription validée</h2>

            <p>
                Ce candidat est déjà confirmé comme étudiant.
            </p>

        </div>

    @elseif($bachelier->etat == 1)

        <div class="card">

            <h2>Dossier en brouillon</h2>

            <p>
                Ce dossier n'est pas encore finalisé ou nécessite des compléments.
            </p>

        </div>

    @endif

</div>

</body>

</html>