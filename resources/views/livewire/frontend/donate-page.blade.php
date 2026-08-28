<div class="min-h-screen bg-canvas text-ink flex flex-col">

    <x-public.site-header />

    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Header Section -->
        <div class="text-center space-y-3">
            <span class="px-3.5 py-1 bg-ochre-soft text-ochre border border-ochre-dim rounded-full text-xs font-bold uppercase tracking-wider">
                Support Our Mission
            </span>
            <h1 class="text-3xl sm:text-5xl font-display font-bold tracking-tight text-ink">Empower Grassroots Tech Education</h1>
            <p class="text-ink-muted text-sm max-w-2xl mx-auto leading-relaxed">
                Your donations directly fund youth digital skills training, community hubs, and ICT development across underserved communities.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Donation Form -->
            <div class="lg:col-span-2 bg-surface border border-line rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
                
                <form wire:submit.prevent="processDonation" class="space-y-6">
                    
                    <!-- 1. Select Donation Amount -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-ink-muted mb-3 tracking-wider">Select Donation Amount (NGN)</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([5000, 10000, 25000, 50000, 100000] as $preset)
                                <button type="button" wire:click="selectAmount({{ $preset }})" class="py-3 px-4 rounded-xl text-xs font-bold border transition font-mono {{ $amount == $preset && !$custom_amount ? 'bg-ochre text-canvas border-ochre shadow-lg shadow-ochre/10' : 'bg-canvas text-ink-muted border-line hover:border-ochre-dim' }}">
                                    ₦{{ number_format($preset) }}
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <input type="number" wire:model.live="custom_amount" placeholder="Or enter custom amount in ₦..." class="w-full bg-canvas border border-line rounded-xl px-4 py-3 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                        </div>
                        @error('amount') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- 2. Donor Information -->
                    <div class="space-y-4 pt-2 border-t border-line/80">
                        <label class="block text-xs font-bold uppercase text-ink-muted tracking-wider">Your Information</label>
                        
                        <div>
                            <input type="text" wire:model="donor_name" placeholder="Full Name or Organization Title" class="w-full bg-canvas border border-line rounded-xl px-4 py-3 text-xs text-ink focus:outline-none focus:border-ochre">
                            @error('donor_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <input type="email" wire:model="donor_email" placeholder="Email Address" class="w-full bg-canvas border border-line rounded-xl px-4 py-3 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                                @error('donor_email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <input type="text" wire:model="donor_phone" placeholder="Phone Number (Optional)" class="w-full bg-canvas border border-line rounded-xl px-4 py-3 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                            </div>
                        </div>

                        <div>
                            <textarea wire:model="notes" rows="2" placeholder="Notes or special intent for this donation (Optional)..." class="w-full bg-canvas border border-line rounded-xl px-4 py-3 text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
                        </div>
                    </div>

                    <!-- 3. Payment Method Choice -->
                    <div class="space-y-3 pt-2 border-t border-line/80">
                        <label class="block text-xs font-bold uppercase text-ink-muted tracking-wider">Select Payment Method</label>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-center space-x-3 {{ $payment_method === 'paystack' ? 'bg-teal-soft border-teal text-teal' : 'bg-canvas border-line text-ink-muted' }}">
                                <input type="radio" wire:model.live="payment_method" value="paystack" class="hidden">
                                <span class="font-bold text-xs">Debit Card / Paystack</span>
                            </label>

                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-center space-x-3 {{ $payment_method === 'bank_transfer' ? 'bg-teal-soft border-teal text-teal' : 'bg-canvas border-line text-ink-muted' }}">
                                <input type="radio" wire:model.live="payment_method" value="bank_transfer" class="hidden">
                                <span class="font-bold text-xs">Direct Bank Transfer</span>
                            </label>
                        </div>
                    </div>

                    <!-- Conditional Upload for Bank Transfer -->
                    @if($payment_method === 'bank_transfer')
                        <div class="p-4 bg-canvas border border-line rounded-2xl space-y-3">
                            <span class="text-xs font-bold text-ochre block uppercase">Upload Transfer Receipt</span>
                            <input type="file" wire:model="proof_of_payment" accept="image/*" class="w-full text-xs text-ink-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-surface-raised file:text-ink">
                            <p class="text-[10px] text-ink-muted">Attach a screenshot or photo of your payment receipt for manual verification.</p>
                            @error('proof_of_payment') <span class="text-red-400 text-[10px] block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Google reCAPTCHA -->
                    @if(config('captcha.sitekey'))
                        <div class="space-y-2 pt-2 border-t border-line/80">
                            <div wire:ignore>
                                <div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}" data-theme="dark" data-callback="onDonateCaptchaSuccess"></div>
                            </div>
                            @error('recaptcha_token') <span class="text-red-400 text-xs font-bold block">{{ $message }}</span> @enderror
                        </div>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <script>
                            window.onDonateCaptchaSuccess = (token) => @this.set('recaptcha_token', token);
                        </script>
                    @endif

                    <!-- Terms & Conditions -->
                    <div class="pt-2 border-t border-line/80 space-y-3">
                        <details class="bg-canvas border border-line rounded-xl px-4 py-3">
                            <summary class="text-xs font-bold text-ink-muted cursor-pointer select-none">Donation Terms &amp; Conditions</summary>
                            <div class="text-[11px] text-ink-muted leading-relaxed mt-3 space-y-2">
                                <p>By donating, you confirm that:</p>
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>This donation is voluntary and, once processed, is non-refundable except in the case of a proven payment error.</li>
                                    <li>Funds will be used at InnoTech Future Foundation's discretion to support its digital skills training, community infrastructure, and regional leadership programs.</li>
                                    <li>Your name, email, and phone number will be used only for donation processing, receipts, and internal record-keeping — never sold or shared with third parties.</li>
                                    <li>Card payments are processed by Paystack; the Foundation does not store your card details and is not liable for delays or failures caused by the payment processor.</li>
                                    <li>Bank transfer donations are marked "pending verification" until our finance team confirms receipt against your uploaded proof of payment.</li>
                                </ul>
                            </div>
                        </details>

                        <label class="flex items-start space-x-2.5 cursor-pointer">
                            <input type="checkbox" wire:model="terms_accepted" class="mt-0.5 rounded bg-canvas border-line text-ochre focus:ring-ochre">
                            <span class="text-xs text-ink-muted leading-relaxed">I have read and agree to the Donation Terms &amp; Conditions above.</span>
                        </label>
                        @error('terms_accepted') <span class="text-red-400 text-[10px] block">{{ $message }}</span> @enderror
                    </div>

                    @error('payment_method') <span class="text-red-400 text-xs font-bold block">{{ $message }}</span> @enderror
                    <button type="submit" wire:loading.attr="disabled" wire:target="processDonation" class="w-full py-4 bg-ochre hover:bg-ochre/90 disabled:opacity-60 text-canvas font-bold rounded-2xl text-sm shadow-xl shadow-ochre/10 transition">
                        Proceed to Donate ₦{{ number_format($amount) }}
                    </button>

                </form>
            </div>

            <!-- Right Column: Direct Bank Account Details Card -->
            <div class="bg-surface border border-line rounded-3xl p-6 space-y-5 shadow-2xl">
                <h3 class="text-sm font-bold text-ink border-b border-line pb-3">NGO Bank Account Details</h3>
                
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-canvas rounded-xl border border-line">
                        <span class="text-ink-muted font-bold block text-[10px] uppercase">Bank Name</span>
                        <span class="text-ink font-bold text-sm">Zenith Bank Plc</span>
                    </div>

                    <div class="p-3 bg-canvas rounded-xl border border-line">
                        <span class="text-ink-muted font-bold block text-[10px] uppercase">Account Number</span>
                        <span class="text-teal font-mono font-bold text-lg tracking-wider">1012345678</span>
                    </div>

                    <div class="p-3 bg-canvas rounded-xl border border-line">
                        <span class="text-ink-muted font-bold block text-[10px] uppercase">Account Name</span>
                        <span class="text-ink font-semibold">InnoTech Future Foundation</span>
                    </div>
                </div>

                <div class="p-3 bg-teal-soft border border-teal-dim text-teal text-[11px] rounded-xl leading-relaxed">
                    <strong>Notice:</strong> For direct bank transfers, please attach your payment receipt in the form so our finance team can verify and issue an official receipt.
                </div>
            </div>

        </div>

    </div>
    </div>

    <x-public.site-footer />

    <!-- Success Confirmation Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/85 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-3xl w-full max-w-md overflow-hidden shadow-2xl p-6 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-teal-soft text-teal border border-teal flex items-center justify-center mx-auto text-2xl font-bold">
                    ✓
                </div>
                <h3 class="text-lg font-display font-bold text-ink">Thank You For Your Support!</h3>
                <p class="text-xs text-ink-muted leading-relaxed">
                    Your donation record has been logged successfully under reference <strong class="text-teal font-mono">{{ $activeReference }}</strong>.
                </p>
                <button wire:click="$set('showSuccessModal', false)" class="w-full py-3 bg-ochre text-canvas font-bold rounded-xl text-xs shadow-lg transition">
                    Close Window
                </button>
            </div>
        </div>
    @endif

    <!-- Paystack Script & Event Handling -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('initiatePaystackPayment', (eventData) => {
                const data = Array.isArray(eventData) ? eventData[0] : eventData;
                const handler = PaystackPop.setup({
                    key: data.key,
                    email: data.email,
                    amount: data.amount,
                    ref: data.ref,
                    callback: function(response) {
                        @this.dispatch('verifyPaystackSuccess', {
                            reference: data.ref,
                            paystackRef: response.reference
                        });
                    },
                    onClose: function() {
                        alert('Transaction popup closed.');
                    }
                });
                handler.openIframe();
            });
        });
    </script>
</div>