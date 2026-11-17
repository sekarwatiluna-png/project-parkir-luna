<template>
  <div class="page">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar">

      <!-- LOGO -->
      <div>
        <div class="logo-area">
          <div class="logo-icon">
            🅿️
          </div>

          <div class="logo-text">
            <h2>ParkirKu</h2>
            <p>Parkir Plaza Andalas</p>
          </div>
        </div>

        <!-- MENU -->
        <div class="menu-title">
          MENU UTAMA
        </div>

        <nav class="menu">

          <button
            type="button"
            class="menu-item"
            @click="keDashboard"
          >
            <span class="menu-icon">📊</span>
            <span>Dashboard</span>
          </button>

          <button
            type="button"
            class="menu-item"
            @click="keTransaksi"
          >
            <span class="menu-icon">💳</span>
            <span>Kelola Transaksi</span>
          </button>

          <button
            type="button"
            class="menu-item active"
            @click="keMember"
          >
            <span class="menu-icon">👥</span>
            <span>Kelola Member</span>
          </button>

          <button
            type="button"
            class="menu-item"
            @click="keScanKeluar"
          >
            <span class="menu-icon">📤</span>
            <span>Scan Keluar</span>
          </button>

          <button
            type="button"
            class="menu-item"
            @click="keLaporan"
          >
            <span class="menu-icon">📋</span>
            <span>Laporan</span>
          </button>

        </nav>
      </div>

      <!-- USER -->
      <div class="sidebar-bottom">

        <div class="user-box">

          <div class="avatar">
            {{ userInitial }}
          </div>

          <div class="user-info">
            <strong>{{ userName }}</strong>
            <small>Petugas</small>
          </div>

        </div>

        <button
          type="button"
          class="logout-button"
          @click="logout"
        >
          🚪
          <span>Keluar</span>
        </button>

      </div>

    </aside>


    <!-- =====================================================
         CONTENT
    ====================================================== -->
    <main class="content">

      <!-- TOPBAR -->
      <header class="topbar">

        <div class="topbar-left">

          <button
            type="button"
            class="back-button"
            @click="keMember"
          >
            <span>←</span>
            <span>Kembali ke Member</span>
          </button>

          <div class="page-heading">

            <div class="heading-icon">
              🪪
            </div>

            <div>
              <h1>Kartu Member</h1>
              <p>Detail kartu member parkir</p>
            </div>

          </div>

        </div>

        <div class="topbar-right">

          <div class="notification">
            🔔
          </div>

          <div class="profile-mini">
            <div class="profile-avatar">
              {{ userInitial }}
            </div>

            <div>
              <strong>Petugas</strong>
              <span>⌄</span>
            </div>
          </div>

        </div>

      </header>


      <!-- =====================================================
           LOADING
      ====================================================== -->
      <div
        v-if="loading"
        class="loading-container"
      >
        <div class="loading-card">

          <div class="spinner"></div>

          <h3>Memuat kartu member...</h3>

          <p>
            Tunggu sebentar.
          </p>

        </div>
      </div>


      <!-- =====================================================
           ERROR
      ====================================================== -->
      <div
        v-else-if="errorMessage"
        class="error-container"
      >

        <div class="error-card">

          <div class="error-icon">
            ⚠️
          </div>

          <h2>Data Member Tidak Ditemukan</h2>

          <p>
            {{ errorMessage }}
          </p>

          <button
            type="button"
            class="primary-button"
            @click="keMember"
          >
            ← Kembali ke Member
          </button>

        </div>

      </div>


      <!-- =====================================================
           KARTU
      ====================================================== -->
      <section
        v-else-if="member"
        class="main-section"
      >

        <!-- DETAIL MEMBER -->
        <div class="detail-card">

          <div class="detail-header">

            <div>
              <h2>Informasi Member</h2>
              <p>
                Data lengkap member parkir
              </p>
            </div>

            <div
              class="status-badge"
              :class="isLunas ? 'lunas' : 'belum'"
            >
              <span class="status-dot"></span>

              {{ isLunas ? "LUNAS" : "BELUM LUNAS" }}
            </div>

          </div>


          <!-- INFORMATION GRID -->
          <div class="info-grid">

            <!-- NAMA -->
            <div class="info-item">

              <div class="info-icon">
                👤
              </div>

              <div class="info-content">

                <span>Nama Member</span>

                <strong>
                  {{ member.nama_member || "-" }}
                </strong>

              </div>

            </div>


            <!-- PERUSAHAAN -->
            <div class="info-item">

              <div class="info-icon">
                🏢
              </div>

              <div class="info-content">

                <span>Perusahaan</span>

                <strong>
                  {{ member.nama_perusahaan || "-" }}
                </strong>

              </div>

            </div>


            <!-- KODE -->
            <div class="info-item">

              <div class="info-icon">
                🔖
              </div>

              <div class="info-content">

                <span>Kode Member</span>

                <strong class="code-text">
                  {{ member.kode_member || "-" }}
                </strong>

              </div>

            </div>


            <!-- STATUS -->
            <div class="info-item">

              <div class="info-icon">
                📅
              </div>

              <div class="info-content">

                <span>Status Pembayaran</span>

                <strong
                  :class="isLunas ? 'text-green' : 'text-orange'"
                >
                  {{ isLunas ? "Lunas" : "Belum Lunas" }}
                </strong>

              </div>

            </div>


            <!-- TANGGAL BAYAR -->
            <div class="info-item">

              <div class="info-icon">
                🗓️
              </div>

              <div class="info-content">

                <span>Tanggal Bayar</span>

                <strong>
                  {{ formatTanggal(member.tanggal_bayar) }}
                </strong>

              </div>

            </div>


            <!-- EXPIRED -->
            <div class="info-item">

              <div class="info-icon">
                ⏰
              </div>

              <div class="info-content">

                <span>Tanggal Expired</span>

                <strong>
                  {{ formatTanggal(member.tanggal_expired) }}
                </strong>

              </div>

            </div>

          </div>


          <!-- =================================================
               KARTU MEMBER VISUAL
          ================================================== -->
          <div class="member-card-section">

            <div class="member-card-title">
              KARTU MEMBER
            </div>

            <div
              id="print-card"
              class="member-card"
            >

              <!-- BACKGROUND DECORATION -->
              <div class="card-circle circle-one"></div>
              <div class="card-circle circle-two"></div>

              <!-- CARD HEADER -->
              <div class="card-header">

                <div class="brand">

                  <div class="brand-icon">
                    🅿️
                  </div>

                  <div>
                    <h3>PARKIRKU</h3>
                    <p>Parkir Plaza Andalas</p>
                  </div>

                </div>

                <div
                  class="card-status"
                  :class="isLunas ? 'lunas' : 'belum'"
                >
                  {{ isLunas ? "LUNAS" : "BELUM LUNAS" }}
                </div>

              </div>


              <!-- CARD BODY -->
              <div class="card-body">

                <div class="card-information">

                  <div class="card-label">
                    NAMA
                  </div>

                  <div class="card-name">
                    {{ member.nama_member || "-" }}
                  </div>


                  <div class="card-label card-label-code">
                    KODE MEMBER
                  </div>

                  <div class="card-code">
                    {{ member.kode_member || "-" }}
                  </div>


                  <div class="card-label card-label-company">
                    PERUSAHAAN
                  </div>

                  <div class="card-company">
                    {{ member.nama_perusahaan || "-" }}
                  </div>

                </div>


                <!-- QR -->
                <div class="qr-container">

                  <QrcodeVue
                    :value="qrValue"
                    :size="145"
                    level="H"
                    render-as="svg"
                    background="#ffffff"
                    foreground="#111827"
                  />

                </div>

              </div>

            </div>

          </div>


          <!-- =================================================
               PAYMENT INFO
          ================================================== -->
          <div class="payment-summary">

            <div class="payment-item">

              <span>Total Harga</span>

              <strong>
                Rp {{ rupiah(member.total_harga) }}
              </strong>

            </div>

            <div class="payment-item">

              <span>Jumlah Dibayar</span>

              <strong class="green-money">
                Rp {{ rupiah(member.jumlah_bayar) }}
              </strong>

            </div>

            <div class="payment-item">

              <span>Kembalian</span>

              <strong>
                Rp {{ rupiah(member.kembalian) }}
              </strong>

            </div>

          </div>


          <!-- =================================================
               BUTTON
          ================================================== -->
          <div class="button-area">

            <button
              type="button"
              class="download-button"
              @click="cetakKartu"
            >
              <span>⬇️</span>
              <span>Download Kartu Member</span>
            </button>

            <button
              type="button"
              class="back-outline-button"
              @click="keMember"
            >
              <span>←</span>
              <span>Kembali</span>
            </button>

          </div>

        </div>

      </section>

    </main>

  </div>
