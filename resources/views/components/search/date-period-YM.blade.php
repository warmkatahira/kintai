<label for="{{ $idFrom }}" class="text-sm text-center bg-theme-sub mt-2">{{ $label }}</label>
<div class="flex flex-col">
    <input type="month" id="{{ $idFrom }}" name="{{ $idFrom }}" class="date_from text-sm py-0" value="{{ session($idFrom) }}" autocomplete="off">
    <label class="text-center text-xs px-1">～</label>
    <input type="month" id="{{ $idTo }}" name="{{ $idTo }}" class="date_to text-sm py-0" value="{{ session($idTo) }}" autocomplete="off">
</div>
