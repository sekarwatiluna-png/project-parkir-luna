<template>
  <div class="page">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar">

      <div>

        <!-- LOGO -->
        <div class="logo-area">
          <div class="logo-icon">
            🅿️
          </div>

          <div class="logo-text">
            <h2>ParkirKu</h2>
            <p>Parkir Plaza Andalas</p>
          </div>
        </div>

        <!-- MENU TITLE -->
        <div class="menu-title">
          MENU UTAMA
        </div>

        <!-- MENU -->
        <nav class="menu">

          <!-- DASHBOARD -->
          <button
            type="button"
            class="menu-item"
            @click="keDashboard"
          >
            <span class="menu-icon">📊</span>
            <span>Dashboard</span>
          </button>

          <!-- TRANSAKSI -->
          <button
            type="button"
            class="menu-item"
            @click="keTransaksi"
          >
            <span class="menu-icon">💳</span>
            <span>Kelola Transaksi</span>
          </button>

          <!-- MEMBER -->
          <button
            type="button"
            class="menu-item active"
          >
            <span class="menu-icon">👥</span>
            <span>Kelola Member</span>
          </button>

          <!-- SCAN KELUAR -->
          <button
            type="button"
            class="menu-item"
            @click="keScanKeluar"
          >
            <span class="menu-icon">📤</span>
            <span>Scan Keluar</span>
          </button>

          <!-- LAPORAN -->
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

      <!-- =====================================================
           USER SIDEBAR
      ====================================================== -->
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
          <span>🚪</span>
          <span>Keluar</span>
        </button>

      </div>

    </aside>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->
    <main class="content">

      <!-- =====================================================
           TOPBAR
      ====================================================== -->
      <header class="topbar">

        <div class="topbar-left">

          <button
            type="button"
            class="back-button"
            @click="keDashboard"
          >
            ← Kembali
          </button>

          <h1>Kelola Member</h1>

          <p>
            Kelola data member Parkir Plaza Andalas.
          </p>

        </div>

        <div class="role-badge">
          👮 Petugas
        </div>

      </header>


      <!-- =====================================================
           STATISTIK
      ====================================================== -->
      <section class="stats">

        <!-- TOTAL -->
        <div class="stat-card">

          <div class="stat-icon blue">
            👥
          </div>

          <div class="stat-content">
            <span>Total Member</span>
            <strong>{{ members.length }}</strong>
            <small>Member terdaftar</small>
          </div>

        </div>


        <!-- LUNAS -->
        <div class="stat-card">

          <div class="stat-icon green">
            ✅
          </div>

          <div class="stat-content">
            <span>Member Lunas</span>
            <strong>{{ jumlahLunas }}</strong>
            <small>Pembayaran selesai</small>
          </div>

        </div>


        <!-- BELUM LUNAS -->
        <div class="stat-card">

          <div class="stat-icon orange">
            ⏳
          </div>

          <div class="stat-content">
            <span>Belum Lunas</span>
            <strong>{{ jumlahBelumLunas }}</strong>
            <small>Menunggu pembayaran</small>
          </div>

        </div>

      </section>


      <!-- =====================================================
           TABLE CARD
      ====================================================== -->
      <section class="table-card">

        <!-- HEADER TABLE -->
        <div class="table-header">

          <div class="table-title">

            <div class="title-icon">
              👥
            </div>

            <div>
              <h2>Data Member</h2>

              <p>
                Daftar member yang terdaftar
              </p>
            </div>

          </div>


          <!-- TAMBAH MEMBER -->
          <NuxtLink
            to="/petugas/member/tambah"
            class="add-button"
          >
            <span class="add-icon">＋</span>
            <span>Tambah Member</span>
          </NuxtLink>

        </div>


        <!-- =====================================================
             SEARCH
        ====================================================== -->
        <div class="search-area">

          <div class="search-box">

            <span class="search-icon">
              🔍
            </span>

            <input
              v-model="search"
              type="text"
              autocomplete="off"
              placeholder="Cari kode, nama, perusahaan, atau token..."
            />

            <button
              v-if="search"
              type="button"
              class="clear-search"
              @click="search = ''"
            >
              ×
            </button>

          </div>


          <div class="result-count">
            <strong>{{ filteredMembers.length }}</strong>
            data ditemukan
          </div>

        </div>


        <!-- =====================================================
             ERROR
        ====================================================== -->
        <div
          v-if="errorMessage"
          class="error-message"
        >

          <div class="error-left">
            <span class="error-icon">
              ⚠️
            </span>

            <span>
              {{ errorMessage }}
            </span>
          </div>

          <button
            type="button"
            class="retry-button"
            @click="loadMembers"
          >
            Coba Lagi
          </button>

        </div>


        <!-- =====================================================
             LOADING
        ====================================================== -->
        <div
          v-if="loading"
          class="loading-box"
        >

          <div class="spinner"></div>

          <strong>
            Memuat data member...
          </strong>

          <p>
            Mohon tunggu sebentar
          </p>

        </div>


        <!-- =====================================================
             TABLE
        ====================================================== -->
        <div
          v-else
          class="table-wrapper"
        >

          <table>

            <thead>

              <tr>
                <th class="no-column">NO</th>
                <th>MEMBER</th>
                <th>PERUSAHAAN</th>
                <th>KODE MEMBER</th>
                <th>MASA BERLAKU</th>
                <th>PEMBAYARAN</th>
                <th class="action-column">AKSI</th>
              </tr>

            </thead>


            <tbody>

              <!-- =================================================
                   DATA MEMBER
              ================================================== -->
              <tr
                v-for="(member, index) in filteredMembers"
                :key="member.id"
              >

                <!-- NO -->
                <td class="no-column">
                  <span class="number">
                    {{ index + 1 }}
                  </span>
                </td>


                <!-- MEMBER -->
                <td>

                  <div class="member-info">

                    <div class="member-avatar">
                      {{ getInitial(member.nama_member) }}
                    </div>

                    <div class="member-text">

                      <strong>
                        {{ member.nama_member || "-" }}
                      </strong>

                      <small>
                        {{ member.token || "Token tidak tersedia" }}
                      </small>

                    </div>

                  </div>

                </td>


                <!-- PERUSAHAAN -->
                <td>

                  <div class="company-box">

                    <strong class="company">
                      {{ member.nama_perusahaan || "-" }}
                    </strong>

                  </div>

                </td>


                <!-- KODE MEMBER -->
                <td>

                  <span class="member-code">
                    {{ member.kode_member || "-" }}
                  </span>

                </td>


                <!-- MASA BERLAKU -->
                <td>

                  <div class="date-box">

                    <strong>
                      {{ formatTanggal(member.tanggal_mulai) }}
                    </strong>

                    <small>
                      s/d
                      {{ formatTanggal(member.tanggal_expired) }}
                    </small>

                  </div>

                </td>


                <!-- PEMBAYARAN -->
                <td>

                  <div class="payment-cell">

                    <!-- LUNAS -->
                    <span
                      v-if="isLunas(member)"
                      class="status lunas"
                    >
                      <span class="status-dot"></span>
                      Lunas
                    </span>

                    <!-- BELUM LUNAS -->
                    <span
                      v-else
                      class="status belum"
                    >
                      <span class="status-dot"></span>
                      Belum Lunas
                    </span>

                    <small>
                      Rp {{ rupiah(member.jumlah_bayar) }}
                      /
                      Rp {{ rupiah(member.total_harga) }}
                    </small>

                  </div>

                </td>


                <!-- =================================================
                     AKSI
                ================================================== -->
                <td class="action-cell">

                  <div class="actions">

                    <!-- =============================================
                         LIHAT KARTU
                         SELALU AKTIF
                    ============================================== -->
                    <NuxtLink
                      :to="`/petugas/member/kartu/${member.id}`"
                      class="action-button view-button"
                      title="Lihat kartu member"
                    >
                      👁️
                    </NuxtLink>


                    <!-- =============================================
                         EDIT - BELUM LUNAS
                    ============================================== -->
                    <button
                      v-if="!isLunas(member)"
                      type="button"
                      class="action-button edit-button"
                      title="Edit member"
                      @click="editMember(member)"
                    >
                      ✏️
                    </button>


                    <!-- =============================================
                         EDIT - SUDAH LUNAS
                         DISABLED / ABU-ABU
                    ============================================== -->
                    <button
                      v-else
                      type="button"
                      class="action-button disabled-button"
                      title="Member lunas tidak dapat diedit"
                      disabled
                    >
                      ✏️
                    </button>


                    <!-- =============================================
                         HAPUS - BELUM LUNAS
                    ============================================== -->
                    <button
                      v-if="!isLunas(member)"
                      type="button"
                      class="action-button delete-button"
                      title="Hapus member"
                      @click="hapusMember(member)"
                    >
                      🗑️
                    </button>


                    <!-- =============================================
                         HAPUS - SUDAH LUNAS
                         DISABLED / ABU-ABU
                    ============================================== -->
                    <button
                      v-else
                      type="button"
                      class="action-button disabled-button"
                      title="Member lunas tidak dapat dihapus"
                      disabled
                    >
                      🗑️
                    </button>

                  </div>

                </td>

              </tr>


              <!-- =================================================
                   DATA KOSONG
              ================================================== -->
              <tr
                v-if="filteredMembers.length === 0"
              >

                <td
                  colspan="7"
                  class="empty"
                >

                  <div class="empty-icon">
                    👥
                  </div>

                  <h3>
                    Data member tidak ditemukan
                  </h3>

                  <p>
                    Belum ada member yang sesuai
                    dengan pencarian.
                  </p>

                  <button
                    v-if="search"
                    type="button"
                    class="empty-button"
                    @click="search = ''"
                  >
                    Reset Pencarian
                  </button>

                </td>

              </tr>

            </tbody>

          </table>

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


