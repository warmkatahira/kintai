@vite(['resources/js/other/temporary_use.js'])

<x-app-layout>
    <x-page-header content="派遣利用入力" />
    <div class="my-5">
        <form method="POST" id="temporary_use_create_enter_form" action="{{ route('temporary_use.create') }}" class="m-0">
            @csrf
            <div class="flex flex-col">
                <p class="text-2xl py-3 pl-3 bg-theme-sub">日付</p>
                <div class="border-2 border-theme-sub bg-white p-5">
                    <input type="date" id="date" name="date" class="w-60" value="{{ now()->toDateString() }}">
                </div>
                <p class="text-2xl py-3 pl-3 bg-theme-sub mt-2">派遣会社選択</p>
                <div class="border-2 border-theme-sub bg-white">
                    <div class="flex flex-row flex-wrap p-5">
                        @foreach($temporary_companies as $temporary_company)
                            <div class="w-1/4 p-2">
                                <input type="radio" name="temporary_company_id" id="{{ $temporary_company->temporary_company_id }}" value="{{ $temporary_company->temporary_company_id }}" data-amount-calc-item="{{ $temporary_company->amount_calc_item }}" class="temporary_company_select hidden" {{ $temporary_company->temporary_company_id == '0' ? 'checked' : '' }}>
                                <label id="{{ $temporary_company->temporary_company_id.'_label' }}" for="{{ $temporary_company->temporary_company_id }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2">{{ $temporary_company->temporary_company_name }}<br>{{ number_format($temporary_company->hourly_rate).'円' }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <p class="text-2xl py-3 pl-3 bg-theme-sub mt-2">荷主選択</p>
                <div class="border-2 border-theme-sub bg-white">
                    <div class="flex flex-row flex-wrap p-5">
                        @foreach($customers as $customer)
                            <div class="w-1/4 p-2">
                                <input type="radio" name="customer_id" id="{{ $customer->customer_id }}" value="{{ $customer->customer_id }}" class="customer_select hidden" {{ $customer->customer_id == '0' ? 'checked' : '' }}>
                                <label id="{{ $customer->customer_id.'_label' }}" for="{{ $customer->customer_id }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2">{{ $customer->customer_name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-row mt-2">
                    <div class="flex flex-col w-5/12 w">
                        <p class="text-2xl py-3 pl-3 bg-theme-sub">人数入力</p>
                        <div class="flex flex-row border-2 border-theme-sub bg-white">
                            <div class="p-5 flex flex-col w-1/2 space-y-3">
                                <button type="button" class="people_button bg-theme-sub rounded-lg p-2">1</button>
                                <button type="button" class="people_button bg-theme-sub rounded-lg p-2">5</button>
                                <button type="button" class="people_button bg-theme-sub rounded-lg p-2">10</button>
                                <button type="button" id="people_clear" class="bg-pink-200 rounded-lg p-2">クリア</button>
                            </div>
                            <div class="p-5 flex flex-col w-1/2">
                                <input type="tel" id="people_input" name="people_input" class="h-full text-center text-7xl" value="1">
                            </div>
                        </div>
                    </div>
                    <div id="working_time_input_div" class="flex flex-col w-5/12 ml-auto">
                        <p class="text-2xl py-3 pl-3 bg-theme-sub">稼働時間入力</p>
                        <div class="flex flex-row border-2 border-theme-sub bg-white">
                            <div class="p-5 flex flex-col w-1/2">
                                <div class="flex flex-row">
                                    <div class="flex flex-col w-1/2 space-y-3 mr-2">
                                        <button type="button" class="working_time_button bg-theme-sub rounded-lg p-2">1</button>
                                        <button type="button" class="working_time_button bg-theme-sub rounded-lg p-2">5</button>
                                        <button type="button" class="working_time_button bg-theme-sub rounded-lg p-2">10</button>
                                    </div>
                                    <div class="flex flex-col w-1/2 space-y-3 ml-2">
                                        <button type="button" class="working_time_button bg-theme-sub rounded-lg p-2">0.25</button>
                                        <button type="button" class="working_time_button bg-theme-sub rounded-lg p-2">0.50</button>
                                        <button type="button" class="working_time_button bg-theme-sub rounded-lg p-2">0.75</button>
                                    </div>
                                </div>
                                <button type="button" id="working_time_clear" class="bg-pink-200 rounded-lg p-2 mt-2">クリア</button>
                            </div>
                            <div class="p-5 flex flex-col w-1/2">
                                <input type="tel" id="working_time_input" name="working_time_input" class="h-full text-center text-7xl" value="{{ number_format(0, 2) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" id="create_enter" class="w-full text-center bg-blue-200 py-8 text-4xl rounded-lg mt-3">入力完了</button>
        </form>
    </div>
</x-app-layout>