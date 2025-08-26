<!DOCTYPE html>
<html>
<head>
    <title>Project Status Updated</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f7; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); overflow: hidden; }
        .header { background: #006cd1; padding: 24px 0 16px 0; text-align: center; color: #fff; }
        .body { padding: 32px 32px 24px 32px; color: #333; text-align: center; }
        .footer { background: #00509a; color: #fff; text-align: center; font-size: 0.95rem; padding: 18px 0 10px 0; border-top: 1px solid #eaeaea; }
        .status { font-weight: bold; color: #cb8900; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Project Status Updated</h2>
        </div>
        <div class="body">
            <p>The status of your project <strong>{{ $project->name }}</strong> has been updated to:</p>
            <p class="status">{{ ucfirst($project->status) }}</p>
            @if(isset($reason) && $project->status === 'rejected')
                <p><strong>Reason for rejection:</strong><br>{{ $reason }}</p>
            @endif
            <p>Thank you for using SCVBA.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SCVBA. All rights reserved.<br>
            <span>South Central Virginia Business Alliance</span>
        </div>
    </div>
</body>
</html>
