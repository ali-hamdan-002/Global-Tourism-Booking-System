
@component('mail::panel')
{{ $code }}
@endcomponent


@component('mail::message')
<p>Dear admin Welcome to our application<span class="app-name">J-path</span></p>
<p>This is the secret code for your account</p>

<p>You presonal account confirmation code is :</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>The allowed duration of the code is one hour from the time the message was sent</p>
@endcomponen
