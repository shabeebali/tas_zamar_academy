<x-mail::message>
###### Thank you for contacting us.

<x-mail::table>
| Name          | {{$data['name']}}        |
| Email         | {{$data['email']}}       |
| Phone         | {{$data['phone']}}       |
| Comment       | {{$data['comment']}}     |
</x-mail::table>

Thank You for your interest. We have received your information.Someone from our team will contact you shortly.<br>
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
