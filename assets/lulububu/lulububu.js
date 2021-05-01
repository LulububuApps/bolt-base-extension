const initializeSelectFields = () => {
    const multiSelectWrappers = document.querySelectorAll('.multiselect-bolt-wrapper');

    multiSelectWrappers.forEach((multiSelectWrapper) => {
        const label = multiSelectWrapper.previousElementSibling;

        if (label) {
            const isRequired = label.querySelector('span.required-label');

            if (isRequired) {
                const inputField    = multiSelectWrapper.querySelector('input[type="hidden"]');
                inputField.type     = 'text';
                inputField.value    = inputField.value !== '[]' ? inputField.value : null;
                inputField.required = true;

                inputField.classList.add('require-select');
            }
        }
    });
};

const initialize = () => {
    initializeSelectFields();
};

document.addEventListener('DOMContentLoaded', initialize, false);