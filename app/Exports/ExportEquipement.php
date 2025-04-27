<?php
namespace App\Exports;
use App\Models\Equipement;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class ExportEquipement implements FromView,ShouldAutoSize
{
    public $html1;
    public function __construct($html1)
    {
        $this->html1=$html1;
    }

    public function view():View
    {
        $html21=$this->html1;
       return view('editions.export_resultat',['html1'=>$html21]);
    }
}
