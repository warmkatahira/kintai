<label for="{{ $id }}" class="text-sm text-center bg-theme-sub mt-2">{{ $label }}</label>
<select id="{{ $id }}" name="{{ $id }}" class="text-sm py-0">
    <option value=""></option>
    @foreach($searchConditions as $search_condition)
        <option value="{{ $search_condition->$value }}" @if($search_condition->$value == session($id)) selected @endif>{{ $search_condition->$text }}</option>
    @endforeach
</select>