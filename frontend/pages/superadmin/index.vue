<template>
  <div class="dashboard-page">

    <!-- =========================
         SIDEBAR
    ========================== -->
    <aside class="sidebar">

      <!-- BRAND -->
      <div class="brand-area">
        <div class="brand-icon">
          P
        </div>

        <div class="brand-text">
          <h1>Parkir Plaza</h1>
          <h1>Andalas</h1>
        </div>
      </div>

      <!-- ROLE -->
      <div class="admin-profile">
        <div class="admin-avatar">
          SA
        </div>

        <div class="admin-info">
          <span class="role-label">
            PANEL
          </span>

          <strong>
            Super Admin
          </strong>

          <small>
            Administrator
          </small>
        </div>

        <div class="online-dot"></div>
      </div>

      <!-- MENU -->
      <div class="menu-section">

        <div class="menu-title">
          MENU UTAMA
        </div>

        <!-- DASHBOARD -->
        <button
          class="menu"
          :class="{ active: activeMenu === 'dashboard' }"
          @click="go('/superadmin')"
        >
          <span class="menu-icon">
            ▣
          </span>

          <span>
            Dashboard
          </span>
        </button>

        <!-- PETUGAS -->
        <button
          class="menu"
          :class="{ active: activeMenu === 'petugas' }"
          @click="go('/superadmin/member')"
        >
          <span class="menu-icon">
            ◉
          </span>

          <span>
            Kelola Petugas
          </span>
        </button>

        <!-- LAPORAN -->
        <button
          class="menu"
          :class="{ active: activeMenu === 'laporan' }"
          @click="go('/superadmin/laporan')"
        >
          <span class="menu-icon">
            ▤
          </span>

          <span>
            Laporan
          </span>
        </button>

      </div>

      <!-- SIDEBAR BOTTOM -->
      <div class="sidebar-bottom">

        <div class="system-card">
          <div class="system-icon">
            ⚡
          </div>

          <div class="system-card-text">
            <strong>
              Sistem Aktif
            </strong>

            <span>
              Parkir Plaza Andalas
            </span>
          </div>
        </div>

        <button
          class="logout-btn"
          @click="logout"
        >
          <span>
            ↪
          </span>

          Keluar
        </button>

      </div>

    </aside>


    <!-- =========================
         MAIN
    ========================== -->
    <main class="main-content">

      <!-- TOPBAR -->
      <header class="topbar">

        <div>
          <div class="breadcrumb">
            Dashboard
            <span>›</span>
            Super Admin
          </div>

          <h2>
            Dashboard
          </h2>
        </div>

        <div class="topbar-right">

          <div class="date-box">

            <span class="date-icon">
              ◷
            </span>

            <div>
              <small>
                Hari ini
              </small>

              <strong>
                {{ currentDate }}
              </strong>
            </div>

          </div>

          <div class="header-profile">

            <div class="header-avatar">
              SA
            </div>

            <div>
              <strong>
                Super Admin
              </strong>

              <span>
                Administrator
              </span>
            </div>

          </div>

        </div>

      </header>


      <!-- =========================
           HERO
      ========================== -->
      <section class="hero">

        <div class="hero-glow hero-glow-one"></div>
        <div class="hero-glow hero-glow-two"></div>

        <div class="hero-content">

          <div class="hero-badge">
            <span class="pulse"></span>

            SISTEM PARKIR TERHUBUNG
          </div>

          <h1>
            Halo, Super Admin
            <span>👋</span>
          </h1>

          <p>
            Kelola seluruh sistem Parkir Plaza Andalas
            dengan mudah melalui satu dashboard.
          </p>

          <div class="hero-actions">

            <button
              class="primary-btn"
              @click="go('/superadmin/member')"
            >
              <span>👥</span>

              Kelola Petugas

              <b>→</b>
            </button>

            <button
              class="secondary-btn"
              @click="go('/superadmin/laporan')"
            >
              Lihat Laporan

              <b>↗</b>
            </button>

          </div>

        </div>


        <!-- HERO VISUAL -->
        <div class="hero-visual">

          <div class="orb orb-one"></div>
          <div class="orb orb-two"></div>

          <div class="parking-card">

            <div class="parking-card-top">
              <span>
                STATUS SISTEM
              </span>

              <div class="live-dot"></div>
            </div>

            <div class="parking-logo">
              P
            </div>

            <strong>
              Parkir Plaza
            </strong>

            <span>
              Andalas
            </span>

            <div class="parking-line"></div>

            <div class="parking-status">

              <span>
                <i></i>
                Semua sistem normal
              </span>

              <b>
                LIVE
              </b>

            </div>

          </div>

        </div>

      </section>


      <!-- =========================
           STATISTIK
      ========================== -->
      <section class="stats-grid">

        <!-- PETUGAS -->
        <div class="stat-card">

          <div class="stat-top">

            <div class="stat-icon blue">
              👤
            </div>

            <span class="stat-arrow">
              ↗
            </span>

          </div>

          <div class="stat-label">
            Total Petugas
          </div>

          <div class="stat-value">
            {{ totalPetugas }}
          </div>

          <div class="stat-footer">
            <span class="up">
              ↑ Aktif terdata
            </span>

            <span>
              akun
            </span>
          </div>

        </div>


        <!-- AKTIF -->
        <div class="stat-card">

          <div class="stat-top">

            <div class="stat-icon green">
              ✓
            </div>

            <span class="stat-arrow">
              ↗
            </span>

          </div>

          <div class="stat-label">
            Petugas Aktif
          </div>

          <div class="stat-value">
            {{ petugasAktif }}
          </div>

          <div class="stat-footer">
            <span class="up">
              ● Online
            </span>

            <span>
              petugas
            </span>
          </div>

        </div>


        <!-- MEMBER -->
        <div class="stat-card">

          <div class="stat-top">

            <div class="stat-icon purple">
              ♙
            </div>

            <span class="stat-arrow">
              ↗
            </span>

          </div>

          <div class="stat-label">
            Total Member
          </div>

          <div class="stat-value">
            {{ totalMember }}
          </div>

          <div class="stat-footer">
            <span class="up">
              ↑ Terdaftar
            </span>

            <span>
              member
            </span>
          </div>

        </div>


        <!-- PENDAPATAN -->
        <div class="stat-card income-card">

          <div class="stat-top">

            <div class="stat-icon orange">
              Rp
            </div>

            <span class="stat-arrow">
              ↗
            </span>

          </div>

          <div class="stat-label">
            Total Pendapatan
          </div>

          <div class="stat-value income">
            Rp {{ formatRupiah(totalPendapatan) }}
          </div>

          <div class="stat-footer">
            <span class="up">
              ↑ Transaksi
            </span>

            <span>
              parkir
            </span>
          </div>

        </div>

      </section>


      <!-- =========================
           CONTENT GRID
      ========================== -->
      <section class="content-grid">

        <!-- AKSES CEPAT -->
        <div class="panel quick-panel">

          <div class="panel-header">

            <div>

              <span class="section-kicker">
                AKSES CEPAT
              </span>

              <h3>
                Kelola Sistem
              </h3>

              <p>
                Akses fitur penting dengan lebih cepat.
              </p>

            </div>

            <div class="panel-badge">
              03 MENU
            </div>

          </div>


          <div class="quick-grid">

            <!-- KELOLA PETUGAS -->
            <button
              class="quick-card"
              @click="go('/superadmin/member')"
            >

              <div class="quick-icon blue">
                👤
              </div>

              <div class="quick-info">

                <strong>
                  Kelola Petugas
                </strong>

                <span>
                  Tambah, edit, dan hapus petugas.
                </span>

              </div>

              <div class="quick-arrow">
                →
              </div>

            </button>


            <!-- TAMBAH PETUGAS -->
            <button
              class="quick-card"
              @click="go('/superadmin/tambah')"
            >

              <div class="quick-icon green">
                ＋
              </div>

              <div class="quick-info">

                <strong>
                  Tambah Petugas
                </strong>

                <span>
                  Tambahkan akun petugas baru.
                </span>

              </div>

              <div class="quick-arrow">
                →
              </div>

            </button>


            <!-- LAPORAN -->
            <button
              class="quick-card"
              @click="go('/superadmin/laporan')"
            >

              <div class="quick-icon purple">
                ▤
              </div>

              <div class="quick-info">

                <strong>
                  Laporan
                </strong>

                <span>
                  Lihat laporan aktivitas sistem parkir.
                </span>

              </div>

              <div class="quick-arrow">
                →
              </div>

            </button>

          </div>

        </div>


        <!-- SISTEM -->
        <div class="panel system-panel">

          <div class="panel-header">

            <div>

              <span class="section-kicker">
                SYSTEM MONITOR
              </span>

              <h3>
                Ringkasan Sistem
              </h3>

            </div>

            <div class="system-online">
              <i></i>
              Online
            </div>

          </div>


          <div class="monitor-list">

            <!-- DATABASE -->
            <div class="monitor-item">

              <div class="monitor-left">

                <div class="monitor-icon blue">
                  ◉
                </div>

                <div>
                  <strong>
                    Database
                  </strong>

                  <span>
                    Koneksi database
                  </span>
                </div>

              </div>

              <div class="monitor-right">

                <b>
                  Normal
                </b>

                <i></i>

              </div>

            </div>


            <!-- API -->
            <div class="monitor-item">

              <div class="monitor-left">

                <div class="monitor-icon green">
                  ◈
                </div>

                <div>
                  <strong>
                    Server API
                  </strong>

                  <span>
                    Laravel backend
                  </span>
                </div>

              </div>

              <div class="monitor-right">

                <b>
                  Aktif
                </b>

                <i></i>

              </div>

            </div>


            <!-- DASHBOARD -->
            <div class="monitor-item">

              <div class="monitor-left">

                <div class="monitor-icon purple">
                  ▣
                </div>

                <div>
                  <strong>
                    Dashboard
                  </strong>

                  <span>
                    Frontend Nuxt
                  </span>
                </div>

              </div>

              <div class="monitor-right">

                <b>
                  Aktif
                </b>

                <i></i>

              </div>

            </div>

          </div>

        </div>

      </section>


      <!-- =========================
           INFO BANNER
      ========================== -->
      <section class="info-banner">

        <div class="info-symbol">
          ✦
        </div>

        <div class="info-content">

          <strong>
            Parkir Plaza Andalas
          </strong>

          <span>
            Gunakan menu di sebelah kiri untuk mengelola
            petugas dan memantau laporan sistem parkir.
          </span>

        </div>

        <div class="info-status">
          <span></span>
          Sistem aktif
        </div>

      </section>


      <!-- FOOTER -->
      <footer class="footer">

        <span>
          © {{ currentYear }} Parkir Plaza Andalas
        </span>

        <span>
          Super Admin Panel
        </span>

      </footer>

    </main>

  </div>
