<template>
  <div class="page">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar">

      <div class="logo-area">
        <div class="logo-icon">
          🅿️
        </div>

        <div>
          <h1>ParkirKu</h1>
          <p>Parkir Plaza Andalas</p>
        </div>
      </div>

      <div class="menu-title">
        MENU UTAMA
      </div>

      <nav class="menu">

        <NuxtLink
          to="/petugas"
          class="menu-item"
        >
          <span class="menu-icon">📊</span>
          <span>Dashboard</span>
        </NuxtLink>

        <NuxtLink
          to="/transaksi"
          class="menu-item active"
        >
          <span class="menu-icon">💳</span>
          <span>Kelola Transaksi</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/member"
          class="menu-item"
        >
          <span class="menu-icon">👥</span>
          <span>Kelola Member</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/scan-keluar"
          class="menu-item"
        >
          <span class="menu-icon">📤</span>
          <span>Scan Keluar</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/laporan"
          class="menu-item"
        >
          <span class="menu-icon">📋</span>
          <span>Laporan</span>
        </NuxtLink>

      </nav>

      <div class="sidebar-bottom">

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
         MAIN
    ====================================================== -->
    <main class="main">

      <!-- HEADER -->
      <header class="topbar">

        <div>
          <h2>Kelola Transaksi</h2>
          <p>
            Data tiket parkir kendaraan Plaza Andalas.
          </p>
        </div>

        <div class="user-box">

          <div class="user-avatar">
            {{ userInitial }}
          </div>

          <div>
            <strong>{{ userName }}</strong>
            <small>Petugas</small>
          </div>

        </div>

      </header>


      <!-- =====================================================
           STATISTIK
      ====================================================== -->
      <section class="stats-grid">

        <div class="stat-card">

          <div class="stat-icon blue">
            🎫
          </div>

          <div>
            <span>Total Transaksi</span>
            <strong>{{ transaksi.length }}</strong>
          </div>

        </div>


        <div class="stat-card">

          <div class="stat-icon orange">
            🚗
          </div>

          <div>
            <span>Kendaraan Masuk</span>
            <strong>{{ totalMasuk }}</strong>
          </div>

        </div>


        <div class="stat-card">

          <div class="stat-icon green">
            ✓
          </div>

          <div>
            <span>Kendaraan Keluar</span>
            <strong>{{ totalKeluar }}</strong>
          </div>

        </div>


        <div class="stat-card">

          <div class="stat-icon purple">
            💰
          </div>

          <div>
            <span>Total Pendapatan</span>
            <strong>{{ formatRupiah(totalPendapatan) }}</strong>
          </div>

        </div>

      </section>


      <!-- =====================================================
           CONTENT CARD
      ====================================================== -->
      <section class="content-card">

        <!-- CARD HEADER -->
        <div class="card-header">

          <div>
            <h3>Data Transaksi</h3>
            <p>
              Daftar tiket parkir yang tercatat dalam sistem.
            </p>
          </div>

          <button
            type="button"
            class="refresh-button"
            :disabled="loading"
            @click="loadTransaksi"
          >
            <span
              :class="{ spinning: loading }"
            >
              ↻
            </span>

            {{ loading ? 'Memuat...' : 'Refresh' }}
          </button>

        </div>


        <!-- =====================================================
             FILTER
        ====================================================== -->
        <div class="filter-area">

          <div class="search-box">

            <span>🔍</span>

            <input
              v-model="search"
              type="text"
              autocomplete="off"
              placeholder="Cari kode tiket, plat nomor, atau token..."
            />

          </div>


          <select
            v-model="filterStatus"
            class="status-filter"
          >
            <option value="semua">
              Semua Status
            </option>

            <option value="masuk">
              Masih Parkir
            </option>

            <option value="keluar">
              Sudah Keluar
            </option>
          </select>

        </div>


        <!-- =====================================================
             ERROR
        ====================================================== -->
        <div
          v-if="errorMessage"
          class="error-message"
        >

          <span>⚠️</span>

          <div>
            <strong>Gagal mengambil data</strong>
            <p>{{ errorMessage }}</p>
          </div>

          <button
            type="button"
            @click="errorMessage = ''"
          >
            ×
          </button>

        </div>


        <!-- =====================================================
             LOADING
        ====================================================== -->
        <div
          v-if="loading"
          class="loading-box"
        >
          <div class="loader"></div>

          <p>
            Mengambil data transaksi...
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
                <th>NO</th>
                <th>TIKET</th>
                <th>KENDARAAN</th>
                <th>PLAT NOMOR</th>
                <th>JAM MASUK</th>
                <th>JAM KELUAR</th>
                <th>TARIF</th>
                <th>STATUS</th>
                <th>AKSI</th>
              </tr>

            </thead>


            <tbody>

              <tr
                v-for="(item, index) in filteredTransaksi"
                :key="item.id || item.token || index"
              >

                <!-- NO -->
                <td>
                  <span class="number">
                    {{ index + 1 }}
                  </span>
                </td>


                <!-- TIKET -->
                <td>

                  <div class="ticket-info">

                    <div class="ticket-icon">
                      🎫
                    </div>

                    <div>

                      <strong>
                        {{ getKodeTiket(item) }}
                      </strong>

                      <small>
                        {{ getToken(item) }}
                      </small>

                    </div>

                  </div>

                </td>


                <!-- KENDARAAN -->
                <td>

                  <span
                    class="vehicle-badge"
                    :class="getJenis(item)"
                  >

                    <span v-if="getJenis(item) === 'motor'">
                      🛵
                    </span>

                    <span v-else>
                      🚗
                    </span>

                    {{ getJenisLabel(item) }}

                  </span>

                </td>


                <!-- PLAT -->
                <td>

                  <strong class="plat">
                    {{ getPlat(item) }}
                  </strong>

                </td>


                <!-- MASUK -->
                <td>

                  <div class="date-info">

                    <strong>
                      {{ formatTanggal(getWaktuMasuk(item)) }}
                    </strong>

                    <small>
                      {{ formatJam(getWaktuMasuk(item)) }}
                    </small>

                  </div>

                </td>


                <!-- KELUAR -->
                <td>

                  <div
                    v-if="getWaktuKeluar(item)"
                    class="date-info"
                  >

                    <strong>
                      {{ formatTanggal(getWaktuKeluar(item)) }}
                    </strong>

                    <small>
                      {{ formatJam(getWaktuKeluar(item)) }}
                    </small>

                  </div>

                  <span
                    v-else
                    class="dash"
                  >
                    -
                  </span>

                </td>


                <!-- TARIF -->
                <td>

                  <strong class="price">
                    {{ formatRupiah(getTarif(item)) }}
                  </strong>

                </td>


                <!-- STATUS -->
                <td>

                  <span
                    v-if="isKeluar(item)"
                    class="status keluar"
                  >
                    <span>●</span>
                    Sudah Keluar
                  </span>

                  <span
                    v-else
                    class="status masuk"
                  >
                    <span>●</span>
                    Masih Parkir
                  </span>

                </td>


                <!-- AKSI -->
                <td>

                  <button
                    type="button"
                    class="detail-button"
                    @click="lihatDetail(item)"
                  >
                    👁️
                    Detail
                  </button>

                </td>

              </tr>


              <!-- EMPTY -->
              <tr
                v-if="filteredTransaksi.length === 0"
              >

                <td
                  colspan="9"
                  class="empty-cell"
                >

                  <div class="empty-box">

                    <div class="empty-icon">
                      🎫
                    </div>

                    <h4>
                      Tidak ada transaksi
                    </h4>

                    <p>
                      {{
                        search
                          ? 'Data yang dicari tidak ditemukan.'
                          : 'Belum ada data transaksi parkir.'
                      }}
                    </p>

                  </div>

                </td>

              </tr>

            </tbody>

          </table>

        </div>


        <!-- FOOTER TABLE -->
        <div
          v-if="!loading"
          class="table-footer"
        >

          <span>
            Menampilkan
            <strong>{{ filteredTransaksi.length }}</strong>
            data
          </span>

          <span>
            Total
            <strong>{{ transaksi.length }}</strong>
            transaksi
          </span>

        </div>

      </section>

    </main>


    <!-- =====================================================
         MODAL DETAIL
    ====================================================== -->
    <div
      v-if="selectedTransaction"
      class="modal-overlay"
      @click.self="closeDetail"
    >

      <div class="detail-modal">

        <button
          type="button"
          class="modal-close"
          @click="closeDetail"
        >
          ×
        </button>


        <div class="modal-title">

          <div class="modal-icon">
            🎫
          </div>

          <div>
            <h3>Detail Transaksi</h3>
            <p>
              Informasi tiket parkir
            </p>
          </div>

        </div>


        <div class="detail-code">

          <span>KODE TIKET</span>

          <strong>
            {{ getKodeTiket(selectedTransaction) }}
          </strong>

        </div>


        <div class="detail-list">

          <div class="detail-row">

            <span>Token</span>

            <strong class="token">
              {{ getToken(selectedTransaction) }}
            </strong>

          </div>


          <div class="detail-row">

            <span>Plat Nomor</span>

            <strong>
              {{ getPlat(selectedTransaction) }}
            </strong>

          </div>


          <div class="detail-row">

            <span>Jenis Kendaraan</span>

            <strong>
              {{ getJenisLabel(selectedTransaction) }}
            </strong>

          </div>


          <div class="detail-row">

            <span>Tarif</span>

            <strong>
              {{ formatRupiah(getTarif(selectedTransaction)) }}
            </strong>

          </div>


          <div class="detail-row">

            <span>Waktu Masuk</span>

            <strong>
              {{ formatTanggalLengkap(getWaktuMasuk(selectedTransaction)) }}
            </strong>

          </div>


          <div class="detail-row">

            <span>Waktu Keluar</span>

            <strong>
              {{
                getWaktuKeluar(selectedTransaction)
                  ? formatTanggalLengkap(getWaktuKeluar(selectedTransaction))
                  : 'Belum keluar'
              }}
            </strong>

          </div>


          <div class="detail-row">

            <span>Status</span>

            <span
              v-if="isKeluar(selectedTransaction)"
              class="status keluar"
            >
              <span>●</span>
              Sudah Keluar
            </span>

            <span
              v-else
              class="status masuk"
            >
              <span>●</span>
              Masih Parkir
            </span>

          </div>

        </div>


        <button
          type="button"
          class="close-detail-button"
          @click="closeDetail"
        >
          Tutup
        </button>

      </div>

    </div>

  </div>
