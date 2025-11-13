@extends('layouts.app')

@section('title', 'О сайте')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">О нашем сайте</h1>
        
        <div class="prose prose-lg max-w-none">
            
            
            <p class="text-xl text-gray-700 mb-6">
                Это учебный проект - блог на Laravel, созданный в процессе изучения веб-разработки.
            </p>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
                <p class="text-blue-800">
                    Этот проект демонстрирует полный цикл разработки веб-приложения на Laravel.
                </p>
            </div>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Технологии</h2>
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-semibold mb-3">Backend</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li><strong>Laravel 12+</strong> - PHP фреймворк</li>
                        <li><strong>MySQL</strong> - база данных</li>
                        <li><strong>Eloquent ORM</strong> - работа с БД</li>
                        <li><strong>Blade Templates</strong> - шаблонизатор</li>
                    </ul>
                </div>
                
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-semibold mb-3">Frontend</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li><strong>Tailwind CSS</strong> - утилитарный CSS фреймворк</li>
                        <li><strong>Vanilla JavaScript</strong> - нативный JS</li>
                    </ul>
                </div>
            </div>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Функциональность</h2>
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-semibold mb-3">Для пользователей</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>Просмотр постов</li>
                        <li>Фильтрация по категориям</li>
                        <li>Регистрация и вход</li>
                        <li>Комментирование (в будущем)</li>
                    </ul>
                </div>
                
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-semibold mb-3">Для авторов</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>Создание постов</li>
                        <li>Редактирование своих постов</li>
                        <li>Удаление постов</li>
                        <li>Управление категориями (в будущем)</li>
                    </ul>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('posts.index') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200">
                    Перейти к постам
                </a>
            </div>
        </div>
    </div>
@endsection