</template>


<script setup>
const config = useRuntimeConfig()
const route = useRoute()

const totalPetugas = ref(0)
const petugasAktif = ref(0)
const totalMember = ref(0)
const totalPendapatan = ref(0)

const currentYear = new Date().getFullYear()

const currentDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
})


/* =========================
   ACTIVE MENU
========================= */

const activeMenu = computed(() => {
  const path = route.path

  if (path === '/superadmin') {
    return 'dashboard'
  }

  if (
    path.startsWith('/superadmin/member') ||
    path.startsWith('/superadmin/tambah')
  ) {
    return 'petugas'
  }

  if (path.startsWith('/superadmin/laporan')) {
    return 'laporan'
  }

  return 'dashboard'
})


/* =========================
   FORMAT RUPIAH
========================= */

const formatRupiah = (value) => {
  return Number(value || 0).toLocaleString('id-ID')
}


/* =========================
   NAVIGATION
========================= */

const go = async (path) => {
  await navigateTo(path)
}


/* =========================
   LOAD PETUGAS
========================= */

const loadPetugas = async () => {
  try {
    const response = await $fetch(
      `${config.public.apiBase}/petugas`
    )

    const data =
      response?.data ??
      response ??
      []

    if (Array.isArray(data)) {

      totalPetugas.value = data.length

      petugasAktif.value = data.filter((item) => {

        const status = String(
          item.status ?? ''
        ).toLowerCase()

        return (
          status === 'aktif' ||
          status === 'active' ||
          status === '1'
        )

      }).length
    }

  } catch (error) {

    console.error(
      'Gagal mengambil data petugas:',
      error
    )

  }
}