</template>


<script setup>

import {
  ref,
  computed,
  onMounted
} from 'vue'


// ============================================================
// API
// ============================================================

const API_URL =
  'http://127.0.0.1:8000/api'


// ============================================================
// STATE
// ============================================================

const transaksi =
  ref([])

const loading =
  ref(false)

const errorMessage =
  ref('')

const search =
  ref('')

const filterStatus =
  ref('semua')

const selectedTransaction =
  ref(null)

const userName =
  ref('Petugas')


// ============================================================
// USER INITIAL
// ============================================================

const userInitial =
  computed(() => {

    const name =
      String(userName.value || 'P')
        .trim()

    return name
      ? name.charAt(0).toUpperCase()
      : 'P'

  })


// ============================================================
// LOAD TRANSAKSI
// ============================================================

async function loadTransaksi() {

  if (loading.value) {
    return
  }

  loading.value = true
  errorMessage.value = ''

  try {

    const token =
      process.client
        ? localStorage.getItem('token')
        : null


    const headers = {
      Accept:
        'application/json'
    }


    if (token) {

      headers.Authorization =
        `Bearer ${token}`

    }


    let response = null


    // ========================================================
    // COBA ENDPOINT /tiketparkir
    // ========================================================

    try {

      response =
        await $fetch(
          `${API_URL}/tiketparkir`,
          {
            method: 'GET',
            headers,
            timeout: 15000
          }
        )

    } catch (firstError) {

      console.warn(
        'GET /tiketparkir gagal, mencoba /tiket...',
        firstError
      )


      // ======================================================
      // FALLBACK /tiket
      // ======================================================

      response =
        await $fetch(
          `${API_URL}/tiket`,
          {
            method: 'GET',
            headers,
            timeout: 15000
          }
        )

    }


    console.log(
      'RESPONSE TRANSAKSI:',
      response
    )


    // ========================================================
    // AMBIL DATA
    // ========================================================

    let data =
      response?.data ??
      response?.tiket ??
      response?.tickets ??
      response


    if (
      !Array.isArray(data) &&
      data?.data &&
      Array.isArray(data.data)
    ) {

      data =
        data.data

    }


    if (Array.isArray(data)) {

      transaksi.value =
        data

    } else {

      transaksi.value =
        []

      console.warn(
        'Format response transaksi tidak dikenali:',
        response
      )

    }

  } catch (error) {

    console.error(
      'ERROR LOAD TRANSAKSI:',
      error
    )

    console.error(
      'ERROR DATA:',
      error?.data
    )


    if (
      error?.status === 401
    ) {

      errorMessage.value =
        'Sesi login sudah berakhir. Silakan login kembali.'

    } else if (
      error?.status === 404
    ) {

      errorMessage.value =
        'Endpoint transaksi belum tersedia di Laravel. Pastikan route /api/tiketparkir atau /api/tiket ada.'

    } else if (
      error?.status === 500
    ) {

      errorMessage.value =
        error?.data?.message ||
        'Terjadi kesalahan pada server Laravel.'

    } else if (
      error?.message?.includes('Failed to fetch') ||
      error?.message?.includes('fetch failed')
    ) {

      errorMessage.value =
        'Laravel tidak dapat dihubungi. Jalankan php artisan serve di folder backend.'

    } else {

      errorMessage.value =
        error?.data?.message ||
        error?.message ||
        'Gagal mengambil data transaksi.'

    }

  } finally {

    loading.value =
      false

  }

}