/* =====================================================
   ROUTER
===================================================== */

const router = useRouter()

const config = useRuntimeConfig()


/* =====================================================
   API URL
===================================================== */

const API_URL = String(
  config.public?.apiBase ||
  "http://127.0.0.1:8000/api"
).replace(/\/$/, "")


/* =====================================================
   STATE
===================================================== */

const members = ref([])

const loading = ref(false)

const search = ref("")

const errorMessage = ref("")

const userName = ref("Petugas")


/* =====================================================
   USER INITIAL
===================================================== */

const userInitial = computed(() => {

  return String(
    userName.value || "P"
  )
    .charAt(0)
    .toUpperCase()

})


/* =====================================================
   TOKEN
===================================================== */

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


/* =====================================================
   CEK LOGIN
===================================================== */

function cekLogin() {

  if (!process.client) {
    return true
  }


  const token =
    localStorage.getItem("token") ||
    localStorage.getItem("access_token")


  const role =
    localStorage.getItem("role")


  /* BELUM LOGIN */

  if (!token) {

    router.push("/")

    return false

  }


  /* CEK ROLE */

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


  /* USER */

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
        user?.email ||
        "Petugas"

    } catch {

      userName.value =
        "Petugas"

    }

  }


  return true

}


/* =====================================================
   LOAD MEMBERS
===================================================== */

