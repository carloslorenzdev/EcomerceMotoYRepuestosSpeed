<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class Categories extends Component
{
    public $search = '';
    
    // Modal states
    public $isModalOpen = false;
    public $editingCategoryId = null;

    // Form fields
    public $name = '';
    public $description = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'required|boolean',
    ];

    /**
     * Toggle visibility of a category.
     */
    public function toggleActive($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->is_active = !$category->is_active;
        $category->save();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Estado de visibilidad de la categoría actualizado.',
        ]);
    }

    /**
     * Prepare form to create a new category.
     */
    public function createCategory()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    /**
     * Prepare form to edit an existing category.
     */
    public function editCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $this->editingCategoryId = $category->id;
        
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;

        $this->isModalOpen = true;
    }

    /**
     * Save category changes.
     */
    public function saveCategory()
    {
        $this->validate();

        $slug = Str::slug($this->name);
        
        // Ensure slug is unique
        $slugConflictQuery = Category::where('slug', $slug);
        if ($this->editingCategoryId) {
            $slugConflictQuery->where('id', '!=', $this->editingCategoryId);
        }
        
        if ($slugConflictQuery->exists()) {
            $this->addError('name', 'Ya existe una categoría con un nombre o slug similar.');
            return;
        }

        if ($this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
        }

        $this->isModalOpen = false;
        $this->resetForm();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Categoría guardada exitosamente.',
        ]);
    }

    /**
     * Reset modal form attributes.
     */
    public function resetForm()
    {
        $this->editingCategoryId = null;
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = Category::query()->withCount('products');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $categories = $query->latest()->get();

        return view('livewire.admin.categories', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
