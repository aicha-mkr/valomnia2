<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticDataController extends Controller
{
     // Données statiques de type et de date
     private $staticData = [
        [
            'type' => 'Type1',
            'date' => '2023-01-01',
        ],
        [
            'type' => 'Type2',
            'date' => '2023-03-15',
        ],
        [
            'type' => 'Type3',
            'date' => '2023-07-10',
        ],
        // Ajoutez autant de données statiques que nécessaire
    ];

    // Méthode pour récupérer toutes les données statiques
    public function getAllStaticData()
    {
        return $this->staticData;
    }

    // Méthode pour obtenir des données statiques par type
    public function getDataByType($type)
    {
        $filteredData = array_filter($this->staticData, function ($data) use ($type) {
            return $data['type'] === $type;
        });

        return $filteredData;
    }

    // Méthode pour obtenir une date spécifique par type
    public function getDateByType($type)
    {
        $filteredData = array_filter($this->staticData, function ($data) use ($type) {
            return $data['type'] === $type;
        });

        $dates = array_column($filteredData, 'date');
        return $dates ?: "Aucune date trouvée pour ce type";
    }
}
