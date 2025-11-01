    </div> <!-- END CONTENT -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById("sidebar");
        const toggle = document.getElementById("sidebarToggle");
        const menuBtn = document.getElementById("menuBtn");

        // Desktop toggle collapse
        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });

        // Mobile menu toggle
        if (menuBtn) {
            menuBtn.addEventListener("click", () => {
                sidebar.classList.toggle("show");
            });
        }
    </script>
</body>

</html>
