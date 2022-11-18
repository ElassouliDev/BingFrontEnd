<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
@if(isset(cached()->logo))
<img src="{{asset(cached()->logo)}}" class="logo" alt="Laravel Logo">
@endif
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
