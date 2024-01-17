<label for="{{ $id }}" class="text-sm text-center bg-theme-sub mt-2">{{ $label }}</label>
<select id="{{ $id }}" name="{{ $id }}" class="text-sm py-0">
    @foreach($searchConditions as $key => $value)
        <option value="{{ $key }}" @if($key == session($id)) selected @endif>{{ $value }}</option>
    @endforeach
</select>