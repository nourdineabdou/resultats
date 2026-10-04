<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des bacheliers</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;

            background: #f4f6fb;

            font-family: Arial, sans-serif;

            color: #29346f;
        }

        .container {
            max-width: 1300px;
            margin: auto;
        }

        h1 {
            margin-bottom: 25px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
        }

        .stat-card h3 {
            margin: 0 0 15px;
            color: #777;
            font-size: 15px;
        }

        .stat-card strong {
            font-size: 32px;
            color: #29346f;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        select,
        button {
            padding: 11px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        button {
            background: #29346f;
            color: white;
            cursor: pointer;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f7f8fc;
        }

        .badge {
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .draft {
            background: #fff0f0;
            color: #b42318;
        }

        .pending {
            background: #fff4d6;
            color: #9a6a00;
        }

        .confirmed {
            background: #e2f6e9;
            color: #18794e;
        }

        .btn-view {
            background: #29346f;
            color: white;
            text-decoration: none;
            padding: 9px 15px;
            border-radius: 7px;
            display: inline-block;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .stats {
                grid-template-columns: 1fr;
            }

            body {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Gestion des bacheliers</h1>

    <!-- Statistiques -->
    <div class="stats">

        <div class="stat-card">
            <h3>Total des bacheliers</h3>
            <strong>{{ $total }}</strong>
        </div>

        <div class="stat-card">
            <h3>Brouillons</h3>
            <strong>{{ $brouillons }}</strong>
        </div>

        <div class="stat-card">
            <h3>Demandes d'inscription</h3>
            <strong>{{ $demandes }}</strong>
        </div>

        <div class="stat-card">
            <h3>Étudiants inscrits</h3>
            <strong>{{ $inscrits }}</strong>
        </div>

    </div>

    <!-- Filtre -->
    <div class="filters">

        <form method="GET"
              action="{{ route('admin.bacheliers.index') }}">

            <label for="etat">
                Filtrer par état :
            </label>

            <select name="etat" id="etat">

                <option value="">
                    Tous les bacheliers
                </option>

                <option value="1"
                    {{ request('etat') == '1' ? 'selected' : '' }}>
                    Brouillons
                </option>

                <option value="2"
                    {{ request('etat') == '2' ? 'selected' : '' }}>
                    Demandes d'inscription
                </option>

                <option value="3"
                    {{ request('etat') == '3' ? 'selected' : '' }}>
                    Étudiants inscrits
                </option>

            </select>

            <button type="submit">
                Filtrer
            </button>

            <a href="{{ route('admin.bacheliers.index') }}">
                Réinitialiser
            </a>

        </form>

    </div>

    <!-- Liste -->
    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>#</th>
                    <th>NNI</th>
                    <th>Nom français</th>
                    <th>Nom arabe</th>
                    <th>Téléphone</th>
                    <th>État</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($bacheliers as $bachelier)

                    <tr>

                        <td>{{ $bachelier->id }}</td>

                        <td>{{ $bachelier->nni }}</td>

                        <td>{{ $bachelier->nompl }}</td>

                        <td>{{ $bachelier->nompa }}</td>

                        <td>{{ $bachelier->tel ?? '-' }}</td>

                        <td>

                            @if($bachelier->etat == 1)

                                <span class="badge draft">
                                    Brouillon
                                </span>

                            @elseif($bachelier->etat == 2)

                                <span class="badge pending">
                                    Demande d'inscription
                                </span>

                            @elseif($bachelier->etat == 3)

                                <span class="badge confirmed">
                                    Inscrit
                                </span>

                            @endif

                        </td>

                        <td>
                            <a
                                href="{{ route('admin.bacheliers.show', $bachelier->id) }}"
                                class="btn-view"
                            >
                                Visualiser
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7">
                            Aucun bachelier trouvé.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="pagination">
            {{ $bacheliers->links() }}
        </div>

    </div>

</div>

</body>

</html>