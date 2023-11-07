<p>※このメールは自動送信です</p>
<p>勤怠で確認が必要なものがあります。</p>
<table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th style="border: 1px solid #000;">営業所</th>
        <th style="border: 1px solid #000;">従業員名</th>
        <th style="border: 1px solid #000;">出勤日</th>
        <th style="border: 1px solid #000;">メッセージ</th>
    </tr>
    @foreach($data as $item)
        <tr>
            <td style="border: 1px solid #000;">{{ $item['base_name'] }}</td>
            <td style="border: 1px solid #000;">{{ $item['employee_name'] }}</td>
            <td style="border: 1px solid #000;">{{ $item['work_day'] }}</td>
            <td style="border: 1px solid #000;">{{ $item['message'] }}</td>
        </tr>
    @endforeach
</table>