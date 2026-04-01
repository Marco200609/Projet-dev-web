const filecv = document.getElementById('filecv');

filecv.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text', 'application/rtf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            this.setCustomValidity("Veuillez sélectionner un fichier au format PDF, DOC, DOCX, ODT, RTF, JPG ou PNG");
        } else if (file.size > 2 * 1024 * 1024) {
            this.setCustomValidity("Le fichier doit être inférieur à 2 Mo");
        } else {
            this.setCustomValidity('');
        }
    } else {
        this.setCustomValidity("Veuillez sélectionner un fichier");
    }
    this.reportValidity();
});
