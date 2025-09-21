import './bootstrap-echo'
import './bootstrap';
import './components.js';
import './broadcast-status';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid';
import 'flatpickr';
import 'flatpickr/dist/l10n/fa.js';
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'
import './components/multi-select';

window.addEventListener('livewire:navigated', function () {
    console.log('navigated');
    // refactorPowergridDatatabel()
});

livewire_hot_reload();