async function loadMembers() {

  loading.value = true

  errorMessage.value = ""


  try {

    const token =
      getToken()


    if (!token) {

      router.push("/")

      return

    }


    const response =
      await $fetch(
        `${API_URL}/members`,
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


    let data = []


    /* RESPONSE ARRAY */

    if (
      Array.isArray(response)
    ) {

      data = response

    }


    /* response.data */

    else if (
      Array.isArray(
        response?.data
      )
    ) {

      data =
        response.data

    }


    /* response.data.data */

    else if (
      Array.isArray(
        response?.data?.data
      )
    ) {

      data =
        response.data.data

    }


    /* response.members */

    else if (
      Array.isArray(
        response?.members
      )
    ) {

      data =
        response.members

    }


    members.value =
      data || []


  } catch (error) {

    console.error(
      "ERROR LOAD MEMBER:",
      error
    )


    const status =
      error?.statusCode ||
      error?.response?.status ||
      error?.status


    /* TOKEN EXPIRED */

    if (status === 401) {

      localStorage.removeItem(
        "token"
      )

      localStorage.removeItem(
        "access_token"
      )

      localStorage.removeItem(
        "user"
      )

      localStorage.removeItem(
        "role"
      )

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


/* =====================================================
   FILTER MEMBER
===================================================== */

const filteredMembers =
  computed(() => {

    const keyword =
      String(
        search.value || ""
      )
        .toLowerCase()
        .trim()


    if (!keyword) {

      return members.value

    }


    return members.value.filter(
      (member) => {

        const kode =
          String(
            member?.kode_member ||
            ""
          ).toLowerCase()


        const nama =
          String(
            member?.nama_member ||
            ""
          ).toLowerCase()


        const perusahaan =
          String(
            member?.nama_perusahaan ||
            ""
          ).toLowerCase()


        const token =
          String(
            member?.token ||
            ""
          ).toLowerCase()


        return (
          kode.includes(keyword) ||
          nama.includes(keyword) ||
          perusahaan.includes(keyword) ||
          token.includes(keyword)
        )

      }
    )

  })


/* =====================================================
   CEK LUNAS
===================================================== */

function isLunas(member) {

  return String(
    member?.status || ""
  )
    .trim()
    .toLowerCase() === "lunas"

}


/* =====================================================
   JUMLAH LUNAS
===================================================== */

const jumlahLunas =
  computed(() => {

    return members.value.filter(
      member =>
        isLunas(member)
    ).length

  })


/* =====================================================
   JUMLAH BELUM LUNAS
===================================================== */

const jumlahBelumLunas =
  computed(() => {

    return members.value.filter(
      member =>
        !isLunas(member)
    ).length

  })


/* =====================================================
   INITIAL NAMA
===================================================== */

function getInitial(nama) {

  const text =
    String(
      nama || "M"
    ).trim()


  return (
    text.charAt(0) ||
    "M"
  ).toUpperCase()

}


/* =====================================================
   FORMAT RUPIAH
===================================================== */

function rupiah(value) {

  return new Intl.NumberFormat(
    "id-ID"
  ).format(
    Number(value || 0)
  )

}


/* =====================================================
   FORMAT TANGGAL
===================================================== */

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
      month: "short",
      year: "numeric"
    }
  )

}


/* =====================================================
   EDIT MEMBER
===================================================== */

function editMember(member) {

  console.log(
    "EDIT MEMBER:",
    member
  )


  if (
    !member ||
    !member.id
  ) {

    alert(
      "ID member tidak ditemukan."
    )

    return

  }


  /* MEMBER LUNAS */

  if (
    isLunas(member)
  ) {

    return

  }


  const url =
    `/petugas/member/edit/${member.id}`


  console.log(
    "PINDAH KE:",
    url
  )


  router.push(url)

}


/* =====================================================
   HAPUS MEMBER
===================================================== */

async function hapusMember(member) {

  if (
    !member ||
    !member.id
  ) {

    alert(
      "ID member tidak ditemukan."
    )

    return

  }


  /* MEMBER LUNAS */

  if (
    isLunas(member)
  ) {

    return

  }


  const yakin =
    window.confirm(
      `Yakin ingin menghapus member "${member.nama_member || ""}"?`
    )


  if (!yakin) {
    return
  }


  try {

    const token =
      getToken()


    if (!token) {

      router.push("/")

      return

    }


    await $fetch(
      `${API_URL}/members/${member.id}`,
      {
        method: "DELETE",

        headers: {

          Accept:
            "application/json",

          Authorization:
            `Bearer ${token}`

        }

      }
    )


    alert(
      "Member berhasil dihapus."
    )


    await loadMembers()


  } catch (error) {

    console.error(
      "ERROR HAPUS:",
      error
    )


    alert(
      error?.data?.message ||
      error?.response?._data?.message ||
      error?.message ||
      "Gagal menghapus member."
    )

  }

}


/* =====================================================
   NAVIGASI
===================================================== */

function keDashboard() {

  router.push(
    "/petugas"
  )

}


function keTransaksi() {

  router.push(
    "/petugas/transaksi"
  )

}


function keScanKeluar() {

  router.push(
    "/petugas/scan-keluar"
  )

}


function keLaporan() {

  router.push(
    "/petugas/laporan"
  )

}


/* =====================================================
   LOGOUT
===================================================== */

function logout() {

  if (process.client) {

    localStorage.removeItem(
      "token"
    )

    localStorage.removeItem(
      "access_token"
    )

    localStorage.removeItem(
      "user"
    )

    localStorage.removeItem(
      "role"
    )

  }


  router.push("/")

}


/* =====================================================
   ON MOUNTED
===================================================== */

onMounted(
  async () => {

    const boleh =
      cekLogin()


    if (!boleh) {
      return
    }


    await loadMembers()

  }
)

</script>


<style scoped>

/* =====================================================
   GLOBAL
===================================================== */

* {
  box-sizing: border-box;
}


/* =====================================================
   PAGE
===================================================== */

.page {

  min-height: 100vh;

  display: flex;

  background:
    linear-gradient(
      135deg,
      #eef5ff 0%,
      #f8f9ff 50%,
      #f3edff 100%
    );

  color: #1f2937;

}


/* =====================================================
   SIDEBAR
===================================================== */

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
      #1e3a8a 50%,
      #3b0764 100%
    );

  color: white;

  box-shadow:
    8px 0 30px
    rgba(30, 64, 175, .18);

  z-index: 1000;

}


