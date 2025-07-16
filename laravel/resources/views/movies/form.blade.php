<div class="space-y-4">
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $movie->title)" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $movie->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="genre_id" :value="__('Genre')" />
        <select id="genre_id" name="genre_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
            @foreach($genres as $id => $name)
                <option value="{{ $id }}" @selected(old('genre_id', $movie->genre_id) == $id)>{{ $name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('genre_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="actors" :value="__('Actors (comma separated)')" />
        <textarea id="actors" name="actors" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('actors', isset($movie->actors) ? $movie->actors->pluck('name')->join(', ') : '') }}</textarea>
        <x-input-error :messages="$errors->get('actors')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="pictures" :value="__('Picture URLs (comma separated)')" />
        <textarea id="pictures" name="pictures" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('pictures', isset($movie->pictures) ? $movie->pictures->pluck('url')->join(', ') : '') }}</textarea>
        <x-input-error :messages="$errors->get('pictures')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
        <a href="{{ route('movies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
    </div>
</div>
