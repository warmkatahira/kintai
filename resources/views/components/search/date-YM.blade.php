<label for="{{ $id }}" class="text-sm text-center bg-theme-sub mt-2">{{ $label }}</label>
<div class="flex flex-col">
    <input type="month" id="{{ $id }}" name="{{ $id }}" class="date_from text-sm py-0" value="{{ session($id) }}" autocomplete="off">
</div>
