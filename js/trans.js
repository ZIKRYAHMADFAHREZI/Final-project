document.addEventListener("DOMContentLoaded", function () {
  const loading = document.getElementById("loading");

  // Fungsi untuk berpindah tab dengan animasi
  function switchTab(url) {
    loading.classList.add("active"); // Menambahkan kelas aktif untuk animasi
    setTimeout(() => {
      window.location.href = "" + url; // Setelah animasi selesai, pindah ke URL baru
    }, 500); // Waktu yang sama dengan durasi animasi
  }

  // Event listener untuk tombol
  document.getElementById("name")?.addEventListener("click", function () {
    switchTab("index.php");
  });

  document.getElementById("home")?.addEventListener("click", function () {
    switchTab("index.php");
  });

  document.getElementById("about")?.addEventListener("click", function () {
    switchTab("about.php");
  });
  document.getElementById("login")?.addEventListener("click", function () {
    switchTab("login.html");
  });
  document.getElementById("regist")?.addEventListener("click", function () {
    switchTab("register.html");
  });
  document.getElementById("profile")?.addEventListener("click", function () {
    switchTab("user_profile.php");
  });
});
