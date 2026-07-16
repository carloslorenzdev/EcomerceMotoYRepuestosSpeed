<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SiteSetting;

class Branding extends Component
{
    public $whatsapp_digits = '';
    public $instagram_url = '';
    public $phone_display = '';
    public $address_line = '';
    public $contact_email = '';
    
    // Custom assets
    public $logo_url = '';
    public $hero_image_url = '';

    protected $rules = [
        'whatsapp_digits' => 'required|string|max:50',
        'instagram_url' => 'required|url|max:255',
        'phone_display' => 'required|string|max:50',
        'address_line' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'logo_url' => 'nullable|string|max:500',
        'hero_image_url' => 'nullable|string|max:500',
    ];

    /**
     * Load current site settings on mount.
     */
    public function mount()
    {
        $settings = SiteSetting::first();
        if (!$settings) {
            $settings = SiteSetting::create();
        }

        $this->whatsapp_digits = $settings->whatsapp_digits;
        $this->instagram_url = $settings->instagram_url;
        $this->phone_display = $settings->phone_display;
        $this->address_line = $settings->address_line;
        $this->contact_email = $settings->contact_email;
        $this->logo_url = $settings->logo_url;
        $this->hero_image_url = $settings->hero_image_url;
    }

    /**
     * Save branding config parameters.
     */
    public function saveSettings()
    {
        $this->validate();

        $settings = SiteSetting::first() ?? new SiteSetting();
        $settings->whatsapp_digits = $this->whatsapp_digits;
        $settings->instagram_url = $this->instagram_url;
        $settings->phone_display = $this->phone_display;
        $settings->address_line = $this->address_line;
        $settings->contact_email = $this->contact_email;
        $settings->logo_url = $this->logo_url;
        $settings->hero_image_url = $this->hero_image_url;
        $settings->save();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Configuración de marca y contacto guardada exitosamente.',
        ]);
    }

    /**
     * Render view.
     */
    public function render()
    {
        return view('livewire.admin.branding')->layout('layouts.admin');
    }
}
