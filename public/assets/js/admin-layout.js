const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggleBtn");
const sidebarTexts = document.querySelectorAll(".sidebar-text");
const navItems = document.querySelectorAll(".nav-item");
let isExpanded = true;
let isAnimatingSidebar = false;

toggleBtn.addEventListener("click", () => {
    if (isAnimatingSidebar) return;

    // Handle Mobile Toggle (< 640px)
    if (window.innerWidth < 640) {
        if (sidebar.classList.contains("hidden")) {
            sidebar.classList.remove("hidden");
            sidebar.classList.add(
                "flex",
                "absolute",
                "inset-y-0",
                "left-0",
                "z-50",
                "w-64",
            );
            sidebarTexts.forEach((text) => {
                text.classList.remove("hidden", "opacity-0");
            });
            navItems.forEach((item) => {
                item.classList.remove("pl-4", "pr-0");
                item.classList.add("pl-3");
            });
        } else {
            sidebar.classList.add("hidden");
            sidebar.classList.remove(
                "flex",
                "absolute",
                "inset-y-0",
                "left-0",
                "z-50",
                "w-64",
            );
        }
        return;
    }

    isAnimatingSidebar = true;
    isExpanded = !isExpanded;

    if (!isExpanded) {
        // === MENUTUP ===
        sidebarTexts.forEach((text) => {
            text.classList.add("opacity-0");
            setTimeout(() => text.classList.add("hidden"), 200);
        });

        sidebar.classList.remove("w-64");
        sidebar.classList.add("w-20");

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

        setTimeout(() => {
            sidebar.classList.remove("w-20");
            sidebar.classList.add("w-64");

            setTimeout(() => {
                sidebarTexts.forEach((text) => {
                    text.classList.remove("hidden");
                    requestAnimationFrame(() => {
                        text.classList.remove("opacity-0");
                    });
                });
                isAnimatingSidebar = false;
            }, 150);
        }, 200);
    }
});

// Responsif Init & Resize
function checkScreenSize() {
    const width = window.innerWidth;
    if (width < 640) {
        // Mobile
        sidebar.classList.add("hidden");
        sidebar.classList.remove(
            "flex",
            "absolute",
            "inset-y-0",
            "left-0",
            "z-50",
            "w-64",
            "w-20",
        );
        isExpanded = false;
    } else if (width < 768) {
        // Tablet (Collapsed)
        sidebar.classList.remove(
            "hidden",
            "absolute",
            "inset-y-0",
            "left-0",
            "z-50",
        );
        sidebar.classList.add("flex", "w-20");
        sidebar.classList.remove("w-64");
        isExpanded = false;

        navItems.forEach((item) => {
            item.classList.remove("pl-3");
            item.classList.add("pl-4", "pr-0");
        });
        sidebarTexts.forEach((text) => {
            text.classList.add("opacity-0", "hidden");
        });
    } else {
        // Desktop (Expanded)
        sidebar.classList.remove(
            "hidden",
            "absolute",
            "inset-y-0",
            "left-0",
            "z-50",
            "w-20",
        );
        sidebar.classList.add("flex", "w-64");
        isExpanded = true;

        navItems.forEach((item) => {
            item.classList.remove("pl-4", "pr-0");
            item.classList.add("pl-3");
        });
        sidebarTexts.forEach((text) => {
            text.classList.remove("hidden", "opacity-0");
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
