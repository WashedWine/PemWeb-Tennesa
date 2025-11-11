// script.js

// Fungsi untuk mengambil data sesi
async function checkUserSession() {
  try {
    const response = await fetch("api/session.php");
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error checking session:", error);
    return { success: false, user: null };
  }
}

// Fungsi untuk logout
async function handleLogout() {
  try {
    await fetch("api/logout.php");
    localStorage.removeItem("user"); // Hapus data user dari localStorage
    window.location.href = "login.html";
  } catch (error) {
    console.error("Error logging out:", error);
  }
}

// Fungsi untuk mengelola UI berdasarkan status login
function setupUI(user) {
  const authBtn = document.querySelector(".auth-btn");
  const dashboardLink = document.getElementById("dashboard-link");

  if (user) {
    // User LOGIN
    if (authBtn) {
      authBtn.innerHTML = `
        <span>Halo, ${user.name || user.email}</span>
        <a href="#" id="logout" class="register">Logout</a>
      `;
      document.getElementById("logout").addEventListener("click", (e) => {
        e.preventDefault();
        handleLogout();
      });
    }

    if (dashboardLink) {
      dashboardLink.style.display = 'inline';
      dashboardLink.href = user.role === "admin" ? "admin.html" : "user.html";
      if (window.location.pathname.includes("admin.html") || window.location.pathname.includes("user.html")) {
        dashboardLink.style.display = "none";
      }
    }
    
    // Redirect jika salah halaman
    if (user.role === "admin" && window.location.pathname.includes("user.html")) {
      window.location.href = "admin.html";
    }
    if (user.role === "user" && window.location.pathname.includes("admin.html")) {
      window.location.href = "user.html";
    }

  } else {
    // User TIDAK LOGIN
    localStorage.removeItem("user"); // Pastikan localStorage bersih jika sesi tidak ada

    if (authBtn) {
      // Tampilkan tombol Login/Register default jika authBtn ada
      authBtn.innerHTML = `
        <a href="login.html" class="login">Login</a>
        <a href="register.html" class="register">Register</a>
      `;
    }

    if (dashboardLink) {
      dashboardLink.style.display = "none";
    }
    
    // Lindungi halaman privat
    const privatePages = ["admin.html", "user.html"];
    const path = window.location.pathname;
    const pageName = path.split('/').pop();

    if (privatePages.includes(pageName)) {
      window.location.href = "login.html";
    }
  }
}

// Jalankan saat DOM dimuat
document.addEventListener("DOMContentLoaded", async () => {
  const sessionData = await checkUserSession();
  setupUI(sessionData.user);
});