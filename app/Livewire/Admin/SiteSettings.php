<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class SiteSettings extends Component
{
    use WithFileUploads;

    public $site_name;
    public $tagline;
    public $contact_email;
    public $contact_phone;
    public $address;
    public $facebook_url;
    public $twitter_url;
    public $instagram_url;
    public $linkedin_url;
    public $whatsapp_group_links = [];

    // Temporary uploads — kept separate from the persisted *_path columns
    // so the form can preview a new file before it's actually saved.
    public $newLogo;
    public $newFavicon;

    public function mount(): void
    {
        $settings = SiteSetting::current();

        $this->site_name = $settings->site_name;
        $this->tagline = $settings->tagline;
        $this->contact_email = $settings->contact_email;
        $this->contact_phone = $settings->contact_phone;
        $this->address = $settings->address;
        $this->facebook_url = $settings->facebook_url;
        $this->twitter_url = $settings->twitter_url;
        $this->instagram_url = $settings->instagram_url;
        $this->linkedin_url = $settings->linkedin_url;
        $this->whatsapp_group_links = $settings->whatsapp_group_links ?? [];
    }

    public function save(): void
    {
        $this->validate([
            'site_name' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:180',
            'contact_email' => 'nullable|email|max:150',
            'contact_phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'whatsapp_group_links' => 'array',
            'whatsapp_group_links.*' => 'nullable|url|max:255',
            'newLogo' => 'nullable|image|max:2048',
            'newFavicon' => 'nullable|mimes:png,ico,jpg,jpeg|max:512',
        ]);

        $settings = SiteSetting::current();

        $data = [
            'site_name' => $this->site_name,
            'tagline' => $this->tagline,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'address' => $this->address,
            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'instagram_url' => $this->instagram_url,
            'linkedin_url' => $this->linkedin_url,
            'whatsapp_group_links' => array_filter($this->whatsapp_group_links ?? []),
        ];

        if ($this->newLogo) {
            if ($settings->logo_path) {
                Storage::disk('site_uploads')->delete($settings->logo_path);
            }
            $data['logo_path'] = $this->newLogo->store('branding', 'site_uploads');
        }

        if ($this->newFavicon) {
            if ($settings->favicon_path) {
                Storage::disk('site_uploads')->delete($settings->favicon_path);
            }
            $data['favicon_path'] = $this->newFavicon->store('branding', 'site_uploads');
        }

        $settings->update($data);
        SiteSetting::clearCache();

        $this->reset(['newLogo', 'newFavicon']);

        session()->flash('message', 'Site settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.site-settings', [
            'settings' => SiteSetting::current(),
            'roles' => Role::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
