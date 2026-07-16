<?php

namespace App\Livewire\Store;

use App\Actions\Checkout\CreateMercadoPagoPreferenceAction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\On;

class Cart extends Component
{
    public bool $isOpen = false;
    public array $cartItems = [];
    public float $total = 0;

    // Checkout form fields
    public string $customer_name = '';
    public string $customer_email = '';
    public string $customer_phone = '';
    public string $address_street = '';
    public string $address_city = '';
    public string $address_commune = '';

    // Geo API fields
    public array $regions = [];
    public array $provinces = [];
    public array $communes = [];
    public string $selected_region = '';
    public string $selected_provincia = '';
    public string $selected_commune = '';
    public bool $manual_mode = false;

    protected $validationAttributes = [
        'customer_name' => 'nombre completo',
        'customer_email' => 'correo electrónico',
        'customer_phone' => 'teléfono de contacto',
        'address_street' => 'calle y número',
        'address_city' => 'ciudad',
        'address_commune' => 'comuna',
        'selected_region' => 'región',
        'selected_provincia' => 'provincia',
        'selected_commune' => 'comuna',
    ];

    public function loadGeoData(): array
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('chile_geo_data', function () {
            try {
                $response = \Illuminate\Support\Facades\Http::get('https://gist.githubusercontent.com/rhernandog/7d055482f5cc803852a762de873bea62/raw/2bed9aed94ab644533b5e624a4e8f165a4650d48/regiones-provincias-comunas.json');
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                // Fail-safe
            }
            return [];
        });
    }

    public function updatedSelectedRegion($value): void
    {
        $geo = $this->loadGeoData();
        $regionData = collect($geo)->firstWhere('region', $value);
        if ($regionData && isset($regionData['provincias'])) {
            $this->provinces = array_map(fn($p) => $p['name'], $regionData['provincias']);
        } else {
            $this->provinces = [];
        }
        $this->communes = [];
        $this->selected_provincia = '';
        $this->selected_commune = '';
        $this->address_city = '';
        $this->address_commune = '';
    }

    public function updatedSelectedProvincia($value): void
    {
        $geo = $this->loadGeoData();
        $regionData = collect($geo)->firstWhere('region', $this->selected_region);
        if ($regionData && isset($regionData['provincias'])) {
            $provData = collect($regionData['provincias'])->firstWhere('name', $value);
            if ($provData && isset($provData['comunas'])) {
                $this->communes = array_map(fn($c) => $c['name'], $provData['comunas']);
            } else {
                $this->communes = [];
            }
        } else {
            $this->communes = [];
        }
        $this->selected_commune = '';
        $this->address_city = '';
        $this->address_commune = '';
    }

    public function updatedSelectedCommune($value): void
    {
        $this->address_commune = $value;
        $this->address_city = $value;
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->customer_name = $user->name;
            $this->customer_email = $user->email;
            $this->customer_phone = $user->phone ?? '';
        }
        
        $geo = $this->loadGeoData();
        if (!empty($geo)) {
            $this->regions = array_map(fn($item) => $item['region'], $geo);
        }

        $this->loadCart();
    }

    #[On('openCart')]
    public function openCart(): void
    {
        $this->loadCart();
        $this->isOpen = true;
    }

    #[On('addToCart')]
    public function addToCart(int $productId, int $quantity = 1): void
    {
        if (!Auth::check()) {
            session()->put('url.intended', url()->previous());
            $this->dispatch('toast', variant: 'error', text: 'Por favor, inicia sesión para poder comprar.');
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $product = Product::find($productId);
        if (!$product) {
            $this->dispatch('toast', variant: 'error', text: 'El producto no existe.');
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            // Check stock limits
            if ($cart[$productId]['quantity'] + $quantity > $product->stock) {
                $this->dispatch('toast', variant: 'error', text: 'No hay suficiente stock disponible.');
                return;
            }
            $cart[$productId]['quantity'] += $quantity;
        } else {
            if ($quantity > $product->stock) {
                $this->dispatch('toast', variant: 'error', text: 'No hay suficiente stock disponible.');
                return;
            }
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'image' => !empty($product->image_url) ? $product->image_url[0] : null,
                'quantity' => $quantity,
                'sku' => $product->sku,
            ];
        }

        session()->put('cart', $cart);
        $this->loadCart();
        $this->dispatch('cartUpdated');
        
        // Open the drawer to give immediate visual feedback
        $this->isOpen = true;
        
        $this->dispatch('toast', variant: 'success', text: 'Producto añadido al carro.');
    }

    public function closeCart(): void
    {
        $this->isOpen = false;
    }

    public function loadCart(): void
    {
        $this->cartItems = session()->get('cart', []);
        $this->calculateTotal();
    }

    private function calculateTotal(): void
    {
        $this->total = array_reduce($this->cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);
    }

    public function incrementQuantity(int $productId): void
    {
        $product = Product::find($productId);
        $cart = session()->get('cart', []);

        if ($product && isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] + 1 > $product->stock) {
                $this->dispatch('toast', variant: 'error', text: 'Máximo stock alcanzado.');
                return;
            }
            $cart[$productId]['quantity']++;
            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function decrementQuantity(int $productId): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] - 1 <= 0) {
                $this->removeItem($productId);
                return;
            }
            $cart[$productId]['quantity']--;
            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function removeItem(int $productId): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
            $this->dispatch('toast', variant: 'success', text: 'Producto eliminado del carro.');
        }
    }

    public function checkout(CreateMercadoPagoPreferenceAction $createPreferenceAction)
    {
        $this->loadCart();
        
        if (empty($this->cartItems)) {
            $this->dispatch('toast', variant: 'error', text: 'Tu carro está vacío.');
            return;
        }

        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'address_street' => 'required|string|max:255',
        ];

        if ($this->manual_mode) {
            $rules['address_city'] = 'required|string|max:100';
            $rules['address_commune'] = 'required|string|max:100';
        } else {
            $rules['selected_region'] = 'required|string';
            $rules['selected_provincia'] = 'required|string';
            $rules['selected_commune'] = 'required|string';
        }

        $this->validate($rules);

        if (!$this->manual_mode) {
            $this->address_commune = $this->selected_commune;
            $this->address_city = $this->selected_commune;
        }

        // Validate stock for all items again before checkout
        foreach ($this->cartItems as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $item['quantity']) {
                $this->dispatch('toast', variant: 'error', text: "El producto {$item['name']} ya no tiene stock suficiente.");
                return;
            }
        }

        try {
            $order = DB::transaction(function () {
                // Save user phone if updated/provided
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->phone !== $this->customer_phone) {
                        $user->update(['phone' => $this->customer_phone]);
                    }
                }

                // Create order record
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                    'total' => $this->total,
                    'payment_gateway' => 'mercado_pago',
                    'customer_name' => $this->customer_name,
                    'customer_email' => $this->customer_email,
                    'customer_phone' => $this->customer_phone,
                    'shipping_address' => [
                        'street' => $this->address_street,
                        'city' => $this->address_city,
                        'commune' => $this->address_commune,
                    ],
                ]);

                // Create order items
                foreach ($this->cartItems as $productId => $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return $order;
            });

            // Call Action to generate payment URL in Mercado Pago
            $preference = $createPreferenceAction->execute($order);

            if ($preference && isset($preference['init_point'])) {
                // Clear the shopping cart session
                session()->forget('cart');
                $this->loadCart();
                $this->dispatch('cartUpdated');
                $this->isOpen = false;

                // Redirect to Mercado Pago checkout gateway
                return redirect()->away($preference['init_point']);
            }

            $this->dispatch('toast', variant: 'error', text: 'Error al iniciar la pasarela de pago. Intenta nuevamente.');
        } catch (\Exception $e) {
            $this->dispatch('toast', variant: 'error', text: 'Hubo un error al procesar tu orden: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.store.cart');
    }
}
