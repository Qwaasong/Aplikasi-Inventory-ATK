const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggleBtn");
const sidebarTexts = document.querySelectorAll(".sidebar-text");
const navItems = document.querySelectorAll(".nav-item");
let isExpanded = true;
let isAnimatingSidebar = false;

const mainContent = document.getElementById("main-content");
const header = document.getElementById("header");

toggleBtn.addEventListener("click", () => {
    if (isAnimatingSidebar) return;

    // Handle Mobile Toggle (< 640px)
    if (window.innerWidth < 640) {
        if (sidebar.classList.contains("-translate-x-full")) {
            sidebar.classList.remove("-translate-x-full");
            sidebar.classList.add("translate-x-0");
        } else {
            sidebar.classList.add("-translate-x-full");
            sidebar.classList.remove("translate-x-0");
        }
        return;
    }

    isAnimatingSidebar = true;
    isExpanded = !isExpanded;

    if (!isExpanded) {
        // === MENUTUP ===
        sidebarTexts.forEach((text) => {
            text.classList.add("opacity-0", "pointer-events-none");
        });

        sidebar.classList.remove("w-64");
        sidebar.classList.add("w-20");

        if (mainContent) {
            mainContent.classList.remove("sm:pl-64");
            mainContent.classList.add("sm:pl-20");
        }

        setTimeout(() => {
            navItems.forEach((item) => {
                item.classList.remove("pl-3");
                item.classList.add("pl-4", "pr-0");
            });
            isAnimatingSidebar = false;
        }, 300);
    } else {
        // === MEMBUKA ===
        navItems.forEach((item) => {
            item.classList.remove("pl-4", "pr-0");
            item.classList.add("pl-3");
        });

        if (mainContent) {
            mainContent.classList.remove("sm:pl-20");
            mainContent.classList.add("sm:pl-64");
        }

        sidebar.classList.remove("w-20");
        sidebar.classList.add("w-64");

        setTimeout(() => {
            sidebarTexts.forEach((text) => {
                text.classList.remove("opacity-0", "pointer-events-none");
            });
            isAnimatingSidebar = false;
        }, 300);
    }
});

// Responsif Init & Resize
function checkScreenSize() {
    const width = window.innerWidth;
    if (width < 640) {
        // Mobile
        sidebar.classList.add("-translate-x-full");
        sidebar.classList.remove("translate-x-0", "w-20", "w-64");
        sidebar.classList.add("w-64"); // Mobile always 64 when shown

        if (mainContent) {
            mainContent.classList.remove("sm:pl-64", "sm:pl-20");
        }
        isExpanded = false;
    } else if (width < 768) {
        // Tablet (Collapsed)
        sidebar.classList.remove("-translate-x-full", "translate-x-0");
        sidebar.classList.add("w-20");
        sidebar.classList.remove("w-64");
        isExpanded = false;

        if (mainContent) {
            mainContent.classList.remove("sm:pl-64");
            mainContent.classList.add("sm:pl-20");
        }

        navItems.forEach((item) => {
            item.classList.remove("pl-3");
            item.classList.add("pl-4", "pr-0");
        });
        sidebarTexts.forEach((text) => {
            text.classList.add("opacity-0", "pointer-events-none");
        });
    } else {
        // Desktop (Expanded)
        sidebar.classList.remove("-translate-x-full", "translate-x-0");
        sidebar.classList.add("w-64");
        sidebar.classList.remove("w-20");
        isExpanded = true;

        if (mainContent) {
            mainContent.classList.remove("sm:pl-20");
            mainContent.classList.add("sm:pl-64");
        }

        navItems.forEach((item) => {
            item.classList.remove("pl-4", "pr-0");
            item.classList.add("pl-3");
        });
        sidebarTexts.forEach((text) => {
            text.classList.remove("opacity-0", "pointer-events-none");
        });
    }
}

window.addEventListener("resize", checkScreenSize);
checkScreenSize();

// Dark Mode Toggle Logic
const darkModeToggle = document.getElementById("darkModeToggle");
if (darkModeToggle) {
    darkModeToggle.addEventListener("click", () => {
        if (document.documentElement.classList.contains("dark")) {
            document.documentElement.classList.remove("dark");
            localStorage.setItem("color-theme", "light");
        } else {
            document.documentElement.classList.add("dark");
            localStorage.setItem("color-theme", "dark");
        }
    });
}
