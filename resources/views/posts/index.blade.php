@extends('layouts.app')

@section('title', 'Все посты')

@section('content')
    <div class="flex justify-between items-center mb-6">
        
        <h1 class="text-3xl font-bold text-gray-900">Все посты</h1>
        
        @auth
            <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">Создать пост</a>
        @endauth
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif
    
    @if($posts->count() > 0)
        <div class="space-y-6">

            @foreach($posts as $post)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                    {{ $post->title }}
                </h2>

                @if($post->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($post->image) }}" class="w-full h-48 object-cover rounded-lg">
                    </div>
                @endif
                
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                    
                    <div class="flex items-center">
                        <strong class="mr-2">Категория:</strong>
                        @if($post->category)
                            <a href="{{ route('posts.category', $post->category->slug) }}" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">{{ $post->category->name }}</a>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">Без категории</span>
                        @endif
                    </div>
                    
                    <div>
                        <strong>Автор:</strong> 
                        @if($post->user)
                            {{ $post->user->name }}
                        @else
                            {{ $post->author_name }}
                        @endif
                    </div>
                    
                    <div>
                        <strong>Дата:</strong> {{ $post->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>
                
                <p class="text-gray-700 mb-4 leading-relaxed">
                   
                    {{ $post->excerpt }}
                </p>
                
                <div class="flex flex-wrap gap-2">
                    
                    <a href="{{ route('posts.show', $post->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">Читать</a>
                    
                    @auth
                        @if(auth()->id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition duration-200">Редактировать</a>
                            
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition duration-200"onclick="return confirm('Удалить этот пост?')">
                                    Удалить
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <h3 class="text-xl text-gray-600 mb-4">Пока нет постов</h3>
            @auth
                <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200">Создать первый пост!</a>
            @else
                <p class="text-gray-600">Войдите в систему, чтобы создать пост</p>
            @endauth
        </div>
    @endif
@endsection