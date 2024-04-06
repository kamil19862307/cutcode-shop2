<?php

namespace App\Http\Controllers\Auth;

use App\Events\AfterSessionRegenerated;
use App\Http\Controllers\Controller;
use App\Http\Requests\SingInFormRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Support\SessionRegenerator;

class SignInController extends Controller
{
    public function page(): View|Application|Factory|RedirectResponse
    {
        return view('auth.login');
    }

    public function handle(SingInFormRequest $request): RedirectResponse
    {
        if(!auth()->once($request->validated())){
            return back()->withErrors([
                'email' => 'Введенные данные не совпадают с имеющимися в базе.',
            ])->onlyInput('email');
        }

        SessionRegenerator::run(fn() => auth()->login(
            auth()->user()
        ));

        return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse
    {
        SessionRegenerator::run(fn() => auth()->logout());

        return redirect()->intended(route('home'));
    }
}
