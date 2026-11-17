<template>
  <div class="dashboard-page">

    <!-- ===================================================== -->
    <!-- SIDEBAR -->
    <!-- ===================================================== -->

    <aside class="sidebar">

      <!-- LOGO -->
      <div class="sidebar-header">

        <div class="logo-icon">
          🚗
        </div>

        <div class="logo-text">
          <h2>Parkir Plaza</h2>
          <p>Panel Petugas</p>
        </div>

      </div>


      <!-- MENU -->
      <div class="sidebar-menu">

        <div class="menu-title">
          MENU UTAMA
        </div>


        <!-- DASHBOARD -->
        <NuxtLink
          to="/petugas"
          class="menu-item"
          active-class="active"
        >
          <span class="menu-icon">📊</span>
          <span>Dashboard</span>
        </NuxtLink>


        <!-- KELOLA TRANSAKSI -->
        <NuxtLink
          to="/petugas/transaksi"
          class="menu-item"
        >
          <span class="menu-icon">💳</span>
          <span>Kelola Transaksi</span>
        </NuxtLink>


        <!-- KELOLA MEMBER -->
        <NuxtLink
          to="/petugas/member"
          class="menu-item"
        >
          <span class="menu-icon">👥</span>
          <span>Kelola Member</span>
        </NuxtLink>


        <!-- SCAN KELUAR -->
        <NuxtLink
          to="/petugas/scan-keluar"
          class="menu-item"
        >
          <span class="menu-icon">📤</span>
          <span>Scan Keluar</span>
        </NuxtLink>


        <!-- LAPORAN -->
        <NuxtLink
          to="/petugas/laporan"
          class="menu-item"
        >
          <span class="menu-icon">📋</span>
          <span>Laporan</span>
        </NuxtLink>

      </div>


      <!-- ================================================= -->
      <!-- BANTUAN -->
      <!-- ================================================= -->

      <div class="sidebar-help">

        <div class="help-title">
          BANTUAN
        </div>

        <button
          type="button"
          class="help-box"
          @click="showHelp"
        >

          <div class="help-icon">
            💡
          </div>

          <div class="help-text">
            <strong>Butuh bantuan?</strong>
            <span>Scan tiket kendaraan</span>
          </div>

          <div class="help-arrow">
            →
          </div>

        </button>

      </div>


      <!-- ================================================= -->
      <!-- USER -->
      <!-- ================================================= -->

      <div class="sidebar-bottom">

        <div class="user-card">

          <div class="user-avatar">
            👮
          </div>

          <div class="user-info">

            <strong>
              {{ userName }}
            </strong>

            <span>
              Petugas
            </span>

          </div>

        </div>


        <button
          type="button"
          class="logout-button"
          @click="logout"
        >
          🚪 Keluar
        </button>

      </div>

    </aside>


    <!-- ===================================================== -->
    <!-- MAIN CONTENT -->
    <!-- ===================================================== -->

    <main class="main-content">

      <!-- TOPBAR -->
      <header class="topbar">

        <div>

          <p class="welcome">
            Selamat datang kembali 👋
          </p>

          <h1>
            Dashboard Petugas
          </h1>

          <p class="subtitle">
            Kelola aktivitas parkir Plaza Andalas dengan mudah.
          </p>

        </div>


        <div class="topbar-right">

          <div class="date-box">

            <span class="date-icon">
              📅
            </span>

            <div>
              <strong>
                {{ currentDate }}
              </strong>

              <small>
                Hari ini
              </small>
            </div>

          </div>

          <div class="role-badge">
            👮 Petugas
          </div>

        </div>

      </header>


      <!-- ================================================= -->
      <!-- STATISTIC -->
      <!-- ================================================= -->

      <section class="stat-grid">

        <!-- TOTAL MEMBER -->
        <div class="stat-card member-card">

          <div class="stat-icon">
            👥
          </div>

          <div class="stat-content">

            <span>
              Total Member
            </span>

            <strong>
              {{ totalMember }}
            </strong>

            <small>
              Member terdaftar
            </small>

          </div>

        </div>


        <!-- MEMBER LUNAS -->
        <div class="stat-card lunas-card">

          <div class="stat-icon">
            ✅
          </div>

          <div class="stat-content">

            <span>
              Member Lunas
            </span>

            <strong>
              {{ memberLunas }}
            </strong>

            <small>
              Pembayaran selesai
            </small>

          </div>

        </div>


        <!-- BELUM LUNAS -->
        <div class="stat-card belum-card">

          <div class="stat-icon">
            ⏳
          </div>

          <div class="stat-content">

            <span>
              Belum Lunas
            </span>

            <strong>
              {{ memberBelumLunas }}
            </strong>

            <small>
              Menunggu pembayaran
            </small>

          </div>

        </div>


        <!-- TOTAL TRANSAKSI -->
        <div class="stat-card transaksi-card">

          <div class="stat-icon">
            🎫
          </div>

          <div class="stat-content">

            <span>
              Transaksi
            </span>

            <strong>
              {{ totalTransaksi }}
            </strong>

            <small>
              Data transaksi
            </small>

          </div>

        </div>

      </section>


      <!-- ================================================= -->
      <!-- QUICK ACTION -->
      <!-- ================================================= -->

      <section class="section">

        <div class="section-header">

          <div>

            <h2>
              Aksi Cepat
            </h2>

            <p>
              Pilih aktivitas yang ingin dilakukan.
            </p>

          </div>

        </div>


        <div class="quick-grid">

          <!-- ================================================= -->
          <!-- TAMBAH MEMBER DIHAPUS DARI SINI -->
          <!-- ================================================= -->


          <!-- KELOLA MEMBER -->
          <NuxtLink
            to="/petugas/member"
            class="quick-card purple"
          >

            <div class="quick-icon">
              👥
            </div>

            <div>
              <strong>
                Kelola Member
              </strong>

              <span>
                Lihat data member
              </span>
            </div>

            <b>
              →
            </b>

          </NuxtLink>


          <!-- SCAN KELUAR -->
          <NuxtLink
            to="/petugas/scan-keluar"
            class="quick-card orange"
          >

            <div class="quick-icon">
              📤
            </div>

            <div>
              <strong>
                Scan Keluar
              </strong>

              <span>
                Proses kendaraan keluar
              </span>
            </div>

            <b>
              →
            </b>

          </NuxtLink>


          <!-- LAPORAN -->
          <NuxtLink
            to="/petugas/laporan"
            class="quick-card green"
          >

            <div class="quick-icon">
              📋
            </div>

            <div>
              <strong>
                Laporan
              </strong>

              <span>
                Lihat laporan parkir
              </span>
            </div>

            <b>
              →
            </b>

          </NuxtLink>

        </div>

      </section>


      <!-- ================================================= -->
      <!-- MEMBER TERBARU -->
      <!-- ================================================= -->

      <section class="section">

        <div class="section-header">

          <div>

            <h2>
              Member Terbaru
            </h2>

            <p>
              Data member yang terakhir ditambahkan.
            </p>

          </div>


          <NuxtLink
            to="/petugas/member"
            class="see-all"
          >
            Lihat Semua →
          </NuxtLink>

        </div>


        <div class="member-table-card">

          <div
            v-if="loading"
            class="loading"
          >
            <div class="spinner"></div>

            <span>
              Memuat data...
            </span>
          </div>


          <div
            v-else-if="members.length === 0"
            class="empty"
          >

            <div class="empty-icon">
              👥
            </div>

            <strong>
              Belum ada member
            </strong>

            <span>
              Data member akan muncul di sini.
            </span>

          </div>


          <div
            v-else
            class="table-wrapper"
          >

            <table>

              <thead>

                <tr>

                  <th>
                    Kode
                  </th>

                  <th>
                    Nama Member
                  </th>

                  <th>
                    Perusahaan
                  </th>

                  <th>
                    Status
                  </th>

                  <th>
                    Expired
                  </th>

                </tr>

              </thead>


              <tbody>

                <tr
                  v-for="member in latestMembers"
                  :key="member.id"
                >

                  <td>

                    <span class="kode">
                      {{ member.kode_member || "-" }}
                    </span>

                  </td>


                  <td>

                    <strong>
                      {{ member.nama_member || "-" }}
                    </strong>

                  </td>


                  <td>
                    {{ member.nama_perusahaan || "-" }}
                  </td>


                  <td>

                    <span
                      :class="[
                        'status',
                        isLunas(member)
                          ? 'lunas'
                          : 'belum'
                      ]"
                    >
                      {{
                        isLunas(member)
                          ? "Lunas"
                          : "Belum Lunas"
                      }}
                    </span>

                  </td>


                  <td>
                    {{ formatTanggal(member.tanggal_expired) }}
                  </td>

                </tr>

              </tbody>

            </table>

          </div>

        </div>

      </section>

    </main>


    <!-- ===================================================== -->
    <!-- HELP MODAL -->
    <!-- ===================================================== -->

    <div
      v-if="helpVisible"
      class="help-overlay"
      @click.self="helpVisible = false"
    >

      <div class="help-modal">

        <button
          type="button"
          class="close-help"
          @click="helpVisible = false"
        >
          ×
        </button>

        <div class="big-help-icon">
          💡
        </div>

        <h2>
          Butuh Bantuan?
        </h2>

        <p>
          Gunakan menu <b>Scan Keluar</b> untuk membaca
          tiket atau barcode kendaraan yang akan keluar.
        </p>

        <NuxtLink
          to="/petugas/scan-keluar"
          class="help-scan-button"
          @click="helpVisible = false"
        >
          📤 Buka Scan Keluar
        </NuxtLink>

      </div>

    </div>

  </div>
