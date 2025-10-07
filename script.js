document.addEventListener("DOMContentLoaded", () => {
  const currentUser = localStorage.getItem("user");
  const authBtn = document.querySelector(".auth-btn");
  const dashboardLink = document.getElementById("dashboard-link");

  if (currentUser) {
    const user = JSON.parse(currentUser);
    if (authBtn) {
      authBtn.innerHTML = `
        <span>Halo, ${user.name || user.email}</span>
        <a href="#" id="logout" class="register">Logout</a>
      `;
      document.getElementById("logout").addEventListener("click", (e) => {
        e.preventDefault();
        localStorage.removeItem("user");
        window.location.href = "login.html";
      });
    }

    // Mengelola visibilitas dan tujuan link "Dashboard"
    if (dashboardLink) {
      dashboardLink.style.display = 'inline'; // Tampilkan link dashboard
      dashboardLink.href = user.role === "admin" ? "admin.html" : "user.html";
      // Sembunyikan jika sudah berada di halaman dashboard
      if (window.location.pathname.includes("admin.html") || window.location.pathname.includes("user.html")) {
        dashboardLink.style.display = "none";
      }
    }

    // Redirect otomatis jika mencoba mengakses halaman yang salah
    if (user.role === "admin" && window.location.pathname.includes("user.html")) {
      window.location.href = "admin.html";
    }
    if (user.role === "user" && window.location.pathname.includes("admin.html")) {
      window.location.href = "user.html";
    }
  } else {
    // Sembunyikan link "Dashboard" jika belum login
    if (dashboardLink) {
      dashboardLink.style.display = "none";
    }
    
    // Logika baru untuk memeriksa halaman mana yang boleh diakses tanpa login
    const publicPages = ["index.html", "tentang.html", "login.html", "register.html"];
    const path = window.location.pathname;
    const pageName = path.split('/').pop();

    // Boleh diakses jika: halaman adalah root ("/" atau "") atau termasuk dalam daftar publicPages
    const isPublic = pageName === "" || publicPages.includes(pageName);

    if (!isPublic) {
      window.location.href = "login.html"; // Alihkan ke login jika halaman tidak publik
    }
    // =============================
  }

  // Handler untuk form login
  const loginForm = document.querySelector("form");
  if (loginForm && window.location.pathname.includes("login.html")) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = loginForm.querySelector("input[type='email']").value;

      let role = "user";
      let name = "John Doe"; // Default name
      let redirect = "user.html";

      if (email === "admin@tennesa.com") {
        role = "admin";
        name = "Admin TENNESA";
        redirect = "admin.html";
      }

      localStorage.setItem("user", JSON.stringify({ email, role, name }));
      window.location.href = redirect;
    });
  }
});