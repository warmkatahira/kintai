<label for="{{ $id }}" class="text-sm text-center bg-theme-sub mt-2">{{ $label }}</label>
<select id="{{ $id }}" name="{{ $id }}" class="text-sm py-0">
    <option value="" @if(is_null(session($id))) selected @endif></option>
    <option value="0" @if(0 === session($id)) selected @endif>無効</option>
    <option value="1" @if(1 === session($id)) selected @endif>有効</option>
</select>