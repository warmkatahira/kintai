@vite(['resources/css/punch_finish_tab.css', 'resources/js/punch/punch_finish_tab.js'])

<x-app-layout>
    <x-page-header content="派遣利用入力" />
    <div class="my-5">
        <form method="POST" id="punch_enter_form" action="{{ route('punch_finish.enter') }}" class="m-0">
            @csrf
            <div class="grid grid-cols-12">
                <p class="col-start-1 col-span-12 text-2xl py-3 pl-3 bg-theme-sub">日付</p>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white p-5">
                    <input type="date" id="date" name="date" class="w-60" value="{{ now()->toDateString() }}">
                </div>
                <p class="col-start-1 col-span-12 text-2xl py-3 pl-3 bg-theme-sub">派遣会社選択</p>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                    <div class="p-5 grid grid-cols-12 gap-4">
                        @foreach($temporary_companies as $temporary_company)
                            <div class="col-span-4">
                                <input type="radio" name="temporary_company_id" id="{{ $temporary_company->temporary_company_id }}" value="{{ $temporary_company->temporary_company_id }}" class="no_rest_time_select hidden" {{ $temporary_company->temporary_company_id == '0' ? 'checked' : '' }}>
                                <label id="{{ $temporary_company->temporary_company_id.'_label' }}" for="{{ $temporary_company->temporary_company_id }}" class="cursor-pointer flex flex-col w-full max-w-lg mx-auto text-center border-2 rounded-lg border-gray-900 p-2 text-xl">{{ $temporary_company->temporary_company_name }}<br>{{ number_format($temporary_company->hourly_rate).'円' }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <p class="col-start-1 col-span-12 text-2xl py-3 pl-3 bg-theme-sub">荷主選択</p>
                <div class="col-start-1 col-span-12 border-2 border-theme-sub bg-white">
                    <div class="p-5 grid grid-cols-12 gap-4">
                        
                    </div>
                </div>
            </div>
            <button type="button" id="input_enter" class="w-full text-center bg-blue-200 py-8 text-4xl rounded-lg mt-3">入力完了</button>
        </form>
    </div>
</x-app-layout>