<!DOCTYPE html>
<html>
<head>
    <title>Document Released</title>
</head>
<body>
    <p>Dear {{ $full_name }},</p>
    <p>We are pleased to inform you that your document has been released and ready to pick up.</p>
    <p><strong>Queue Number:</strong> {{ $queue_number }}</p>
    <p><strong>Released Date:</strong> {{ $released_date }}</p>
    <p>Thank you!</p>
</body>
</html>