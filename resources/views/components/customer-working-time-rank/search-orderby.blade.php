<label for="search_orderby" class="text-sm text-center bg-theme-sub mt-2">並び順</label>
<select id="search_orderby" name="search_orderby" class="text-sm py-0">
    <option value="total" @if('total' == session('search_orderby')) selected @endif>全て</option>
    <option value="full" @if('full' == session('search_orderby')) selected @endif>正社員</option>
    <option value="part" @if('part' == session('search_orderby')) selected @endif>パート</option>
</select>