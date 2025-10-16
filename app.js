document.addEventListener("DOMContentLoaded", () => {
  // Fungsi untuk inisialisasi data awal jika localStorage kosong
  const initializeData = () => {
    // Inisialisasi data lapangan jika belum ada
    if (!localStorage.getItem("tennesa_courts")) {
      const courts = [
        {
          id: 1,
          name: "Lapangan A",
          facilities: "Lighting, Net Standar, Lantai Sintetis",
          price: 150000,
          status: "Tersedia",
        },
        {
          id: 2,
          name: "Lapangan B",
          facilities: "Lighting, Net Standar, Lantai Clay",
          price: 120000,
          status: "Tersedia",
        },
        {
          id: 3,
          name: "Lapangan C",
          facilities: "Net Standar, Lantai Hard Court",
          price: 100000,
          status: "Penuh",
        },
      ];
      localStorage.setItem("tennesa_courts", JSON.stringify(courts));
    }

    // Inisialisasi data booking (kosong) jika belum ada
    if (!localStorage.getItem("tennesa_bookings")) {
      localStorage.setItem("tennesa_bookings", JSON.stringify([]));
    }

    // Inisialisasi data notifikasi (kosong) jika belum ada
    if (!localStorage.getItem("tennesa_notifications")) {
        localStorage.setItem("tennesa_notifications", JSON.stringify([]));
    }
  };

  initializeData();
});