<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $component = Livewire\Livewire::test('store.product-detail', ['slug' => 'neumatico-trasero-130-70-17-pirelli-diablo-rosso-iii']);
    echo "Rendered successfully!";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