</template>


<script setup lang="ts">

import {
  ref,
  computed,
  onMounted
} from "vue";


// =====================================================
// ROUTER
// =====================================================

const router = useRouter();


// =====================================================
// API
// =====================================================

const API_URL =
  "http://127.0.0.1:8000/api";


// =====================================================
// INTERFACE MEMBER
// =====================================================

interface Member {

  id: number;

  kode_member?: string;

  token?: string;

  nama_member?: string;

  nama_perusahaan?: string;

  total_harga?: number;

  jumlah_bayar?: number;

  kembalian?: number;

  status?: string;

  tanggal_bayar?: string;

  tanggal_mulai?: string;

  tanggal_expired?: string;

}


// =====================================================
// DATA
// =====================================================

const members =
  ref<Member[]>([]);


// =====================================================
// LOADING
// =====================================================

const loading =
  ref(false);


// =====================================================
// USER NAME
// =====================================================

const userName =
  ref("Petugas");


// =====================================================
// HELP
// =====================================================

const helpVisible =
  ref(false);


// =====================================================
// TANGGAL
// =====================================================

const currentDate =
  ref("");


// =====================================================
// TOKEN
// =====================================================

const getToken = () => {

  if (!process.client) {

    return "";

  }

  return (
    localStorage.getItem("token") ||
    ""
  );

};