// ============================================================
// FILTER DATA
// ============================================================

const filteredTransaksi =
  computed(() => {

    let result =
      [...transaksi.value]


    // ========================================================
    // SEARCH
    // ========================================================

    const keyword =
      String(search.value || '')
        .trim()
        .toLowerCase()


    if (keyword) {

      result =
        result.filter(item => {

          const kode =
            getKodeTiket(item)
              .toLowerCase()

          const plat =
            getPlat(item)
              .toLowerCase()

          const token =
            getToken(item)
              .toLowerCase()

          const jenis =
            getJenisLabel(item)
              .toLowerCase()


          return (
            kode.includes(keyword) ||
            plat.includes(keyword) ||
            token.includes(keyword) ||
            jenis.includes(keyword)
          )

        })

    }


    // ========================================================
    // FILTER STATUS
    // ========================================================

    if (
      filterStatus.value === 'masuk'
    ) {

      result =
        result.filter(
          item => !isKeluar(item)
        )

    }


    if (
      filterStatus.value === 'keluar'
    ) {

      result =
        result.filter(
          item => isKeluar(item)
        )

    }


    return result

  })


// ============================================================
// TOTAL MASUK
// ============================================================

const totalMasuk =
  computed(() => {

    return transaksi.value
      .filter(
        item => !isKeluar(item)
      )
      .length

  })


