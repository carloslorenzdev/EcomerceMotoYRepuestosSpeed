<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RelBaseService;

class SyncRelBase extends Command
{
    protected $signature = 'relbase:sync';
    protected $description = 'Sincroniza el catálogo de productos desde RelBase';

    /**
     * Execute console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronización con RelBase...');
        
        try {
            $service = app(RelBaseService::class);
            $result = $service->syncCatalog();

            if ($result['status'] === 'success') {
                $this->info('¡Éxito! ' . $result['message']);
            } else {
                $this->error('Error en la sincronización: ' . $result['message']);
            }
        } catch (\Exception $e) {
            $this->error('Excepción: ' . $e->getMessage());
        }
    }
}
