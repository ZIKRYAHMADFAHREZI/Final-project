function confirmLogout() {
    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda akan keluar dari akun ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../logout.php'; // Ganti URL sesuai dengan rute logout Anda
        }
    });
}

// Sidebar Toggle
document.getElementById("toggle-btn").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    sidebar.classList.toggle("closed");
    content.classList.toggle("expanded");
});

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdowns = document.querySelectorAll('.dropdown-toggle');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        var isClickInside = false;
        dropdowns.forEach(function(dropdown) {
            if (dropdown.contains(event.target)) {
                isClickInside = true;
            }
        });
        
        if (!isClickInside) {
            var openDropdowns = document.querySelectorAll('.collapse.show');
            openDropdowns.forEach(function(dropdown) {
                bootstrap.Collapse.getInstance(dropdown)?.hide();
            });
        }
    });
});