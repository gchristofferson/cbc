@component('mail::message')
# New Inquiry: {{ $inquiry->subject }}

  {{ $inquiry->body }}<br><br>


{{ $user->first_name }} {{ $user->last_name }}<br>
[{{ $user->company_name }}]({{ $user->company_website }})<br>
[{{ $user->email }}](mailto:{{ $user->email }})<br>
{{ $user->phone_number }}<br><br>

@if($attachment_links != [])
<hr>
## Attachments
@foreach($attachment_links as $link)
* [Attachment {{ $loop->index + 1 }}]({{ $link }})
@endforeach
@endif

<hr>
@component('mail::button', ['url' => url('/inquiries/' . $inquiry->id)])
View Inquiry
@endcomponent

Thank you,<br>
[CommercialBrokerConnections.com](https://commercialbrokerconnections.com)

<hr>
Join Commercial Broker Connections to connect with commercial brokers in your area.
Members can send their messages to over 1,300 brokers.  3 month free trial and
$99 annual dues.


@endcomponent