/* =====================================================
   LOGO
===================================================== */

.logo-area {

  height: 100px;

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

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 15px;

  background:
    rgba(255,255,255,.15);

  font-size: 25px;

}


.logo-text {
  margin-left: 12px;
}


.logo-area h2 {

  margin: 0 0 2px;

  font-size: 20px;

}


.logo-area p {

  margin: 0;

  color:
    rgba(219,234,254,.8);

  font-size: 12px;

}


/* =====================================================
   MENU
===================================================== */

.menu-title {

  padding:
    25px 20px 10px;

  font-size: 11px;

  font-weight: 800;

  letter-spacing: 1.5px;

  color:
    rgba(191,219,254,.7);

}


.menu {

  padding:
    0 14px;

  display: flex;

  flex-direction: column;

  gap: 7px;

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

  background:
    transparent;

  text-align: left;

  font-size: 14px;

  cursor: pointer;

  transition:
    .2s ease;

}


.menu-item:hover {

  background:
    rgba(255,255,255,.1);

  color: white;

}


.menu-item.active {

  background:
    rgba(255,255,255,.16);

  color: white;

  font-weight: 700;

  box-shadow:
    inset 3px 0 0
    #93c5fd;

}


.menu-icon {

  width: 22px;

  text-align: center;

  font-size: 16px;

}


