@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Мои друзья</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Найти друзей</h2>
                    <form action="{{ route('friends.search') }}" method="GET" class="flex gap-4">
                        <input type="text" name="query" placeholder="введите имя или email" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                            Найти
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-2 py-4">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold">Мои друзья ({{ $friends->count() }})</h2>
                    </div>

                    @if($friends->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($friends as $friend)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $friend->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('friends.remove', $friend) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition duration-200" onclick="return confirm('Удалить {{ $friend->name }} из друзей?')">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Пока нет друзей</h3>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                @if($pending->count())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-2 py-4">
                        <h2 class="text-xl font-semibold mb-4">Входящие запросы</h2>
                        <div class="space-y-3">
                            @foreach($pending as $request)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-medium">{{ $request->user->name }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('friends.accept', $request->user) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button class="text-green-600 hover:text-green-800 text-sm">
                                                <p class="text-sm p-2">Принять</p>
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.reject', $request->user) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button class="text-red-600 hover:text-red-800 text-sm">
                                                <p class="text-sm p-2">Отклонить</p>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($sent->count())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold mb-4">Исходящие запросы</h2>
                        <div class="space-y-3">
                            @foreach($sent as $request)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-medium">{{ $request->friend->name }}</span>
                                    </div>
                                    <form action="{{ route('friends.cancel', $request->friend) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button class="text-red-600 hover:text-red-800 text-sm">
                                            Отменить
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
