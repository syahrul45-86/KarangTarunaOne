(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        // =========================
        // AUTO HIDE ALERT
        // =========================
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';

                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });

        // =========================
        // SMOOTH SCROLL
        // =========================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (!targetId || targetId === '#') return;

                const target = document.querySelector(targetId);
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

    });

})();
