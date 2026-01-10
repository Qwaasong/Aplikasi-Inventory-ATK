// Deklarasikan variabel di luar fungsi agar bisa diakses secara global
let menuBtn, closeBtn, mobileMenu, overlayBg, sidebar;

document.addEventListener("DOMContentLoaded", function () {
    // --- Mobile Menu Interactivity ---
    menuBtn = document.getElementById('menu-btn');
    closeBtn = document.getElementById('close-btn');
    mobileMenu = document.getElementById('mobile-menu');
    overlayBg = mobileMenu.querySelector('div.absolute');
    sidebar = mobileMenu.querySelector('div.relative');

    if (menuBtn) menuBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlayBg) overlayBg.addEventListener('click', closeSidebar);

});

function openSidebar() {
    console.log("Opening sidebar");
    mobileMenu.classList.remove('hidden');
    overlayBg.classList.remove('opacity-0');
    overlayBg.classList.add('opacity-50');
    setTimeout(() => {
        sidebar.classList.remove('-translate-x-full');
    }, 50); // Delay kecil untuk smooth start
};

function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    overlayBg.classList.remove('opacity-50');
    overlayBg.classList.add('opacity-0');
    setTimeout(() => {
        mobileMenu.classList.add('hidden');
    }, 500); // Sesuaikan dengan duration transisi
};
