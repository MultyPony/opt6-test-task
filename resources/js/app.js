import './bootstrap';
import $ from 'jquery';
import dt from 'datatables.net';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask'

Alpine.plugin(mask)
window.Alpine = Alpine;

Alpine.start();

$(document).ready( function () {
    $('#table_id').DataTable();
} );
