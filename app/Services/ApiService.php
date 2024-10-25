<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{

    // https://www.omdbapi.com/?s=dark&apikey=de079f19
    // https://www.omdbapi.com/?t=the&apikey=de079f19

    public function getData($searchType, $searchQuery, $page, $perPage)
    {
        try {

            $params = '';

            if($searchType=='word' && $searchQuery!='') {
                $params .= '&s='.trim($searchQuery);
            }
            else if($searchType=='imdbID' && $searchQuery!='') {
                $params .= '&i='.trim($searchQuery);
            }

            if($page!='') {
                $params .= '&page='.$page;
            }

            // $params .= '&s=dark';
            $response = Http::get('https://www.omdbapi.com/?apikey=de079f19'.$params);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            return ['error' => 'Unable to fetch data'];
        }
        return null;
    }
    public function getDetailData($imdbID)
    {
        try {

            $params = '&i='.$imdbID;

            $response = Http::get('https://www.omdbapi.com/?apikey=de079f19'.$params);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            return ['error' => 'Unable to fetch data'];
        }
        return null;
    }

    public function postData($data)
    {
        $response = Http::post('https://api.example.com/data', $data);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
