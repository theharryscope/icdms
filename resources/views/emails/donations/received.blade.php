<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #020617; color: #f8fafc; padding: 20px; }
        .card { background-color: #0f172a; border: 1px solid #1e293b; padding: 24px; border-radius: 12px; max-width: 600px; margin: 0 auto; }
        .emerald { color: #10b981; font-weight: bold; }
        .mono { font-family: monospace; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Thank You for Your Support, {{ $donation->donor_name }}!</h2>
        <p>We have received your donation submission. Below are your transaction details:</p>
        <ul>
            <li>Reference: <span class="emerald mono">{{ $donation->reference_code }}</span></li>
            <li>Amount: <strong>₦{{ number_format($donation->amount, 2) }}</strong></li>
            <li>Payment Method: <span class="mono">{{ strtoupper($donation->payment_method) }}</span></li>
        </ul>
        <p>If you paid via Direct Bank Transfer, our finance team will review your receipt and send your official verified receipt shortly.</p>
        <p>&copy; {{ date('Y') }} InnoTech Future Foundation</p>
    </div>
</body>
</html>