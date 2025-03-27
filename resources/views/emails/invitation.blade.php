@component('mail::message')
# You're Invited!

You have been invited to join {{ $companyName }}.

Click the button below to complete your registration:

@component('mail::button', ['url' => $url])
Register Now
@endcomponent

If you did not expect to receive an invitation to this company, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
