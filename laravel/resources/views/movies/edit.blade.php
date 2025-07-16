<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Movie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('movies.form', ['movie' => $movie])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
