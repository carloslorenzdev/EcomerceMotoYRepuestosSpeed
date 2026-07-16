<?php

namespace App\Livewire\Store;

use App\Models\Service;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.storefront')]
class Services extends Component
{
    public $services = [];

    // Booking form properties
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $motorcycle = '';
    public string $service_id = '';
    public string $notes = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'motorcycle' => 'required|string|max:255',
        'service_id' => 'required|exists:services,id',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $validationAttributes = [
        'name' => 'nombre completo',
        'email' => 'correo electrónico',
        'phone' => 'teléfono',
        'motorcycle' => 'marca y modelo de motocicleta',
        'service_id' => 'servicio a cotizar',
        'notes' => 'detalles adicionales',
    ];

    public function mount(): void
    {
        $this->services = Service::where('is_active', true)->get();
    }

    public function submitBooking(): void
    {
        $this->validate();

        // Process booking (Log or trigger notification)
        Log::info('New Service Booking Requested:', [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'motorcycle' => $this->motorcycle,
            'service_id' => $this->service_id,
            'notes' => $this->notes,
        ]);

        $this->dispatch('toast', variant: 'success', text: '¡Solicitud enviada! Nos pondremos en contacto a la brevedad.');
        $this->reset(['name', 'email', 'phone', 'motorcycle', 'service_id', 'notes']);
    }

    public function selectService(int $id): void
    {
        $this->service_id = (string) $id;
        // Scroll to booking form
        $this->dispatch('scroll-to-booking');
    }

    public function render()
    {
        return view('livewire.store.services');
    }
}
