document.addEventListener("DOMContentLoaded", function () {
    // Xử lý submenu trên mobile
    document.querySelectorAll(".dropdown-menu .dropdown-toggle").forEach(function (element) {
        element.addEventListener("click", function (event) {
            if (window.innerWidth < 992) { // Mobile
                event.preventDefault();
                event.stopPropagation();

                let nextMenu = this.nextElementSibling;
                if (nextMenu && nextMenu.classList.contains("dropdown-menu")) {
                    let isOpen = nextMenu.classList.contains("show");

                    // Đóng tất cả submenu trước khi mở cái mới
                    document.querySelectorAll(".dropdown-menu .show").forEach(submenu => {
                        submenu.classList.remove("show");
                    });

                    // Nếu submenu chưa mở, thì mở nó
                    if (!isOpen) {
                        nextMenu.classList.add("show");
                    }
                }
            }
        });
    });

    // Đóng menu khi click ra ngoài trên mobile
    document.addEventListener("click", function (event) {
        if (window.innerWidth < 992) {
            document.querySelectorAll(".dropdown-menu .show").forEach(submenu => {
                if (!submenu.parentNode.contains(event.target)) {
                    submenu.classList.remove("show");
                }
            });
        }
    });
});
// Hiện nút cuộn lên khi kéo xuống
window.addEventListener("scroll", function () {
    let scrollTopBtn = document.getElementById("scrollTopBtn");
    if (window.scrollY > 200) {
        scrollTopBtn.style.display = "flex";
    } else {
        scrollTopBtn.style.display = "none";
    }
});

// Xử lý sự kiện click cuộn lên đầu trang
document.getElementById("scrollTopBtn").addEventListener("click", function () {
    window.scrollTo({ top: 0, behavior: "smooth" });
});
