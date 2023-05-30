<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\JiraAtlassian\Requests as JiraRequests;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class JiraAtlassianController extends Controller
{
    use ApiResponser;

    public function requestSla(Request $request)
    {
        // Verifica se o parâmetro jiratoken foi informado com o token no header.
        if (!$request->headers->has('jiratoken')) {
            return $this->error("O parametro 'jiratoken' no header é necessário.");
        }

        // Verifica se o parâmetro email foi informado no body.
        if (!$request->has('email')) {
            return $this->error("Informe o 'email' no body é necessário.");
        }

        // Verifica se o parâmetro location foi informado no body.
        if (!$request->location) {
            return $this->error("Informe o 'location' no body é necessário.");
        }

        $jirsRequest = new JiraRequests($request->get('location'), $request->get('email'), $request->headers->get('jiratoken'));

        return $this->success($jirsRequest->reqSla(), 'Dados recuperados no Jira Atlassian.');
    }
}
