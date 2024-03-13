<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Exive a tela de login.
     *
     * @return void
     */
    public function index()
    {
        return view('login.index')->with([
            "title" => env('APP_NAME'),
        ]);
    }

    /**
     * @author Luan Santos <lvluansantos@gmail.com>
     *
     * @param LoginRequest $request
     * @return void
     */
    public function auth(LoginRequest $request)
    {
        // Verificando se os dados foram preenchidos corretamente. $this->getMessage("apperror", "ErrorEmailNotInformed")
        if (in_array('', $request->only(["username", "password"]))) {
            return redirect()->back()->withInput([
                "username" => $request->username
            ]);
        }

        try {
            // Verificando se email e senha existem.
            if (!Auth::attempt($request->only(["username", "password"]))) {
                return redirect()->back()->withInput([
                    "username" => $request->username
                ]);
            }
        } catch (\PDOException $err) {
            dd($err);
            if (\str_contains($err->getMessage(), "Connection refused") || $err->getCode() == 2002) {
                return redirect()->back()->withInput([
                    "username" => $request->username
                ]);
            }
        }

        // Retorna home se os dados baterem com os do db.
        return redirect()->route("");
    }
}