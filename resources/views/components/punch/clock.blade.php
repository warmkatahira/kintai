@vite(['resources/css/clock.css', 'resources/js/clock.js'])

<!-- 時計を表示 -->
<div class="ml-auto clock_container rounded-lg">
    <div class="clock grid grid-cols-12 Arvo">
        <p class="clock-date col-span-6 text-5xl py-1 text-center"></p>
        <p class="clock-time col-span-6 text-5xl py-1 text-center"></p>
    </div>
</div>