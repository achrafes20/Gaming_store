@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://imgur.com/9owd9vd.png" class="logo" alt="NextLevelGaming Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
