// Professional enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255,255,255,0.98)';
        } else {
            navbar.style.background = 'rgba(255,255,255,0.95)';
        }
    });

    // Cutoff time check (front-end hint only)
    if (window.location.pathname.includes('update.html') || window.location.pathname.includes('update')) {
        const now = new Date();
        const cutoff = new Date();
        cutoff.setHours(21, 0, 0, 0);
        if (now > cutoff) {
            const form = document.getElementById('updateForm');
            if (form) {
                form.innerHTML = '<div class="alert alert-warning text-center p-4"><i class="fas fa-clock me-2"></i>Updates closed for tomorrow. Opens 12AM.</div>';
            }
            return false;
        }
    }

    // Card hover animations
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-10px)');
        card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });

    // Form smooth focus
    const forms = document.querySelectorAll('.form-control, .form-select');
    forms.forEach(form => {
        form.addEventListener('focus', () => form.parentElement.style.transform = 'scale(1.02)');
        form.addEventListener('blur', () => form.parentElement.style.transform = 'scale(1)');
    });
});
