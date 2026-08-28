<div class="min-h-screen bg-slate-950 text-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Header Title -->
        <div class="text-center space-y-3">
            <span class="px-3.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-bold uppercase tracking-wider">
                Support Our Mission
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">Empower Grassroots Tech Education</h1>
            <p class="text-slate-400 text-sm max-w-2xl mx-auto leading-relaxed">
                Your donations directly fund youth digital skills training, community hubs, and ICT development across underserved communities.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left: Donation Form -->
            <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
                
                <form wire:submit.prevent="processDonation" class="space-y-6">
                    
                    <!-- 1. Select Donation Amount -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-3 tracking-wider">Select Donation Amount (NGN)</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([5000, 10000, 25000, 50000, 100000] as $preset)
                                <button type="button" wire:click="selectAmount({{ $preset }})" class="py-3 px-4 rounded-xl text-xs font-bold border transition font-mono {{ $amount == $preset && !$custom_amount ? 'bg-emerald-600 text-white border-emerald-500 shadow-lg shadow-emerald-900/30' : 'bg-slate-950 text-slate-300 border-slate-800 hover:border-slate-700' }}">
                                    ₦{{ number_format($preset) }}
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <input type="number" wire:model.live="custom_amount" placeholder="Or enter custom amount in ₦..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-emerald-500 font-mono">
                        </div>
                        @error('amount') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- 2. Donor Information -->
                    <div class="space-y-4 pt-2 border-t border-slate-800/80">
                        <label class="block text-xs font-bold uppercase text-slate-400 tracking-wider">Your Information</label>
                        
                        <div>
                            <input type="text" wire:model="donor_name" placeholder="Full Name or Organization Title" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-emerald-500">
                            @error('donor_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <input type="email" wire:model="donor_email" placeholder="Email Address" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-emerald-500 font-mono">
                                @error('donor_email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <input type="text" wire:model="donor_phone" placeholder="Phone Number (Optional)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-emerald-500 font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method Choice -->
                    <div class="space-y-3 pt-2 border-t border-slate-800/80">
                        <label class="block text-xs font-bold uppercase text-slate-400 tracking-wider">Select Payment Method</label>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-center space-x-3 {{ $payment_method === 'paystack' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400' : 'bg-slate-950 border-slate-800 text-slate-400' }}">
                                <input type="radio" wire:model.live="payment_method" value="paystack" class="hidden">
                                <span class="font-bold text-xs">Debit Card / Paystack</span>
                            </label>

                            <label class="p-4 rounded-2xl border cursor-pointer transition flex items-center space-x-3 {{ $payment_method === 'bank_transfer' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-400' : 'bg-slate-950 border-slate-800 text-slate-400' }}">
                                <input type="radio" wire:model.live="payment_method" value="bank_transfer" class="hidden">
                                <span class="font-bold text-xs">Direct Bank Transfer</span>
                            </label>
                        </div>
                    </div>

                    <!-- Conditional Upload for Bank Transfer -->
                    @if($payment_method === 'bank_transfer')
                        <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3">
                            <span class="text-xs font-bold text-amber-400 block uppercase">Upload Transfer Receipt</span>
                            <input type="file" wire:model="proof_of_payment" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200">
                            @error('proof_of_payment') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Google reCAPTCHA Container -->
<div class="space-y-2 pt-2 border-t border-slate-800">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div wire:ignore>
        <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}" data-theme="dark" data-callback="onDonateCaptchaSuccess"></div>
    </div>
    @error('recaptcha_token')
        <span class="text-red-400 text-xs font-bold block">{{ $message }}</span>
    @enderror
</div>

<script>
    function onDonateCaptchaSuccess(token) {
        @this.set('recaptcha_token', token);
    }
</script>

                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-2xl text-sm shadow-xl shadow-emerald-900/40 transition">
                        Proceed to Donate ₦{{ number_format($amount) }}
                    </button>

                </form>
            </div>

            <!-- Right: Direct Bank Account Details Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-5 shadow-2xl">
                <h3 class="text-sm font-bold text-slate-100 border-b border-slate-800 pb-3">NGO Bank Account Details</h3>
                
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-slate-500 font-bold block text-[10px] uppercase">Bank Name</span>
                        <span class="text-slate-100 font-bold text-sm">Zenith Bank Plc</span>
                    </div>

                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-slate-500 font-bold block text-[10px] uppercase">Account Number</span>
                        <span class="text-emerald-400 font-mono font-black text-lg tracking-wider">1012345678</span>
                    </div>

                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800">
                        <span class="text-slate-500 font-bold block text-[10px] uppercase">Account Name</span>
                        <span class="text-slate-200 font-semibold">InnoTech Future Foundation</span>
                    </div>
                </div>

                <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[11px] rounded-xl leading-relaxed">
                    <strong>Notice:</strong> For direct bank transfers, please attach your payment receipt in the form so our finance team can verify and issue an official receipt.
                </div>
            </div>

        </div>

    </div>

    <!-- Success Confirmation Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/85 backdrop-blur-sm p-4">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl p-6 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto text-2xl font-bold">
                    ✓
                </div>
                <h3 class="text-lg font-black text-slate-100">Thank You For Your Support!</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Your donation record has been logged successfully under reference <strong class="text-emerald-400 font-mono">{{ $activeReference }}</strong>.
                </p>
                <button wire:click="$set('showSuccessModal', false)" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-xs">
                    Close Window
                </button>
            </div>
        </div>
    @endif

    <!-- Paystack Script -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('initiatePaystackPayment', (eventData) => {
                const data = eventData[0];
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
                        alert('Transaction cancelled.');
                    }
                });
                handler.openIframe();
            });
        });
    </script>
</div>