// ============================================================
// TOTAL KELUAR
// ============================================================

const totalKeluar =
  computed(() => {

    return transaksi.value
      .filter(
        item => isKeluar(item)
      )
      .length

  })


// ============================================================
// TOTAL PENDAPATAN
// ============================================================

const totalPendapatan =
  computed(() => {

    return transaksi.value
      .filter(
        item => isKeluar(item)
      )
      .reduce(
        (total, item) => {

          return (
            total +
            getTarif(item)
          )

        },
        0
      )

  })


// ============================================================
// GET KODE TIKET
// ============================================================

function getKodeTiket(item) {

  if (!item) {
    return '-'
  }

  return String(
    item.kode_tiket ||
    item.kode_ticket ||
    item.kode ||
    item.ticket_code ||
    '-'
  )

}


// ============================================================
// GET TOKEN
// ============================================================

function getToken(item) {

  if (!item) {
    return '-'
  }

  return String(
    item.token ||
    item.ticket_token ||
    '-'
  )

}


// ============================================================
// GET PLAT
// ============================================================

function getPlat(item) {

  if (!item) {
    return '-'
  }

  return String(
    item.plat_nomor ||
    item.nomor_plat ||
    item.plat ||
    item.plate_number ||
    '-'
  ).toUpperCase()

}


// ============================================================
// GET JENIS
// ============================================================

function getJenis(item) {

  if (!item) {
    return 'motor'
  }

  const value =
    String(
      item.jenis_kendaraan ||
      item.jenis ||
      item.kendaraan ||
      'motor'
    )
      .toLowerCase()
      .trim()


  if (
    value.includes('mobil') ||
    value.includes('car')
  ) {

    return 'mobil'

  }


  return 'motor'

}


// ============================================================
// GET JENIS LABEL
// ============================================================

function getJenisLabel(item) {

  return getJenis(item) === 'mobil'
    ? 'Mobil'
    : 'Motor'

}


// ============================================================
// GET WAKTU MASUK
// ============================================================

function getWaktuMasuk(item) {

  if (!item) {
    return null
  }

  return (
    item.waktu_masuk ||
    item.jam_masuk ||
    item.tanggal_masuk ||
    item.created_at ||
    null
  )

}


// ============================================================
// GET WAKTU KELUAR
// ============================================================

function getWaktuKeluar(item) {

  if (!item) {
    return null
  }

  return (
    item.waktu_keluar ||
    item.jam_keluar ||
    item.tanggal_keluar ||
    null
  )

}


// ============================================================
// CEK SUDAH KELUAR
// ============================================================

function isKeluar(item) {

  const waktuKeluar =
    getWaktuKeluar(item)


  if (
    waktuKeluar
  ) {

    return true

  }


  const status =
    String(
      item?.status ||
      item?.status_tiket ||
      ''
    )
      .toLowerCase()
      .trim()


  return (
    status === 'keluar' ||
    status === 'selesai' ||
    status === 'completed'
  )

}


// ============================================================
// GET TARIF
// ============================================================

function getTarif(item) {

  if (!item) {
    return 0
  }

  // MEMBER TIDAK MEMBAYAR PARKIR PER JAM
  const jenis =
    String(
      item.jenis_kendaraan ||
      item.jenis ||
      item.kendaraan ||
      ''
    )
      .toLowerCase()
      .trim()

  if (jenis === 'member') {
    return 0
  }

  // Transaksi yang belum keluar belum mempunyai biaya parkir.
  const waktuMasuk = getWaktuMasuk(item)
  const waktuKeluar = getWaktuKeluar(item)

  if (!waktuMasuk || !waktuKeluar) {
    return 0
  }

  const masuk = new Date(waktuMasuk)
  const keluar = new Date(waktuKeluar)

  if (
    Number.isNaN(masuk.getTime()) ||
    Number.isNaN(keluar.getTime())
  ) {
    return 0
  }

  // Hitung lama parkir dalam menit.
  const selisihMenit =
    Math.max(
      0,
      Math.ceil(
        (keluar.getTime() - masuk.getTime()) /
        (1000 * 60)
      )
    )

  // Minimal dikenakan 1 jam.
  const jumlahJam =
    Math.max(
      1,
      Math.ceil(selisihMenit / 60)
    )

  // MOTOR = Rp3.000 / jam
  if (jenis.includes('motor')) {
    return jumlahJam * 3000
  }

  // MOBIL = Rp5.000 / jam
  if (
    jenis.includes('mobil') ||
    jenis.includes('car')
  ) {
    return jumlahJam * 5000
  }

  return 0
}


