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
                        <button data-tab="comments" class="tab-button py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">Прокомментированные посты {{ $commentedPosts->total() }}</button>
                    </nav>
                </div>

                <div class="p-6">
                    <div id="posts-content" class="tab-content">
                        @if($posts->count())
                            <div class="space-y-6">
                                @foreach($posts as $post)
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
                                            <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                            <span class="mx-2"> </span>
                                            <span>{{ $post->comments_count }} комментариев</span>
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
                        @if($commentedPosts->count())
                            <div class="space-y-6">
                                @foreach($commentedPosts as $post)
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
                                            <span>{{ $post->comments_count }} комментариев</span>
                                        </div>
                                        <p class="text-gray-700 line-clamp-3">
                                            {{ Str::limit($post->excerpt ?? $post->content, 200) }}
                                        </p>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $commentedPosts->links() }}
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Вы еще не оставили ни одного комментария.
                            </p>
                        @endif
                    </div>
                </div>
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
