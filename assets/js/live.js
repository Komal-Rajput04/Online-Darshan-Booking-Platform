const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
});

document.querySelectorAll('.fade-up').forEach(elem => {
    observer.observe(elem);
});

const bhajan = document.getElementById('bhajan');

function toggleBhajan() {
    if (bhajan.paused) {
        bhajan.play();
    } else {
        bhajan.pause();
    }
}

// Safely play bell on user interaction
document.addEventListener('DOMContentLoaded', () => {
    bhajan.volume = 0.5;

    function enableAudio() {
        bhajan.play().then(() => {
            document.removeEventListener('click', enableAudio);
            document.removeEventListener('touchstart', enableAudio);
        }).catch(err => {
            console.log('User interaction required to play audio.');
        });
    }

    document.addEventListener('click', enableAudio);
    document.addEventListener('touchstart', enableAudio);
});