/* =========================
   LOAD MEMBER
========================= */

const loadMember = async () => {
  try {

    const response = await $fetch(
      `${config.public.apiBase}/members`
    )

    const data =
      response?.data ??
      response ??
      []

    if (Array.isArray(data)) {
      totalMember.value = data.length
    }

  } catch (error) {

    console.error(
      'Gagal mengambil data member:',
      error
    )

  }
}


/* =========================
   LOAD PENDAPATAN
========================= */

const loadPendapatan = async () => {
  try {

    const response = await $fetch(
      `${config.public.apiBase}/tiketparkir`
    )

    const data =
      response?.data ??
      response ??
      []

    if (Array.isArray(data)) {

      totalPendapatan.value =
        data.reduce((total, item) => {

          const biaya = Number(
            item.biaya ??
            item.total_biaya ??
            0
          )

          return total + biaya

        }, 0)
    }

  } catch (error) {

    console.error(
      'Gagal mengambil data pendapatan:',
      error
    )

    totalPendapatan.value = 0
  }
}


/* =========================
   LOGOUT
========================= */

const logout = async () => {

  try {

    localStorage.removeItem('token')
    localStorage.removeItem('user')
    localStorage.removeItem('auth')

  } catch (error) {

    console.error(error)

  }

  await navigateTo('/')
}


/* =========================
   LOAD DATA
========================= */

