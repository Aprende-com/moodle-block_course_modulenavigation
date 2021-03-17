/**
 * AMD module to manage expanded sections state
 */
const init = (lefthandsections) => {
    registerEventListener(lefthandsections);
};

const registerEventListener = selector => {
    const $element = document.querySelector(selector);
    $element.addEventListener('click', event => {
        if (event.target.dataset.toggle == 'collapse' && event.target.dataset.parent == '#accordion') {
            const $parent = event.target.closest('.section');
            if ($parent) {
                const $allsections = document.querySelectorAll(selector + '>.section');
                $allsections.forEach(function(item) {
                    item.classList.remove('current');
                });
                $parent.classList.add('current');
            }
        }
    });
};

// Export the module
export { init };
