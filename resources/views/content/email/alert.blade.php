<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $template->subject }}</title>
</head>
<body style="background-color: #f5f5f5; padding: 20px; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background-color: #126de5; padding: 24px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 24px;">{{ $template->title ?: 'Alert Notification' }}</h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <div style="font-size: 16px; line-height: 1.6; color: #333;">
                {!! $replacePlaceholders($template->content ?: 'Alert: [ALERT_TITLE] - [DESCRIPTION]', $alertData) !!}
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 5px;">
                <h3 style="margin-top: 0; color: #126de5;">Alert Details:</h3>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li><strong>Alert:</strong> {{ $alert->title }}</li>
                    <li><strong>Type:</strong> {{ $alert->type->name ?? 'Unknown' }}</li>
                    <li><strong>Time:</strong> {{ $alertData['current_time'] }}</li>
                    @if(isset($alertData['warehouse']))
                    <li><strong>Warehouse:</strong> {{ $alertData['warehouse'] }}</li>
                    @endif
                </ul>
            </div>
            
            @if($template->has_btn && $template->btn_name && $template->btn_link)
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $template->btn_link }}" 
                   style="background-color: #126de5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    {{ $template->btn_name }}
                </a>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px;">
            © {{ date('Y') }} Valomnia - Alert System
        </div>
    </div>
</body>
</html>