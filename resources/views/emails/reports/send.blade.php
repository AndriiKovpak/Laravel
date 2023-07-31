@component('mail::message')
# {{ $title }}

You can find the report in the attachment to this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
