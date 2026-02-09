<!DOCTYPE html>
<html>
<head>
    <title>New Member Directory Submitted</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #fff;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif;
        }
        .email-container {
            max-width: 480px;
            margin: 32px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            overflow: hidden;
        }
        .email-header {
            background: #006cd1;
            padding: 24px 0 16px 0;
            text-align: center;
        }
        .email-header img {
            width: 64px;
            margin-bottom: 12px;
        }
        .email-header h1 {
            color: #fff;
            margin: 0;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 32px 32px 24px 32px;
            color: #333;
            text-align: center;
        }
        .email-body p {
            font-size: 1.1rem;
            margin: 0 0 12px 0;
        }
        .project-info {
            background: #f8fafd;
            border-radius: 8px;
            padding: 16px 10px;
            margin: 12px 0 24px 0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            font-size: 1.1rem;
        }
        .project-info strong {
            color: #cb8900;
            font-weight: 700;
        }
        .cta-button {
            display: inline-block;
            background: #df9816;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 24px 0 0 0;
            box-shadow: 0 2px 8px rgba(22,223,126,0.10);
            transition: background 0.2s;
            border: none;
        }
        .cta-button:hover {
            background: #cb8900;
        }
        .email-footer {
            background: #00509a;
            color: #ffffff;
            text-align: center;
            font-size: 0.95rem;
            padding: 18px 0 10px 0;
            border-top: 1px solid #eaeaea;
        }
        .email-footer span {
            display: block;
            font-size: 0.9em;
            margin-top: 2px;
        }
        @media (max-width: 600px) {
            .email-container { width: 98% !important; }
            .email-body { padding: 18px 8px 8px 8px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://img.icons8.com/clouds/100/000000/handshake.png" alt="SCVBA Logo">
            <h1>New Member Directory Submitted</h1>
        </div>
        <div class="email-body">
            <p style="font-size:1.15rem; font-weight:500; margin-bottom: 10px;">
                A new member has been submitted by <strong>{{ $user->name }}</strong> ({{ $user->email }}).
            </p>
            <div class="project-info">
                <p><strong>Title:</strong> {{ $member->title }}</p>
                <p><strong>Email:</strong> {{ $member->email }}</p>
                <p><strong>Phone:</strong> {{ $member->phone_no }}</p>
            </div>
            <a href="{{ route('member_directory.show', $member->id) }}" class="cta-button" target="_blank">View Member</a>
            <p style="margin-top:28px; color:#888; font-size:1rem;">
                Please review and approve or reject the member directory entry.
            </p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} SCVBA. All rights reserved.
            <span>South Central Virginia Business Alliance</span>
        </div>
    </div>
</body>
</html>