// FORMAT RUPIAH
// ============================================================

function formatRupiah(value) {

  const number =
    Number(value || 0)


  return (
    'Rp' +
    number.toLocaleString(
      'id-ID'
    )
  )

}


// ============================================================
// FORMAT TANGGAL
// ============================================================

function formatTanggal(value) {

  if (!value) {
    return '-'
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
    'id-ID',
    {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    }
  )

}


// ============================================================
// FORMAT JAM
// ============================================================

function formatJam(value) {

  if (!value) {
    return '-'
  }


  const date =
    new Date(value)


  if (
    Number.isNaN(
      date.getTime()
    )
  ) {

    return '-'

  }


  return date.toLocaleTimeString(
    'id-ID',
    {
      hour: '2-digit',
      minute: '2-digit'
    }
  )

}


// ============================================================
// FORMAT LENGKAP
// ============================================================

function formatTanggalLengkap(value) {

  if (!value) {
    return '-'
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


  return date.toLocaleString(
    'id-ID',
    {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }
  )

}


// ============================================================
// DETAIL
// ============================================================

function lihatDetail(item) {

  selectedTransaction.value =
    item

}


function closeDetail() {

  selectedTransaction.value =
    null

}


// ============================================================
// LOGOUT
// ============================================================

function logout() {

  if (process.client) {

    localStorage.removeItem(
      'token'
    )

    localStorage.removeItem(
      'user'
    )

    localStorage.removeItem(
      'role'
    )

  }


  navigateTo('/')

}


// ============================================================
// LOAD USER
// ============================================================

function loadUser() {

  if (!process.client) {
    return
  }


  try {

    const savedUser =
      localStorage.getItem(
        'user'
      )


    if (!savedUser) {
      return
    }


    const user =
      JSON.parse(savedUser)


    userName.value =
      user?.name ||
      user?.username ||
      user?.nama ||
      'Petugas'

  } catch (error) {

    console.error(
      'Gagal membaca user:',
      error
    )

  }

}


// ============================================================
// ON MOUNTED
// ============================================================

onMounted(() => {

  loadUser()

  loadTransaksi()

})

</script>


<style scoped>

/* ============================================================
   RESET
============================================================ */

* {
  box-sizing: border-box;
}


/* ============================================================
   PAGE
============================================================ */

.page {
  min-height: 100vh;

  display: flex;

  background:
    linear-gradient(
      135deg,
      #eef4ff 0%,
      #f7f8ff 50%,
      #f4efff 100%
    );

  color: #172554;
}


/* ============================================================
   SIDEBAR
============================================================ */

.sidebar {

  position: fixed;

  left: 0;
  top: 0;
  bottom: 0;

  width: 270px;

  display: flex;
  flex-direction: column;

  background:
    linear-gradient(
      180deg,
      #172554 0%,
      #1e3a8a 55%,
      #243b7a 100%
    );

  color: white;

  z-index: 100;

}


.logo-area {

  height: 100px;

  padding: 0 28px;

  display: flex;

  align-items: center;

  gap: 14px;

  border-bottom:
    1px solid
    rgba(255,255,255,.12);

}


.logo-icon {

  width: 52px;
  height: 52px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 15px;

  background:
    linear-gradient(
      135deg,
      #3b82f6,
      #6366f1
    );

  font-size: 25px;

}


.logo-area h1 {

  margin: 0;

  font-size: 21px;

  font-weight: 850;

}


.logo-area p {

  margin: 4px 0 0;

  color:
    #bfdbfe;

  font-size: 11px;

}


.menu-title {

  padding:
    28px 30px 12px;

  color:
    #93c5fd;

  font-size: 11px;

  font-weight: 800;

  letter-spacing: 1.5px;

}


.menu {

  padding:
    0 17px;

}


.menu-item {

  height: 57px;

  display: flex;

  align-items: center;

  gap: 14px;

  padding: 0 18px;

  margin-bottom: 7px;

  border-radius: 15px;

  color:
    #dbeafe;

  text-decoration: none;

  font-size: 14px;

  font-weight: 750;

  transition:
    .2s;

}


.menu-item:hover {

  background:
    rgba(
      255,
      255,
      255,
      .09
    );

  color: white;

}


.menu-item.active {

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #4f46e5
    );

  color: white;

  box-shadow:
    0 10px 25px
    rgba(
      37,
      99,
      235,
      .3
    );

}