/* =====================================================
   SIDEBAR BOTTOM
===================================================== */

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

  border-radius: 50%;

  display: flex;

  align-items: center;

  justify-content: center;

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

  font-size: 14px;

}


.user-info small {

  display: block;

  margin-top: 2px;

  color: #bfdbfe;

  font-size: 11px;

}


.logout-button {

  width: 100%;

  margin-top: 15px;

  padding: 11px;

  border:
    1px solid
    rgba(255,255,255,.1);

  border-radius: 11px;

  background:
    rgba(255,255,255,.07);

  color: white;

  display: flex;

  align-items: center;

  justify-content: center;

  gap: 8px;

  cursor: pointer;

  transition: .2s;

}


.logout-button:hover {

  background:
    rgba(255,255,255,.15);

}


/* =====================================================
   CONTENT
===================================================== */

.content {

  width:
    calc(100% - 260px);

  margin-left: 260px;

  padding-bottom: 50px;

}


/* =====================================================
   TOPBAR
===================================================== */

.topbar {

  min-height: 110px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  padding:
    22px 34px;

  background:
    rgba(255,255,255,.88);

  border-bottom:
    1px solid #e5e7eb;

  backdrop-filter:
    blur(12px);

  position: sticky;

  top: 0;

  z-index: 100;

}


.back-button {

  border: none;

  background:
    transparent;

  color: #2563eb;

  font-size: 13px;

  font-weight: 700;

  cursor: pointer;

  padding: 0;

  margin-bottom: 5px;

}


.back-button:hover {

  color: #1d4ed8;

}


.topbar h1 {

  margin: 0;

  font-size: 27px;

  color: #111827;

}


.topbar p {

  margin:
    5px 0 0;

  color: #6b7280;

  font-size: 13px;

}


.role-badge {

  padding:
    10px 16px;

  border-radius: 999px;

  background: #eef2ff;

  color: #4338ca;

  font-size: 13px;

  font-weight: 800;

}


/* =====================================================
   STATISTIK
===================================================== */

.stats {

  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 18px;

  padding:
    28px 34px 0;

}


