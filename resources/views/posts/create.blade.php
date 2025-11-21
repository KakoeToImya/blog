@extends('layouts.app')

@section('title', 'Создать новый пост')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Создать новый пост</h1>
        <p class="text-gray-600 mt-2">Заполните форму ниже чтобы создать новый пост</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong class="font-bold">Исправьте следующие ошибки:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Заголовок поста *
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Введите заголовок поста">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Категория
                </label>
                <select id="category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Выберите категорию --</option>

                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Краткое описание *
                </label>
                <textarea id="excerpt" name="excerpt" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="Краткое описание поста (будет показано в списке)">
                    {{ old('excerpt') }}
                </textarea>
                @error('excerpt')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Содержание поста *
                </label>
                <textarea id="content" name="content" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="Полное содержание поста...">
                    {{ old('content') }}
                </textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Изображение поста</label>
                <input type="file" id="image" name="image" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" accept="image/*">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex space-x-3">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md transition duration-200">
                        Создать пост
                    </button>

                    <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition duration-200">
                        Отмена
                    </a>
                </div>

                <p class="text-sm text-gray-500">* - обязательные поля</p>
            </div>
        </form>
    </div>
@endsection