</template>


<script setup>
import {
  ref,
  computed,
  onMounted
} from "vue"

import QrcodeVue from "qrcode.vue"


/* =========================================================
   ROUTER
========================================================= */

const router = useRouter()
const route = useRoute()
const config = useRuntimeConfig()


/* =========================================================
   API
========================================================= */

const API_URL = String(
  config.public?.apiBase ||
  "http://127.0.0.1:8000/api"
).replace(/\/$/, "")


/* =========================================================
   DATA
========================================================= */

const member = ref(null)

const loading = ref(false)

const errorMessage = ref("")

const userName = ref("Petugas")


/* =========================================================
   USER
========================================================= */

const userInitial = computed(() => {
  return String(
    userName.value || "P"
  )
    .charAt(0)
    .toUpperCase()
})


/* =========================================================
   TOKEN
========================================================= */

function getToken() {

  if (!process.client) {
    return ""
  }

  return (
    localStorage.getItem("token") ||
    localStorage.getItem("access_token") ||
    ""
  )
}


/* =========================================================
   LOGIN
========================================================= */

function cekLogin() {

  if (!process.client) {
    return true
  }

  const token =
    localStorage.getItem("token") ||
    localStorage.getItem("access_token")

  const role =
    localStorage.getItem("role")

  if (!token) {

    router.push("/")

    return false
  }

  if (
    role &&
    role !== "petugas"
  ) {

    if (role === "superadmin") {

      router.push("/superadmin")

    } else {

      router.push("/")
    }

    return false
  }


  const userData =
    localStorage.getItem("user")

  if (userData) {

    try {

      const user =
        JSON.parse(userData)

      userName.value =
        user?.name ||
        user?.nama ||
        user?.username ||
        "Petugas"

    } catch {

      userName.value = "Petugas"

    }
  }

  return true
}


