<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Movie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('movies.form', ['movie' => new App\Models\Movie()])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