.stat-card {

  min-height: 110px;

  display: flex;

  align-items: center;

  gap: 15px;

  padding: 20px;

  border-radius: 20px;

  background: white;

  box-shadow:
    0 8px 25px
    rgba(15,23,42,.07);

  transition:
    transform .2s ease,
    box-shadow .2s ease;

}


.stat-card:hover {

  transform:
    translateY(-2px);

  box-shadow:
    0 12px 30px
    rgba(15,23,42,.1);

}


.stat-icon {

  width: 52px;
  height: 52px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 15px;

  font-size: 22px;

}


.stat-icon.blue {

  background: #dbeafe;

}


.stat-icon.green {

  background: #dcfce7;

}


.stat-icon.orange {

  background: #ffedd5;

}


.stat-content span {

  display: block;

  color: #6b7280;

  font-size: 12px;

}


.stat-content strong {

  display: block;

  margin-top: 3px;

  color: #111827;

  font-size: 25px;

}


.stat-content small {

  display: block;

  margin-top: 2px;

  color: #94a3b8;

  font-size: 10px;

}


/* =====================================================
   TABLE CARD
===================================================== */

.table-card {

  position: relative;

  z-index: 10;

  margin:
    24px 34px 0;

  background: white;

  border-radius: 24px;

  box-shadow:
    0 12px 35px
    rgba(15,23,42,.08);

  overflow: visible;

}


/* =====================================================
   TABLE HEADER
===================================================== */

.table-header {

  padding: 24px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 20px;

  border-bottom:
    1px solid #f1f5f9;

}


.table-title {

  display: flex;

  align-items: center;

  gap: 13px;

}


.title-icon {

  width: 43px;
  height: 43px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 12px;

  background:
    linear-gradient(
      135deg,
      #dbeafe,
      #ede9fe
    );

  font-size: 19px;

}


.table-header h2 {

  margin: 0;

  font-size: 19px;

  color: #111827;

}


.table-header p {

  margin:
    4px 0 0;

  color: #6b7280;

  font-size: 12px;

}


/* =====================================================
   TAMBAH
===================================================== */

.add-button {

  border: none;

  display: flex;

  align-items: center;

  justify-content: center;

  gap: 7px;

  padding:
    11px 17px;

  border-radius: 12px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  color: white;

  font-weight: 800;

  font-size: 13px;

  cursor: pointer;

  text-decoration: none;

  box-shadow:
    0 6px 16px
    rgba(79,70,229,.2);

  transition: .2s ease;

}


.add-button:hover {

  transform:
    translateY(-2px);

  box-shadow:
    0 9px 20px
    rgba(79,70,229,.3);

}


.add-icon {

  font-size: 20px;

  line-height: 1;

}


/* =====================================================
   SEARCH
===================================================== */

.search-area {

  padding:
    18px 24px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 20px;

}


.search-box {

  width:
    min(520px, 100%);

  height: 45px;

  display: flex;

  align-items: center;

  gap: 10px;

  padding:
    0 14px;

  border:
    1px solid #e5e7eb;

  border-radius: 12px;

  background: #f8fafc;

  transition: .2s;

}


.search-box:focus-within {

  border-color:
    #6366f1;

  background: white;

  box-shadow:
    0 0 0 4px
    rgba(99,102,241,.1);

}


.search-icon {

  font-size: 15px;

}


.search-box input {

  width: 100%;

  border: none;

  outline: none;

  background:
    transparent;

  color: #1f2937;

  font-size: 13px;

}


.clear-search {

  width: 25px;
  height: 25px;

  border: none;

  border-radius: 50%;

  background: #e5e7eb;

  color: #64748b;

  cursor: pointer;

  font-size: 16px;

  line-height: 1;

}


.result-count {

  color: #6b7280;

  font-size: 12px;

  white-space: nowrap;

}


.result-count strong {

  color: #4338ca;

}


/* =====================================================
   ERROR
===================================================== */

.error-message {

  margin:
    0 24px 18px;

  padding:
    13px 15px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 15px;

  border-radius: 12px;

  background: #fef2f2;

  border:
    1px solid #fecaca;

  color: #b91c1c;

  font-size: 13px;

}


.error-left {

  display: flex;

  align-items: center;

  gap: 9px;

}


