<x-mail::message>
# Dear {{ $user->name }},

The reservation are paid and finalized.

Reservation details:

|          Row(s)        |          Column(s)        |
| ---------------------- | ------------------------- |
@foreach($seats as $seat)
|    {{ $seat->row }}    |    {{ $seat->column }}    |
@endforeach

Thanks,<br />
{{ config('app.name') }}
</x-mail::message>