onMounted(async () => {

  await Promise.all([
    loadPetugas(),
    loadMember(),
    loadPendapatan()
  ])

})
</script>


<style scoped>

/* =================================
   RESET
================================= */

* {
  box-sizing: border-box;
}


/* =================================
   PAGE
================================= */

.dashboard-page {
  min-height: 100vh;

  display: flex;

  background:
    radial-gradient(
      circle at 20% 0%,
      rgba(44, 91, 220, 0.08),
      transparent 28%
    ),
    radial-gradient(
      circle at 90% 30%,
      rgba(115, 74, 230, 0.07),
      transparent 26%
    ),
    #f4f7fc;

  color: #172a57;

  font-family:
    Inter,
    Poppins,
    Arial,
    sans-serif;
}


/* =================================
   SIDEBAR
   UKURAN INI YANG DIBESARKAN
================================= */

.sidebar {
  width: 340px;
  min-width: 340px;

  height: 100vh;

  position: sticky;
  top: 0;

  display: flex;
  flex-direction: column;

  background:
    radial-gradient(
      circle at 20% 10%,
      rgba(67, 115, 255, 0.15),
      transparent 25%
    ),
    linear-gradient(
      180deg,
      #172e68 0%,
      #122553 55%,
      #0e1d43 100%
    );

  color: white;

  padding: 35px 20px 20px;

  overflow: hidden;
}


/* =================================
   BRAND
================================= */

.brand-area {
  display: flex;
  align-items: center;

  gap: 15px;

  padding: 6px 16px 30px;
}

.brand-icon {
  width: 62px;
  height: 62px;

  flex-shrink: 0;

  border-radius: 19px;

  display: flex;
  align-items: center;
  justify-content: center;

  background:
    linear-gradient(
      145deg,
      #4d8aff,
      #1e5de4
    );

  font-size: 28px;
  font-weight: 900;

  box-shadow:
    0 12px 25px rgba(31, 95, 232, 0.35);
}

.brand-text h1 {
  margin: 0;

  font-size: 21px;
  line-height: 1.12;

  font-weight: 850;

  letter-spacing: -0.4px;
}


/* =================================
   ADMIN PROFILE
================================= */

.admin-profile {
  position: relative;

  display: flex;
  align-items: center;

  gap: 14px;

  margin: 7px 0 32px;

  padding: 17px;

  border:
    1px solid rgba(255, 255, 255, 0.08);

  border-radius: 21px;

  background:
    linear-gradient(
      145deg,
      rgba(255, 255, 255, 0.11),
      rgba(255, 255, 255, 0.045)
    );

  box-shadow:
    inset 0 1px 0 rgba(255, 255, 255, 0.05);
}

.admin-avatar {
  width: 54px;
  height: 54px;

  flex-shrink: 0;

  border-radius: 16px;

  display: flex;
  align-items: center;
  justify-content: center;

  background:
    linear-gradient(
      135deg,
      #5d8cff,
      #285ee8
    );

  font-size: 15px;
  font-weight: 900;

  box-shadow:
    0 8px 20px rgba(55, 100, 235, 0.32);
}

.admin-info {
  display: flex;
  flex-direction: column;
}

.role-label {
  font-size: 10px;

  font-weight: 800;

  letter-spacing: 1.5px;

  opacity: 0.55;

  margin-bottom: 3px;
}

.admin-info strong {
  font-size: 16px;
  line-height: 1.15;
}

.admin-info small {
  margin-top: 3px;

  font-size: 12px;

  color:
    rgba(255, 255, 255, 0.58);
}

.online-dot {
  width: 10px;
  height: 10px;

  border-radius: 50%;

  position: absolute;

  right: 17px;
  top: 17px;

  background: #42df97;

  box-shadow:
    0 0 0 5px rgba(66, 223, 151, 0.12);
}


/* =================================
   MENU
================================= */

.menu-section {
  flex: 1;
}

.menu-title {
  padding: 0 16px;

  margin: 0 0 13px;

  font-size: 11px;

  font-weight: 800;

  letter-spacing: 1.6px;

  color:
    rgba(255, 255, 255, 0.42);
}


/* SEMUA MENU UKURANNYA SAMA */

.menu {
  width: 100%;
  height: 74px;
  min-height: 74px;

  margin: 3px 0;

  border: none;

  border-radius: 18px;

  padding: 0 20px;

  display: flex;
  align-items: center;

  gap: 20px;

  color:
    rgba(255, 255, 255, 0.78);

  background: transparent;

  font-size: 16px;

  font-weight: 700;

  cursor: pointer;

  transition:
    transform 0.2s ease,
    background 0.2s ease,
    color 0.2s ease,
    box-shadow 0.2s ease;

  text-align: left;
}