.error-icon {

  font-size: 17px;

}


.retry-button {

  border: none;

  background: #dc2626;

  color: white;

  padding:
    7px 12px;

  border-radius: 8px;

  cursor: pointer;

  font-weight: 700;

}


.retry-button:hover {

  background: #b91c1c;

}


/* =====================================================
   LOADING
===================================================== */

.loading-box {

  min-height: 300px;

  display: flex;

  flex-direction: column;

  align-items: center;

  justify-content: center;

  color: #6b7280;

}


.loading-box strong {

  color: #374151;

  font-size: 14px;

}


.loading-box p {

  margin-top: 5px;

  font-size: 11px;

}


.spinner {

  width: 40px;
  height: 40px;

  border-radius: 50%;

  border:
    4px solid #e5e7eb;

  border-top-color:
    #4f46e5;

  animation:
    spin .8s linear infinite;

  margin-bottom: 12px;

}


@keyframes spin {

  to {
    transform: rotate(360deg);
  }

}


/* =====================================================
   TABLE
===================================================== */

.table-wrapper {

  position: relative;

  z-index: 20;

  width: 100%;

  overflow-x: auto;

}


table {

  width: 100%;

  min-width: 1050px;

  border-collapse:
    separate;

  border-spacing: 0;

}


th {

  padding:
    13px 15px;

  background: #f8fafc;

  color: #64748b;

  font-size: 10px;

  letter-spacing: .5px;

  text-align: left;

  white-space: nowrap;

}


td {

  padding:
    16px 15px;

  border-top:
    1px solid #f1f5f9;

  font-size: 13px;

}


tbody tr {

  transition:
    background .15s ease;

}


tbody tr:hover {

  background:
    #f8fafc;

}


.no-column {

  width: 55px;

  text-align: center;

}


.number {

  color: #64748b;

  font-size: 12px;

  font-weight: 700;

}


/* =====================================================
   MEMBER INFO
===================================================== */

.member-info {

  display: flex;

  align-items: center;

  gap: 10px;

}


.member-avatar {

  width: 38px;
  height: 38px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 11px;

  background:
    linear-gradient(
      135deg,
      #dbeafe,
      #ede9fe
    );

  color: #4338ca;

  font-weight: 800;

}


.member-text strong {

  display: block;

  max-width: 170px;

  overflow: hidden;

  text-overflow: ellipsis;

  white-space: nowrap;

  color: #1f2937;

}


.member-text small {

  display: block;

  max-width: 170px;

  margin-top: 3px;

  color: #94a3b8;

  font-size: 10px;

  overflow: hidden;

  text-overflow: ellipsis;

  white-space: nowrap;

}


/* =====================================================
   PERUSAHAAN
===================================================== */

.company {

  color: #374151;

  font-size: 13px;

}


/* =====================================================
   KODE
===================================================== */

.member-code {

  display: inline-block;

  padding:
    6px 9px;

  border-radius: 8px;

  background: #eef2ff;

  color: #4338ca;

  font-size: 11px;

  font-weight: 800;

}


/* =====================================================
   TANGGAL
===================================================== */

.date-box strong {

  display: block;

  color: #374151;

}


.date-box small {

  display: block;

  margin-top: 3px;

  color: #94a3b8;

  font-size: 10px;

}


/* =====================================================
   PEMBAYARAN
===================================================== */

.payment-cell {

  min-width: 130px;

}


.status {

  display: inline-flex;

  align-items: center;

  gap: 6px;

  font-weight: 800;

  font-size: 11px;

}


.status-dot {

  width: 7px;
  height: 7px;

  border-radius: 50%;

}


.status.lunas {

  color: #059669;

}


.status.lunas .status-dot {

  background: #10b981;

  box-shadow:
    0 0 0 3px
    rgba(16,185,129,.12);

}


.status.belum {

  color: #d97706;

}


.status.belum .status-dot {

  background: #f59e0b;

  box-shadow:
    0 0 0 3px
    rgba(245,158,11,.12);

}


.payment-cell small {

  display: block;

  margin-top: 5px;

  color: #94a3b8;

  font-size: 10px;

}


/* =====================================================
   AKSI
===================================================== */

.action-column {

  text-align: center;

}


.action-cell {

  min-width: 150px;

  position: relative;

  z-index: 100;

}


