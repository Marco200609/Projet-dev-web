document.addEventListener('DOMContentLoaded', () => {
    const passwordWrappers = document.querySelectorAll('.password-wrapper');

    passwordWrappers.forEach(wrapper => {
        const btnToggle = wrapper.querySelector('.toggle-password');
        const inputMDP = wrapper.querySelector('.password-input');
        const eyeOpen = wrapper.querySelector('.eye-open');
        const eyeClosed = wrapper.querySelector('.eye-closed');

        if (btnToggle && inputMDP) {
            btnToggle.addEventListener('click', (e) => {
                e.preventDefault();

                const isPassword = inputMDP.type === 'password';
                inputMDP.type = isPassword ? 'text' : 'password';

                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');

                btnToggle.setAttribute('aria-label', isPassword ? "Cacher le mot de passe" : "Afficher le mot de passe");
            });
        }
    });
});