// =====================================================
// USER
// =====================================================

const getUser = () => {

  if (!process.client) {

    return;

  }

  try {

    const savedUser =
      localStorage.getItem("user");

    if (!savedUser) {

      return;

    }

    const user =
      JSON.parse(savedUser);

    userName.value =
      user?.name ||
      user?.username ||
      "Petugas";

  }

  catch (error) {

    console.error(
      "Gagal membaca user:",
      error
    );

    userName.value =
      "Petugas";

  }

};


// =====================================================
// LOAD MEMBER
// =====================================================

const loadMembers =
  async () => {

    loading.value =
      true;

    try {

      const token =
        getToken();

      const response: any =
        await $fetch(
          `${API_URL}/members`,
          {

            method: "GET",

            headers: {

              Accept:
                "application/json",

              ...(token
                ? {
                    Authorization:
                      `Bearer ${token}`
                  }
                : {})

            }

          }
        );


      console.log(
        "MEMBER DASHBOARD:",
        response
      );


      if (
        Array.isArray(response)
      ) {

        members.value =
          response;

      }

      else if (
        Array.isArray(
          response?.data
        )
      ) {

        members.value =
          response.data;

      }

      else if (
        Array.isArray(
          response?.data?.data
        )
      ) {

        members.value =
          response.data.data;

      }

      else {

        members.value =
          [];

      }

    }

    catch (error: any) {

      console.error(
        "ERROR LOAD MEMBER:",
        error
      );

      /*
       * JANGAN REDIRECT KE LOGIN DI SINI.
       *
       * Ini penting supaya ketika API error,
       * dashboard tidak tiba-tiba kembali ke halaman login.
       */

      members.value =
        [];

    }

    finally {

      loading.value =
        false;

    }

  };


