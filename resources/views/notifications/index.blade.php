@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                Мои уведомления
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="text-lg font-normal text-gray-600">
                    ({{ auth()->user()->unreadNotifications->count() }} новых)
                </span>
                @endif
            </h1>

            <div class="flex space-x-4">
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        @method('POST')
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            Прочитать все
                        </button>
                    </form>
                @endif

                @if(auth()->user()->notifications->count() > 0)
                    <form action="{{ route('notifications.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200" onclick="return confirm('Удалить все уведомления?')">
                            Очистить все
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if($notifications->count())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                @foreach($notifications as $notification)
                    <div class="border-b border-gray-200 last:border-b-0 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                        <div class="p-4 flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    @if(!$notification->read_at)
                                        <span class="h-2 w-2 bg-blue-500 rounded-full" title="Новое уведомление"></span>
                                    @endif

                                    <div class="flex-1">
                                        <p class="text-gray-900 {{ $notification->read_at ? '' : 'font-semibold' }}">
                                            {{ $notification->data['message'] ?? 'Уведомление' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('friends.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition duration-200">Перейти</a>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                            Прочитано
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <div class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Нет уведомлений</h3>
            </div>
        @endif
    </div>
@endsection