.menu-icon {

  width: 25px;

  text-align: center;

  font-size: 18px;

}


.sidebar-bottom {

  margin-top: auto;

  padding: 18px;

}


.logout-button {

  width: 100%;

  height: 52px;

  display: flex;

  align-items: center;

  gap: 14px;

  padding: 0 20px;

  border: none;

  border-radius: 14px;

  background:
    rgba(
      255,
      255,
      255,
      .09
    );

  color: white;

  font-size: 14px;

  font-weight: 750;

  cursor: pointer;

}


.logout-button:hover {

  background:
    rgba(
      255,
      255,
      255,
      .16
    );

}


/* ============================================================
   MAIN
============================================================ */

.main {

  width: calc(
    100% - 270px
  );

  margin-left: 270px;

  min-height: 100vh;

  padding: 30px 42px 50px;

}


/* ============================================================
   TOPBAR
============================================================ */

.topbar {

  display: flex;

  justify-content: space-between;

  align-items: center;

  margin-bottom: 28px;

}


.topbar h2 {

  margin: 0;

  color:
    #172554;

  font-size: 30px;

  font-weight: 850;

}


.topbar p {

  margin: 6px 0 0;

  color:
    #64748b;

  font-size: 13px;

}


.user-box {

  display: flex;

  align-items: center;

  gap: 10px;

  padding:
    9px 15px;

  border:
    1px solid
    #e2e8f0;

  border-radius: 13px;

  background: white;

  box-shadow:
    0 8px 25px
    rgba(
      15,
      23,
      42,
      .05
    );

}


.user-avatar {

  width: 38px;
  height: 38px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 11px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  color: white;

  font-weight: 800;

}


.user-box strong {

  display: block;

  color:
    #1e293b;

  font-size: 12px;

}


.user-box small {

  display: block;

  margin-top: 2px;

  color:
    #64748b;

  font-size: 10px;

}


/* ============================================================
   STATS
============================================================ */

.stats-grid {

  display: grid;

  grid-template-columns:
    repeat(4, 1fr);

  gap: 16px;

  margin-bottom: 22px;

}


.stat-card {

  min-height: 105px;

  display: flex;

  align-items: center;

  gap: 14px;

  padding: 19px;

  border:
    1px solid
    #e2e8f0;

  border-radius: 17px;

  background:
    rgba(
      255,
      255,
      255,
      .9
    );

  box-shadow:
    0 10px 28px
    rgba(
      15,
      23,
      42,
      .05
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

  font-size: 22px;

}


.stat-icon.blue {

  background: #dbeafe;

}


.stat-icon.orange {

  background: #ffedd5;

}


.stat-icon.green {

  background: #dcfce7;

}


.stat-icon.purple {

  background: #ede9fe;

}


.stat-card span {

  display: block;

  color:
    #64748b;

  font-size: 11px;

}


.stat-card strong {

  display: block;

  margin-top: 5px;

  color:
    #172554;

  font-size: 21px;

}


/* ============================================================
   CONTENT CARD
============================================================ */

.content-card {

  overflow: hidden;

  border:
    1px solid
    #e2e8f0;

  border-radius: 20px;

  background: white;

  box-shadow:
    0 14px 35px
    rgba(
      15,
      23,
      42,
      .06
    );

}


.card-header {

  display: flex;

  justify-content: space-between;

  align-items: center;

  padding: 24px 26px;

  border-bottom:
    1px solid
    #eef2f7;

}


.card-header h3 {

  margin: 0;

  color:
    #172033;

  font-size: 19px;

}


.card-header p {

  margin: 5px 0 0;

  color:
    #64748b;

  font-size: 11px;

}


.refresh-button {

  height: 40px;

  padding:
    0 15px;

  display: flex;

  align-items: center;

  gap: 7px;

  border: none;

  border-radius: 10px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #4f46e5
    );

  color: white;

  font-size: 12px;

  font-weight: 750;

  cursor: pointer;

}


.refresh-button:disabled {

  opacity: .6;

  cursor: wait;

}


.refresh-button span {

  font-size: 18px;

}


.spinning {

  display: inline-block;

  animation:
    spin .8s linear infinite;

}


@keyframes spin {

  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }

}


/* ============================================================
   FILTER
============================================================ */

.filter-area {

  display: flex;

  align-items: center;

  gap: 10px;

  padding: 18px 26px;

  background:
    #fafbff;

  border-bottom:
    1px solid
    #eef2f7;

}


.search-box {

  width: 440px;

  height: 45px;

  display: flex;

  align-items: center;

  gap: 9px;

  padding:
    0 13px;

  border:
    1px solid
    #dbe3ef;

  border-radius: 11px;

  background: white;

}


.search-box span {

  font-size: 17px;

}


