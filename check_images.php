<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::whereNotNull('image_url')->take(10)->get();
foreach($products as $p) {
    echo "SKU: " . $p->sku . " | Img: " . json_encode($p->image_url, JSON_UNESCAPED_SLASHES) . "\n";
}
