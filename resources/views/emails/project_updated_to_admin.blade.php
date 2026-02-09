<!DOCTYPE html>
<html>
<head>
    <title>Project Updated</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f7; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); overflow: hidden; }
        .header { background: #006cd1; padding: 24px 0 16px 0; text-align: center; color: #fff; } /* Blue header */
        .body { padding: 32px 32px 24px 32px; color: #333; text-align: center; }
        .footer { background: #00509a; color: #fff; text-align: center; font-size: 0.95rem; padding: 18px 0 10px 0; border-top: 1px solid #eaeaea; } /* Darker blue footer */
        .highlight { font-weight: bold; color: #cb8900; } /* Orange highlight for project name */
        .button { display: inline-block; background-color: #cb8900; color: #ffffff !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .button:hover { background-color: #ac790c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Project Update Notification</h2>
        </div>
        <div class="body">
            <p>Project <span class="highlight">{{ $project->name }}</span> has been updated by:</p>
            <p><strong>Name:</strong> {{ $updater->name }} {{ $updater->last_name }}<br>
               <strong>Role:</strong> {{ $updater->role }}</p>

            <p>Please review the changes.</p>

            <a href="{{ route('projects.show', $project->id) }}" class="button">View Project Details</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SCVBA. All rights reserved.<br>
            <span>South Central Virginia Business Alliance</span>
        </div>
    </div>
</body>
</html> 