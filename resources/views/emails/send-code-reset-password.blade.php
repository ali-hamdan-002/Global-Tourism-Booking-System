@component('mail::message')
<h1>welcome dear user to our tourism application j-path</h1>
<h1>i wish you happy travels</h1>

<p>You presonal account confirmation code is :</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>The allowed duration of the code is one hour from the time the message was sent</p>
@endcomponen
