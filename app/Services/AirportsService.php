<?php

namespace App\Services;

use App\Models\Airport;

class AirportsService
{
    private const MAX_AIRPORTS = 7;

    public function searchAirports($term) : array
    {
        if(strlen($term) < 3) return [];

        $columns = ['code', 'name_ru', 'name_en', 'city_ru', 'city_en', 'country_ru', 'country_en'];

        $query = Airport::whereLike($columns[0], '%'.$term.'%');

        foreach($columns as $column)
        {
            $query = $query->orWhereLike($column, '%'.$term.'%');
        }

        $result = [];
        foreach($query->get() as $airport)
        {
            if(count($result) >= self::MAX_AIRPORTS) break;
            if($airport->code == '' || $airport->country_ru == '' || $airport->city_ru == '') continue;

            $result[] = [
                'code' => $airport->code,
                'city' => $airport->city_ru,
                'country' => $airport->country_ru,
            ];
        }

        return $result;
    }
}
