<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #14120e; color: #f3eee3; padding: 24px; margin: 0; }
        .card { background-color: #1e1a14; border: 1px solid #35302a; padding: 28px; border-radius: 12px; max-width: 560px; margin: 0 auto; }
        .badge { display: inline-block; background: #16302c; color: #2f9e8f; border: 1px solid #1b5850; padding: 4px 10px; border-radius: 6px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
        h2 { color: #f3eee3; font-size: 20px; margin: 18px 0 12px; }
        p { color: #cabf9f; font-size: 14px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        td { padding: 8px 0; font-size: 13px; border-bottom: 1px solid #35302a; }
        td.label { color: #a79a85; width: 140px; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
        td.value { color: #f3eee3; font-weight: 600; }
        .btn { display: inline-block; background: #db8a2e; color: #14120e; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; margin-top: 16px; }
        .footer { color: #a79a85; font-size: 11px; margin-top: 24px; border-top: 1px solid #35302a; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">Pending Review</span>
        <h2>A new applicant needs review</h2>
        <p>Someone applied to join InnoTech Future Foundation. Their account is inactive until an administrator approves it.</p>
        <table>
            <tr><td class="label">Name</td><td class="value">{{ $applicant->name }}</td></tr>
            <tr><td class="label">Email</td><td class="value">{{ $applicant->email }}</td></tr>
            <tr><td class="label">Phone</td><td class="value">{{ $applicant->phone }}</td></tr>
            <tr><td class="label">Applied Role</td><td class="value">{{ $role }}</td></tr>
        </table>
        <a href="{{ route('admin.users') }}" class="btn">Review Applicant &rarr;</a>
        <div class="footer">
            InnoTech Future Foundation &middot; ICDMS Platform<br>
            You're receiving this because you're listed as a Super Admin.
        </div>
    </div>
</body>
</html>
