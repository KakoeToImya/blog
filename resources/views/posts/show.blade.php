@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('posts.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Назад к списку постов
        </a>
    </div>

    <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

        <header class="p-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

            @if($post->image)
                <div class="mb-6">
                    <img src="{{ Storage::url($post->image) }}" class="w-full h-auto rounded-lg shadow-md">
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                @if($post->category)
                    <div class="flex items-center">
                        <strong class="mr-2">Категория:</strong>
                        <a href="{{ route('posts.category', $post->category->slug) }}" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">{{ $post->category->name }}</a>
                    </div>
                @endif

                <div>
                    <strong>Автор:</strong>
                    @if($post->user)
                        {{ $post->user->name }}
                    @else
                        {{ $post->author_name }}
                    @endif
                </div>

                <div>
                    <strong>Опубликовано:</strong> {{ $post->created_at->format('d.m.Y в H:i') }}
                </div>
            </div>
        </header>

        <div class="p-6">
            <div class="prose max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>


        <footer class="p-6 border-t border-gray-200 bg-gray-50">

            <div class="flex flex-wrap gap-2">
                @auth
                    @if(auth()->id() === $post->user_id)
                        <a href="{{ route('posts.edit', $post->id) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition duration-200">
                            Редактировать пост
                        </a>

                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition duration-200" onclick="return confirm('Удалить этот пост?')">
                                Удалить пост
                            </button>
                        </form>
                    @endif
                @endauth

                <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">Все посты</a>
            </div>
        </footer>

    </article>


     <section class="mt-12">
        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Комментарии
                @if($post->comments->count() > 0)
                    <span class="text-lg font-normal text-gray-600">({{ $post->comments->count() }})</span>
                @endif
            </h2>

            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-8" enctype="multipart/form-data">
                    @csrf
                    <div  class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Ваш комментарий</label>
                        <textarea id="content" name="content" rows="4"class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"placeholder="Напишите ваш комментарий" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Прикрепить файл (необязательно)</label>
                        <input type="file" id="attachment" name="attachment" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" accept="image/*,.pdf,.doc,.docx">
                        <p class="text-xs text-gray-500 mt-1">Можно прикрепить изображение или документ</p>
                    </div>

                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition duration-200">
                        Оставить комментарий
                    </button>
                </form>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                    <p class="text-blue-800">
                        <a href="{{ route('login') }}" class="font-medium hover:underline">Войдите</a>
                        или
                        <a href="{{ route('register') }}" class="font-medium hover:underline">зарегистрируйтесь</a>,
                        чтобы оставить комментарий.
                    </p>
                </div>
            @endauth

            <div  class="space-y-6">
                @forelse($post->comments as $comment)
                    <div data-comment-id="{{ $comment->id }}"  class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $comment->user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $comment->created_at->format('d.m.Y в H:i') }}</p>
                                </div>
                            </div>

                            @auth
                                @if(auth()->id() === $comment->user_id)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Удалить этот комментарий?')">
                                            Удалить
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>

                        <p class="text-gray-700 leading-relaxed mb-4">{{ $comment->content }}</p>

                        @if($comment->attachment)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Прикрепленный файл:</p>
                                <a href="{{ Storage::url($comment->attachment) }}" target="_blank" class="inline-flex items-center text-blue-500 hover:text-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    {{ basename($comment->attachment) }}
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Пока нет комментариев. Будьте первым!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded',function (){
           function scrollToComment(commentId){
               let elComment = document.querySelector(`[data-comment-id="${commentId}"]`);

               if(elComment){
                   elComment.classList.add('highlight-comment');

                   let elPosition= elComment.getBoundingClientRect().top+window.scrollY;
                   let offsetPosition = elPosition - 120;

                   window.scrollTo({
                       top: offsetPosition,
                       behavior: 'smooth',
                   });

                   let url = new URL(window.location);
                   url.searchParams.delete('comment');
                   window.history.replaceState({},'',url);
                   setTimeout(()=>{
                      elComment.classList.remove('highlight-comment');
                   },2000);
               }
           }

           let urlParams= new URLSearchParams(window.location.search);
           let commentId = urlParams.get('comment');

           if(commentId){
               scrollToComment(commentId);
           }

           document.addEventListener('click', function (evt){
               let link = evt.target.closest('a[href*="comment"]');
               if(link && link.href.includes(window.location.origin)){
                   evt.preventDefault();

                   let url = new URL(link.href);

                   let comment_id = url.searchParams.get('comment');

                   if(url.pathname === window.location.pathname){
                       scrollToComment(comment_id);
                   }
                   else {
                       window.location.href = link.href;
                   }
               }
           })
        });
    </script>

@endsection
