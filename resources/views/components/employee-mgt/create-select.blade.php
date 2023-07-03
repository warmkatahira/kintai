<div class="flex text-sm bg-gray-600 py-1 text-white pl-5">
    <p>{{ $label }}@if($tippy != 0)<i class="lar la-question-circle la-lg ml-1 {{ $tippy }}"></i>@endif</p>
    @if($required == 1)
        <span class="text-red-500 text-xs bg-pink-100 text-center px-2 py-0.5 ml-auto mr-2">必須</span>
    @endif
</div>
<div class="border px-10 py-5">
    <select name="{{ $id }}" class="w-96 text-sm">
        <option value=""></option>
        <option value="0" @if(old($id, $db) == 0) selected @endif>無効</option>
        <option value="1" @if(old($id, $db) == 1) selected @endif>有効</option>
    </select>
</div>