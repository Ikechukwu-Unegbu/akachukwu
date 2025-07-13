@component('mail::message')
# {{ $greeting }}

Here's an update about your scheduled transaction:

@component('mail::panel')
@foreach($content as $key => $value)
**{{ $key }}**: {{ $value }}

@endforeach
@endcomponent

@if($transaction->status === 'failed')
We encountered an issue processing your transaction. Our team has been notified and will look into it.
@elseif($transaction->status === 'cancelled')
This transaction has been cancelled as requested.
@else
Your transaction has been processed successfully.
@endif

@component('mail::button', ['url' => route('dashboard')])
View Transaction Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent