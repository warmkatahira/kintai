<!-- ページネーション -->
<div class="ml-auto">
    @if($pages)
        <div class="">
            {{ $pages->appends(request()->input())->links() }}
        </div>
    @endif
</div>