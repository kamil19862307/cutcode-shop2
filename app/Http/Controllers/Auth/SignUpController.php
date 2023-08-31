<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SingUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
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
        $action(NewUserDTO::fromRequest($request));

        return redirect()->intended(route('home'));
    }
}
