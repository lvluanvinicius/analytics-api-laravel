<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @yield('head')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            padding: 0;
            margin: 0 auto;
        }
    </style>
</head>

<body class="bg-purple-950">

    <div class="flex justify-center items-center h-[100vh] w-[100vw]">

        <div class="bg-zinc-700 w-[80%] h-[90%] flex flex-nowrap md:flex-wrap rounded-lg">

            <div class="w-[60%]"></div>

            <div class="border-l border-l-white/10 w-[40%]">
                <div class="px-6 py-10 h-full">

                    <h1 class="font-bold text-dracula-light text-[2.1rem] h-24 px-8 ">Entrar</h1>

                    <div class="mt-4 h-[50vh] w-full px-8">
                        <form action="{{ route('login.auth') }}" method="POST" class="">
                            @csrf

                            <div class="text-white font-bold text-[1rem] w-full">
                                <label for="username" class="opacity-75">Usuário:</label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}"
                                    class="outline-none w-full mt-1 bg-transparent rounded-md shadow-md focus:border-none" />
                            </div>

                            <div class="text-white font-bold text-[1rem] w-full mb-4 mt-4">
                                <label for="password" class="opacity-75">Senha: </label>
                                <input type="password" name="password" id="password"
                                    class="outline-none w-full mt-1 bg-transparent rounded-md shadow-md focus:border-none"/tests>
                            </div>

                            <div class="text-white text-[.9rem] w-full my-4 pt-4 text-center">
                                <hr class="opacity-20" />
                                @if ($errors->has('username'))
                                    @foreach ($errors->get('username') as $message)
                                        <span class="text-red-600">{{ $message }}</span>
                                    @endforeach
                                @endif


                            </div>


                            <div class="text-white font-bold text-[1rem] w-full my-4 relative">
                                <button type="submit"
                                    class="bg-slate-500 w-full py-2 rounded-md shadow-md">Entrar</button>
                            </div>

                        </form>
                    </div>

                    <div class="text-center w-full px-8">
                        <a href="#" target="_blank"
                            class="text-cyan-600/70 tracking-tighter font-bold text-[1.2rem]">
                            DNSecury Services
                        </a>
                    </div>

                </div>
            </div>

        </div>

        @if ($errors->has('password'))
            @foreach ($errors->get('password') as $message)
                <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                    </svg>
                    <p>Something happened that you should know about.</p>
                </div>
            @endforeach
        @endif

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    @yield('js-content')
</body>

</html>