// =====================================================
// TOTAL MEMBER
// =====================================================

const totalMember =
  computed(() => {

    return members.value.length;

  });


// =====================================================
// MEMBER LUNAS
// =====================================================

const memberLunas =
  computed(() => {

    return members.value.filter(
      member =>
        isLunas(member)
    ).length;

  });


// =====================================================
// MEMBER BELUM LUNAS
// =====================================================

const memberBelumLunas =
  computed(() => {

    return members.value.filter(
      member =>
        !isLunas(member)
    ).length;

  });


// =====================================================
// TOTAL TRANSAKSI
// =====================================================

const totalTransaksi =
  computed(() => {

    /*
     * Untuk sementara jumlah transaksi
     * mengikuti jumlah data member.
     *
     * Nanti bisa diganti API transaksi
     * kalau tabel transaksi sudah tersedia.
     */

    return members.value.length;

  });


// =====================================================
// MEMBER TERBARU
// =====================================================

const latestMembers =
  computed(() => {

    return [...members.value]
      .sort(
        (a, b) =>
          Number(b.id || 0) -
          Number(a.id || 0)
      )
      .slice(0, 5);

  });


// =====================================================
// CEK LUNAS
// =====================================================

const isLunas =
  (
    member: Member
  ) => {

    return String(
      member?.status || ""
    )
      .trim()
      .toLowerCase() ===
      "lunas";

  };


// =====================================================
// FORMAT TANGGAL
// =====================================================

const formatTanggal =
  (
    value: any
  ) => {

    if (!value) {

      return "-";

    }

    const date =
      new Date(value);

    if (
      isNaN(
        date.getTime()
      )
    ) {

      return String(value);

    }

    return date.toLocaleDateString(
      "id-ID",
      {
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
      }
    );

  };


// =====================================================
// SHOW HELP
// =====================================================

const showHelp =
  () => {

    helpVisible.value =
      true;

  };


// =====================================================
// LOGOUT
// =====================================================

const logout =
  () => {

    if (process.client) {

      localStorage.removeItem(
        "token"
      );

      localStorage.removeItem(
        "user"
      );

      localStorage.removeItem(
        "role"
      );

    }

    router.push("/");

  };


// =====================================================
// TANGGAL HARI INI
// =====================================================

const setCurrentDate =
  () => {

    const today =
      new Date();

    currentDate.value =
      today.toLocaleDateString(
        "id-ID",
        {
          weekday: "long",
          day: "2-digit",
          month: "long",
          year: "numeric"
        }
      );

  };


// =====================================================
// ON MOUNTED
// =====================================================

onMounted(() => {

  getUser();

  setCurrentDate();

  loadMembers();

});

</script>


<style scoped>

/* ===================================================== */
/* RESET */
/* ===================================================== */

* {
  box-sizing: border-box;
}


/* ===================================================== */
/* PAGE */
/* ===================================================== */