.menu:hover {
  background:
    rgba(255, 255, 255, 0.07);

  color: white;

  transform:
    translateX(2px);
}


/* ACTIVE */

.menu.active {
  background:
    linear-gradient(
      135deg,
      #2f72ef,
      #245bd8
    );

  color: white;

  box-shadow:
    0 12px 24px rgba(28, 80, 190, 0.25);
}


/* ICON */

.menu-icon {
  width: 27px;

  display: inline-flex;
  align-items: center;
  justify-content: center;

  font-size: 20px;

  opacity: 0.95;

  flex-shrink: 0;
}


/* =================================
   SIDEBAR BOTTOM
================================= */

.sidebar-bottom {
  margin-top: 20px;
}


/* SYSTEM CARD */

.system-card {
  display: flex;
  align-items: center;

  gap: 13px;

  padding: 15px;

  border-radius: 20px;

  background:
    rgba(255, 255, 255, 0.07);

  border:
    1px solid rgba(255, 255, 255, 0.07);
}

.system-icon {
  width: 44px;
  height: 44px;

  flex-shrink: 0;

  border-radius: 14px;

  display: flex;
  align-items: center;
  justify-content: center;

  background:
    linear-gradient(
      135deg,
      #ffbd32,
      #f59d16
    );

  color: white;

  font-weight: 800;

  font-size: 19px;
}

.system-card-text {
  display: flex;
  flex-direction: column;
}

.system-card strong {
  font-size: 12px;
}

.system-card span {
  font-size: 10px;

  color:
    rgba(255, 255, 255, 0.53);

  margin-top: 3px;
}


/* LOGOUT */

.logout-btn {
  width: 100%;
  height: 55px;

  margin-top: 11px;

  border-radius: 17px;

  border:
    1px solid rgba(255, 255, 255, 0.12);

  background: transparent;

  color:
    rgba(255, 255, 255, 0.78);

  font-size: 15px;

  font-weight: 700;

  cursor: pointer;

  transition:
    0.2s ease;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 8px;
}

.logout-btn:hover {
  background:
    rgba(255, 255, 255, 0.08);

  color: white;
}


/* =================================
   MAIN
================================= */

.main-content {
  flex: 1;

  min-width: 0;

  padding:
    28px 43px 35px;
}


/* =================================
   TOPBAR
================================= */

.topbar {
  min-height: 70px;

  display: flex;

  justify-content: space-between;
  align-items: center;

  margin-bottom: 24px;
}

.breadcrumb {
  color: #7f8ca9;

  font-size: 11px;

  font-weight: 650;

  margin-bottom: 6px;
}

.breadcrumb span {
  margin: 0 7px;

  color: #b5bfd2;
}

.topbar h2 {
  margin: 0;

  font-size: 29px;

  letter-spacing: -1px;

  color: #122653;
}

.topbar-right {
  display: flex;

  align-items: center;

  gap: 14px;
}

.date-box,
.header-profile {
  display: flex;
  align-items: center;

  gap: 10px;

  padding: 9px 12px;

  border:
    1px solid #e4e9f2;

  border-radius: 14px;

  background:
    rgba(255, 255, 255, 0.85);

  box-shadow:
    0 8px 22px rgba(29, 53, 104, 0.05);
}

.date-icon {
  width: 33px;
  height: 33px;

  border-radius: 10px;

  display: flex;
  align-items: center;
  justify-content: center;

  background: #edf3ff;

  color: #2d68df;
}

.date-box div,
.header-profile div:last-child {
  display: flex;
  flex-direction: column;
}

.date-box small,
.header-profile span {
  font-size: 9px;

  color: #93a0b7;
}

.date-box strong,
.header-profile strong {
  font-size: 11px;

  color: #243661;
}

.header-avatar {
  width: 36px;
  height: 36px;

  border-radius: 11px;

  display: flex;
  align-items: center;
  justify-content: center;

  background:
    linear-gradient(
      135deg,
      #3e78f4,
      #2559d5
    );

  color: white;

  font-size: 10px;

  font-weight: 900;
}


/* =================================
   HERO
================================= */

.hero {
  position: relative;

  min-height: 290px;

  overflow: hidden;

  border-radius: 29px;

  background:
    linear-gradient(
      115deg,
      #2463df 0%,
      #345ce6 45%,
      #4a39cc 100%
    );

  box-shadow:
    0 24px 55px rgba(38, 77, 192, 0.22);

  margin-bottom: 23px;
}

.hero::after {
  content: "";

  position: absolute;

  inset: 0;

  background:
    linear-gradient(
      100deg,
      rgba(255, 255, 255, 0.08),
      transparent 35%
    );

  pointer-events: none;
}