/* =========================================================
   STATUS
========================================================= */

const isLunas = computed(() => {

  return String(
    member.value?.status || ""
  )
    .trim()
    .toLowerCase() === "lunas"

})


/* =========================================================
   QR VALUE
========================================================= */

const qrValue = computed(() => {

  if (!member.value) {
    return ""
  }

  return String(
    member.value.token ||
    member.value.kode_member ||
    member.value.id ||
    ""
  )

})


/* =========================================================
   FORMAT RUPIAH
========================================================= */

function rupiah(value) {

  return new Intl.NumberFormat(
    "id-ID"
  ).format(
    Number(value || 0)
  )

}


/* =========================================================
   FORMAT TANGGAL
========================================================= */

function formatTanggal(value) {

  if (!value) {
    return "-"
  }

  const date =
    new Date(value)

  if (
    Number.isNaN(
      date.getTime()
    )
  ) {

    return String(value)

  }

  return date.toLocaleDateString(
    "id-ID",
    {
      day: "2-digit",
      month: "numeric",
      year: "numeric"
    }
  )

}


/* =========================================================
   LOAD MEMBER
========================================================= */

async function loadMember() {

  loading.value = true

  errorMessage.value = ""

  try {

    const token =
      getToken()

    if (!token) {

      router.push("/")

      return
    }


    const id =
      String(
        route.params.id || ""
      )


    if (!id) {

      errorMessage.value =
        "ID member tidak ditemukan."

      return
    }


    console.log(
      "LOAD MEMBER ID:",
      id
    )


    const response =
      await $fetch(
        `${API_URL}/members/${id}`,
        {
          method: "GET",

          headers: {
            Accept:
              "application/json",

            Authorization:
              `Bearer ${token}`
          }
        }
      )


    console.log(
      "RESPON MEMBER:",
      response
    )


    let data = null


    if (
      response?.data &&
      !Array.isArray(response.data)
    ) {

      data =
        response.data

    } else if (
      response?.member
    ) {

      data =
        response.member

    } else {

      data =
        response
    }


    member.value = data


    if (!member.value) {

      errorMessage.value =
        "Data member tidak ditemukan."

    }

  } catch (error) {

    console.error(
      "ERROR LOAD KARTU:",
      error
    )


    const status =
      error?.statusCode ||
      error?.response?.status ||
      error?.status


    if (status === 401) {

      localStorage.removeItem("token")
      localStorage.removeItem("access_token")
      localStorage.removeItem("user")
      localStorage.removeItem("role")

      router.push("/")

      return
    }


    errorMessage.value =
      error?.data?.message ||
      error?.response?._data?.message ||
      error?.message ||
      "Gagal mengambil data member."

  } finally {

    loading.value = false

  }

}


