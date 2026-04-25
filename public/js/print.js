window.addEventListener('DOMContentLoaded', () => {
    if (!document.body.classList.contains('js-auto-print')) {
        return;
    }

    window.print();
});
