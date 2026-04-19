import './bootstrap';
import * as bootstrap from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {
    // Load Select2 only on pages that use it.
    if ($('.select2').length) {
        import('select2').then((module) => {
            if (typeof module.default === 'function') {
                module.default(window, $);
            }

            if (typeof $.fn.select2 !== 'function') {
                console.warn('Select2 plugin not available after import.');
                return;
            }

            $('.select2').each(function () {
                const $el = $(this);
                if ($el.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $el.select2({
                    width: '100%'
                });
            });
        }).catch((error) => {
            console.error('Failed to import Select2:', error);
        });
    }

       $('img[loading="lazy"]').each(function () {
        const img = this;

        if (img.complete) {
            $(img).addClass('loaded');
        } else {
            $(img).on('load', function () {
                $(this).addClass('loaded');
            });
        }
    });
});