.dashboard-page {

  min-height: 100vh;

  display: flex;

  background:
    linear-gradient(
      135deg,
      #f4f7ff 0%,
      #f8fafc 55%,
      #f4f1ff 100%
    );

  color: #1e293b;

  font-family:
    Arial,
    Helvetica,
    sans-serif;

}


/* ===================================================== */
/* SIDEBAR */
/* ===================================================== */

.sidebar {

  width: 290px;

  min-height: 100vh;

  position: fixed;

  top: 0;
  left: 0;
  bottom: 0;

  display: flex;

  flex-direction: column;

  background:
    linear-gradient(
      180deg,
      #172554 0%,
      #1e3368 100%
    );

  color: white;

  box-shadow:
    8px 0 30px
    rgba(
      15,
      23,
      42,
      0.10
    );

}


/* ===================================================== */
/* SIDEBAR HEADER */
/* ===================================================== */

.sidebar-header {

  height: 103px;

  display: flex;

  align-items: center;

  gap: 14px;

  padding:
    20px 18px;

  border-bottom:
    1px solid
    rgba(
      255,
      255,
      255,
      0.10
    );

}


.logo-icon {

  width: 54px;

  height: 54px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 16px;

  background:
    linear-gradient(
      135deg,
      #2f80ed,
      #2563eb
    );

  font-size: 25px;

  box-shadow:
    0 8px 20px
    rgba(
      37,
      99,
      235,
      0.35
    );

}


.logo-text h2 {

  margin: 0;

  color: white;

  font-size: 21px;

  font-weight: 900;

}


.logo-text p {

  margin:
    3px 0 0;

  color: #a9b9da;

  font-size: 12px;

}


/* ===================================================== */
/* MENU */
/* ===================================================== */

.sidebar-menu {

  padding:
    24px 18px;

}


.menu-title {

  margin:
    0 0 13px 4px;

  color: #8ea1c7;

  font-size: 10px;

  font-weight: 900;

  letter-spacing: 1.5px;

}


.menu-item {

  width: 100%;

  min-height: 61px;

  margin-bottom: 4px;

  padding:
    0 12px;

  display: flex;

  align-items: center;

  gap: 17px;

  border-radius: 14px;

  color: #bdc9e2;

  text-decoration: none;

  font-size: 15px;

  font-weight: 800;

  transition:
    0.2s ease;

}


.menu-item:hover {

  background:
    rgba(
      255,
      255,
      255,
      0.08
    );

  color: white;

}


.menu-item.active {

  color: white;

  background:
    linear-gradient(
      135deg,
      #3478ed,
      #2864df
    );

  box-shadow:
    0 10px 25px
    rgba(
      37,
      99,
      235,
      0.30
    );

}


.menu-icon {

  width: 25px;

  text-align: center;

  font-size: 18px;

}


/* ===================================================== */
/* HELP */
/* ===================================================== */

.sidebar-help {

  padding:
    0 18px;

  margin-top: auto;

}


.help-title {

  margin:
    0 0 12px 4px;

  color: #8ea1c7;

  font-size: 10px;

  font-weight: 900;

  letter-spacing: 1.5px;

}


.help-box {

  width: 100%;

  min-height: 70px;

  padding:
    10px;

  display: flex;

  align-items: center;

  gap: 10px;

  border:
    1px solid
    rgba(
      255,
      255,
      255,
      0.10
    );

  border-radius: 15px;

  background:
    rgba(
      255,
      255,
      255,
      0.05
    );

  color: white;

  cursor: pointer;

  text-align: left;

}


.help-box:hover {

  background:
    rgba(
      255,
      255,
      255,
      0.09
    );

}


.help-icon {

  width: 40px;

  height: 40px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 12px;

  background:
    rgba(
      67,
      114,
      218,
      0.30
    );

  font-size: 17px;

}


.help-text {

  flex: 1;

}


.help-text strong {

  display: block;

  font-size: 12px;

  color: white;

}


.help-text span {

  display: block;

  margin-top: 3px;

  color: #8fa3cb;

  font-size: 9px;

}


