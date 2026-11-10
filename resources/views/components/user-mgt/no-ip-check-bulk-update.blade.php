<div class="bg-white border border-gray-600 shadow-md mr-3 mt-3">
    <p class="text-sm bg-gray-600 text-white py-3 px-3 text-center">IPチェックなし一括更新</p>
    <form method="POST" action="{{ route('user_update.no_ip_check_bulk_update') }}" id="no_ip_check_bulk_update_form" class="m-0">
        @csrf
        <input type="hidden" name="no_ip_check" id="no_ip_check_bulk_value">
        <div class="flex flex-col px-3">
            <label for="bulk_base_id" class="text-sm text-center bg-theme-sub mt-2">拠点</label>
            <select id="bulk_base_id" name="bulk_base_id" class="text-sm py-0">
                <option value=""></option>
                @foreach($bases as $base)
                    <option value="{{ $base->base_id }}" @if($base->base_id == old('bulk_base_id', session('search_base_id'))) selected @endif>{{ $base->base_name }}</option>
                @endforeach
            </select>
            <button type="button" class="no_ip_check_bulk_enter text-sm text-center border border-red-500 text-red-500 bg-pink-100 py-2 mt-2 shadow-md" data-value="{{ App\Enums\NoIpCheckEnum::ACTIVE }}" data-label="{{ App\Enums\NoIpCheckEnum::ACTIVE_JP }}">
                <i class="las la-toggle-on la-lg"></i>一括{{ App\Enums\NoIpCheckEnum::ACTIVE_JP }}
            </button>
            <button type="button" class="no_ip_check_bulk_enter text-sm text-center border border-blue-500 text-blue-500 bg-blue-100 py-2 my-2 shadow-md" data-value="{{ App\Enums\NoIpCheckEnum::DEACTIVE }}" data-label="{{ App\Enums\NoIpCheckEnum::DEACTIVE_JP }}">
                <i class="las la-toggle-off la-lg"></i>一括{{ App\Enums\NoIpCheckEnum::DEACTIVE_JP }}
            </button>
        </div>
    </form>
</div>
