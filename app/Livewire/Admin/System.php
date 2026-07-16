<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WebhookLog;
use App\Services\RelBaseService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class System extends Component
{
    use WithPagination;

    public $search = '';
    public $providerFilter = '';
    public $statusFilter = '';

    // Credentials edit
    public $relbase_client_id = '';
    public $relbase_client_secret = '';
    public $relbase_refresh_token = '';

    // Test connection state
    public $connectionTestResult = null;
    public $isTestingConnection = false;

    // Log modal details
    public $isPayloadModalOpen = false;
    public $selectedLogId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'providerFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    /**
     * Populate credentials on mount.
     */
    public function mount()
    {
        $this->relbase_client_id = env('RELBASE_CLIENT_ID', '');
        $this->relbase_client_secret = env('RELBASE_CLIENT_SECRET', '');
        $this->relbase_refresh_token = env('RELBASE_REFRESH_TOKEN', '');
    }

    /**
     * Reset pagination on search filter updates.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProviderFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * Test RelBase OAuth connection.
     */
    public function testRelBaseConnection()
    {
        $this->isTestingConnection = true;
        $this->connectionTestResult = null;

        try {
            $service = app(RelBaseService::class);
            $token = $service->getValidToken();

            if ($token) {
                $this->connectionTestResult = [
                    'success' => true,
                    'message' => '¡Conexión establecida con éxito! El token de acceso se ha renovado y verificado.',
                ];
            } else {
                $this->connectionTestResult = [
                    'success' => false,
                    'message' => 'Fallo al autenticar. Por favor verifica las credenciales de RelBase configuradas.',
                ];
            }
        } catch (\Exception $e) {
            $this->connectionTestResult = [
                'success' => false,
                'message' => 'Excepción durante la prueba: ' . $e->getMessage(),
            ];
        }

        $this->isTestingConnection = false;
    }

    /**
     * Save updated API credentials directly to the .env file.
     */
    public function saveCredentials()
    {
        try {
            $envPath = base_path('.env');
            if (File::exists($envPath)) {
                $envContent = File::get($envPath);

                $updates = [
                    'RELBASE_CLIENT_ID' => $this->relbase_client_id,
                    'RELBASE_CLIENT_SECRET' => $this->relbase_client_secret,
                    'RELBASE_REFRESH_TOKEN' => $this->relbase_refresh_token,
                ];

                foreach ($updates as $key => $val) {
                    if (str_contains($envContent, "{$key}=")) {
                        $envContent = preg_replace("/{$key}=.*/", "{$key}=\"{$val}\"", $envContent);
                    } else {
                        $envContent .= "\n{$key}=\"{$val}\"";
                    }
                }

                File::put($envPath, $envContent);
                
                // Clear Laravel config cache
                Artisan::call('config:clear');

                session()->flash('toast', [
                    'type' => 'success',
                    'message' => 'Credenciales del sistema actualizadas y guardadas en .env.',
                ]);
            } else {
                session()->flash('toast', [
                    'type' => 'error',
                    'message' => 'No se encontró el archivo .env en la instalación.',
                ]);
            }
        } catch (\Exception $e) {
            session()->flash('toast', [
                'type' => 'error',
                'message' => 'Error al guardar credenciales: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * View raw JSON payload in modal.
     */
    public function viewPayload($logId)
    {
        $this->selectedLogId = $logId;
        $this->isPayloadModalOpen = true;
    }

    /**
     * Clear all logs.
     */
    public function clearAllLogs()
    {
        WebhookLog::truncate();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Historial de logs limpiado exitosamente.',
        ]);
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = WebhookLog::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('error', 'like', '%' . $this->search . '%')
                  ->orWhere('provider', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->providerFilter) {
            $query->where('provider', $this->providerFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $logs = $query->latest()->paginate(10);
        $selectedLog = $this->selectedLogId ? WebhookLog::find($this->selectedLogId) : null;

        return view('livewire.admin.system', [
            'logs' => $logs,
            'selectedLog' => $selectedLog,
        ])->layout('layouts.admin');
    }
}