.help-arrow {

  color: #a8b8d7;

  font-size: 18px;

}


/* ===================================================== */
/* USER BOTTOM */
/* ===================================================== */

.sidebar-bottom {

  padding:
    17px 18px 20px;

}


.user-card {

  display: flex;

  align-items: center;

  gap: 10px;

  padding:
    10px;

  margin-bottom: 9px;

  border-radius: 13px;

  background:
    rgba(
      255,
      255,
      255,
      0.06
    );

}


.user-avatar {

  width: 38px;

  height: 38px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 50%;

  background: #dbeafe;

}


.user-info {

  min-width: 0;

}


.user-info strong {

  display: block;

  max-width: 170px;

  overflow: hidden;

  text-overflow: ellipsis;

  white-space: nowrap;

  font-size: 12px;

}


.user-info span {

  display: block;

  margin-top: 3px;

  color: #9db0d3;

  font-size: 10px;

}


.logout-button {

  width: 100%;

  height: 42px;

  border:
    1px solid
    rgba(
      255,
      255,
      255,
      0.10
    );

  border-radius: 10px;

  background:
    rgba(
      255,
      255,
      255,
      0.05
    );

  color: #dbeafe;

  cursor: pointer;

  font-size: 12px;

  font-weight: 800;

}


.logout-button:hover {

  background:
    rgba(
      255,
      255,
      255,
      0.10
    );

  color: white;

}


/* ===================================================== */
/* MAIN CONTENT */
/* ===================================================== */

.main-content {

  width:
    calc(
      100% - 290px
    );

  margin-left: 290px;

  min-height: 100vh;

  padding:
    32px 36px 45px;

}


/* ===================================================== */
/* TOPBAR */
/* ===================================================== */

.topbar {

  display: flex;

  justify-content: space-between;

  align-items: flex-start;

  gap: 20px;

  margin-bottom: 30px;

}


.welcome {

  margin: 0 0 5px;

  color: #64748b;

  font-size: 12px;

  font-weight: 700;

}


.topbar h1 {

  margin: 0;

  color: #0f172a;

  font-size: 30px;

  font-weight: 900;

}


.subtitle {

  margin:
    7px 0 0;

  color: #64748b;

  font-size: 13px;

}


.topbar-right {

  display: flex;

  align-items: center;

  gap: 12px;

}


.date-box {

  display: flex;

  align-items: center;

  gap: 9px;

  padding:
    9px 13px;

  border:
    1px solid #e2e8f0;

  border-radius: 12px;

  background: white;

}


.date-icon {

  font-size: 18px;

}


.date-box strong {

  display: block;

  color: #334155;

  font-size: 11px;

}


.date-box small {

  display: block;

  margin-top: 2px;

  color: #94a3b8;

  font-size: 9px;

}


.role-badge {

  padding:
    10px 14px;

  border-radius: 20px;

  background: #eff6ff;

  color: #2563eb;

  font-size: 11px;

  font-weight: 900;

}


/* ===================================================== */
/* STAT */
/* ===================================================== */

.stat-grid {

  display: grid;

  grid-template-columns:
    repeat(
      4,
      1fr
    );

  gap: 16px;

  margin-bottom: 30px;

}


.stat-card {

  min-height: 135px;

  padding: 20px;

  display: flex;

  align-items: center;

  gap: 15px;

  border:
    1px solid #e2e8f0;

  border-radius: 17px;

  background: white;

  box-shadow:
    0 8px 25px
    rgba(
      15,
      23,
      42,
      0.05
    );

}


.stat-icon {

  width: 50px;

  height: 50px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 14px;

  font-size: 21px;

}


.member-card .stat-icon {

  background: #dbeafe;

}


.lunas-card .stat-icon {

  background: #dcfce7;

}


.belum-card .stat-icon {

  background: #fef3c7;

}


.transaksi-card .stat-icon {

  background: #ede9fe;

}


.stat-content span {

  display: block;

  color: #64748b;

  font-size: 11px;

  font-weight: 700;

}


