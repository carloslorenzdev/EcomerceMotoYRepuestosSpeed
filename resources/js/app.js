import 'preline';

function initPrelineComponents() {
    if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
        window.HSStaticMethods.autoInit();
    }
}

// Reinitialize when Livewire has navigated (SPA mode)
document.addEventListener('livewire:navigated', () => {
    initPrelineComponents();
});

// Reinitialize when Livewire updates the DOM
document.addEventListener('livewire:updated', () => {
    initPrelineComponents();
});

// Initial boot
document.addEventListener('DOMContentLoaded', () => {
    initPrelineComponents();
});
