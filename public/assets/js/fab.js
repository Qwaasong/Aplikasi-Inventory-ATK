const fab = document.querySelector('.fab');
const mainBtn = document.getElementById('fabMain');
const iconX = document.getElementById('iconX');

const wrappers = Array.from(document.querySelectorAll('.fab-item-wrapper'));
const items = wrappers.map(w => w.querySelector('.fab-item'));
const labels = wrappers.map(w => w.querySelector('.fab-label'));

let isOpen = false;        // logical state (what UI *should* be)
let isAnimating = false;  // whether an animation sequence is running
let timers = [];          // active scheduled timeouts

/**
 * Menambahkan CSS kustom ke dalam <style> tag di <head>.
 */

function addStyles() {
    if (document.getElementById('fab-styles')) {
        return; // Sudah ditambahkan
    }

    const style = document.createElement('style');
    style.id = 'fab-styles';
    style.textContent = `
        .fab-items {
            pointer-events: none;
        }

        .fab-main {
            position: relative;
        }

        #fabIconWrapper {
            position: relative;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 260ms;
        }

        .fab-icon {
            position: absolute;
            opacity: 0;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0.98);
            line-height: 1;
            user-select: none;
            pointer-events: none;
            transition: transform 260ms cubic-bezier(.2, .9, .2, 1), opacity 200ms linear;
        }

        .fab-icon.visible {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
            pointer-events: auto;
        }

        .fab-icon.fab-rotate-in {
            transform: translate(-50%, -50%) scale(1) rotate(135deg);
        }

        .fab-icon.fab-rotate-out {
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
        }

        .fab-icon.fade-in {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .fab-icon.fade-out {
            opacity: 0;
            transform: translate(-50%, -50%) scale(.86);
        }

        @keyframes shrinkPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(.88);
            }

            100% {
                transform: scale(1);
            }
        }

        .shrink-pulse {
            animation: shrinkPulse .12s ease forwards;
        }

        @keyframes shrinkClick {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(.82);
            }

            100% {
                transform: scale(1);
            }
        }

        .shrink-click {
            animation: shrinkClick .15s ease forwards;
        }

        .fab-item-wrapper {
            display: flex;
            gap: 8px;
            align-items: center;
            transform-origin: right center;
            pointer-events: none;
        }

        .fab-item {
            transition: transform 280ms cubic-bezier(.2, .9, .2, 1), opacity 220ms linear;
            transform-origin: center;
            opacity: 0;
            transform: translateY(12px) scale(.85);
            pointer-events: none;
        }

        .fab-item.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .fab-item.shrink-out {
            transition: transform 140ms ease, opacity 140ms ease;
            opacity: 0;
            transform: translateY(10px) scale(.55);
            pointer-events: none;
        }

        .fab-label {
            display: inline-block;
            white-space: nowrap;
            font-size: 0.85rem;
            padding: 6px 10px;
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(2, 6, 23, .12);
            background: rgba(15, 23, 42, 0.95);
            color: #fff;
            transform: translateX(8px);
            opacity: 0;
            transition: transform 160ms cubic-bezier(.2, .9, .2, 1), opacity 140ms ease;
            pointer-events: none;
        }

        .fab-label.show {
            transform: translateX(0);
            opacity: 1;
            pointer-events: auto;
        }

        .fab.open .fab-items {
            pointer-events: auto;
        }

        .fab-item-wrapper.hidden-space {
            opacity: 0;
            transform: translateX(6px);
        }
        `;
    document.head.appendChild(style);
}

addStyles();

// helper: schedule & track timers so we can cancel on interrupt
function schedule(fn, delay) {
    const id = setTimeout(() => {
        // remove the id from timers when fired
        timers = timers.filter(t => t !== id);
        fn();
    }, delay);
    timers.push(id);
    return id;
}

function clearScheduled() {
    if (timers.length) {
        //console.log(new Date().toISOString(), 'clearing', timers.length, 'scheduled timers (interrupt).');
    }
    timers.forEach(clearTimeout);
    timers = [];
}