.stat-content strong {

  display: block;

  margin-top: 4px;

  color: #0f172a;

  font-size: 27px;

  font-weight: 900;

}


.stat-content small {

  display: block;

  margin-top: 4px;

  color: #94a3b8;

  font-size: 9px;

}


/* ===================================================== */
/* SECTION */
/* ===================================================== */

.section {

  margin-bottom: 27px;

}


.section-header {

  display: flex;

  align-items: center;

  justify-content: space-between;

  margin-bottom: 13px;

}


.section-header h2 {

  margin: 0;

  color: #172033;

  font-size: 18px;

  font-weight: 900;

}


.section-header p {

  margin:
    4px 0 0;

  color: #94a3b8;

  font-size: 11px;

}


.see-all {

  color: #2563eb;

  font-size: 11px;

  font-weight: 900;

  text-decoration: none;

}


.see-all:hover {

  text-decoration: underline;

}


/* ===================================================== */
/* QUICK GRID */
/* ===================================================== */

.quick-grid {

  display: grid;

  grid-template-columns:
    repeat(
      3,
      1fr
    );

  gap: 14px;

}


.quick-card {

  min-height: 88px;

  padding:
    15px;

  display: flex;

  align-items: center;

  gap: 11px;

  border:
    1px solid #e2e8f0;

  border-radius: 15px;

  background: white;

  text-decoration: none;

  transition:
    0.2s ease;

}


.quick-card:hover {

  transform:
    translateY(-2px);

  box-shadow:
    0 10px 25px
    rgba(
      15,
      23,
      42,
      0.08
    );

}


.quick-icon {

  width: 42px;

  height: 42px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 12px;

  font-size: 18px;

}


.quick-card.blue .quick-icon {

  background: #dbeafe;

}


.quick-card.purple .quick-icon {

  background: #ede9fe;

}


.quick-card.orange .quick-icon {

  background: #ffedd5;

}


.quick-card.green .quick-icon {

  background: #dcfce7;

}


.quick-card div:nth-child(2) {

  flex: 1;

}


.quick-card strong {

  display: block;

  color: #334155;

  font-size: 11px;

  font-weight: 900;

}


.quick-card span {

  display: block;

  margin-top: 4px;

  color: #94a3b8;

  font-size: 9px;

}


.quick-card b {

  color: #94a3b8;

  font-size: 16px;

}


/* ===================================================== */
/* TABLE CARD */
/* ===================================================== */

.member-table-card {

  min-height: 210px;

  overflow: hidden;

  border:
    1px solid #e2e8f0;

  border-radius: 17px;

  background: white;

  box-shadow:
    0 8px 25px
    rgba(
      15,
      23,
      42,
      0.05
    );

}


.table-wrapper {

  width: 100%;

  overflow-x: auto;

}


table {

  width: 100%;

  border-collapse: collapse;

}


thead {

  background: #f8fafc;

}


th {

  padding:
    13px 15px;

  text-align: left;

  color: #64748b;

  font-size: 9px;

  font-weight: 900;

  text-transform: uppercase;

  white-space: nowrap;

}


td {

  padding:
    14px 15px;

  border-top:
    1px solid #f1f5f9;

  color: #475569;

  font-size: 11px;

  white-space: nowrap;

}


.kode {

  display: inline-block;

  padding:
    5px 8px;

  border-radius: 7px;

  background: #eff6ff;

  color: #2563eb;

  font-family: monospace;

  font-size: 9px;

  font-weight: 900;

}


.status {

  display: inline-flex;

  padding:
    5px 9px;

  border-radius: 20px;

  font-size: 9px;

  font-weight: 900;

}


.status.lunas {

  background: #dcfce7;

  color: #15803d;

}


.status.belum {

  background: #fef3c7;

  color: #b45309;

}


/* ===================================================== */
/* LOADING */
/* ===================================================== */

