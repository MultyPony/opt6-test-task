import './bootstrap';
import $ from 'jquery';
import dt from 'datatables.net';
import translate from './translate-tables';
import Cookies from 'js-cookie';
import {round} from 'lodash';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask'

Alpine.plugin(mask)
window.Alpine = Alpine;
Alpine.start();


$(document).ready(function () {
    if (document.getElementById('products-table')) {
        $('#products-table').DataTable({
            language: translate,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/products',
                type: 'GET',
                headers: {
                    'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN'),
                    'Accept': 'application/json',
                    'Format': 'datatables',
                },
            },
            columns: [
                {
                    data: 'id',
                },
                {
                    data: 'name',
                    render: function (data, second, row) {
                        return `<a class="underline" href="${row.route}">${data}</a>`;
                    }
                },
                {
                    data: 'price'
                },
            ],
        });
    }
});