.hero-content {
  position: relative;

  z-index: 4;

  width: 60%;

  padding:
    39px 38px;
}

.hero-badge {
  display: inline-flex;

  align-items: center;

  gap: 8px;

  padding:
    7px 11px;

  border-radius: 999px;

  background:
    rgba(255, 255, 255, 0.12);

  border:
    1px solid rgba(255, 255, 255, 0.14);

  color:
    rgba(255, 255, 255, 0.82);

  font-size: 8px;

  font-weight: 850;

  letter-spacing: 1.2px;
}

.pulse,
.live-dot {
  width: 7px;
  height: 7px;

  border-radius: 50%;

  background: #58e8a0;

  box-shadow:
    0 0 0 4px rgba(88, 232, 160, 0.16);
}

.hero h1 {
  margin:
    22px 0 10px;

  color: white;

  font-size: 38px;

  letter-spacing: -1.6px;

  font-weight: 850;
}

.hero h1 span {
  font-size: 31px;
}

.hero p {
  max-width: 560px;

  margin: 0;

  color:
    rgba(255, 255, 255, 0.75);

  font-size: 13px;

  line-height: 1.7;
}

.hero-actions {
  display: flex;

  gap: 10px;

  margin-top: 25px;
}

.primary-btn,
.secondary-btn {
  height: 45px;

  border-radius: 13px;

  padding: 0 17px;

  border: none;

  font-size: 11px;

  font-weight: 800;

  cursor: pointer;

  display: flex;
  align-items: center;

  gap: 8px;

  transition: 0.2s ease;
}

.primary-btn {
  color: #255bd3;

  background: white;

  box-shadow:
    0 10px 20px rgba(4, 35, 113, 0.18);
}

.primary-btn:hover,
.secondary-btn:hover {
  transform:
    translateY(-2px);
}

.primary-btn b {
  margin-left: 4px;
}

.secondary-btn {
  color: white;

  background:
    rgba(255, 255, 255, 0.11);

  border:
    1px solid rgba(255, 255, 255, 0.18);
}

.secondary-btn b {
  margin-left: 4px;
}


/* =================================
   HERO VISUAL
================================= */

.hero-visual {
  position: absolute;

  right: 35px;
  top: 0;

  width: 39%;
  height: 100%;
}

.orb {
  position: absolute;

  border-radius: 50%;

  border:
    1px solid rgba(255, 255, 255, 0.09);

  background:
    rgba(255, 255, 255, 0.04);
}

.orb-one {
  width: 240px;
  height: 240px;

  right: 15px;
  top: 16px;
}

.orb-two {
  width: 145px;
  height: 145px;

  right: 78px;
  top: 65px;
}

.parking-card {
  position: absolute;

  width: 235px;

  right: 45px;
  top: 31px;

  padding: 22px;

  border-radius: 22px;

  background:
    linear-gradient(
      145deg,
      rgba(255, 255, 255, 0.18),
      rgba(255, 255, 255, 0.07)
    );

  border:
    1px solid rgba(255, 255, 255, 0.18);

  backdrop-filter: blur(15px);

  box-shadow:
    0 25px 45px rgba(22, 36, 110, 0.18);

  transform:
    rotate(3deg);
}

.parking-card-top {
  display: flex;

  justify-content: space-between;

  align-items: center;
}

.parking-card-top span {
  color:
    rgba(255, 255, 255, 0.65);

  font-size: 8px;

  font-weight: 800;

  letter-spacing: 1px;
}

.parking-logo {
  width: 53px;
  height: 53px;

  margin:
    18px 0 12px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 15px;

  background: white;

  color: #315edb;

  font-size: 24px;

  font-weight: 900;
}

.parking-card strong,
.parking-card > span {
  display: block;

  color: white;

  font-size: 15px;
}

.parking-card > span {
  font-size: 11px;

  opacity: 0.65;
}

.parking-line {
  height: 1px;

  margin:
    17px 0 13px;

  background:
    rgba(255, 255, 255, 0.11);
}

.parking-status {
  display: flex;

  justify-content: space-between;

  align-items: center;
}

.parking-status span {
  color:
    rgba(255, 255, 255, 0.67);

  font-size: 9px;
}

.parking-status span i {
  display: inline-block;

  width: 6px;
  height: 6px;

  margin-right: 5px;

  border-radius: 50%;

  background: #4ae29b;
}

.parking-status b {
  font-size: 8px;

  padding:
    5px 7px;

  border-radius: 7px;

  background:
    rgba(74, 226, 155, 0.13);

  color: #68efa9;
}


/* =================================
   GLOW
================================= */

.hero-glow {
  position: absolute;

  border-radius: 50%;

  filter: blur(4px);
}

