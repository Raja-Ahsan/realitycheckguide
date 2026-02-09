<!DOCTYPE html>
<html>
<head>
    <title>Member Directory Status Updated</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f7; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); overflow: hidden; }
        .header { background: #006cd1; padding: 24px 0 16px; text-align: center; color: #fff; }
        .body { padding: 32px; color: #333; text-align: center; }
        .footer { background: #00509a; color: #fff; text-align: center; font-size: 0.95rem; padding: 18px 0 10px; border-top: 1px solid #eaeaea; }
        .status { 
            font-weight: bold; 
            display: inline-block;
            padding: 5px 30px;
            border-radius: 20px;
            margin: 10px auto;
            color: white;
        }
        .status-approved { 
            background-color: #00a65a; 
        }
        .status-rejected { 
            background-color: #ff0000; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Member Directory Status Updated</h2>
        </div>
        <div class="body">
            <p>The status of your member directory entry <strong>{{ $memberDirectory->title }}</strong> has been updated to:</p>
            <div style="text-align: center;">
                <span class="status @if($memberDirectory->status === 'approved') status-approved @elseif($memberDirectory->status === 'rejected') status-rejected @endif">{{ ucfirst($memberDirectory->status) }}</span>
            </div>
            @if(isset($rejectionReason) && $memberDirectory->status === 'rejected')
                <p><strong>Reason for rejection:</strong><br>{{ $rejectionReason }}</p>
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

