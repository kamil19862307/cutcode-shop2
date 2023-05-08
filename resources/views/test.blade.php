<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite([
        'resources/css/app.css',
        'resources/sass/main.sass',
        'resources/js/app.js',
        ])
</head>
<body>

    <main class="md:min-h-screen md:flex md:items-center md:justify-center py-16 lg:py-20">
        <div class="container">

            <!-- Page heading -->
            <div class="text-center">
                <a href="index.html" class="inline-block" rel="home">
                    <img src="{{Vite::asset('resources/images/logo.svg')}}" class="w-[148px] md:w-[201px] h-[36px] md:h-[50px]" alt="CutCode">
                </a>
            </div>

            <div class="max-w-[640px] mt-12 mx-auto p-6 xs:p-8 md:p-12 2xl:p-16 rounded-[20px] bg-purple">
                <h1 class="mb-5 text-lg font-semibold">Вход в аккаунт</h1>
                <form class="space-y-3">
                    <input type="email" class="w-full h-14 px-4 rounded-lg border border-[#A07BF0] bg-white/20 focus:border-pink focus:shadow-[0_0_0_2px_#EC4176] outline-none transition text-white placeholder:text-white text-xxs md:text-xs font-semibold" placeholder="E-mail" required>
                    <input type="password" class="w-full h-14 px-4 rounded-lg border border-[#A07BF0] bg-white/20 focus:border-pink focus:shadow-[0_0_0_2px_#EC4176] outline-none transition text-white placeholder:text-white text-xxs md:text-xs font-semibold" placeholder="Пароль" required>
                    <button type="submit" class="w-full btn btn-pink">Войти</button>
                </form>
                <div class="space-y-3 mt-5">
                    <div class="text-xxs md:text-xs"><a href="lost-password.html" class="text-white hover:text-white/70 font-bold">Забыли пароль?</a></div>
                    <div class="text-xxs md:text-xs"><a href="register.html" class="text-white hover:text-white/70 font-bold">Регистрация</a></div>
                </div>
                <ul class="flex flex-col md:flex-row justify-between gap-3 md:gap-4 mt-14 md:mt-20">
                    <li>
                        <a href="#" class="inline-block text-white hover:text-white/70 text-xxs md:text-xs font-medium" target="_blank" rel="noopener">Пользовательское соглашение</a>
                    </li>
                    <li class="hidden md:block">
                        <div class="h-full w-[2px] bg-white/20"></div>
                    </li>
                    <li>
                        <a href="#" class="inline-block text-white hover:text-white/70 text-xxs md:text-xs font-medium" target="_blank" rel="noopener">Политика конфиденциальности</a>
                    </li>
                </ul>
            </div>

        </div>
    </main>

</body>
</html>
