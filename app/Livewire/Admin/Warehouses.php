<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Warehouse;

class Warehouses extends Component
{
    public $search = '';
    public $isModalOpen = false;
    public $editingWarehouseId = null;

    // Form fields
    public $name = '';
    public $code = '';
    public $address = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:100',
        'address' => 'nullable|string|max:500',
        'is_active' => 'required|boolean',
    ];

    /**
     * Toggle visibility/active state.
     */
    public function toggleActive($id)
    {
        $wh = Warehouse::findOrFail($id);
        $wh->is_active = !$wh->is_active;
        $wh->save();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Estado del almacén actualizado.',
        ]);
    }

    /**
     * Open form to create warehouse.
     */
    public function createWarehouse()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    /**
     * Open form to edit warehouse.
     */
    public function editWarehouse($id)
    {
        $wh = Warehouse::findOrFail($id);
        $this->editingWarehouseId = $wh->id;
        
        $this->name = $wh->name;
        $this->code = $wh->code;
        $this->address = $wh->address;
        $this->is_active = $wh->is_active;

        $this->isModalOpen = true;
    }

    /**
     * Save changes.
     */
    public function saveWarehouse()
    {
        $this->validate();

        $code = strtolower(trim($this->code));

        $conflict = Warehouse::where('code', $code);
        if ($this->editingWarehouseId) {
            $conflict->where('id', '!=', $this->editingWarehouseId);
        }

        if ($conflict->exists()) {
            $this->addError('code', 'Este código de almacén ya está registrado.');
            return;
        }

        if ($this->editingWarehouseId) {
            $wh = Warehouse::findOrFail($this->editingWarehouseId);
            $wh->update([
                'name' => $this->name,
                'code' => $code,
                'address' => $this->address,
                'is_active' => $this->is_active,
            ]);
        } else {
            Warehouse::create([
                'name' => $this->name,
                'code' => $code,
                'address' => $this->address,
                'is_active' => $this->is_active,
            ]);
        }

        $this->isModalOpen = false;
        $this->resetForm();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Almacén guardado exitosamente.',
        ]);
    }

    /**
     * Reset modal properties.
     */
    public function resetForm()
    {
        $this->editingWarehouseId = null;
        $this->name = '';
        $this->code = '';
        $this->address = '';
        $this->is_active = true;
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = Warehouse::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
        }

        $warehouses = $query->latest()->get();

        return view('livewire.admin.warehouses', [
            'warehouses' => $warehouses,
        ])->layout('layouts.admin');
    }
}
