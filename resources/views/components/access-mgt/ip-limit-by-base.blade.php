<div class="flex flex-row text-xs gap-x-2 mb-2">
    @foreach($ipLimitByBase as $ip_limit_by_base)
        <div class="flex flex-col">
            <span class="bg-gray-600 text-white px-2 py-1">{{ $ip_limit_by_base->base->base_name }}</span>
            <span class="bg-white text-center py-1">{{ $ip_limit_by_base->register_count }}</span>
        </div>
    @endforeach
</div>