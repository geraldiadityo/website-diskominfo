import './bootstrap';

/*
|--------------------------------------------------------------------------
| Scroll-triggered entrance animations
|--------------------------------------------------------------------------
| Uses IntersectionObserver to add .is-visible when .animate-on-scroll
| elements enter the viewport. Re-observes after Livewire DOM updates.
*/
function observeAnimations() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
    );

    document.querySelectorAll('.animate-on-scroll:not(.is-visible)').forEach((el) => {
        observer.observe(el);
    });
}

// Run on initial page load
document.addEventListener('DOMContentLoaded', observeAnimations);

// Re-run after Livewire navigated (e.g., wire:navigate)
document.addEventListener('livewire:navigated', observeAnimations);

// Livewire 3 hook: Re-run after component DOM updates (e.g., search, pagination)
document.addEventListener('livewire:initialized', () => {
    Livewire.hook('commit', ({ succeed }) => {
        succeed(() => {
            requestAnimationFrame(observeAnimations);
        });
    });
});
