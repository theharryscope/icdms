<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #14120e; color: #f3eee3; padding: 24px; margin: 0; }
        .card { background-color: #1e1a14; border: 1px solid #35302a; padding: 28px; border-radius: 12px; max-width: 560px; margin: 0 auto; }
        .badge { display: inline-block; background: #3a2a15; color: #db8a2e; border: 1px solid #7a4a18; padding: 4px 10px; border-radius: 6px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
        h2 { color: #f3eee3; font-size: 20px; margin: 18px 0 8px; }
        p { color: #cabf9f; font-size: 14px; line-height: 1.6; }
        .field { font-family: 'Courier New', monospace; color: #2f9e8f; }
        .footer { color: #a79a85; font-size: 11px; margin-top: 24px; border-top: 1px solid #35302a; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">Application Received</span>
        <h2>Thanks for applying, {{ $name }}.</h2>
        <p>We've received your application to join InnoTech Future Foundation as a <strong class="field">{{ $role }}</strong>.</p>
        <p>Your application is now pending review by an administrator. This usually involves a quick check of your details and the document you submitted. You'll receive another email as soon as a decision is made — there's nothing further you need to do right now.</p>
        <p>Once approved, you'll be able to sign in and access your dashboard directly.</p>
        <div class="footer">
            InnoTech Future Foundation &middot; ICDMS Platform<br>
            This is an automated message — please don't reply directly to this email.
        </div>
    </div>
</body>
</html>