.actions {

  display: flex;

  align-items: center;

  justify-content: center;

  gap: 7px;

  position: relative;

  z-index: 200;

  pointer-events: auto;

}


.action-button {

  width: 38px;
  height: 38px;

  min-width: 38px;

  display: inline-flex;

  align-items: center;

  justify-content: center;

  border: none;

  border-radius: 10px;

  font-size: 16px;

  line-height: 1;

  cursor: pointer;

  text-decoration: none;

  pointer-events: auto;

  transition:
    transform .2s ease,
    background .2s ease,
    color .2s ease,
    box-shadow .2s ease;

}


/* =====================================================
   HOVER AKSI
===================================================== */

.action-button:hover:not(:disabled) {

  transform:
    translateY(-2px);

}


/* =====================================================
   LIHAT KARTU
===================================================== */

.view-button {

  background: #dbeafe;

  color: #2563eb;

}


.view-button:hover {

  background: #2563eb;

  color: white;

  box-shadow:
    0 5px 12px
    rgba(37,99,235,.25);

}


/* =====================================================
   EDIT AKTIF
===================================================== */

.edit-button {

  background: #fef3c7;

  color: #d97706;

}


.edit-button:hover {

  background: #d97706;

  color: white;

  box-shadow:
    0 5px 12px
    rgba(217,119,6,.25);

}


/* =====================================================
   HAPUS AKTIF
===================================================== */

.delete-button {

  background: #fee2e2;

  color: #dc2626;

}


.delete-button:hover {

  background: #dc2626;

  color: white;

  box-shadow:
    0 5px 12px
    rgba(220,38,38,.25);

}


/* =====================================================
   EDIT + HAPUS MEMBER LUNAS
===================================================== */

.disabled-button {

  background:
    #e5e7eb !important;

  color:
    #9ca3af !important;

  cursor:
    not-allowed !important;

  opacity:
    .8;

  box-shadow:
    none !important;

  pointer-events:
    none;

}


.disabled-button:hover {

  background:
    #e5e7eb !important;

  color:
    #9ca3af !important;

  transform:
    none !important;

}


/* =====================================================
   EMPTY
===================================================== */

.empty {

  padding:
    65px 20px;

  text-align: center;

  color: #6b7280;

}


.empty-icon {

  width: 70px;
  height: 70px;

  margin:
    0 auto 15px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 20px;

  background: #f1f5f9;

  font-size: 32px;

}


.empty h3 {

  margin: 0;

  color: #374151;

  font-size: 16px;

}


.empty p {

  margin:
    7px 0 15px;

  font-size: 12px;

}


.empty-button {

  border: none;

  padding:
    9px 15px;

  border-radius: 9px;

  background: #4f46e5;

  color: white;

  font-size: 12px;

  font-weight: 700;

  cursor: pointer;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 1100px) {

  .sidebar {

    width: 230px;

  }


  .content {

    width:
      calc(100% - 230px);

    margin-left: 230px;

  }


  .stats {

    grid-template-columns:
      repeat(3, 1fr);

    gap: 12px;

    padding-left: 20px;
    padding-right: 20px;

  }


  .table-card {

    margin-left: 20px;
    margin-right: 20px;

  }

}


@media (max-width: 850px) {

  .sidebar {

    width: 210px;

  }


  .content {

    width:
      calc(100% - 210px);

    margin-left: 210px;

  }


  .stats {

    grid-template-columns: 1fr;

  }


  .topbar {

    padding:
      20px;

  }

}


@media (max-width: 700px) {

  .page {

    display: block;

  }


  .sidebar {

    position: relative;

    width: 100%;

    height: auto;

  }


  .content {

    width: 100%;

    margin-left: 0;

  }


  .topbar {

    position: relative;

    flex-direction: column;

    align-items: flex-start;

    gap: 15px;

  }


  .stats {

    padding:
      20px;

  }


  .table-card {

    margin:
      20px;

  }


  .table-header {

    flex-direction: column;

    align-items: flex-start;

  }


  .add-button {

    width: 100%;

  }


  .search-area {

    flex-direction: column;

    align-items: stretch;

  }


  .search-box {

    width: 100%;

  }


  .result-count {

    text-align: right;

  }

}

</style>