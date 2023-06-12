<x-mail::message>
<p class="title">パスワードリセットのご連絡</p>

{{-- Intro Lines --}}
こちらからパスワードリセットを行って下さい

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
パスワードリセット
</x-mail::button>
@endisset

{{-- Outro Lines --}}
<div class="detail">パスワードリセットの有効期間は60分です。<br>
本メールに心当たりがない場合は、操作しないで下さい。
</div>

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
ボタンがクリックできない場合は、こちらのURLを使用して下さい。
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