/* =========================================================
   CETAK / DOWNLOAD
========================================================= */

function cetakKartu() {

  if (!member.value) {
    return
  }

  window.print()

}


/* =========================================================
   NAVIGATION
========================================================= */

function keDashboard() {

  router.push("/petugas")

}


function keTransaksi() {

  router.push("/petugas/transaksi")

}


function keMember() {

  router.push("/petugas/member")

}


function keScanKeluar() {

  router.push("/petugas/scan-keluar")

}


function keLaporan() {

  router.push("/petugas/laporan")

}


/* =========================================================
   LOGOUT
========================================================= */

function logout() {

  if (process.client) {

    localStorage.removeItem("token")
    localStorage.removeItem("access_token")
    localStorage.removeItem("user")
    localStorage.removeItem("role")

  }

  router.push("/")

}


/* =========================================================
   MOUNTED
========================================================= */

onMounted(async () => {

  const boleh =
    cekLogin()

  if (!boleh) {
    return
  }

  await loadMember()

})
</script>


<style scoped>

/* =========================================================
   GLOBAL
========================================================= */

* {
  box-sizing: border-box;
}

.page {
  min-height: 100vh;

  display: flex;

  background:
    linear-gradient(
      135deg,
      #eef4ff 0%,
      #f8f8ff 45%,
      #f1edff 100%
    );

  color: #172554;
}


/* =========================================================
   SIDEBAR
========================================================= */

.sidebar {
  position: fixed;

  left: 0;
  top: 0;
  bottom: 0;

  width: 260px;

  display: flex;
  flex-direction: column;
  justify-content: space-between;

  background:
    linear-gradient(
      180deg,
      #172554 0%,
      #1e3a8a 48%,
      #3b0764 100%
    );

  color: white;

  box-shadow:
    10px 0 35px
    rgba(30, 41, 120, .18);

  z-index: 100;
}


/* =========================================================
   LOGO
========================================================= */

.logo-area {
  min-height: 100px;

  display: flex;
  align-items: center;

  padding: 20px;

  border-bottom:
    1px solid
    rgba(255,255,255,.1);
}

.logo-icon {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 15px;

  background:
    rgba(255,255,255,.15);

  font-size: 24px;

  box-shadow:
    inset 0 1px 0
    rgba(255,255,255,.15);
}

.logo-text {
  margin-left: 12px;
}

.logo-text h2 {
  margin: 0;

  font-size: 20px;

  font-weight: 800;
}

.logo-text p {
  margin: 3px 0 0;

  color:
    rgba(219,234,254,.8);

  font-size: 11px;
}


/* =========================================================
   MENU
========================================================= */

.menu-title {
  padding:
    25px 20px 10px;

  font-size: 10px;

  font-weight: 800;

  letter-spacing: 1.5px;

  color:
    rgba(191,219,254,.7);
}

.menu {
  padding: 0 14px;

  display: flex;
  flex-direction: column;

  gap: 6px;
}

.menu-item {
  width: 100%;

  border: none;

  display: flex;
  align-items: center;

  gap: 13px;

  padding:
    13px 15px;

  border-radius: 13px;

  color: #dbeafe;

  background: transparent;

  text-align: left;

  font-size: 13px;

  font-weight: 600;

  cursor: pointer;

  transition: .2s;
}

.menu-item:hover {
  background:
    rgba(255,255,255,.1);

  color: white;
}

.menu-item.active {
  background:
    linear-gradient(
      90deg,
      rgba(96,165,250,.35),
      rgba(99,102,241,.25)
    );

  color: white;

  box-shadow:
    inset 3px 0 0 #93c5fd;
}

.menu-icon {
  width: 23px;

  text-align: center;

  font-size: 17px;
}


/* =========================================================
   USER SIDEBAR
========================================================= */

.sidebar-bottom {
  padding: 18px;

  border-top:
    1px solid
    rgba(255,255,255,.1);
}

.user-box {
  display: flex;
  align-items: center;

  gap: 11px;
}

.avatar {
  width: 40px;
  height: 40px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background:
    linear-gradient(
      135deg,
      #60a5fa,
      #a855f7
    );

  font-weight: 800;
}

.user-info strong {
  display: block;

  font-size: 13px;
}

