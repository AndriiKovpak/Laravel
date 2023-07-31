@component('mail::message')
# {{ $title }}

This report has been generated, however the file was too large to attach.  The file has been uploaded to the ftp site.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
