<!DOCTYPE html>
<html>
<head>
    <title>Compliance Reminder</title>
</head>
<body>
    <h1>Compliance Reminder</h1>
    <p>Dear User,</p>
    <p>Here are your compliance tasks:</p>

    <ul>
        @foreach ($data as $item)
            {{-- <li>{{ $item['compliance_name'] }} - Deadline: {{ $item['deadline'] }}</li> --}}
        @endforeach
    </ul>

    <p>Please ensure these are completed on time.</p>
    <p>Thank you!</p>
</body>
</html>
