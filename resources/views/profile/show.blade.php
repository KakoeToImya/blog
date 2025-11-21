@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <p class="text-sm text-gray-500">
                                Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px" id="tabs">
                        <button data-tab="posts" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-blue-500 border-blue-500">Мои посты {{ $posts->total() }}</button>
                        <button data-tab="comments" class="tab-button py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">Прокомментированные посты {{ $comments->total() }}</button>
                    </nav>
                </div>

                <div class="p-6">
                    <div id="posts-content" class="tab-content">
                        @if($posts->count())
                            <div class="space-y-6">
                                @foreach($posts  as $post)
                                    <article class="border-b border-gray-200 pb-6">
                                        <h2 class="text-xl font-semibold mb-2">
                                            <a href="{{ route('posts.show', $post) }}"
                                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $post->title }}
                                            </a>
                                        </h2>
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <div class="flex items-center">
                                                <strong class="mr-2">Категория:</strong>
                                                @if($post->category)
                                                    <a href="{{ route('posts.category', $post->category->slug) }}" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">{{ $post->category->name }}</a>
                                                @else
                                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">Без категории</span>
                                                @endif
                                            </div>
                                            <span class="mx-2"> </span>
                                            <span>Автор: {{ $post->user->name }}</span>
                                            <span class="mx-2"> </span>
                                            <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <p class="text-gray-700 line-clamp-3">
                                            {{ Str::limit($post->excerpt ?? $post->content, 200) }}
                                        </p>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $posts->links() }}
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Вы еще не написали ни одного поста.
                            </p>
                        @endif
                    </div>



                    <div id="comments-content" class="tab-content hidden">
                        @if($comments->count())
                            <div class="space-y-6">
                                @foreach($comments as $comment)
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                    <a href="{{ route('posts.show', ['id' => $comment->post->id, 'highlight_comment' => $comment->id]) }}" class="hover:text-blue-600 transition duration-200">
                                                        {{ $comment->post->title }}
                                                    </a>
                                                </h3>
                                                <div class="flex items-center space-x-3 text-sm text-gray-600">
                                                    <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $comment->post->category->name }}</span>
                                                    <span>Автор: {{ $comment->post->user->name }}</span>
                                                    <span>•</span>
                                                    <span>{{ $comment->post->created_at->format('d.m.Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-4 mb-4 border-l-4 border-blue-500">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-700"> Ваш комментарий:</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            <p class="text-gray-700 whitespace-pre-line">{{ $comment->content }}</p>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('posts.show', ['id' => $comment->post->id, 'highlight_comment' => $comment->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Перейти к комментарию
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Пагинация -->
                            <div class="mt-8">
                                {{ $comments->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="text-gray-400 text-6xl mb-4">💬</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Пока нет комментариев</h3>
                                <a href="{{ route('posts.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium transition duration-200">
                                    Читать посты
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let tabButtons = document.querySelectorAll('.tab-button');
            let tabContents = document.querySelectorAll('.tab-content');

            function switchTab(tabName) {
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                tabButtons.forEach(button => {
                    button.classList.remove('border-blue-500', 'text-blue-500');
                    button.classList.add('border-transparent', 'text-gray-500');
                });

                document.querySelector(`#${tabName}-content`).classList.remove('hidden');

                let activeButton = document.querySelector(`[data-tab="${tabName}"]`);
                activeButton.classList.add('border-blue-500', 'text-blue-500');
                activeButton.classList.remove('border-transparent', 'text-gray-500');
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    let tabName = this.getAttribute('data-tab');
                    switchTab(tabName);
                });
            });

            switchTab('posts');
        });


    </script>


@endsection
