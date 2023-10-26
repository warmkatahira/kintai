<label for="search_orderby" class="text-sm text-center bg-theme-sub mt-2">並び順</label>
<select id="search_orderby" name="search_orderby" class="text-sm py-0">
    <option value="total" @if('total' == session('search_orderby')) selected @endif>合計</option>
    <option value="shain" @if('shain' == session('search_orderby')) selected @endif>社員</option>
    <option value="part" @if('part' == session('search_orderby')) selected @endif>パート</option>
    <option value="temporary" @if('temporary' == session('search_orderby')) selected @endif>派遣</option>
</select>