.search-box input {

  width: 100%;

  height: 100%;

  border: none;

  outline: none;

  background: transparent;

  color:
    #172033;

  font-size: 12px;

}


.status-filter {

  height: 45px;

  min-width: 170px;

  padding:
    0 12px;

  border:
    1px solid
    #dbe3ef;

  border-radius: 11px;

  outline: none;

  background: white;

  color:
    #334155;

  font-size: 12px;

  cursor: pointer;

}


/* ============================================================
   ERROR
============================================================ */

.error-message {

  margin:
    18px 26px;

  padding: 13px 15px;

  display: flex;

  align-items: center;

  gap: 11px;

  border:
    1px solid
    #fecaca;

  border-radius: 11px;

  background:
    #fef2f2;

  color:
    #b91c1c;

}


.error-message > div {

  flex: 1;

}


.error-message strong {

  display: block;

  font-size: 12px;

}


.error-message p {

  margin: 3px 0 0;

  font-size: 11px;

}


.error-message button {

  border: none;

  background: transparent;

  color:
    #b91c1c;

  font-size: 20px;

  cursor: pointer;

}


/* ============================================================
   LOADING
============================================================ */

.loading-box {

  min-height: 300px;

  display: flex;

  flex-direction: column;

  align-items: center;

  justify-content: center;

}


.loader {

  width: 38px;
  height: 38px;

  border:
    4px solid
    #dbeafe;

  border-top-color:
    #2563eb;

  border-radius: 50%;

  animation:
    spin .8s linear infinite;

}


.loading-box p {

  margin-top: 12px;

  color:
    #64748b;

  font-size: 12px;

}


/* ============================================================
   TABLE
============================================================ */

.table-wrapper {

  width: 100%;

  overflow-x: auto;

}


table {

  width: 100%;

  min-width: 1050px;

  border-collapse: collapse;

}


thead {

  background:
    #f8faff;

}


th {

  height: 50px;

  padding:
    0 15px;

  color:
    #64748b;

  font-size: 10px;

  font-weight: 850;

  text-align: left;

  white-space: nowrap;

}


td {

  padding:
    14px 15px;

  border-top:
    1px solid
    #eef2f7;

  color:
    #334155;

  font-size: 11px;

  vertical-align: middle;

}


tbody tr:hover {

  background:
    #fafcff;

}


.number {

  color:
    #64748b;

  font-weight: 700;

}


/* ============================================================
   TICKET
============================================================ */

.ticket-info {

  display: flex;

  align-items: center;

  gap: 9px;

}


.ticket-icon {

  width: 35px;
  height: 35px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 9px;

  background:
    #eff6ff;

}


.ticket-info strong {

  display: block;

  color:
    #1d4ed8;

  font-size: 11px;

}


.ticket-info small {

  display: block;

  width: 125px;

  margin-top: 3px;

  overflow: hidden;

  text-overflow: ellipsis;

  white-space: nowrap;

  color:
    #94a3b8;

  font-family: monospace;

  font-size: 8px;

}


/* ============================================================
   VEHICLE
============================================================ */

.vehicle-badge {

  display: inline-flex;

  align-items: center;

  gap: 5px;

  padding:
    6px 9px;

  border-radius: 8px;

  font-size: 10px;

  font-weight: 750;

}


.vehicle-badge.motor {

  background:
    #eff6ff;

  color:
    #1d4ed8;

}


.vehicle-badge.mobil {

  background:
    #f3e8ff;

  color:
    #7e22ce;

}


/* ============================================================
   PLAT
============================================================ */

.plat {

  color:
    #172554;

  font-size: 12px;

  letter-spacing: .5px;

}


/* ============================================================
   DATE
============================================================ */

.date-info strong {

  display: block;

  color:
    #334155;

  font-size: 10px;

}


.date-info small {

  display: block;

  margin-top: 3px;

  color:
    #64748b;

  font-size: 9px;

}


.dash {

  color:
    #94a3b8;

}


/* ============================================================
   PRICE
============================================================ */

.price {

  color:
    #166534;

  font-size: 11px;

}


/* ============================================================
   STATUS
============================================================ */

.status {

  display: inline-flex;

  align-items: center;

  gap: 5px;

  padding:
    6px 9px;

  border-radius: 999px;

  font-size: 9px;

  font-weight: 800;

  white-space: nowrap;

}


.status.masuk {

  background:
    #fff7ed;

  color:
    #c2410c;

}


.status.keluar {

  background:
    #ecfdf5;

  color:
    #047857;

}


/* ============================================================
   DETAIL BUTTON
============================================================ */

.detail-button {

  height: 34px;

  padding:
    0 10px;

  border:
    1px solid
    #dbeafe;

  border-radius: 9px;

  background:
    #eff6ff;

  color:
    #1d4ed8;

  font-size: 10px;

  font-weight: 750;

  cursor: pointer;

  white-space: nowrap;

  transition:
    .2s;

}


