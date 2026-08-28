<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #020617; color: #f8fafc; padding: 20px; }
        .card { background-color: #0f172a; border: 1px solid #10b981; padding: 24px; border-radius: 12px; max-width: 600px; margin: 0 auto; }
        .badge { background: #064e3b; color: #34d399; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Donation Payment Verified <span class="badge">Confirmed</span></h2>
        <p>Dear {{ $donation->donor_name }},</p>
        <p>Your payment of <strong>₦{{ number_format($donation->amount, 2) }}</strong> (Ref: <code>{{ $donation->reference_code }}</code>) has been successfully verified and logged into our organizational accounts.</p>
        <p>Your contribution directly powers digital capacity bootcamps and grassroots community infrastructure.</p>
        <p>Thank you for partnering with us!</p>
    </div>
</body>
</html>