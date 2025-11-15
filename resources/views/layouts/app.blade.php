<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <!-- nav -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- навигация\лого -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">Мой блог</a>
                    </div>
                    <!-- ссылки  -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('posts.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">Все посты</a>

                        <!-- меню -->
                        <div class="relative" id="categories-dropdown">
                            <button type="button" id="categories-toggle" class="inline-flex items-center px-1 pt-2 mt-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Категории
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div id="categories-menu" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden">
                                <div class="py-1">
                                    @foreach(\App\Models\Category::all() as $category)
                                        <a href="{{ route('posts.category', $category->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">О сайте</a>
                    </div>
                </div>
                <!-- пользователь -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('profile.show') }}" class="text-sm text-gray-700 hover:text-gray-900">
                                {{ Auth::user()->name }}
                            </a>
                            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">Создать пост</a>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                    выйти
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Войти</a>
                            <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">Регистрация</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- посты -->
    <main>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm-rounded-lg">
                    <div class="p-6 text-gray-900">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        let categoriesToggle = document.querySelector('#categories-toggle');
        let categoriesMenu = document.querySelector('#categories-menu');

        if (categoriesToggle && categoriesMenu) {

            function toggleCategoriesMenu() {
                if (categoriesMenu.classList.contains('hidden')) {
                    categoriesMenu.classList.remove('hidden');
                } else {
                    categoriesMenu.classList.add('hidden');
                }
            }

            function hideCategoriesMenu() {
                categoriesMenu.classList.add('hidden');
            }

            categoriesToggle.addEventListener('click', function(evt) {
                evt.stopPropagation();
                toggleCategoriesMenu();
            });

            categoriesMenu.addEventListener('click', function(evt) {
                evt.stopPropagation();
            });

            document.addEventListener('click', function() {
                hideCategoriesMenu();
            });

            document.addEventListener('keydown', function(evt) {
                if (evt.key === 'Escape') {
                    hideCategoriesMenu();
                }
            });
        }
    });
</script>
</body>
</html>
<!--
/* Фон */
bg-white       /* белый */
bg-gray-100    /* светло-серый */
bg-blue-500    /* синий */
bg-red-500     /* красный */
bg-green-500   /* зеленый */

/* Текст */
text-white     /* белый */
text-gray-500  /* серый */
text-blue-500  /* синий */

/* Границы */
border-gray-200  /* светло-серая */
border-blue-500  /* синяя */

/* Padding (внутренние отступы) */
p-1  /* 0.25rem = 4px */
p-2  /* 0.5rem = 8px */
p-4  /* 1rem = 16px */
p-6  /* 1.5rem = 24px */

px-4  /* слева/справа */
py-2  /* сверху/снизу */

/* Margin (внешние отступы) */
m-4     /* со всех сторон */
mt-2    /* сверху */
mb-4    /* снизу */
ml-3    /* слева */
mr-4    /* справа */
mx-auto /* центрирование */

text-xs    /* 12px */
text-sm    /* 14px */
text-base  /* 16px */
text-lg    /* 18px */
text-xl    /* 20px */
text-2xl   /* 24px */
text-3xl   /* 30px */

font-normal   /* обычный */
font-medium   /* средний */
font-bold     /* жирный */

flex           /* гибкий контейнер */
flex-col       /* вертикальное направление */

justify-start  /* выровнять по началу */
justify-center /* выровнять по центру */
justify-between /* раздвинуть по краям */

items-start    /* выровнять по верху */
items-center   /* выровнять по центру вертикально */

space-x-4      /* горизонтальные отступы между элементами */
space-y-2      /* вертикальные отступы между элементами */

rounded       /* скругленные углы */
rounded-md    /* среднее скругление */
rounded-lg    /* большое скругление */

shadow-sm     /* маленькая тень */
shadow-md     /* средняя тень */
shadow-lg     /* большая тень */

transition duration-200  /* плавные переходы 200ms */

-->