.detail-button:hover {

  background:
    #dbeafe;

  transform:
    translateY(-1px);

}


/* ============================================================
   EMPTY
============================================================ */

.empty-cell {

  height: 300px;

}


.empty-box {

  text-align: center;

}


.empty-icon {

  width: 60px;
  height: 60px;

  margin: auto;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 17px;

  background:
    #eff6ff;

  font-size: 25px;

}


.empty-box h4 {

  margin:
    12px 0 4px;

  color:
    #334155;

  font-size: 14px;

}


.empty-box p {

  margin: 0;

  color:
    #94a3b8;

  font-size: 11px;

}


/* ============================================================
   TABLE FOOTER
============================================================ */

.table-footer {

  display: flex;

  justify-content: space-between;

  padding:
    15px 26px;

  border-top:
    1px solid
    #eef2f7;

  color:
    #64748b;

  font-size: 10px;

}


.table-footer strong {

  color:
    #334155;

}


/* ============================================================
   MODAL
============================================================ */

.modal-overlay {

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
      .65
    );

  backdrop-filter:
    blur(5px);

}


.detail-modal {

  position: relative;

  width:
    min(
      480px,
      100%
    );

  max-height: 90vh;

  overflow-y: auto;

  padding: 26px;

  border-radius: 20px;

  background: white;

  box-shadow:
    0 30px 70px
    rgba(
      15,
      23,
      42,
      .25
    );

}


.modal-close {

  position: absolute;

  top: 12px;
  right: 12px;

  width: 32px;
  height: 32px;

  border: none;

  border-radius: 50%;

  background:
    #f1f5f9;

  color:
    #64748b;

  font-size: 20px;

  cursor: pointer;

}


.modal-title {

  display: flex;

  align-items: center;

  gap: 12px;

  margin-bottom: 20px;

}


.modal-icon {

  width: 48px;
  height: 48px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 13px;

  background:
    #dbeafe;

  font-size: 22px;

}


.modal-title h3 {

  margin: 0;

  color:
    #172033;

  font-size: 18px;

}


.modal-title p {

  margin: 3px 0 0;

  color:
    #64748b;

  font-size: 10px;

}


/* ============================================================
   DETAIL CODE
============================================================ */

.detail-code {

  padding: 15px;

  margin-bottom: 15px;

  border:
    1px dashed
    #93c5fd;

  border-radius: 12px;

  background:
    #eff6ff;

  text-align: center;

}


.detail-code span {

  display: block;

  color:
    #64748b;

  font-size: 9px;

  letter-spacing: 1px;

}


.detail-code strong {

  display: block;

  margin-top: 5px;

  color:
    #1d4ed8;

  font-size: 22px;

}


/* ============================================================
   DETAIL LIST
============================================================ */

.detail-list {

  border-top:
    1px solid
    #eef2f7;

}


.detail-row {

  min-height: 45px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 15px;

  border-bottom:
    1px solid
    #eef2f7;

  font-size: 11px;

}


.detail-row > span:first-child {

  color:
    #64748b;

}


.detail-row > strong {

  color:
    #334155;

  text-align: right;

}


.token {

  max-width: 250px;

  overflow: hidden;

  text-overflow: ellipsis;

  white-space: nowrap;

  font-family: monospace;

  font-size: 9px;

}


.close-detail-button {

  width: 100%;

  height: 43px;

  margin-top: 20px;

  border: none;

  border-radius: 10px;

  background:
    #334155;

  color: white;

  font-weight: 750;

  cursor: pointer;

}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (
  max-width: 1100px
) {

  .sidebar {

    width: 230px;

  }

  .main {

    width:
      calc(
        100% - 230px
      );

    margin-left: 230px;

    padding:
      25px;

  }

  .stats-grid {

    grid-template-columns:
      repeat(2, 1fr);

  }

}


@media (
  max-width: 750px
) {

  .sidebar {

    position: static;

    width: 100%;

    min-height: auto;

  }

  .page {

    display: block;

  }

  .main {

    width: 100%;

    margin-left: 0;

    padding: 20px;

  }

  .menu {

    display: grid;

    grid-template-columns:
      repeat(2, 1fr);

  }

  .sidebar-bottom {

    display: none;

  }

  .stats-grid {

    grid-template-columns:
      1fr;

  }

  .topbar {

    align-items: flex-start;

    gap: 15px;

    flex-direction: column;

  }

  .filter-area {

    flex-direction: column;

    align-items: stretch;

  }

  .search-box {

    width: 100%;

  }

}


@media (
  max-width: 500px
) {

  .menu {

    grid-template-columns:
      1fr;

  }

  .card-header {

    align-items: flex-start;

    gap: 12px;

    flex-direction: column;

  }

  .table-footer {

    flex-direction: column;

    gap: 5px;

  }

}

</style>