.user-info small {
  display: block;

  margin-top: 2px;

  color: #bfdbfe;

  font-size: 10px;
}

.logout-button {
  width: 100%;

  margin-top: 15px;

  padding: 11px;

  border: none;

  border-radius: 11px;

  background:
    rgba(239,68,68,.25);

  color: #fecaca;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 8px;

  font-weight: 700;

  cursor: pointer;

  transition: .2s;
}

.logout-button:hover {
  background:
    rgba(239,68,68,.4);

  color: white;
}


/* =========================================================
   CONTENT
========================================================= */

.content {
  width: calc(100% - 260px);

  margin-left: 260px;

  min-height: 100vh;
}


/* =========================================================
   TOPBAR
========================================================= */

.topbar {
  min-height: 90px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding:
    18px 34px;

  background:
    rgba(255,255,255,.84);

  border-bottom:
    1px solid #e5e7eb;

  backdrop-filter:
    blur(15px);

  position: sticky;

  top: 0;

  z-index: 30;
}

.topbar-left {
  display: flex;
  flex-direction: column;

  gap: 9px;
}

.back-button {
  width: fit-content;

  border: 1px solid #c7d2fe;

  background:
    #f8faff;

  color: #1e3a8a;

  padding:
    8px 13px;

  border-radius: 10px;

  display: flex;
  align-items: center;

  gap: 8px;

  font-size: 12px;

  font-weight: 800;

  cursor: pointer;

  transition: .2s;
}

.back-button:hover {
  background: #eef2ff;

  transform:
    translateX(-2px);
}

.page-heading {
  display: flex;
  align-items: center;

  gap: 13px;
}

.heading-icon {
  width: 47px;
  height: 47px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #7c3aed,
      #4f46e5
    );

  color: white;

  font-size: 22px;

  box-shadow:
    0 8px 18px
    rgba(79,70,229,.25);
}

.page-heading h1 {
  margin: 0;

  font-size: 25px;

  color: #172554;
}

.page-heading p {
  margin: 3px 0 0;

  color: #64748b;

  font-size: 12px;
}

.topbar-right {
  display: flex;
  align-items: center;

  gap: 18px;
}

.notification {
  width: 38px;
  height: 38px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: white;

  box-shadow:
    0 5px 15px
    rgba(15,23,42,.08);

  font-size: 17px;
}

.profile-mini {
  display: flex;
  align-items: center;

  gap: 9px;

  font-size: 12px;
}

.profile-mini strong {
  margin-right: 5px;
}

.profile-avatar {
  width: 36px;
  height: 36px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background:
    linear-gradient(
      135deg,
      #7c3aed,
      #6366f1
    );

  color: white;

  font-weight: 800;
}


/* =========================================================
   MAIN
========================================================= */

.main-section {
  padding:
    28px 34px 50px;
}

.detail-card {
  max-width: 900px;

  margin: 0 auto;

  padding: 28px;

  background:
    rgba(255,255,255,.92);

  border:
    1px solid
    rgba(255,255,255,.9);

  border-radius: 24px;

  box-shadow:
    0 15px 45px
    rgba(15,23,42,.08);
}


/* =========================================================
   DETAIL HEADER
========================================================= */

.detail-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  padding-bottom: 22px;

  border-bottom:
    1px solid #edf2f7;
}

.detail-header h2 {
  margin: 0;

  font-size: 18px;

  color: #172554;
}

.detail-header p {
  margin: 5px 0 0;

  color: #64748b;

  font-size: 12px;
}

.status-badge {
  display: flex;
  align-items: center;

  gap: 7px;

  padding:
    8px 13px;

  border-radius: 999px;

  font-size: 11px;

  font-weight: 800;
}

.status-badge.lunas {
  background: #dcfce7;

  color: #047857;
}

.status-badge.belum {
  background: #ffedd5;

  color: #c2410c;
}

.status-dot {
  width: 7px;
  height: 7px;

  border-radius: 50%;

  background: currentColor;
}


/* =========================================================
   INFORMATION
========================================================= */

.info-grid {
  display: grid;

  grid-template-columns:
    repeat(2, 1fr);

  gap: 10px 40px;

  padding:
    25px 0;
}

.info-item {
  display: flex;
  align-items: center;

  gap: 12px;

  padding: 8px 0;
}