.loading {

  min-height: 210px;

  display: flex;

  align-items: center;

  justify-content: center;

  flex-direction: column;

  gap: 10px;

  color: #64748b;

  font-size: 11px;

}


.spinner {

  width: 30px;

  height: 30px;

  border:
    4px solid #dbeafe;

  border-top-color:
    #2563eb;

  border-radius: 50%;

  animation:
    spin 0.8s linear infinite;

}


@keyframes spin {

  to {

    transform:
      rotate(360deg);

  }

}


/* ===================================================== */
/* EMPTY */
/* ===================================================== */

.empty {

  min-height: 210px;

  display: flex;

  align-items: center;

  justify-content: center;

  flex-direction: column;

}


.empty-icon {

  margin-bottom: 7px;

  font-size: 32px;

}


.empty strong {

  color: #475569;

  font-size: 13px;

}


.empty span {

  margin-top: 5px;

  color: #94a3b8;

  font-size: 10px;

}


/* ===================================================== */
/* HELP MODAL */
/* ===================================================== */

.help-overlay {

  position: fixed;

  inset: 0;

  z-index: 9999;

  display: flex;

  align-items: center;

  justify-content: center;

  padding: 20px;

  background:
    rgba(
      15,
      23,
      42,
      0.55
    );

  backdrop-filter:
    blur(4px);

}


.help-modal {

  position: relative;

  width: 100%;

  max-width: 400px;

  padding: 30px;

  text-align: center;

  border-radius: 20px;

  background: white;

  box-shadow:
    0 25px 70px
    rgba(
      15,
      23,
      42,
      0.25
    );

}


.close-help {

  position: absolute;

  top: 12px;

  right: 14px;

  width: 32px;

  height: 32px;

  border: none;

  border-radius: 50%;

  background: #f1f5f9;

  color: #475569;

  font-size: 20px;

  cursor: pointer;

}


.big-help-icon {

  width: 65px;

  height: 65px;

  margin:
    0 auto 15px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 18px;

  background: #dbeafe;

  font-size: 28px;

}


.help-modal h2 {

  margin: 0;

  color: #0f172a;

  font-size: 20px;

}


.help-modal p {

  margin:
    10px 0 20px;

  color: #64748b;

  font-size: 12px;

  line-height: 1.6;

}


.help-scan-button {

  display: block;

  padding:
    12px;

  border-radius: 10px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #4f46e5
    );

  color: white;

  text-decoration: none;

  font-size: 12px;

  font-weight: 900;

}


/* ===================================================== */
/* RESPONSIVE */
/* ===================================================== */

@media (max-width: 1200px) {

  .stat-grid {

    grid-template-columns:
      repeat(
        2,
        1fr
      );

  }

  .quick-grid {

    grid-template-columns:
      repeat(
        2,
        1fr
      );

  }

}


@media (max-width: 900px) {

  .sidebar {

    width: 235px;

  }

  .main-content {

    width:
      calc(
        100% - 235px
      );

    margin-left: 235px;

    padding:
      25px;

  }

  .topbar {

    flex-direction: column;

  }

}


@media (max-width: 700px) {

  .dashboard-page {

    display: block;

  }

  .sidebar {

    position: relative;

    width: 100%;

    min-height: auto;

  }

  .main-content {

    width: 100%;

    margin-left: 0;

    padding: 20px;

  }

  .sidebar-menu {

    padding:
      15px;

  }

  .sidebar-help {

    margin-top: 10px;

    padding:
      0 15px;

  }

  .sidebar-bottom {

    padding:
      15px;

  }

  .stat-grid {

    grid-template-columns:
      1fr;

  }

  .quick-grid {

    grid-template-columns:
      1fr;

  }

}


@media (max-width: 450px) {

  .main-content {

    padding: 15px;

  }

  .topbar h1 {

    font-size: 24px;

  }

  .topbar-right {

    width: 100%;

    flex-direction: column;

    align-items: stretch;

  }

  .date-box {

    width: 100%;

  }

  .role-badge {

    text-align: center;

  }

}

</style>