.hero-glow-one {
  width: 340px;
  height: 340px;

  right: -100px;
  top: -140px;

  background:
    rgba(117, 102, 255, 0.33);
}

.hero-glow-two {
  width: 170px;
  height: 170px;

  right: 240px;
  bottom: -90px;

  background:
    rgba(0, 187, 255, 0.12);
}


/* =================================
   STATISTICS
================================= */

.stats-grid {
  display: grid;

  grid-template-columns:
    repeat(4, 1fr);

  gap: 17px;

  margin-bottom: 23px;
}

.stat-card {
  padding: 19px;

  border-radius: 21px;

  background:
    rgba(255, 255, 255, 0.9);

  border:
    1px solid #e7ebf3;

  box-shadow:
    0 13px 30px rgba(26, 50, 96, 0.055);

  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.stat-card:hover {
  transform:
    translateY(-4px);

  box-shadow:
    0 18px 35px rgba(30, 55, 105, 0.09);
}

.stat-top {
  display: flex;

  justify-content: space-between;

  align-items: center;
}

.stat-icon {
  width: 44px;
  height: 44px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 14px;

  font-size: 17px;

  font-weight: 900;
}

.stat-icon.blue {
  color: #2c67e7;
  background: #eaf1ff;
}

.stat-icon.green {
  color: #11a878;
  background: #e5f8f0;
}

.stat-icon.purple {
  color: #7443d7;
  background: #f0eaff;
}

.stat-icon.orange {
  color: #dc9815;
  background: #fff1d9;

  font-size: 11px;
}

.stat-arrow {
  color: #aab7cd;
}

.stat-label {
  margin-top: 17px;

  color: #8a97ae;

  font-size: 11px;
}

.stat-value {
  margin-top: 4px;

  color: #142b5b;

  font-size: 29px;

  font-weight: 850;

  letter-spacing: -1px;
}

.stat-value.income {
  font-size: 21px;
}

.stat-footer {
  display: flex;

  gap: 4px;

  margin-top: 8px;

  color: #9da8bb;

  font-size: 9px;
}

.stat-footer .up {
  color: #26a574;

  font-weight: 700;
}


/* =================================
   CONTENT GRID
================================= */

.content-grid {
  display: grid;

  grid-template-columns:
    1.7fr 1fr;

  gap: 19px;

  margin-bottom: 21px;
}

.panel {
  padding: 23px;

  border-radius: 23px;

  background:
    rgba(255, 255, 255, 0.92);

  border:
    1px solid #e7ebf3;

  box-shadow:
    0 13px 30px rgba(26, 50, 96, 0.05);
}

.panel-header {
  display: flex;

  justify-content: space-between;

  align-items: flex-start;

  margin-bottom: 19px;
}

.section-kicker {
  color: #6c7e9f;

  font-size: 8px;

  font-weight: 850;

  letter-spacing: 1.3px;
}

.panel-header h3 {
  margin:
    6px 0 3px;

  color: #17305e;

  font-size: 18px;

  letter-spacing: -0.4px;
}

.panel-header p {
  margin: 0;

  color: #9aa6b9;

  font-size: 10px;
}

.panel-badge {
  padding:
    7px 10px;

  border-radius: 9px;

  background: #f0f4fa;

  color: #8190a9;

  font-size: 8px;

  font-weight: 800;
}


/* =================================
   QUICK
================================= */

.quick-grid {
  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 10px;
}

.quick-card {
  min-height: 145px;

  position: relative;

  padding: 17px;

  border-radius: 16px;

  border:
    1px solid #e6ebf3;

  background: white;

  cursor: pointer;

  text-align: left;

  transition: 0.2s ease;
}

.quick-card:hover {
  transform:
    translateY(-3px);

  box-shadow:
    0 12px 25px rgba(35, 63, 113, 0.08);

  border-color: #dce5f8;
}

.quick-icon {
  width: 39px;
  height: 39px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 12px;

  font-size: 16px;

  font-weight: 800;
}

.quick-icon.blue {
  color: #2c67e7;
  background: #eaf1ff;
}

.quick-icon.green {
  color: #15ab77;
  background: #e6f9f1;
}

.quick-icon.purple {
  color: #7443d7;
  background: #f0eaff;
}

.quick-info {
  display: flex;

  flex-direction: column;

  margin-top: 17px;
}

.quick-info strong {
  color: #19325f;

  font-size: 11px;
}

.quick-info span {
  max-width: 145px;

  margin-top: 5px;

  color: #9aa5b8;

  font-size: 9px;

  line-height: 1.5;
}

.quick-arrow {
  position: absolute;

  right: 14px;
  bottom: 13px;

  color: #a3afc1;

  font-size: 15px;
}


/* =================================
   SYSTEM
================================= */

.system-online {
  display: flex;

  align-items: center;

  gap: 6px;

  padding:
    6px 9px;

  border-radius: 999px;

  background: #eafaf2;

  color: #22a674;

  font-size: 9px;

  font-weight: 800;
}

.system-online i {
  width: 6px;
  height: 6px;

  border-radius: 50%;

  background: #32cd91;
}

.monitor-list {
  display: flex;

  flex-direction: column;

  gap: 6px;
}

.monitor-item {
  min-height: 64px;

  padding:
    10px 12px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  border-radius: 14px;

  background: #fafbfd;
}

.monitor-left {
  display: flex;

  align-items: center;

  gap: 10px;
}

.monitor-icon {
  width: 37px;
  height: 37px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 11px;

  font-size: 13px;
}

.monitor-icon.blue {
  color: #2f6be8;
  background: #eaf1ff;
}

.monitor-icon.green {
  color: #1aa477;
  background: #e8f9f2;
}

.monitor-icon.purple {
  color: #7748db;
  background: #f1ebff;
}

.monitor-left div:last-child {
  display: flex;

  flex-direction: column;
}

.monitor-left strong {
  color: #203862;

  font-size: 10px;
}

.monitor-left span {
  margin-top: 3px;

  color: #9aa6b9;

  font-size: 8px;
}

.monitor-right {
  display: flex;

  align-items: center;

  gap: 7px;
}

.monitor-right b {
  color: #23a372;

  font-size: 8px;
}

.monitor-right i {
  width: 7px;
  height: 7px;

  border-radius: 50%;

  background: #3ed69a;
}


/* =================================
   INFO
================================= */

.info-banner {
  min-height: 77px;

  display: flex;

  align-items: center;

  gap: 13px;

  padding:
    14px 18px;

  border-radius: 18px;

  background:
    linear-gradient(
      100deg,
      #eef4ff,
      #f5f7ff
    );

  border:
    1px solid #dbe6fa;

  margin-bottom: 18px;
}

.info-symbol {
  width: 41px;
  height: 41px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 13px;

  background: #dce8ff;

  color: #3268df;

  font-size: 18px;
}

.info-content {
  flex: 1;

  display: flex;

  flex-direction: column;
}

.info-content strong {
  font-size: 11px;

  color: #1c396e;
}

.info-content span {
  margin-top: 3px;

  color: #8794ab;

  font-size: 9px;
}

.info-status {
  display: flex;

  align-items: center;

  gap: 6px;

  padding:
    7px 10px;

  border-radius: 9px;

  background: white;

  color: #3d8f74;

  font-size: 8px;

  font-weight: 800;
}

.info-status span {
  width: 6px;
  height: 6px;

  border-radius: 50%;

  background: #3ac592;
}


/* =================================
   FOOTER
================================= */

.footer {
  display: flex;

  justify-content: space-between;

  color: #9aa6b9;

  font-size: 9px;

  padding:
    2px 3px;
}


/* =================================
   RESPONSIVE
================================= */

@media (max-width: 1200px) {

  .main-content {
    padding: 25px;
  }

  .hero-content {
    width: 64%;
  }

  .hero-visual {
    right: 10px;
  }

  .parking-card {
    right: 25px;
  }

  .stats-grid {
    grid-template-columns:
      repeat(2, 1fr);
  }

  .content-grid {
    grid-template-columns:
      1fr;
  }
}


/* =================================
   TABLET
================================= */

@media (max-width: 850px) {

  .sidebar {
    width: 340px;
    min-width: 340px;
  }

  .main-content {
    padding: 20px;
  }

  .hero {
    min-height: 315px;
  }

  .hero-content {
    width: 100%;
  }

  .hero-visual {
    display: none;
  }

  .topbar-right {
    display: none;
  }
}


/* =================================
   MOBILE
================================= */

@media (max-width: 650px) {

  .dashboard-page {
    display: block;
  }

  .sidebar {
    width: 100%;
    min-width: 100%;

    height: auto;

    position: relative;

    padding:
      22px 18px;
  }

  .menu-section {
    display: none;
  }

  .sidebar-bottom {
    display: none;
  }

  .admin-profile {
    margin-bottom: 0;
  }

  .stats-grid {
    grid-template-columns:
      1fr;
  }

  .quick-grid {
    grid-template-columns:
      1fr;
  }

  .topbar {
    margin-top: 10px;
  }

  .hero-content {
    padding:
      28px 23px;
  }

  .hero h1 {
    font-size: 29px;
  }

  .info-status {
    display: none;
  }

  .footer {
    flex-direction: column;

    gap: 5px;
  }
}

</style>
