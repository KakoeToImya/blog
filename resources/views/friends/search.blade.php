@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Поиск друзей</h1>

            <form action="{{ route('friends.search') }}" method="GET" class="mt-4 flex gap-4">
                <input type="text" name="query" value="{{ $query }}" placeholder="Введите имя или email" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Найти
                </button>
                <a href="{{ route('friends.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Назад
                </a>
            </form>
        </div>

        @if($users->count())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold mb-4">Результаты  ({{ $users->total() }})</h2>
                @foreach($users as $user)

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">

                            <div class="flex items-center space-x-3 mb-3">

                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    @if($user->created_at)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                                @if($user->friendship_status === "accepted")
                                    <span class="text-green-600 text-sm font-medium">В друзьях</span>
                                @elseif($user->friendship_status === "sent")
                                    <form action="{{ route('friends.cancel', $user) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button class="text-yellow-600 hover:text-yellow-800 text-sm">
                                            Отменить запрос
                                        </button>
                                    </form>
                                @elseif($user->friendship_status === "recieived")
                                    <div class="flex space-x-2">
                                        <form action="{{ route('friends.accept', $user) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button class="text-green-600 hover:text-green-800 text-sm">
                                                Принять
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.reject', $user) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button class="text-red-600 hover:text-red-800 text-sm">
                                                Отклонить
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form action="{{ route('friends.send', $user) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                            Добавить в друзья
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Пользователи не найдены</h3>
                <a href="{{ route('friends.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Вернуться к списку друзей
                </a>
            </div>
        @endif
    </div>
@endsection
