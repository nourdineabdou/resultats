

        namespace App\Exports;

        use App\Models\Equipement;
        use Maatwebsite\Excel\Concerns\FromView;
        use Illuminate\Contracts\View\View;
        use Maatwebsite\Excel\Concerns\ShouldAutoSize;

        class ExportBudget implements FromView, ShouldAutoSize
        {
            public $type;
            public $secteur;
            public $localite;

            public function __construct($type, $secteur, $localite)
            {
                $this->type = $type;
                $this->secteur = $secteur;
                $this->localite = $localite;
            }

            public function view(): View
            {
        $equipements = Equipement::with('ref_types_equipement','secteur','localite')->where('active',1)->get();
        if ($this->type != 'all')
            $equipements = $equipements->where('ref_types_equipement_id', $this->type );
        if ($this->secteur != 'all')
            $equipements = $equipements->where('secteur_id', $this->secteur);
        if ($this->localite != 'all')
            $equipements = $equipements->where('localite_id', $this->localite);
       return view('equipements.export_resultat',['equipements' =>$equipements]);
    }
}
