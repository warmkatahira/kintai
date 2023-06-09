@vite(['resources/js/punch/punch_begin_type.js'])

<div class="my-10">
    <input type="checkbox" id="punch_begin_type" name="punch_begin_type" class="peer hidden" {{ $db == 1 ? 'checked' : '' }}>
    <label for="punch_begin_type" id="punch_begin_type_label" class="w-96 bg-gray-200 select-none cursor-pointer rounded-lg border-2 border-black py-8 px-6 transition-colors duration-200 ease-in-out peer-checked:bg-theme-sub text-center text-2xl">{{ $db == 1 ? '早出' : '通常' }}</label>
</div>