// OPEN (can force even if currently animating)
function openFab(force = false) {
    if (isAnimating && !force) return;
    isAnimating = true;
    isOpen = true; // set intent immediately
    //console.log(new Date().toISOString(), 'openFab() start (force=' + !!force + ')');

    // visual feedback pulse
    mainBtn.classList.add('shrink-pulse');
    mainBtn.addEventListener('animationend', () => mainBtn.classList.remove('shrink-pulse'), { once: true });

    // rotate icon to "open" pose
    iconX.classList.add('fab-rotate-in');
    iconX.classList.remove('fab-rotate-out');

    fab.classList.add('open');
    mainBtn.setAttribute('aria-expanded', 'true');

    // animate items with stagger
    wrappers.forEach((wrap, i) => {
        const delay = i * 45;
        schedule(() => {
            wrap.classList.remove('hidden-space');
            labels[i].classList.add('show');
            items[i].classList.add('show');
        }, delay);
    });

    // finish after total duration
    const finishDelay = 300 + wrappers.length * 45;
    schedule(() => {
        isAnimating = false;
        //console.log(new Date().toISOString(), 'finished opening FAB');
    }, finishDelay);
}

// sequence to hide items (used by close)
function closeSequence(force = false) {
    const revIdx = [...Array(items.length).keys()].reverse();
    revIdx.forEach((idx, k) => {
        const delay = k * 45;
        schedule(() => {
            labels[idx].classList.remove('show');
            items[idx].classList.add('shrink-out');
        }, delay);

        // after shrink-out, reset state
        schedule(() => {
            items[idx].classList.remove('shrink-out', 'show');
            wrappers[idx].classList.add('hidden-space');
        }, 140 + delay);
    });

    // remove open state after done
    schedule(() => {
        fab.classList.remove('open');
        mainBtn.setAttribute('aria-expanded', 'false');
        //console.log(new Date().toISOString(), 'finished closing FAB');
        isAnimating = false;
    }, 180 + revIdx.length * 45);
}

// CLOSE (can force even if currently animating)
function closeFab(force = false) {
    if (isAnimating && !force) return;
    isAnimating = true;
    isOpen = false; // set intent immediately
    //console.log(new Date().toISOString(), 'closeFab() start (force=' + !!force + ')');

    // rotate back immediately
    iconX.classList.remove('fab-rotate-in');
    iconX.classList.add('fab-rotate-out');

    // small click shrink
    mainBtn.classList.add('shrink-click');
    mainBtn.addEventListener('animationend', () => mainBtn.classList.remove('shrink-click'), { once: true });

    // start hide sequence
    closeSequence(force);
}

// toggles normally (respecting isAnimating) 
function toggleFab() {
    if (!isOpen) openFab();
    else closeFab();
}

// INTERRUPTIBLE toggle: clear pending timeouts and force the opposite action
function interruptibleToggle() {
    const targetOpen = !isOpen; // what we want after click
    // cancel pending scheduled tasks from previous action
    clearScheduled();

    // allow an immediate reverse: call open/close with force = true
    if (targetOpen) {
        //console.log(new Date().toISOString(), 'interrupt -> opening now');
        openFab(true);
    } else {
        //console.log(new Date().toISOString(), 'interrupt -> closing now');
        closeFab(true);
    }
}

// MAIN BUTTON: always allow interrupt on click
mainBtn.addEventListener('click', (e) => {
    const now = new Date().toISOString();
    //console.log(`[${now}] FAB main clicked — currently isOpen =`, isOpen, ', isAnimating =', isAnimating);

    // If nothing animating, normal toggle (keeps protections)
    if (!isAnimating) {
        toggleFab();
        return;
    }

    // If animating, interrupt and reverse immediately
    interruptibleToggle();
});

// Close when clicking outside: allow interrupt as well
document.addEventListener('click', (e) => {
    if (isOpen && !fab.contains(e.target)) {
        //console.log(new Date().toISOString(), 'outside click -> interrupt + close');
        clearScheduled();
        closeFab(true);
    }
});

// keyboard: Enter/Space toggles, Escape closes (allow interrupt)
mainBtn.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        if (!isAnimating) toggleFab();
        else interruptibleToggle();
    }
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen) {
        clearScheduled();
        closeFab(true);
    }
});

// clicking item buttons — log & close (interruptible)
items.forEach((btn, i) => {
    btn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        //console.log(new Date().toISOString(), 'Clicked item', i);
        clearScheduled();
        closeFab(true);
    });
});