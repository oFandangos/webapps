<?php

namespace App\Http\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ApiService
{
    public function api($repo){
        try{
            $url = parse_url($repo);
            $segmentos = explode('/',$url['path']);
            $owner = $segmentos[1];
            $repo = $segmentos[2];
            $url_completa = "https://api.github.com/repos/$owner/$repo/tags";

            $response = Http::withToken(config('webapps.token'))
            ->get("$url_completa")->throw();

            if(!$response->json()){
                return [
                    'ok' => false, 
                    'content' => 'Não foi encontrada uma tag no repositório'
                ];
            } 

            return [
                'ok' => true,
                'content' => $response->json()[0]['name']
            ];

        }catch(\Exception $e){
            if(empty($segmentos[1]) || empty($segmentos[2])){
                return [
                    'ok' => false,
                    'content' => 'A URL precisa conter o usuário e o repositório.'
                ];
            }

            if($e->response->status() == 404){
                return [
                    'ok' => false,
                    'content' => 'O repositório não foi encontrado. Verifique a visibilidade do repositório.'
                ];
            }
        }
    }
}
