<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SingInFormRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class SignInController extends Controller
{
    public function page(): View|Application|Factory|RedirectResponse
    {
        return view('auth.login');
    }

    public function handle(SingInFormRequest $request): RedirectResponse
    {
        //TODO ratelimit
        if(!auth()->attempt($request->validated(), true)){
            return back()->withErrors([
                'email' => 'Введенные данные не совпадают с имеющимися в базе.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->intended(route('home'));
    }
}
