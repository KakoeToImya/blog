@extends('layouts.app')

@section('title', 'Категория: ' . $category->name)

@section('content')
    <h1>Посты в категории: "{{ $category->name }}"</h1>
    
    <div style="margin-bottom: 20px;">
        <a href="/posts" class="btn">← Все посты</a>
    </div>
    
    @if($posts->count() > 0)
        <div class="posts">
            @foreach($posts as $post)
            <div class="post">
                <h2>{{ $post->title }}</h2>
                
                <p><strong>Категория:</strong> 
                    <span style="background: #e9ecef; padding: 3px 8px; border-radius: 3px;">
                        {{ $category->name }}
                    </span>
                </p>
                
                <p><strong>Автор:</strong> {{ $post->author_name }}</p>
                <p><strong>Дата:</strong> {{ $post->created_at->format('d.m.Y H:i') }}</p>
                <p>{{ $post->excerpt }}</p>
                
                <a href="{{ route('posts.show', $post->id) }}" class="btn">Читать далее</a>
            </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px;">
            <h3>В этой категории пока нет постов</h3>
            <p>Будьте первым, кто напишет пост в категории "{{ $category->name }}"!</p>
            <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">Создать пост</a>
        </div>
    @endif
@endsection