.info-icon {
  width: 40px;
  height: 40px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 12px;

  background:
    linear-gradient(
      135deg,
      #eef2ff,
      #e0e7ff
    );

  font-size: 17px;
}

.info-content span {
  display: block;

  margin-bottom: 4px;

  color: #64748b;

  font-size: 10px;
}

.info-content strong {
  display: block;

  color: #172554;

  font-size: 14px;
}

.code-text {
  color: #4f46e5 !important;
}

.text-green {
  color: #059669 !important;
}

.text-orange {
  color: #d97706 !important;
}


/* =========================================================
   MEMBER CARD SECTION
========================================================= */

.member-card-section {
  padding-top: 8px;
}

.member-card-title {
  text-align: center;

  margin-bottom: 12px;

  color: #64748b;

  font-size: 11px;

  font-weight: 800;

  letter-spacing: .8px;
}


/* =========================================================
   VISUAL CARD
========================================================= */

.member-card {
  width: 100%;

  max-width: 720px;

  min-height: 330px;

  margin: 0 auto;

  position: relative;

  overflow: hidden;

  padding: 28px;

  border-radius: 22px;

  background:
    linear-gradient(
      135deg,
      #1d4ed8 0%,
      #3730a3 52%,
      #312e81 100%
    );

  color: white;

  box-shadow:
    0 18px 35px
    rgba(37,99,235,.28);
}

.card-circle {
  position: absolute;

  border-radius: 50%;

  pointer-events: none;
}

.circle-one {
  width: 350px;
  height: 350px;

  right: -120px;
  bottom: -190px;

  background:
    rgba(129,140,248,.18);
}

.circle-two {
  width: 260px;
  height: 260px;

  right: 60px;
  bottom: -180px;

  background:
    rgba(255,255,255,.06);
}


/* =========================================================
   CARD HEADER
========================================================= */

.card-header {
  position: relative;

  z-index: 2;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding-bottom: 20px;

  border-bottom:
    1px solid
    rgba(255,255,255,.18);
}

.brand {
  display: flex;
  align-items: center;

  gap: 11px;
}

.brand-icon {
  width: 45px;
  height: 45px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 13px;

  background: white;

  color: #3730a3;

  font-size: 23px;
}

.brand h3 {
  margin: 0;

  font-size: 17px;

  letter-spacing: .5px;
}

.brand p {
  margin: 2px 0 0;

  color:
    rgba(255,255,255,.75);

  font-size: 10px;
}

.card-status {
  padding:
    7px 12px;

  border-radius: 999px;

  font-size: 10px;

  font-weight: 900;
}

.card-status.lunas {
  background: #22c55e;

  color: white;
}

.card-status.belum {
  background: #f59e0b;

  color: white;
}


/* =========================================================
   CARD BODY
========================================================= */

.card-body {
  position: relative;

  z-index: 2;

  min-height: 220px;

  display: flex;
  justify-content: space-between;

  align-items: center;

  gap: 25px;

  padding-top: 25px;
}

.card-information {
  flex: 1;
}

.card-label {
  margin-bottom: 4px;

  color:
    rgba(255,255,255,.62);

  font-size: 9px;

  font-weight: 700;

  letter-spacing: .7px;
}

.card-name {
  margin-bottom: 20px;

  font-size: 25px;

  font-weight: 900;
}

.card-label-code {
  margin-top: 2px;
}

.card-code {
  margin-bottom: 20px;

  color: #fde047;

  font-size: 16px;

  font-weight: 900;

  letter-spacing: .5px;
}

.card-label-company {
  margin-top: 2px;
}

.card-company {
  font-size: 15px;

  font-weight: 700;
}

.qr-container {
  width: 170px;
  height: 170px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 11px;

  border-radius: 16px;

  background: white;

  box-shadow:
    0 10px 25px
    rgba(0,0,0,.18);
}

.qr-container :deep(svg) {
  width: 145px !important;
  height: 145px !important;

  display: block;
}


/* =========================================================
   PAYMENT
========================================================= */

.payment-summary {
  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 12px;

  margin-top: 24px;

  padding: 16px;

  border-radius: 15px;

  background: #f8fafc;
}

.payment-item {
  text-align: center;

  padding:
    5px 10px;
}

.payment-item + .payment-item {
  border-left:
    1px solid #e2e8f0;
}

.payment-item span {
  display: block;

  margin-bottom: 5px;

  color: #64748b;

  font-size: 10px;
}

.payment-item strong {
  color: #172554;

  font-size: 14px;
}

