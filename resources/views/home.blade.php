@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Добро пожаловать в мой блог!</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Это мой первый блог на Laravel. Здесь я учусь веб-разработке и создаю интересный контент.
        </p>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Последние посты</h2>
        
        @if($posts->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @foreach($posts as $post)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-200">
                   
                    <div class="p-6">
                        @if($post->category)
                            <div class="mb-3">
                                <a href="{{ route('posts.category', $post->category->slug) }}" class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $post->category->name }}
                                </a>
                            </div>
                        @endif
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <span>
                                @if($post->user)
                                    {{ $post->user->name }}
                                @else
                                    {{ $post->author_name }}
                                @endif
                            </span>
                            <span class="mx-2">•</span>
                            <span>{{ $post->created_at->format('d.m.Y') }}</span>
                        </div>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $post->excerpt }}
                        </p>
                        
                        <a href="{{ route('posts.show', $post->id) }}" class="inline-flex items-center text-blue-500 hover:text-blue-700 font-medium">
                            Читать далее
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">Пока нет постов</p>
                @auth
                    <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-200">
                        Создать первый пост
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">
                        Зарегистрируйтесь чтобы создать пост
                    </a>
                @endauth
            </div>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('posts.index') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg transition duration-200">
            Смотреть все посты
        </a>
    </div>
@endsection