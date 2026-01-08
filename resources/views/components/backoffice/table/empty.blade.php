@props(['colspan' => 5, 'message' => 'No data found.'])

<tr>
    <td colspan="{{ $colspan }}" class="text-center text-muted">{{ $message }}</td>
</tr>
