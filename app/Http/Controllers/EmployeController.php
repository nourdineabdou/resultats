<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Models\Employe;
use App\Models\Equipement;
use App\Models\Experience;
use http\Encoding\Stream\Enbrotli;
use Illuminate\Http\Request;
use App\Models\RefGenre;
use App\Models\RefSituationFamilliale;
use  App\Models\RefNiveauEtude;
use App\Models\Specialite;
use App\Models\RefFonction;
use App\Models\RefTypesContrat;
use App\Models\RefAppreciationsHierarchy;
use App\Models\Commune;
use App\Models\EnteteCommune;
use App\Models\Service;
use App\Models\MyPDF;
use App\Http\Requests\EmployeRequest;
use DataTables;
use App\User;
use Auth;
use App;
use PDF;

use Carbon\Carbon;

class EmployeController extends Controller
{
    private $module = 'employes';
    private $module_exp = 'employes/experiences';

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {
        $refGenres = RefGenre::all();
        $refSituationFamilliales = RefSituationFamilliale::all();
        //$refNiveauEtudes = RefNiveauEtude::all();
        //$specialites = Specialite::all();
        //$refFonctions = RefFonction::all();
        $rfTypesContrats = RefTypesContrat::all();
        //$refAppreciationsHierarchys = RefAppreciationsHierarchy::all();
        $communes = Commune::all();

        return view($this->module . '.index', [
            'refGenres' => $refGenres,
            'refSituationFamilliales' => $refSituationFamilliales,
            /*'refNiveauEtudes' => $refNiveauEtudes,
            'specialites' => $specialites,
            'refFonctions' => $refFonctions,*/
            'rfTypesContrats' => $rfTypesContrats,
            // 'refAppreciationsHierarchys' => $refAppreciationsHierarchys,
            'communes' => $communes
        ]);
    }


