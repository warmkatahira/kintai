<div class="mt-5 p-5 border-2 border-theme-sub rounded-lg bg-white">
    <ul class="tab">
        <li class="tab_menu active">全て</li>
        @foreach($customergroups as $customer_group)
            <li class="tab_menu">{{ $customer_group->customer_group_name }}</li>
        @endforeach
        <li class="tab_menu">応援</li>
    </ul>
    <ul class="tab_detail_wrap show">
        <div class="grid grid-cols-12 gap-4 mt-5">
            @foreach($customers as $customer)
                <button type="button" class="working_time_input_modal_open col-span-4 text-center text-2xl bg-theme-sub rounded-lg py-4 px-2" value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</button>
            @endforeach
        </div>
    </ul>
    @foreach($customergroups as $customer_group)
        <ul class="tab_detail_wrap">
            <div class="grid grid-cols-12 gap-4 mt-5">
                @foreach($customer_group->customers as $customer)
                    @if($customer->is_available == 1)
                        <button type="button" class="working_time_input_modal_open col-span-4 text-center text-2xl bg-theme-sub rounded-lg py-4 px-2" value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</button>
                    @endif
                @endforeach
            </div>
        </ul>
    @endforeach
    <ul class="tab_detail_wrap">
        <div class="grid grid-cols-12 gap-4 mt-5">
            @foreach($supportbases as $support_base)
                <button type="button" class="working_time_input_modal_open col-span-4 text-center text-2xl bg-theme-sub rounded-lg py-4 px-2" value="{{ $support_base->base_id }}">{{ $support_base->base_name }}</button>
            @endforeach
        </div>
    </ul>
</div>