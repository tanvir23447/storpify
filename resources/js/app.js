import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;

// Initialize Lucide icons if present
import { createIcons, icons } from 'lucide';
window.createIcons = createIcons;
window.lucideIcons = icons;

document.addEventListener('DOMContentLoaded', () => {
    if (window.createIcons && window.lucideIcons) {
        window.createIcons({ icons: window.lucideIcons });
    }
});

document.addEventListener('livewire:navigated', () => {
    if (window.createIcons && window.lucideIcons) {
        window.createIcons({ icons: window.lucideIcons });
    }
});

Alpine.start();