    public function getDT($genre, $type_contrat, $refSituationFamilliale, $selected = 'all')
    {
        $employe = null;
        if (Auth::user()->hasAccess([6]))
            $employes = Employe::with('ref_genre', 'ref_types_contrat', 'ref_situation_familliale');
//  dd($employes=Employe::al);

        if ($genre != 'all')
            $employes = $employes->where('ref_genre_id', $genre);
        if ($type_contrat != 'all')
            $employes = $employes->where('ref_types_contrat_id', $type_contrat);
        if ($refSituationFamilliale != 'all')
            $employes = $employes->where('ref_situation_familliale_id', $refSituationFamilliale);
        if ($selected != 'all')
            $employes = $employes->orderByRaw('employes.id = ? desc', [$selected]);

        return DataTables::of($employes)
            ->addColumn('actions', function ($employe) {

                $msg_supp = trans("text.confirm_suppression") . "$employe->prenom $employe->nom";
                $html = '<div class="btn-group">';
                if (Auth::user()->hasAccess([6]))
                    $html .= ' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal(' . $employe->id . ',\'' . $this->module . '\',1,\'xl\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fa fa-fw fa-eye"></i></button> ';
                if (Auth::user()->hasAccess([6], 5))
                    $html .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\'' . url($this->module . '/delete/' . $employe->id) . '\',\'' . $msg_supp . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button> ';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date_naissance', function (Employe $employe) {
                return Carbon::parse($employe->date_naissance)->format('d-m-Y');
            })
            ->setRowClass(function ($employe) use ($selected) {
                return $employe->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['id', 'actions'])
            ->make(true);
    }

    public function edite_exp(ExperienceRequest $request)
    {
        $en_cours = 1;
        if (!$request->en_cours) {
            $this->validate($request, [
                'annee_fin' => 'required',
            ]);
            $en_cours = 0;
        }
        $experience = Experience::find($request->id);
        $experience->libelle = $request->poste;

        $experience->service_id = $request->service;
        $experience->annee_deb = $request->annee_deb;
        $experience->mois_deb = $request->mois_deb;
        $experience->jour_deb = $request->jour_deb;
        $experience->annee_fin = $request->annee_fin;
        $experience->mois_fin = $request->mois_fin;
        $experience->jour_fin = $request->jour_fin;
        $experience->mission_principal = $request->mission;
        $experience->encours = $en_cours;
        $experience->save();
        return response()->json($experience->id, 200);

    }

    public function getDT_exp($emp, $selected = 'all')
    {
        $DES = Experience::where('employe_id', $emp);

        $DES =$DES->orderBy('id','desc');
        if ($selected != 'all')
            $DES = $DES->orderByRaw('id = ? desc', [$selected]);

        return DataTables::of($DES)
            ->addColumn('description', function (Experience $de) {
                $date_fin = ($de->encours) ? 'à nos jours ' : $de->annee_fin;
                $date = $de->annee_deb . "   -  " . $date_fin;
                $titre = $de->libelle;
                //$etab = $de->entreprise . "," . $de->ville . " , " . $de->pay->libelle . ' (' . $de->service->libelle . " )";
                $etab = $de->service->libelle;
                $mission = "<br><b>" . trans('text_sd.mission') . "</b>  " . $de->mission_principal;
                $cord = $etab . $mission;
                $buttons = ' <button type="button" class="btn btn-sm btn-dark" onClick="openObjectModal_sd(' . $de->id . ',\'' . $this->module_exp . '\',\'.datatableshow_ind2\',\'de_tab\',false,\'lg\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.visualiser') . '"><i class="fa fa-fw fa-eye"></i></button> ';

                $buttons .= ' <button type="button" class="btn btn-sm btn-secondary" onClick="confirmAction(\'' . url($this->module_exp . '/delete/' . $de->id) . '\',\'' . trans('text.confirm_suppression') . '\')" data-toggle="tooltip" data-placement="top" title="' . trans('text.supprimer') . '"><i class="fas fa-trash"></i></button> ';

                $donnee = '<div class="card post-card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-xs-12 col-sm-2 text-center ">
                           <p>' . $date . '</p>
                          </div>
                          <div class="col-xs-12 col-sm-8">
                            <div class="post-card-heading"> <strong><b>' . $titre . '</b></strong>  </div>
                            <div class="post-card-content text-beta">' . $cord . '</div>';
                $donnee .= '
                          </div>
                          <div class="col-xs-12 col-sm-2 text-center align-self-center">
                             ' . $buttons . '
                          </div>
                        </div>
                      </div>
                    </div>';

                return $donnee;
            })
            /* ->setRowClass(function ($de) use ($selected) {
                 return $de->id == $selected ? 'alert-success' : '';
             })*/
            ->setRowClass(function ($de) use ($selected) {
                return $de->id == $selected ? 'alert-success' : '';
            })
            ->rawColumns(['description'])
            ->make(true);
    }

    public function get_exp($id)
    {
        $experience = Experience::find($id);

        $services = Service::all();
        $modal_title = '<b>' . $experience->libelle . '</b>';
        return view($this->module_exp.'.edite_exp', ['modal_title' => $modal_title,'experience'=>$experience,'services' => $services]);

    }

    public function formAddExp($id)
    {
        $services = Service::all();
        return view($this->module_exp.".add",['id'=>$id,'services'=>$services]);
    }
    public function addExp(ExperienceRequest $request)
    {

        $en_cours = 1;
        if (!$request->en_cours) {
            $this->validate($request, [
                'annee_fin' => 'required',
            ]);
            $en_cours = 0;
        }
        $experience = new Experience();
        $experience->libelle = $request->poste;

        $experience->service_id = $request->service;
        $experience->annee_deb = $request->annee_deb;
        $experience->mois_deb = $request->mois_deb;
        $experience->jour_deb = $request->jour_deb;
        $experience->annee_fin = $request->annee_fin;
        $experience->mois_fin = $request->mois_fin;
        $experience->jour_fin = $request->jour_fin;
        $experience->mission_principal = $request->mission;
        $experience->employe_id = $request->id_emp;
        $experience->encours = $en_cours;
        $experience->save();
        if($experience->id)
        {
            $set_emp = Employe::find($request->id_emp);
            if($set_emp != null && $set_emp->service_id != $request->service)
            {
                $set_emp->service_id=$request->service;
                $set_emp->save();
            }
            $res = array('id'=>$experience->id,'datatable' => '.datatableshow_ind2');
            return response()->json($res, 200);
        }

    }

    public function deleteExp($id)
    {

        $setExp = Experience::find($id);
        $datatableshow = ".datatableshow_ind2";
        /*if ($famille->has_articles)
            return response()->json(['success'=>'false', 'msg'=>trans('text.famille_cant_be_del_bcuz_of_articles')],200);
        else {*/
        $setExp->delete();
        return response()->json(['success' => 'true', 'datatableshow' => $datatableshow, 'msg' => trans('text.element_well_deleted')], 200);


    }
    public function gettab_experience($id, $tab)
    {
        $experience = Experience::find($id);
        $services = App\Models\Service::all();


        switch ($tab) {
            case '31':
                $parametres = ['experience' => $experience, 'services' => $services];
                break;

            default :
                $parametres = ['famille' => ''];
                break;
        }
        return view($this->module_exp . '.tabs.tab' . $tab, $parametres);
    }

    public function formAdd()
    {
        $refGenres = RefGenre::all();
        $refSituationFamilliales = RefSituationFamilliale::all();
        $refNiveauEtudes = RefNiveauEtude::all();
        $specialites = Specialite::all();
        $refFonctions = RefFonction::all();
        $rfTypesContrats = RefTypesContrat::all();
        $refAppreciationsHierarchys = RefAppreciationsHierarchy::all();
        $communes = Commune::all();

        return view($this->module . '.add', [
            'refGenres' => $refGenres,
            'refSituationFamilliales' => $refSituationFamilliales,
            'refNiveauEtudes' => $refNiveauEtudes,
            'specialites' => $specialites,
            'refFonctions' => $refFonctions,
            'rfTypesContrats' => $rfTypesContrats,
            'refAppreciationsHierarchys' => $refAppreciationsHierarchys,
            'communes' => $communes
        ]);
    }


    public function openModalImage($id)
    {
        $de = Employe::find($id);
        // $image = Ged::where(['type_ged'=>1,'objet_id'=>$id,'type'=>1])->where('emplacement','/files/ImgStag')->first();
        return view($this->module . '.openModalImage', ['de' => $de]);
    }

    public function updateImage(Request $request)
    {
        /**
         * name        : addStagiaire
         * parametres  : Les info
         * return      : json
         * Description :
         */
        // dd($request);
        $this->validate($request, [
            'fichier' => 'required',
        ]);
        $id = $request->id;
        if ($request->fichier) {
            // dd($request->fichier);


            $de = Employe::find($id);
            $extension = $request->file('fichier')->getClientOriginalExtension();

            if ($de->photo && file_exists($de->photo)) {
                if (\File::exists(public_path($de->photo))) {

                    \File::delete(public_path($de->photo));
                }
            }
            $path = "files/employes/profil$id.$extension";
            $de->photo = $path;
            $de->save();
            $imageName = "profil$id" . '.' . $request->file('fichier')->getClientOriginalExtension();
            $request->file('fichier')->move(
                base_path() . '/public/files/employes/', $imageName
            );
            $link = $path;
        }
        // $link = url("redirectto/requestlissements/".$specialite->id);

        /*$val =   "<img id='avatar' src='".$path."' style='height: 200px;width: 100%'
                             class='avatar img-circle img-thumbnail' alt='avatar'>";*/

        return response()->json($link, 200);
    }

    public function add(EmployeRequest $request)
    {
        //dd($request);
        $employe = new Employe();

        $employe->nom = $request->nom;
        $employe->prenom = $request->prenom;
        $employe->date_naissance = $request->date_naissance;
        $employe->nom_famille = $request->nom_famille;
        $employe->nni = $request->nni;
        $employe->lieu_naissance = $request->lieu_naissance;
        $employe->ref_genre_id = $request->ref_genre_id;
        $employe->ref_situation_familliale_id = $request->ref_situation_familliale_id;
        $employe->ref_types_contrat_id = $request->type_contrat;
        $employe->save();
        return response()->json($employe->id, 200);
    }

//$id = env('APP_COMMUNE');
    public function entete($titre, $or = 'P', $id = false)
    {
        if ($id == false)
            $id = env('APP_COMMUNE');
        $class = '';
        if ($or != 'P')
            $class = '_g';
        $commune = Commune::find($id);
        $entete = EnteteCommune::where('commune_id', $id)->first();

        $titre1 = $entete->titre1;
        $titre2 = $entete->titre2;
        $titre3 = $entete->titre3;

        $titre1_ar = $entete->titre1_ar;
        $titre2_ar = $entete->titre2_ar;
        $titre3_ar = $entete->titre3_ar;
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';

        //dd($html);
        $entet_fr = '<table><tr><td class="titre' . $class . '">' . $titre1 . '</td></tr><tr><td class="titre2' . $class . '">' . $titre2 . '</td></tr><tr><td class="titre3' . $class . '">' . $titre3 . '</td></tr></table>';
        $entet_ar = '<table ><tr><td class="titre_ar' . $class . '">' . $titre1_ar . '</td></tr><tr><td class="titre2_ar' . $class . '">' . $titre2_ar . '</td></tr><tr><td class="titre3_ar' . $class . '">' . $titre3_ar . '</td></tr></table>';
        $logo = '<table  ><tr ><td align="center"><input><img src="' . $entete->logo . '" alt="avatar"  /></td></tr></table>';
        $table = '<table><tr><td class="t_left' . $class . '">' . $entet_fr . '</td><td class="t_center' . $class . '">' . $logo . '</td><td class="t_right' . $class . '">' . $entet_ar . '</td></tr></table>';
        $html .= $table;
        $info_commune = '<br><br><table class="info_commune">
        <tr>
        <td><table><tr><td >' . trans("text_me.willaye") . ' </td><td class="t_bold">' . $commune->moughataa->wilaya->libelle . '</td></tr>
        <tr><td >' . trans("text_me.moughataa") . '</td><td class="t_bold">' . $commune->moughataa->libelle . '</td></tr>
        <tr><td >' . trans("text_me.commune") . '</td><td class="t_bold">' . $commune->libelle . '</td></tr> </table></td>
       
            <td ><table class="info_commune_ar"><tr><td class="t_bold">' . $commune->moughataa->wilaya->libelle_ar . '</td><td >' . trans("text_me.willaye_ar") . ' </td></tr>
        <tr><td class="t_bold">' . $commune->moughataa->libelle_ar . '</td><td>' . trans("text_me.moughataa_ar") . '</td></tr>
        <tr><td class="t_bold">' . $commune->libelle_ar . '</td><td >' . trans("text_me.commune_ar") . '</td></tr> </table> </td>
        </tr>
       
        
    </table>';
        $titre_entete = '<br><h4 class="titre_entete">' . $titre . '<br><br></h4>';
        $html .= $info_commune . $titre_entete;
        return $html;
    }

    public function liste_employes1($headers, $datas)
    {
        $start = 1;
        $end = 254;
        $step = 1;

        //$arr = range($start, $end, $step);

        //dd($contente_header);

        $table_header = sprintf("<thead><tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr></thead>", $headers[0], $headers[1], $headers[2], $headers[3], $headers[4], $headers[5], $headers[6], $headers[7]);
        $table_begin = '<table nobr="true">';
        $table = '';
        $ligne = '<tbody>';
        $i = 0;
        foreach ($datas as $row) {
            $class = '';
            if ($i % 2 == 0) {
                $class = "odd";
            }
            $nom = $row->prenom . ' ' . $row->nom;
            $genre = ($row->ref_genre) ? $row->ref_genre->libelle : '';
            $sit_fam = ($row->ref_situation_familliale) ? $row->ref_situation_familliale->libelle : '';
            $date_nais = $row->date_naissance;
            $lieu = ($row->commune) ? $row->commune->libelle : '';
            $type_cont = ($row->ref_types_contrat) ? $row->ref_types_contrat->libelle : '';
            $fonction = ($row->ref_fonction) ? $row->ref_fonction->libelle : '';
            $service = ($row->service) ? $row->service->libelle : '';
            $ligne .= sprintf("<tr class=\"$class\">\n<td>%s</td>\n<td>%s</td>\n<td>%s</td>\n<td>%s</td>\n<td>%s</td>\n<td>%s</td>\n<td>%s</td>\n<td>%s</td>\n</tr>\n", $nom, $genre, $sit_fam, $date_nais, $lieu, $type_cont, $fonction, $service);

            $i++;
        }


        $ligne .= '</tbody>';
        $tbl = <<<EOD
            <style>
               table {
         border-collapse: collapse;
         border-spacing: 0;
         
     }
   
     th {
         text-align: left;
     }
     table.table {
         width: 100%;
         max-width: 100%;
         margin-bottom: 20px;
         line-height: 15px;
     }
     table.table > thead > tr > th,
     table.table > tbody > tr > th,
     table.table > tfoot > tr > th,
     table.table > thead > tr > td,
     table.table > tbody > tr > td,
     table.table > tfoot > tr > td {
         padding: 8px;
         line-height: 1.42857143;
         vertical-align: top;
         border-top: 1px solid #dddddd;
     }
     table.table > thead > tr > th {
         vertical-align: bottom;
         border-bottom: 2px solid #dddddd;
     }
     table.table > caption + thead > tr:first-child > th,
     table.table > colgroup + thead > tr:first-child > th,
     table.table > thead:first-child > tr:first-child > th,
     table.table > caption + thead > tr:first-child > td,
     table.table > colgroup + thead > tr:first-child > td,
     table.table > thead:first-child > tr:first-child > td {
         border-top: 0;
     }
     table.table > tbody + tbody {
         border-top: 2px solid #dddddd;
     }
     table.table .table {
         background-color: #ffffff;
     }
     td {
         padding: 5px;
     }
     table {
         border: 1px solid #dddddd;
     }
   
    
    th{
     border-bottom-width: 2px;
     font-weight: bold;
     background-color: #dea71b;
     color: #fff;
    }
  
     tr.odd {
         background-color:#f9f9f9;
        
     }
     table.table-hover > tbody > tr:hover {
         background-color: #f5f5f5;
     }
     table col[class*="col-"] {
         position: static;
         float: none;
         display: table-column;
     }
     table td[class*="col-"],
     table th[class*="col-"] {
         position: static;
         float: none;
         display: table-cell;
     }
            </style>

      
        <table class="table">
        {$table_header}
        {$ligne}

</table>
EOD;

        //dd($tbl);
        return $tbl;

    }

    public function liste_employes($header, $data)
    {
        // Colors, line width and bold font

        PDF::SetFillColor(976, 245, 458);
        PDF::SetTextColor(0);
        PDF::SetDrawColor(0, 0, 0);
        PDF::SetLineWidth(0.3);
        PDF::SetFont('', 'B');
        // Header
        $w = array(55, 25, 30, 40, 40, 30, 30, 30);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {

            PDF::StartTransform();
            PDF::Rotate(90);
            PDF::Cell($w[$i], 7, $header[$i].'yrg', 1, 0, 'C', 1);
            PDF::StopTransform();
        }
        PDF::Ln();
        // Color and font restoration
        PDF::SetFillColor(224, 235, 255);
        PDF::SetTextColor(0);
        PDF::SetFont('');
        // Data
        $fill = 0;
        $st_f = '';
        $lieu_nais = '';
        $type_contrat = '';

        foreach ($data as $row) {
            PDF::Cell($w[0], 6, $row->prenom . ' ' . $row->nom, 'LR', 0, 'L', $fill);
            PDF::Cell($w[1], 6, ($row->ref_genre) ? $row->ref_genre->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[2], 6, ($row->ref_situation_familliale) ? $row->ref_situation_familliale->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[3], 6, $row->date_naissance, 'LR', 0, 'L', $fill);
            PDF::Cell($w[4], 6, ($row->commune) ? $row->commune->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[5], 6, ($row->ref_types_contrat) ? $row->ref_types_contrat->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[6], 6, ($row->ref_fonction) ? $row->ref_fonction->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Cell($w[7], 6, ($row->service) ? $row->service->libelle : '', 'LR', 0, 'L', $fill);
            PDF::Ln();
            $fill = !$fill;
        }
        PDF::Cell(array_sum($w), 0, '', 'T');
    }

    public function filter_export($type, $genre, $sit_f)
    {
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';
        $filter = '<table class="filter"><tr><td >' . trans('text_hb.type_contrat') . ' : <b> ' . $type . '</b></td>
                              <td >' . trans('text_hb.genre') . ' : <b>' . $genre . '</b></td>
                               <td >' . trans('text_hb.sit_fam') . ' : <b>' . $sit_f . '</b></td>
                             </tr>
                             
                             
                     </table>';

        $info = '<div class="filter_exp">' . $filter . '</div>';
        $html .= $info;
        return $html;
    }

    public function exportEmployesPDF(Request $request)
    {
        $type = $request->type_contrat;
        $genre = $request->ref_genre_id;
        $sit_fam = $request->ref_situation_familliale_id;

        $employes = Employe::with('ref_genre', 'ref_types_contrat', 'ref_situation_familliale');

//  dd($employes=Employe::al);
        $f_genre = trans('text_hb.all');
        $f_type = trans('text_hb.all');
        $f_sit_fam = trans('text_hb.all');
        // $lib=trans('text_hb.libelle');
        $lib = 'libelle';
        $titre = "Liste des empoyes";
        $entete = $this->entete($titre, 'L');

        $header = array('nom et prénom', 'Genre', 'Situation Fam', 'Date de naissance', 'lieu de naissance', 'Type contrat', 'Function', 'Service');
        //$header =array('nom et prénom','Genre','Situation Famillaile','Date de naissance');

        //dd($liste_emp)
        if ($genre != 'all') {
            $employes = $employes->where('ref_genre_id', $genre);
            $f_genre = RefGenre::find($genre)->$lib;
        }
        if ($type != 'all') {
            $employes = $employes->where('ref_types_contrat_id', $type);
            $f_type = RefTypesContrat::find($type)->$lib;
        }
        if ($sit_fam != 'all') {
            $employes = $employes->where('ref_situation_familliale_id', $sit_fam);
            $f_sit_fam = RefSituationFamilliale::find($sit_fam)->$lib;
        }

        $employes = $employes->get();

      //  $liste_emp = $this->liste_employes1($header, $employes);

        $filter = $this->filter_export($f_type, $f_genre, $f_sit_fam);
        //$view = \View::make($this->module.'.export_empoyes',['employes'=>$employes,'genre'=>$f_genre,'type'=>$f_type,'sit_f'=>$f_sit_fam]);
        //$html_content = $view->render();

        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('liste des employes');
        PDF::SetSubject('liste des employes');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('L', 'A4');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($entete, true, false, true, false, '');
        $this->titre_block('filtre', 'L');
        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($filter, true, false, true, false, '');
        //PDF::writeHTML($this->liste_employes($header,$employes), true, false, true, false, '');
        // PDF::writeHTML($html_content, true, false, true, false, '');
        $this->liste_employes($header,$employes);
        PDF::Output(uniqid() . 'liste_emp.pdf');

    }

    public function edit(EmployeRequest $request)
    {
        //dd($request);
        $employe = Employe::find($request->id);

        // etat civil
        $employe->prenom = $request->prenom;
        $employe->nom = $request->nom;
        $employe->nom_famille = $request->nom_famille;
        $employe->ref_genre_id = $request->ref_genre_id;
        $employe->nni = $request->nni;
        $employe->date_naissance = $request->date_naissance;
        $employe->lieu_naissance = $request->lieu_naissance;
        if (isset($request->ref_situation_familliale_id))
            $employe->ref_situation_familliale_id = $request->ref_situation_familliale_id;
        else
            $employe->ref_situation_familliale_id = null;

        $employe->prenom_ar = $request->prenom_ar;
        $employe->nom_ar = $request->nom_ar;
        $employe->nom_famille_ar = $request->nom_famille_ar;


        // contact
        $employe->adress = $request->adresse;
        $employe->tel = $request->tel;
        $employe->email = $request->email;
        $employe->whatsapp = $request->whatsapp;
        $employe->prenom_personne = $request->prenom_pr;
        $employe->nom_personne = $request->nom_pr;
        $employe->tel_personne = $request->tel_pr;

        // formation
        if (isset($request->niveau_etude))
            $employe->ref_niveau_etude_id = $request->niveau_etude;
        else
            $employe->ref_niveau_etude_id = null;
        if (isset($request->specialite))
            $employe->specialite_id = $request->specialite;
        else
            $employe->specialite_id = null;

        // mise en position

        $employe->code = $request->code;
        $employe->date_embauche = $request->date_emp;
        $employe->titre = $request->titre;
        if ($request->fonction != null)
            $employe->ref_fonction_id = $request->fonction;
        else
            $employe->ref_fonction_id = null;

        $employe->ref_types_contrat_id = $request->type_contrat;
        $employe->salaire_mensuel = $request->salaire;

        if ($request->servies_rattachement != null)
            $employe->service_id = $request->servies_rattachement;
        else
            $employe->service_id = null;

        $employe->ref_appreciations_hierarchie_id = $request->ap_heir;
        $employe->taches = $request->taches;
        $employe->commentaires = $request->commentaire;

        $employe->save();
        $full_name = $employe->prenom . ' ' . $employe->nom . ' ' . $employe->nom_famille;
        $surname = "@ " . $employe->prenom . $employe->nom;

        $resultat = ' <p class="username "><a href="#" class="social-icon facebook"><i class="fa fa-envelope"></i></a>
                             ' . $employe->email . '
                        </p>
                        <p class="username " ><a href="#" class="social-icon whatsapp"><i class="fab fa-whatsapp"></i></a>
                          ' . $employe->whatsapp . '
                        </p>';
        if (Auth::user()->hasAccess([6], 4)) {
            $resultat .= ' <p class="username " ><a href="#" onclick="get_fiche_pdf()"  class="social-icon linkedin"><i class="fa fa-print"></i></a>
                        ' . trans("text_hb.imprimer_fiche") . '
                          </p>';
        }

        $data = array('full_name' => $full_name, 'surname' => $surname, 'titre' => $employe->titre, 'info_right' => $resultat);
        return response()->json($data, 200);
    }

    public function leadhtml()
    {
        $data = "<table class='table'><tr><th>Titre1</th><th>Titre2</th><th>Titre3</th></tr><tr><td>ggggg</td><td>ggggg</td><td>ggggg</td></tr></table>";
        return view($this->module . '.test', ["data" => $data]);
    }

    public function test()
    {
        $header = array('nom et prénom', 'Genre', 'Situation Fam', 'Date de naissance', 'lieu de naissance', 'Type contrat', 'Function', 'Service');

        $tbl = $this->liste_employes1($header, '');
        //PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor('Author');
        PDF::SetTitle('TCPDF HTML Table');
        PDF::SetSubject('TCPDF Tutorial');
        PDF::SetKeywords('TCPDF, PDF, html,table, example, test, guide');

// set default header data
        PDF::SetHeaderData('', '', ' HTML table', '');

// set header and footer fonts
        PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
        PDF::SetMargins(15, 15, 15);
        PDF::SetHeaderMargin(15);
        PDF::SetFooterMargin(15);

// set auto page breaks
        PDF::SetAutoPageBreak(TRUE, 15);

// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// ---------------------------------------------------------
// set font
        PDF::SetFont('times', '', 10);

// add a page
        PDF::AddPage();


        PDF::writeHTML($tbl, true, false, false, false, '');


// reset pointer to the last page
//$pdf->lastPage();
// ---------------------------------------------------------
//Close and output PDF document
        PDF::Output('html_table.pdf', 'I');
    }

    public function titre_block($titre, $orientation = false)
    {
        $heith = 190;
        if ($orientation != false)
            $heith = 277;
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::SetFillColor(220, 220, 220);
        PDF::MultiCell($heith, 6, $titre, 0, 'L', true);
        $y = PDF::getY();
        //dd($set_formation);
        //$pdf::Cell(195, 150, '', 1, 1, 'C');
        //$pdf::Cell(60, 60, '', 1, 1, 'C');
        //$pdf::setXY(10,$y+5);


    }

    function info_fiche($set_emp)
    {
        $commune = ($set_emp->commune) ? $set_emp->commune->libelle : '';
        $genre = ($set_emp->ref_genre) ? $set_emp->ref_genre->libelle : '';
        $sit_fam = ($set_emp->ref_situation_familliale) ? $set_emp->ref_situation_familliale->libelle : '';
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';
        $img = 'img/avatar_2x.png';
        if (!empty($set_emp->photo))
            $img = $set_emp->photo;
        $date_nais = '';
        if (!empty($set_emp->date_naissance))
            $date_nais = Carbon::parse($set_emp->date_naissanc)->format('d-m-Y');
        $etat_civil = '<table class="etat_civil"><tr><td >' . trans('text_hb.nom_prenom') . '</td><td class="t_bold">' . $set_emp->prenom . '' . $set_emp->nom . '' . $set_emp->nom_famille . '</td></tr>
                              <tr><td >' . trans('text_hb.nni') . '</td><td class="t_bold">' . $set_emp->nni . '</td></tr>
                               <tr><td >' . trans('text_hb.date_naissance') . '</td><td class="t_bold">' . $date_nais . '</td></tr>
                              <tr><td >' . trans('text_hb.lieu_naissance') . '</td><td class="t_bold">' . $commune . '</td></tr>
                              <tr><td >' . trans('text_hb.genre') . '</td><td class="t_bold">' . $genre . '</td></tr>
                              <tr><td >' . trans('text_hb.sit_fam') . '</td><td class="t_bold">' . $sit_fam . '</td></tr>
                             
                     </table>';
        $info = '<div class="test"><table class="panel"><tr><td  class="td_img"><img width="160" height="120" src="' . $img . '" alt="avatar"  /></td><td class="td_info">' . $etat_civil . ' </td></tr></table></div>';
        $html .= $info;
        return $html;

    }

    public function info_contact($set_emp)
    {
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';
        $contact = '<table class="contact"><tr><td >' . trans('text_hb.tel') . '</td><td class="t_bold">' . $set_emp->tel . '</td></tr>
                              <tr><td >' . trans('text_hb.adresse') . '</td><td class="t_bold">' . $set_emp->adresse . '</td></tr>
                               <tr><td >' . trans('text_hb.email') . '</td><td class="t_bold">' . $set_emp->email . '</td></tr>
                              <tr><td >' . trans('text_hb.whatsap') . '</td><td class="t_bold">' . $set_emp->whatsapp . '</td></tr>
                             
                             
                     </table>';
        $contact_pers = '<table ><tr><td class="t_bold" colspan="2"  class="pers_cont">**' . trans('text_hb.persone_urgence') . '</td></tr>
                              <tr><td >' . trans('text_hb.prenom') . '</td><td class="t_bold">' . $set_emp->prenom_personne . '</td></tr>
                               <tr><td >' . trans('text_hb.nom') . '</td><td class="t_bold">' . $set_emp->nom_personne . '</td></tr>
                              <tr><td >' . trans('text_hb.tel') . '</td><td class="t_bold">' . $set_emp->tel_personne . '</td></tr>
                             
                             
                     </table>';
        $info = '<div class="test"><table class="contact" ><tr><td>' . $contact . '</td><td >' . $contact_pers . ' </td></tr></table></div>';
        $html .= $info;
        return $html;
    }

    public function formation($set_emp)
    {

        $specialite = ($set_emp->specialite) ? $set_emp->specialite->libelle : '';
        $niveau_etude = ($set_emp->ref_niveau_etude) ? $set_emp->ref_niveau_etude->libelle : '';
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';


        $info = '<div class="test"><table class="formation"><tr><td >' . trans('text_hb.specialite') . '</td><td class="t_bold">' . $specialite . '</td>
                            <td >' . trans('text_hb.niveau_etude') . '</td><td class="t_bold">' . $niveau_etude . '</td></tr>
                              
                             
                             
                     </table></div>';
        $html .= $info;
        return $html;
    }

    public function position_act($set_emp)
    {
        $fonction = ($set_emp->ref_fonction) ? $set_emp->ref_fonction->libelle : '';
        $types_contrat = ($set_emp->ref_types_contrat) ? $set_emp->ref_types_contrat->libelle : '';
        $ap = ($set_emp->ref_appreciations_hierarchy) ? $set_emp->ref_appreciations_hierarchy->libelle : '';
        $html = '<style>' . file_get_contents(url('css/style_pdf.css')) . '</style>';
        $contact = '<table ><tr><td >' . trans('text_hb.code') . '</td><td class="t_bold">' . $set_emp->code . '</td></tr>
                              <tr><td >' . trans('text_hb.date_emp') . '</td><td class="t_bold">' . $set_emp->date_embauche . '</td></tr>
                               <tr><td >' . trans('text_hb.fonction') . '</td><td class="t_bold">' . $fonction . '</td></tr>
                              <tr><td >' . trans('text_hb.type_contrat') . '</td><td class="t_bold">' . $types_contrat . '</td></tr>
                              <tr><td >' . trans('text_hb.salaire_mensuel') . '</td><td class="t_bold">' . $set_emp->salaire_mensuel . '</td></tr>
                             
                             
                     </table>';
        $contact_pers = '<table ><tr><td  >' . trans('text_hb.titre') . '</td><td class="t_bold">' . $set_emp->titre . '</td></tr>
                              <tr><td >' . trans('text_hb.service') . '</td><td class="t_bold">' . $set_emp->servies_rattachement . '</td></tr>
                               <tr><td >' . trans('text_hb.Appreciation_heirarchie') . '</td><td class="t_bold">' . $ap . '</td></tr>
                              <tr><td >' . trans('text_hb.tache') . '</td><td class="t_bold">' . $set_emp->taches . '</td></tr>
                             
                     </table>';
        $info = '<div class="test"><table class="position_act" ><tr><td>' . $contact . '</td><td >' . $contact_pers . ' </td></tr></table></div>';
        $html .= $info;
        return $html;
    }

    public function fiche_pdf(Request $request)
    {

        $id_emp = $request->id_emp;
        $set_emp = Employe::find($id_emp);
        $entete = $this->entete('Fiche de l\'employé ');
        $info = $this->info_fiche($set_emp);
        $contact = $this->info_contact($set_emp);
        $formation = $this->formation($set_emp);
        $position_act = $this->position_act($set_emp);

        PDF::SetAuthor('SIDGCT');
        PDF::SetTitle('Fiche d\'employé');
        PDF::SetSubject('Fiche d\'employé');
        PDF::SetMargins(10, 10, 10);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('10px');
        PDF::SetAutoPageBreak(TRUE);
        PDF::AddPage('P', 'A4');


        PDF::SetFont('dejavusans', '', 10);
        PDF::writeHTML($entete, true, false, true, false, '');

        $this->titre_block('Etat civile');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($info, true, false, true, false, '');
        $this->titre_block('Info de Contact');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($contact, true, false, true, false, '');

        $this->titre_block('Formation');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($formation, true, false, true, false, '');

        $this->titre_block('Position actuelle');
        PDF::SetFont('dejavusans', '', 9);
        PDF::writeHTML($position_act, true, false, true, false, '');
        // PDF::writeHTML($html_content, true, false, true, false, '');

        PDF::Output(uniqid() . 'fiche_emp.pdf');
    }

    public function editImage(Request $request)
    {
        {
            $id = $request->id;
            $employe = Employe::find($id);
            //
            if ($request->fichier) {
                // dd($request->fichier);
                $emplacement = "/files/employes/";
                $extension = $request->file('fichier')->getClientOriginalExtension();
                $imageName = 'emp_' . $id . '.' . $request->file('fichier')->getClientOriginalExtension();
                $path = $emplacement . $imageName;
                $employe->photo = $path;
                $employe->save();
                $request->file('fichier')->move(
                    base_path() . '/public/files/employes', $imageName
                );

            }

            // $link = url("redirectto/requestlissements/".$specialite->id);
            return response()->json($path, 200);
        }
    }

    public function get($id)
    {
        $employe = Employe::find($id);
        $tablink = $this->module . '/getTab/' . $id;
        $tabs = [

            '<i class="fa fa-info-circle"></i> ' . trans('text.info') => $tablink . '/1',
            '<i class="fas fa-i-cursor"></i> ' . trans('text_hb.parcours') => $tablink . '/3',
            '<i class="fa fa-file-archive"></i> ' . trans('text_hb.documents') => $tablink . '/2',

        ];
        $modal_title = '<b>' . $employe->prenom . '</b>';
        return view('tabs', ['tabs' => $tabs, 'modal_title' => $modal_title]);
    }

    public function getTab($id, $tab)
    {
        $employe = Employe::find($id);

//dd($employe);
        switch ($tab) {
            case '1':
                $refGenres = RefGenre::all();
                $refSituationFamilliales = RefSituationFamilliale::all();
                $refNiveauEtudes = RefNiveauEtude::all();
                $specialites = Specialite::all();
                $refFonctions = RefFonction::all();
                $rfTypesContrats = RefTypesContrat::all();
                $refAppreciationsHierarchys = RefAppreciationsHierarchy::all();
                $services = App\Models\Service::where('commune_id', env('APP_COMMUNE'))->get();
                $communes = Commune::all();
                $parametres = ['employe' => $employe, 'refGenres' => $refGenres,
                    'refSituationFamilliales' => $refSituationFamilliales,
                    'refNiveauEtudes' => $refNiveauEtudes,
                    'specialites' => $specialites,
                    'refFonctions' => $refFonctions,
                    'rfTypesContrats' => $rfTypesContrats,
                    'services' => $services,
                    'communes' => $communes,
                    'refAppreciationsHierarchys' => $refAppreciationsHierarchys,];
                break;
            case 2:
                $parametres = ['employe' => $employe];
                break;
            case 3:
                $parametres = ['employe' => $employe];
                break;
            default :
                $parametres = ['employe' => $employe];
                break;
        }
        return view($this->module . '.tabs.tab' . $tab, $parametres);
    }

    public function delete($id)
    {
        $employe = Employe::find($id);
        /*if ($employe->id)
            return response()->json(['success' => 'false', 'msg' => trans('text.famille_cant_be_del_bcuz_of_articles')], 200);
        else {*/
            $employe->delete();
            return response()->json(['success' => 'true', 'msg' => trans('text.element_well_deleted')], 200);
       /* }*/
    }
}
