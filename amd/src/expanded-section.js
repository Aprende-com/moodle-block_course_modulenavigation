/**
 * AMD module to manage expanded sections state
 */
define(['jquery'], $ => {
    return {
        init: (lefthandsections) => {
            registerEventListeners(lefthandsections);
        }
    };

    function registerEventListeners(selector) {
        const $toggler = document.querySelector(selector);
        $toggler.addEventListener('click', event => {
            if (event.target.dataset.toggle === 'collapse' && event.target.closest('.section-title__link')) {
                const $allSections = document.querySelectorAll(selector + '>.section');
                const $targetSection = event.target.closest('.section');

                $allSections.forEach(function($section) {
                    $section.classList.remove('current');
                });

                const $collapsibleTarget = $targetSection.querySelector('.section-collapse');
                setTimeout(() => {
                    if ($collapsibleTarget.classList.contains('show')) {
                        $targetSection.classList.add('current');
                    }
                    // Resize the special scrollbar for the nav drawer when the drawer's
                    // contents get bigger or smaller.
                    $('#nav-drawer').getNiceScroll().resize();
                }, 500);
            }
        });
    }
});
