<div class="flex flex-row mt-1">
    <p class="w-40 bg-black text-white text-center py-2 text-sm">{{ $label }}</p>
    <p {{ $attributes->merge(['class' => 'border border-black text-sm py-2 bg-white']) }}>{{ $value }}</p>
</div>