document.addEventListener('DOMContentLoaded', () => {
    const flecheWrappers = document.querySelectorAll('.fleche-wrapper');

    flecheWrappers.forEach(wrapper => {
        const btnToggle = wrapper.querySelector('.toggle-fleche');

        const chevronUp = wrapper.querySelector('.bi-chevron-compact-up');
        const chevronDown = wrapper.querySelector('.bi-chevron-compact-down');

        if (btnToggle) {
            btnToggle.addEventListener('click', (e) => {

                e.preventDefault();

                chevronUp.classList.toggle('hidden');
                chevronDown.classList.toggle('hidden');
            });
        }
    });
});