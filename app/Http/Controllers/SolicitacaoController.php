<?php

namespace App\Http\Controllers;

use App\Http\Services\ApiService;
use App\Http\Requests\WebappRequest;
use App\Models\Moderation;
use App\Models\Webapp;
use Illuminate\Http\Client\RequestException;

class SolicitacaoController extends Controller
{
    public function index(){
        $webapps = Webapp::where('user_id',auth()->user()->id)->get();
        return view('solicitacoes', ['webapps' => $webapps]);
    }

    public function edit(Webapp $webapp){
        return view('edit', ['webapp' => $webapp]);
    }

    public function update(Webapp $webapp, WebappRequest $request){ //colocar no webappcontroller?
        $validated = $request->validated();
        $webapp['status'] = "Solicitado";

        if($validated['tipo'] == 'outro_app'){
            $repo_version = new ApiService;
            $resultado = $repo_version->api($validated['url_github']);
            $status = $resultado['ok'];
            $content = $resultado['content'];

            if($status == false){
                return redirect("/edit/$webapp->id")
                ->withInput($validated)
                ->withErrors(['repo' => $content]);
            }
            $validated['version'] = $content;
        }

        $webapp->update($validated);

       session()->flash('alert-success','Seu pedido foi alterado. Aguardando revisão de um administrador');
       return redirect('/minhas-solicitacoes');
    }
}
