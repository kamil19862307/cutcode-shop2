<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Support\SessionRegenerator;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(NewUserDTO $data)
    {
        $user = User::query()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password),
        ]);

        event(new Registered($user));
//        В app/Providers/EventServiceProvider.php закомментировал   //SendEmailVerificationNotification::class,
//        пока по этому эвенту ничего отправлять не будем

        // Сохраняем корзину, если новый пользователь регистрируется на сайте.
        SessionRegenerator::run(fn() => auth()->login($user));


    }
}
