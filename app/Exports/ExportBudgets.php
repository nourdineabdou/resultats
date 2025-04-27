<?php


namespace App\Exports;


use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FinanceLocaleController;
use App\Models\Budget;
use App\Models\Commune;
use App\Models\EnteteCommune;
use App\Models\Equipement;
use App\Models\NomenclatureElement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBudgets implements FromView,ShouldAutoSize
{
    public $id_bdg;
    public $niveau;
    public $classe;
    public function __construct($id_bdg,$niveau,$classe)
{
    $this->id_bdg=$id_bdg;
    $this->niveau=$niveau;
    $this->classe=$classe;
}

    public function view():View
{
    $finance = new FinanceLocaleController();
    $id = env('APP_COMMUNE');
    $commune = Commune::find($id);
    $entete_id = EnteteCommune::where('commune_id', $id)->get()->first()->id;
    $budgets = Budget::find($this->id_bdg);
    $entete = EnteteCommune::find($entete_id);
    $classe_libelle = $classe_libelle_ar = 'tous';
    $libelle_niveau = $finance->libelle_niveau($this->niveau);
    $htmlg='';
    $colonnes ='<table border="1" width="100%">
                    <thead>
                    <tr bgcolor="#87ceeb">
                    
                        <th width="10%"><b>'.trans("text_me.compte").'</b></th>
                        <th width="30%"><b>'.trans("text_me.libelle").'</b></th>
                        <th align="center" width="20%"><b>'.trans("text_me.montant").'</b></th>
                        <th width="30%" align="right"><b>'.trans("text_me.libelle_ar").' </b></th>
                        <th align="right" width="10%"><b>'.trans("text_me.compte_ar").'</b></th>
                    </tr>
                    </thead><tbody>';
    if ($this->niveau != 'all') {
        $array = NomenclatureElement::where('budget_id', $this->id_bdg)->where('niveau', '<=', $this->niveau)
            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
            ->select('nomenclature_elements.*', 'budget_details.montant')
            ->distinct()->get();
    } else {
        $array = NomenclatureElement::where('budget_id', $this->id_bdg)
            ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
            ->select('nomenclature_elements.*', 'budget_details.montant')
            ->distinct()->get();
    }
    if ($this->classe != 0 ) {
        $classes='';
        $exploded = explode(",", trim($this->classe));
        foreach ($exploded as $class) {
            $data = NomenclatureElement::find($class);
            $classes .=$data->code .',';
        }
        $classes  = rtrim( $classes , ",");
        $htmlg.='<div class="filter" >
                    <table width="100%">
                        <tr><td>'.trans('text_me.filtrage') .':</td><td></td><td align="right">:'. trans('text_me.filtrage') .'</td></tr>
                        <tr>
                            <td>
                                '.trans('text_me.classe') .' :
                                '.$classes.'
                            </td>
                            <td>
                                '. trans('text_me.niveau_affichage') .' :
                                '.$libelle_niveau.'
                                :'. trans('text_me.niveau_affichage_ar') .'
                            </td>
                            <td align="right">
                                '.$classes.'
                            </td>
                        </tr>
                    </table>
                </div><br>';
        $exploded = explode(",", trim($this->classe));
        foreach ($exploded as $clas) {
            $data = NomenclatureElement::find($clas);
            $classe_libelle = $data->libelle;
            $classe_libelle_ar = $data->libelle_ar;
            $montant = NomenclatureElement::where('budget_id', $this->id_bdg)->where('nomenclature_element_id', $clas)
                ->join('budget_details', 'nomenclature_elements.id', '=', 'budget_details.nomenclature_element_id')
                ->select('nomenclature_elements.*', 'budget_details.montant')
                ->distinct()->get()->first();
            $htmlg .= '<table width="100%">
                       <tr>
                            <td>
                                <b>
                                ' . trans('text_me.classe') . ' :
                                ' . $data->code . ' ' . $data->libelle . '
                                </b>
                            </td><td align="center">' . number_format($montant->montant, 2) . '</td>
                            <td align="right">
                                <b>
                                 ' . $data->libelle_ar . ' ' . $data->code . '
                                 </b>
                            </td>
                        </tr>
                    </table>';
            if ($this->niveau != 1) {
                $htmlg .= $colonnes;
                $html = $finance->showElmtsBudget($clas, $this->niveau, $array, $this->id_bdg);
                $htmlg .= '' . $html.'';
                $htmlg .= '</tbody></table>';
            }
        }
    }
    else
    {
        if($this->niveau != 'all'){
            $htmlg.='<div class="filter">
                         <table width="100%">
                            <tr>
                            <td>'.trans('text_me.filtrage') .':</td>
                            <td></td><td align="right">:'. trans('text_me.filtrage') .'</td>
                            </tr>
                            <tr>
                            <td>
                            '.trans('text_me.classe') .' :
                            '.trans('text_me.tous').'
                            </td>
                            <td>
                            '.trans('text_me.niveau_affichage') .':
                            '.$libelle_niveau.'
                            :'. trans('text_me.niveau_affichage_ar') .'
                            </td>
                            <td align="right">
                                '.trans('text_me.tous').'
                            </td>
                        </tr>
                    </table>
                </div>';
        }
        $htmlg.=$colonnes;
        $html = $finance->showElmtsBudget($this->classe, $this->niveau, $array, $this->id_bdg);
        $htmlg .=''.$html;
        $htmlg.='</table>';
    }
    $lib = ''.trans("text_me.exercice") .': '.$budgets->annee.'  : '. trans("text_me.exercice_ar");
    $conreoller = new EmployeController();
    $enetet = $conreoller->entete( $lib );
    $html1 ='';
    $html1 .=$enetet;
    $view = \View::make('finances.exports.exportPdfBudget', ['html' => $htmlg, 'commune' => $commune, 'budgets' => $budgets]);
    $html_content = $view->render();
    $html1 .= $html_content ;
    return view('finances.exports.export_resultat',['html1' =>$html1]);
}
}

