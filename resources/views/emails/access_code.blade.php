<x-mail::message>
# Verification Code

Your verification code is <strong>{{$code}}</strong>
<p>This code is valid for 10 minutes</p>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