.green-money {
  color: #059669 !important;
}


/* =========================================================
   BUTTON
========================================================= */

.button-area {
  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 12px;

  margin-top: 20px;
}

.download-button,
.back-outline-button {
  height: 48px;

  border-radius: 12px;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 8px;

  font-size: 13px;

  font-weight: 800;

  cursor: pointer;

  transition: .2s;
}

.download-button {
  border: none;

  background:
    linear-gradient(
      135deg,
      #16a34a,
      #059669
    );

  color: white;

  box-shadow:
    0 8px 18px
    rgba(5,150,105,.18);
}

.download-button:hover {
  transform:
    translateY(-2px);

  box-shadow:
    0 12px 25px
    rgba(5,150,105,.25);
}

.back-outline-button {
  border:
    1px solid #a5b4fc;

  background: white;

  color: #3730a3;
}

.back-outline-button:hover {
  background: #eef2ff;
}


/* =========================================================
   LOADING
========================================================= */

.loading-container {
  min-height:
    calc(100vh - 90px);

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 30px;
}

.loading-card {
  width: 350px;

  padding: 35px;

  text-align: center;

  background: white;

  border-radius: 20px;

  box-shadow:
    0 15px 40px
    rgba(15,23,42,.08);
}

.spinner {
  width: 45px;
  height: 45px;

  margin:
    0 auto 15px;

  border:
    4px solid #e0e7ff;

  border-top-color:
    #4f46e5;

  border-radius: 50%;

  animation:
    spin .8s linear infinite;
}

@keyframes spin {

  to {
    transform: rotate(360deg);
  }

}

.loading-card h3 {
  margin: 0;

  color: #172554;

  font-size: 16px;
}

.loading-card p {
  margin-top: 5px;

  color: #64748b;

  font-size: 12px;
}


/* =========================================================
   ERROR
========================================================= */

.error-container {
  min-height:
    calc(100vh - 90px);

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 30px;
}

.error-card {
  max-width: 450px;

  padding: 40px;

  text-align: center;

  background: white;

  border-radius: 22px;

  box-shadow:
    0 15px 40px
    rgba(15,23,42,.08);
}

.error-icon {
  font-size: 45px;

  margin-bottom: 10px;
}

.error-card h2 {
  margin: 0;

  color: #172554;

  font-size: 20px;
}

.error-card p {
  color: #64748b;

  font-size: 13px;

  line-height: 1.6;
}

.primary-button {
  border: none;

  padding:
    11px 18px;

  border-radius: 11px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #4f46e5
    );

  color: white;

  font-weight: 800;

  cursor: pointer;
}


/* =========================================================
   PRINT
========================================================= */

@media print {

  body {
    background: white !important;
  }

  .sidebar,
  .topbar,
  .detail-header,
  .info-grid,
  .payment-summary,
  .button-area {
    display: none !important;
  }

  .content {
    width: 100% !important;
    margin: 0 !important;
  }

  .main-section {
    padding: 20px !important;
  }

  .detail-card {
    box-shadow: none !important;
    border: none !important;
    max-width: 800px !important;
  }

  .member-card-section {
    padding: 0 !important;
  }

  .member-card {
    max-width: 720px !important;
    margin: auto !important;

    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
  }

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

  .sidebar {
    width: 220px;
  }

  .content {
    width: calc(100% - 220px);

    margin-left: 220px;
  }

}

@media (max-width: 800px) {

  .info-grid {
    grid-template-columns: 1fr;
  }

  .payment-summary {
    grid-template-columns: 1fr;
  }

  .payment-item + .payment-item {
    border-left: none;

    border-top:
      1px solid #e2e8f0;

    padding-top: 12px;
  }

  .member-card {
    min-height: auto;
  }

}

@media (max-width: 700px) {

  .sidebar {
    position: relative;

    width: 100%;

    height: auto;
  }

  .page {
    display: block;
  }

  .content {
    width: 100%;

    margin-left: 0;
  }

  .topbar {
    position: relative;

    padding: 18px;
  }

  .topbar-right {
    display: none;
  }

  .main-section {
    padding: 20px 15px;
  }

  .detail-card {
    padding: 18px;

    border-radius: 18px;
  }

  .card-body {
    flex-direction: column;

    align-items: flex-start;
  }

  .qr-container {
    align-self: center;
  }

  .button-area {
    grid-template-columns: 1fr;
  }

}

</style>