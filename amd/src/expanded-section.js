/**
 * AMD module to manage expanded sections state
 */
const init = (lefthandsections) => {
    registerEventListeners(lefthandsections);
};

const registerEventListeners = selector => {
    const $element = document.querySelector(selector);
    $element.addEventListener('click', event => {
        if (event.target.dataset.toggle == 'collapse' && event.target.dataset.parent == '#accordion') {
            const $allsections = document.querySelectorAll(selector + '>.section');
            const $sectionLink = event.target;
            window.console.log($sectionLink);
            const $parent = event.target.closest('.section');

            $allsections.forEach(function($section) {
                $section.classList.remove('current');
                $section.querySelector('.module-navigation-section-heading').classList.remove('underline');
            });

            setTimeout(() => {
                if ($sectionLink.getAttribute('aria-expanded') == 'true') {
                        $parent.querySelector('.module-navigation-section-heading').classList.add('underline');
                        $parent.classList.add('current');
                }
            }, 250);
        }
    });
};

// Export the module
export { init };
