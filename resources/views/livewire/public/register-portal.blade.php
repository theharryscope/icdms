<div class="min-h-screen bg-canvas font-sans text-ink flex flex-col">

    <x-public.site-header />

    <div class="flex-1 py-8 sm:py-12 px-4 sm:px-6 flex items-center justify-center">
        <div class="max-w-3xl w-full bg-surface border border-line rounded-2xl p-5 sm:p-8 shadow-2xl space-y-6">

        <!-- Header -->
        <div class="text-center space-y-2 border-b border-line pb-6">
            <h2 class="text-xl sm:text-2xl font-display font-bold tracking-tight">Portal Registration</h2>
            <p class="text-xs text-ink-muted">Submit your detailed profile for foundation onboarding and administrative review.</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-6">

            <!-- Role Selection Tabs -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Apply As</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3">
                    <button type="button" wire:click="$set('registration_role', 'volunteer')" class="py-2.5 px-2 text-xs font-bold rounded-xl border transition leading-tight {{ $registration_role === 'volunteer' ? 'bg-ochre border-ochre text-canvas' : 'bg-canvas border-line text-ink-muted' }}">
                        Volunteer
                    </button>
                    <button type="button" wire:click="$set('registration_role', 'coordinator')" class="py-2.5 px-2 text-xs font-bold rounded-xl border transition leading-tight {{ $registration_role === 'coordinator' ? 'bg-ochre border-ochre text-canvas' : 'bg-canvas border-line text-ink-muted' }}">
                        Coordinator
                    </button>
                    <button type="button" wire:click="$set('registration_role', 'partner')" class="py-2.5 px-2 text-xs font-bold rounded-xl border transition leading-tight {{ $registration_role === 'partner' ? 'bg-ochre border-ochre text-canvas' : 'bg-canvas border-line text-ink-muted' }}">
                        Partner / Donor
                    </button>
                    <button type="button" wire:click="$set('registration_role', 'student')" class="py-2.5 px-2 text-xs font-bold rounded-xl border transition leading-tight {{ $registration_role === 'student' ? 'bg-ochre border-ochre text-canvas' : 'bg-canvas border-line text-ink-muted' }}">
                        Student
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Account Credentials -->
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Full Name</label>
                    <input type="text" wire:model="name" placeholder="e.g. Joy Eze" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Email Address</label>
                    <input type="email" wire:model="email" placeholder="e.g. joy@example.com" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" placeholder="+234..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                    @error('phone') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Password</label>
                    <input type="password" wire:model="password" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('password') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <!-- Location Jurisdiction -->
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">State of Residence / Scope</label>
                    <select wire:model.live="selected_state_id" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                    @error('selected_state_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">LGA Jurisdiction</label>
                    <select wire:model="selected_lga_id" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre" {{ !$selected_state_id ? 'disabled' : '' }}>
                        <option value="">Select LGA</option>
                        @foreach($lgas as $lga)
                            <option value="{{ $lga->id }}">{{ $lga->name }} LGA</option>
                        @endforeach
                    </select>
                    @error('selected_lga_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                @if($registration_role === 'partner')
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Organization / Corporate Name</label>
                        <input type="text" wire:model="organization_name" placeholder="e.g. Global Tech Initiatives Ltd" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    </div>
                @endif

                @if(in_array($registration_role, ['volunteer', 'coordinator']))
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Highest Qualification / Degree</label>
                        <input type="text" wire:model="qualification_degree" placeholder="e.g. B.Sc Computer Science" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Skills & Key Expertise</label>
                        <input type="text" wire:model="skills_and_expertise" placeholder="e.g. Field Logistics, Data Analytics" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Profile Picture (optional)</label>
                    <input type="file" wire:model="profile_photo" accept="image/*" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-[16px] sm:text-xs text-ink-muted focus:outline-none">
                    <p class="text-[10px] text-ink-muted/70 mt-1">PNG or JPG, up to 2MB.</p>
                    @error('profile_photo') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Motivation / Application Statement</label>
                    <textarea wire:model="motivation_statement" rows="3" placeholder="Briefly state why you are applying for this role..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
                    @error('motivation_statement') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Upload CV / Verification Document (PDF, Word max 5MB{{ $registration_role === 'partner' ? ', optional for partners / donors' : '' }})</label>
                    <input type="file" wire:model="document" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-[16px] sm:text-xs text-ink-muted focus:outline-none">
                    @error('document') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

            </div>

            <!-- Actions: reCAPTCHA gets its own full-width row so it never has to
                 squeeze in beside text/buttons — that was the main mobile overflow. -->
            <div class="pt-4 border-t border-line space-y-4">

                <div class="space-y-3">
                    <label class="flex items-start gap-3 text-xs text-ink-muted cursor-pointer">
                        <input type="checkbox" wire:model="privacy_policy_accepted" class="mt-0.5 rounded bg-canvas border-line text-ochre focus:ring-ochre">
                        <span>I accept the <a href="{{ route('public.page', 'privacy-policy') }}" target="_blank" class="text-ochre hover:underline">Privacy Policy</a>.</span>
                    </label>
                    @error('privacy_policy_accepted') <span class="text-red-400 text-[10px] block">{{ $message }}</span> @enderror

                    <label class="flex items-start gap-3 text-xs text-ink-muted cursor-pointer">
                        <input type="checkbox" wire:model="terms_accepted" class="mt-0.5 rounded bg-canvas border-line text-ochre focus:ring-ochre">
                        <span>I accept the <a href="{{ route('public.page', 'terms-of-service') }}" target="_blank" class="text-ochre hover:underline">Terms and Conditions</a>.</span>
                    </label>
                    @error('terms_accepted') <span class="text-red-400 text-[10px] block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-center">
                    <div wire:ignore>
                        <div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}" data-theme="dark" data-callback="onRegisterCaptchaSuccess"></div>
                    </div>
                </div>
                @error('recaptcha_token')
                    <p class="text-red-400 text-xs font-bold text-center">{{ $message }}</p>
                @enderror

                <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-4">
                    <a href="{{ route('landing') }}" class="order-2 sm:order-1 text-xs text-ink-muted hover:text-ink">
                        &larr; Back to Home
                    </a>
                    <button type="submit" class="order-1 sm:order-2 w-full sm:w-auto px-8 py-3 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-xl text-xs shadow-lg shadow-ochre/10 transition">
                        Submit Registration Profile
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <x-public.site-footer />
</div>

<script>
    function onRegisterCaptchaSuccess(token) {
        @this.set('recaptcha_token', token);
    }
</script>
