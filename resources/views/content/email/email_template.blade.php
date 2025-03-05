<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .footer { background-color: #f1f1f1; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $header }}</h1>
    </div>
    <div class="content">
        <p>{{ $content }}</p>
    </div>
    <div class="footer">
        <p>{{ $footer }}</p>
    </div>
</body>
</html>
