<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SingUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class SignUpController extends Controller
{
    public function page(): View|Application|Factory
    {
        return view('auth.sign-up');
    }

    public function handle(SingUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {
        // TODO make DTOs
        $action(
            $request->get('name'),
            $request->get('email'),
            $request->get('password'),
        );

        return redirect()->intended(route('home'));
    }
}
