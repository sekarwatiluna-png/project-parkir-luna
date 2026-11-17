<template>
  <div class="page">

    <!-- =====================================================
         SIDEBAR SUPER ADMIN
    ====================================================== -->
    <aside class="sidebar">

      <div>

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
            to="/superadmin"
            class="menu-item"
          >
            <span class="menu-icon">🏠</span>
            <span>Dashboard</span>
          </NuxtLink>


          <NuxtLink
            to="/superadmin/member"
            class="menu-item"
          >
            <span class="menu-icon">👥</span>
            <span>Kelola Petugas</span>
          </NuxtLink>


          <NuxtLink
            to="/superadmin/laporan"
            class="menu-item active"
          >
            <span class="menu-icon">📋</span>
            <span>Laporan</span>
          </NuxtLink>

        </nav>

      </div>


      <div class="sidebar-bottom">

        <div class="admin-info">

          <div class="admin-avatar">
            👑
          </div>

          <div>
            <strong>{{ userName }}</strong>
            <small>Super Admin</small>
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
         MAIN
    ====================================================== -->
    <main class="main">

      <!-- HEADER -->
      <header class="topbar">

        <div>

          <h2>
            Laporan
          </h2>

          <p>
            Data transaksi parkir kendaraan Plaza Andalas.
          </p>

        </div>


        <div class="user-box">

          <div class="user-avatar">
            {{ userInitial }}
          </div>

          <div>
            <strong>
              {{ userName }}
            </strong>

            <small>
              Super Admin
            </small>
          </div>

        </div>

      </header>


      <!-- =====================================================
           STATISTIK
      ====================================================== -->
      <section class="stats-grid">

        <!-- TOTAL TRANSAKSI -->
        <div class="stat-card">

          <div class="stat-icon blue">
            🎫
          </div>

          <div>

            <span>
              Total Transaksi
            </span>

            <strong>
              {{ transaksi.length }}
            </strong>

          </div>

        </div>


        <!-- KENDARAAN MASUK -->
        <div class="stat-card">

          <div class="stat-icon orange">
            🚗
          </div>

          <div>

            <span>
              Kendaraan Masuk
            </span>

            <strong>
              {{ totalMasuk }}
            </strong>

          </div>

        </div>


        <!-- KENDARAAN KELUAR -->
        <div class="stat-card">

          <div class="stat-icon green">
            ✓
          </div>

          <div>

            <span>
              Kendaraan Keluar
            </span>

            <strong>
              {{ totalKeluar }}
            </strong>

          </div>

        </div>


        <!-- PENDAPATAN -->
        <div class="stat-card">

          <div class="stat-icon purple">
            💰
          </div>

          <div>

            <span>
              Total Pendapatan
            </span>

            <strong>
              {{ formatRupiah(totalPendapatan) }}
            </strong>

          </div>

        </div>

      </section>


      <!-- =====================================================
           CONTENT CARD
      ====================================================== -->
      <section class="content-card">

        <!-- HEADER CARD -->
        <div class="card-header">

          <div>

            <h3>
              Data Transaksi
            </h3>

            <p>
              Daftar tiket parkir yang tercatat dalam sistem.
            </p>

          </div>


          <div class="header-actions">

            <!-- DOWNLOAD -->
            <button
              type="button"
              class="download-button"
              :disabled="
                loading ||
                filteredTransaksi.length === 0
              "
              @click="downloadPDF"
            >

              📄

              Download PDF

            </button>


            <!-- REFRESH -->
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

        </div>


        <!-- =====================================================
             FILTER
        ====================================================== -->
        <div class="filter-area">

          <!-- SEARCH -->
          <div class="search-box">

            <span>
              🔍
            </span>

            <input
              v-model="search"
              type="text"
              autocomplete="off"
              placeholder="Cari kode tiket, plat nomor, atau token..."
            />

          </div>


          <!-- STATUS -->
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


          <!-- MEMBER -->
          <select
            v-model="filterJenisKendaraan"
            class="status-filter"
          >

            <option value="semua">
              Semua Kendaraan
            </option>

            <option value="member">
              Member
            </option>

            <option value="nonmember">
              Non Member
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

          <span>
            ⚠️
          </span>

          <div>

            <strong>
              Gagal mengambil data
            </strong>

            <p>
              {{ errorMessage }}
            </p>

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

                <th>
                  NO
                </th>

                <th>
                  TIKET
                </th>

                <th>
                  KENDARAAN
                </th>

                <th>
                  STATUS KENDARAAN
                </th>

                <th>
                  PLAT NOMOR
                </th>

                <th>
                  JAM MASUK
                </th>

                <th>
                  JAM KELUAR
                </th>

                <th>
                  TARIF
                </th>

                <th>
                  STATUS
                </th>

                <th>
                  AKSI
                </th>

              </tr>

            </thead>


            <tbody>

              <tr
                v-for="(item, index) in filteredTransaksi"
                :key="
                  item.id ||
                  item.token ||
                  index
                "
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

                    <span
                      v-if="
                        getJenis(item) === 'motor'
                      "
                    >
                      🛵
                    </span>

                    <span v-else>
                      🚗
                    </span>

                    {{ getJenisLabel(item) }}

                  </span>

                </td>


                <!-- STATUS KENDARAAN -->
                <td>

                  <span
                    v-if="isMember(item)"
                    class="member-badge"
                  >
                    👤 Member
                  </span>

                  <span
                    v-else
                    class="nonmember-badge"
                  >
                    🚗 Non Member
                  </span>

                </td>


                <!-- PLAT -->
                <td>

                  <strong class="plat">
                    {{ getPlat(item) }}
                  </strong>

                </td>


                <!-- JAM MASUK -->
                <td>

                  <div class="date-info">

                    <strong>
                      {{
                        formatTanggal(
                          getWaktuMasuk(item)
                        )
                      }}
                    </strong>

                    <small>
                      {{
                        formatJam(
                          getWaktuMasuk(item)
                        )
                      }}
                    </small>

                  </div>

                </td>


                <!-- JAM KELUAR -->
                <td>

                  <div
                    v-if="
                      getWaktuKeluar(item)
                    "
                    class="date-info"
                  >

                    <strong>
                      {{
                        formatTanggal(
                          getWaktuKeluar(item)
                        )
                      }}
                    </strong>

                    <small>
                      {{
                        formatJam(
                          getWaktuKeluar(item)
                        )
                      }}
                    </small>

                  </div>

                  <span
                    v-else
                    class="dash"
                  >
                    -
                  </span>

                </td>


                <!-- =================================================
                     TARIF

                     PENTING:
                     Kalau masih parkir -> "-"
                     Kalau sudah keluar -> tarif dihitung
                ================================================== -->
                <td>

                  <strong
                    v-if="isKeluar(item)"
                    class="price"
                  >
                    {{
                      formatRupiah(
                        getTarif(item)
                      )
                    }}
                  </strong>

                  <span
                    v-else
                    class="dash"
                  >
                    -
                  </span>

                </td>


                <!-- STATUS -->
                <td>

                  <span
                    v-if="isKeluar(item)"
                    class="status keluar"
                  >

                    <span>
                      ●
                    </span>

                    Sudah Keluar

                  </span>


                  <span
                    v-else
                    class="status masuk"
                  >

                    <span>
                      ●
                    </span>

                    Masih Parkir

                  </span>

                </td>


                <!-- AKSI -->
                <td>

                  <button
                    type="button"
                    class="detail-button"
                    @click="
                      lihatDetail(item)
                    "
                  >

                    👁️

                    Detail

                  </button>

                </td>

              </tr>


              <!-- EMPTY -->
              <tr
                v-if="
                  filteredTransaksi.length === 0
                "
              >

                <td
                  colspan="10"
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


        <!-- =====================================================
             FOOTER TABLE
        ====================================================== -->
        <div
          v-if="!loading"
          class="table-footer"
        >

          <span>

            Menampilkan

            <strong>
              {{ filteredTransaksi.length }}
            </strong>

            data

          </span>


          <span>

            Total

            <strong>
              {{ transaksi.length }}
            </strong>

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

            <h3>
              Detail Transaksi
            </h3>

            <p>
              Informasi tiket parkir
            </p>

          </div>

        </div>


        <div class="detail-code">

          <span>
            KODE TIKET
          </span>

          <strong>
            {{
              getKodeTiket(
                selectedTransaction
              )
            }}
          </strong>

        </div>


        <div class="detail-list">

          <!-- TOKEN -->
          <div class="detail-row">

            <span>
              Token
            </span>

            <strong class="token">
              {{
                getToken(
                  selectedTransaction
                )
              }}
            </strong>

          </div>


          <!-- PLAT -->
          <div class="detail-row">

            <span>
              Plat Nomor
            </span>

            <strong>
              {{
                getPlat(
                  selectedTransaction
                )
              }}
            </strong>

          </div>


          <!-- JENIS -->
          <div class="detail-row">

            <span>
              Jenis Kendaraan
            </span>

            <strong>
              {{
                getJenisLabel(
                  selectedTransaction
                )
              }}
            </strong>

          </div>


          <!-- MEMBER -->
          <div class="detail-row">

            <span>
              Status Kendaraan
            </span>

            <strong>

              {{
                isMember(
                  selectedTransaction
                )
                  ? 'Member'
                  : 'Non Member'
              }}

            </strong>

          </div>


          <!-- TARIF -->
          <div class="detail-row">

            <span>
              Tarif
            </span>

            <strong>

              {{
                isKeluar(
                  selectedTransaction
                )
                  ? formatRupiah(
                      getTarif(
                        selectedTransaction
                      )
                    )
                  : '-'
              }}

            </strong>

          </div>


          <!-- MASUK -->
          <div class="detail-row">

            <span>
              Waktu Masuk
            </span>

            <strong>

              {{
                formatTanggalLengkap(
                  getWaktuMasuk(
                    selectedTransaction
                  )
                )
              }}

            </strong>

          </div>


          <!-- KELUAR -->
          <div class="detail-row">

            <span>
              Waktu Keluar
            </span>

            <strong>

              {{
                getWaktuKeluar(
                  selectedTransaction
                )
                  ? formatTanggalLengkap(
                      getWaktuKeluar(
                        selectedTransaction
                      )
                    )
                  : 'Belum keluar'
              }}

            </strong>

          </div>


          <!-- STATUS -->
          <div class="detail-row">

            <span>
              Status
            </span>


            <span
              v-if="
                isKeluar(
                  selectedTransaction
                )
              "
              class="status keluar"
            >

              <span>
                ●
              </span>

              Sudah Keluar

            </span>


            <span
              v-else
              class="status masuk"
            >

              <span>
                ●
              </span>

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

import { jsPDF } from 'jspdf'
import autoTable from 'jspdf-autotable'


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

const filterJenisKendaraan =
  ref('semua')

const selectedTransaction =
  ref(null)

const userName =
  ref('Super Admin')


// ============================================================
// USER INITIAL
// ============================================================

const userInitial =
  computed(() => {

    const name =
      String(
        userName.value ||
        'S'
      ).trim()

    return name
      ? name
          .charAt(0)
          .toUpperCase()
      : 'S'

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
        ? localStorage.getItem(
            'token'
          )
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
    // ENDPOINT UTAMA
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
      // FALLBACK
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
      Array.isArray(
        data.data
      )
    ) {

      data =
        data.data

    }


    if (
      Array.isArray(data)
    ) {

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


    // ========================================================
    // URUTKAN TERBARU
    // ========================================================

    transaksi.value.sort(
      (a, b) => {

        const tanggalA =
          new Date(
            getWaktuKeluar(a) ||
            getWaktuMasuk(a) ||
            a.updated_at ||
            a.created_at ||
            0
          )

        const tanggalB =
          new Date(
            getWaktuKeluar(b) ||
            getWaktuMasuk(b) ||
            b.updated_at ||
            b.created_at ||
            0
          )

        return (
          tanggalB.getTime() -
          tanggalA.getTime()
        )

      }
    )

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
      error?.message?.includes(
        'Failed to fetch'
      ) ||
      error?.message?.includes(
        'fetch failed'
      )
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
      [
        ...transaksi.value
      ]


    // ========================================================
    // SEARCH
    // ========================================================

    const keyword =
      String(
        search.value || ''
      )
        .trim()
        .toLowerCase()


    if (keyword) {

      result =
        result.filter(
          item => {

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

          }
        )

    }


    // ========================================================
    // FILTER STATUS
    // ========================================================

    if (
      filterStatus.value ===
      'masuk'
    ) {

      result =
        result.filter(
          item =>
            !isKeluar(item)
        )

    }


    if (
      filterStatus.value ===
      'keluar'
    ) {

      result =
        result.filter(
          item =>
            isKeluar(item)
        )

    }


    // ========================================================
    // FILTER MEMBER / NON MEMBER
    // ========================================================

    if (
      filterJenisKendaraan.value ===
      'member'
    ) {

      result =
        result.filter(
          item =>
            isMember(item)
        )

    }


    if (
      filterJenisKendaraan.value ===
      'nonmember'
    ) {

      result =
        result.filter(
          item =>
            !isMember(item)
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
        item =>
          !isKeluar(item)
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
        item =>
          isKeluar(item)
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
        item =>
          isKeluar(item)
      )
      .reduce(
        (
          total,
          item
        ) => {

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
// CEK MEMBER / NON MEMBER
// ============================================================

function isMember(item) {

  if (!item) {
    return false
  }


  if (item.member) {
    return true
  }


  const statusMember =
    String(
      item.status_member ||
      item.jenis_member ||
      item.tipe_kendaraan ||
      ''
    )
      .toLowerCase()
      .trim()


  if (
    statusMember ===
    'member'
  ) {

    return true

  }


  const jenis =
    String(
      item.jenis_kendaraan ||
      item.jenis ||
      item.kendaraan ||
      ''
    )
      .toLowerCase()
      .trim()


  if (
    jenis ===
    'member'
  ) {

    return true

  }


  const plat =
    getPlat(item)


  return plat.startsWith(
    'MBR-'
  )

}


// ============================================================
// GET JENIS
// MOTOR = Rp3.000/jam
// MOBIL = Rp5.000/jam
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
      item.member?.jenis_kendaraan ||
      item.member?.jenis ||
      item.jenis_kendaraan_member ||
      ''
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

  return getJenis(item) ===
    'mobil'
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
//
// PENTING:
// - Masih parkir = 0
// - Sudah keluar = hitung
// - Member = 0
// - Motor = 3.000/jam
// - Mobil = 5.000/jam
// ============================================================

function getTarif(item) {

  if (!item) {
    return 0
  }


  // MEMBER TIDAK BAYAR PARKIR PER JAM
  if (
    isMember(item)
  ) {

    return 0

  }


  // ========================================================
  // KALAU MASIH PARKIR
  // JANGAN HITUNG TARIF
  // ========================================================

  const waktuMasuk =
    getWaktuMasuk(item)

  const waktuKeluar =
    getWaktuKeluar(item)


  if (
    !waktuMasuk ||
    !waktuKeluar
  ) {

    return 0

  }


  // ========================================================
  // JENIS KENDARAAN
  // ========================================================

  const tarifPerJam =
    getJenis(item) ===
    'mobil'
      ? 5000
      : 3000


  // ========================================================
  // WAKTU
  // ========================================================

  const masuk =
    new Date(
      waktuMasuk
    )

  const keluar =
    new Date(
      waktuKeluar
    )


  if (
    Number.isNaN(
      masuk.getTime()
    ) ||
    Number.isNaN(
      keluar.getTime()
    )
  ) {

    return 0

  }


  const selisihMenit =
    Math.max(
      0,
      Math.ceil(
        (
          keluar.getTime() -
          masuk.getTime()
        ) /
        (
          1000 *
          60
        )
      )
    )


  // ========================================================
  // SETIAP MULAI JAM DIHITUNG 1 JAM
  //
  // 10 menit     = 1 jam
  // 1 jam 10 mnt = 2 jam
  // 2 jam 10 mnt = 3 jam
  // ========================================================

  const jumlahJam =
    Math.max(
      1,
      Math.ceil(
        selisihMenit /
        60
      )
    )


  return (
    jumlahJam *
    tarifPerJam
  )

}


// ============================================================
// FORMAT RUPIAH
// ============================================================

function formatRupiah(value) {

  const number =
    Number(
      value || 0
    )


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
// FORMAT TANGGAL LENGKAP
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
// DOWNLOAD PDF
// ============================================================

function downloadPDF() {

  const rows =
    filteredTransaksi.value


  if (
    !rows.length
  ) {

    alert(
      'Tidak ada data transaksi untuk di-download.'
    )

    return

  }


  const doc =
    new jsPDF(
      'landscape',
      'mm',
      'a4'
    )


  const now =
    new Date()
      .toLocaleString(
        'id-ID'
      )


  // ========================================================
  // HEADER PDF
  // ========================================================

  doc.setFont(
    'helvetica',
    'bold'
  )

  doc.setFontSize(
    18
  )

  doc.text(
    'LAPORAN PARKIRKU',
    148,
    14,
    {
      align: 'center'
    }
  )


  doc.setFontSize(
    12
  )

  doc.text(
    'Laporan Transaksi Parkir - Super Admin',
    148,
    21,
    {
      align: 'center'
    }
  )


  doc.setFont(
    'helvetica',
    'normal'
  )

  doc.setFontSize(
    9
  )

  doc.text(
    `Dicetak: ${now}`,
    14,
    29
  )


  doc.text(
    `Jumlah data: ${rows.length}`,
    14,
    35
  )


  // ========================================================
  // TABLE PDF
  // ========================================================

  autoTable(
    doc,
    {

      startY: 41,

      head: [[
        'No',
        'Kode Tiket',
        'Plat Nomor',
        'Jenis Kendaraan',
        'Status Kendaraan',
        'Jam Masuk',
        'Jam Keluar',
        'Tarif',
        'Status'
      ]],

      body:
        rows.map(
          (
            item,
            index
          ) => [

            index + 1,

            getKodeTiket(item),

            getPlat(item),

            getJenisLabel(item),

            isMember(item)
              ? 'Member'
              : 'Non Member',

            formatTanggalLengkap(
              getWaktuMasuk(item)
            ),

            getWaktuKeluar(item)
              ? formatTanggalLengkap(
                  getWaktuKeluar(item)
                )
              : '-',

            isKeluar(item)
              ? formatRupiah(
                  getTarif(item)
                )
              : '-',

            isKeluar(item)
              ? 'Sudah Keluar'
              : 'Masih Parkir'

          ]
        ),

      styles: {

        fontSize: 7,

        cellPadding: 2,

        valign: 'middle'

      },

      headStyles: {

        fontStyle:
          'bold',

        halign:
          'center'

      },

      columnStyles: {

        0: {
          halign:
            'center',
          cellWidth:
            10
        },

        1: {
          cellWidth:
            28
        },

        2: {
          cellWidth:
            28
        },

        3: {
          cellWidth:
            28
        },

        4: {
          cellWidth:
            27
        },

        5: {
          cellWidth:
            34
        },

        6: {
          cellWidth:
            34
        },

        7: {
          cellWidth:
            27,
          halign:
            'right'
        },

        8: {
          cellWidth:
            27
        }

      },

      margin: {

        left: 10,

        right: 10

      }

    }
  )


  // ========================================================
  // TOTAL PDF
  // ========================================================

  const finalY =
    doc.lastAutoTable?.finalY ||
    190


  const totalPendapatanPDF =
    rows
      .filter(
        item =>
          isKeluar(item)
      )
      .reduce(
        (
          total,
          item
        ) =>
          total +
          getTarif(item),
        0
      )


  doc.setFont(
    'helvetica',
    'bold'
  )

  doc.setFontSize(
    10
  )


  doc.text(
    `Total Pendapatan: ${formatRupiah(totalPendapatanPDF)}`,
    14,
    finalY + 10
  )


  doc.text(
    `Kendaraan Keluar: ${
      rows.filter(
        item =>
          isKeluar(item)
      ).length
    }`,
    14,
    finalY + 16
  )


  doc.save(
    `laporan-parkir-superadmin-${new Date()
      .toISOString()
      .slice(0, 10)}.pdf`
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

  if (
    process.client
  ) {

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


  navigateTo(
    '/'
  )

}


// ============================================================
// LOAD USER
// ============================================================

function loadUser() {

  if (
    !process.client
  ) {

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
      JSON.parse(
        savedUser
      )


    userName.value =
      user?.name ||
      user?.username ||
      user?.nama ||
      'Super Admin'

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

  color:
    #172554;

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

  justify-content: space-between;

  background:
    linear-gradient(
      180deg,
      #172554 0%,
      #1e3a8a 55%,
      #243b7a 100%
    );

  color:
    white;

  z-index:
    100;

}


.logo-area {

  height:
    100px;

  padding:
    0 28px;

  display:
    flex;

  align-items:
    center;

  gap:
    14px;

  border-bottom:
    1px solid
    rgba(255,255,255,.12);

}


.logo-icon {

  width:
    52px;

  height:
    52px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    15px;

  background:
    linear-gradient(
      135deg,
      #3b82f6,
      #6366f1
    );

  font-size:
    25px;

}


.logo-area h1 {

  margin:
    0;

  font-size:
    21px;

  font-weight:
    850;

}


.logo-area p {

  margin:
    4px 0 0;

  color:
    #bfdbfe;

  font-size:
    11px;

}


/* ============================================================
   MENU
============================================================ */

.menu-title {

  padding:
    28px 30px 12px;

  color:
    #93c5fd;

  font-size:
    11px;

  font-weight:
    800;

  letter-spacing:
    1.5px;

}


.menu {

  padding:
    0 17px;

}


.menu-item {

  height:
    57px;

  display:
    flex;

  align-items:
    center;

  gap:
    14px;

  padding:
    0 18px;

  margin-bottom:
    7px;

  border-radius:
    15px;

  color:
    white;

  text-decoration:
    none;

  font-size:
    14px;

  font-weight:
    650;

  transition:
    .2s;

}


.menu-item:hover {

  background:
    rgba(
      255,
      255,
      255,
      .10
    );

}


.menu-item.active {

  background:
    linear-gradient(
      135deg,
      rgba(59,130,246,.95),
      rgba(99,102,241,.95)
    );

  box-shadow:
    0 8px 22px
    rgba(
      37,
      99,
      235,
      .25
    );

}


.menu-icon {

  width:
    24px;

  text-align:
    center;

  font-size:
    18px;

}


/* ============================================================
   SIDEBAR BOTTOM
============================================================ */

.sidebar-bottom {

  padding:
    18px;

}


.admin-info {

  display:
    flex;

  align-items:
    center;

  gap:
    11px;

  padding:
    12px;

  margin-bottom:
    10px;

  border-radius:
    14px;

  background:
    rgba(
      255,
      255,
      255,
      .10
    );

}


.admin-avatar {

  width:
    40px;

  height:
    40px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    12px;

  background:
    rgba(
      255,
      255,
      255,
      .14
    );

  font-size:
    19px;

}


.admin-info strong,
.admin-info small {

  display:
    block;

}


.admin-info strong {

  font-size:
    13px;

}


.admin-info small {

  margin-top:
    3px;

  font-size:
    11px;

  color:
    #bfdbfe;

}


.logout-button {

  width:
    100%;

  height:
    46px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  gap:
    8px;

  border:
    1px solid
    rgba(
      255,
      255,
      255,
      .13
    );

  border-radius:
    12px;

  background:
    rgba(
      255,
      255,
      255,
      .08
    );

  color:
    white;

  cursor:
    pointer;

  font-weight:
    700;

}


.logout-button:hover {

  background:
    rgba(
      255,
      255,
      255,
      .15
    );

}


/* ============================================================
   MAIN
============================================================ */

.main {

  margin-left:
    270px;

  width:
    calc(
      100% - 270px
    );

  min-height:
    100vh;

  padding:
    28px 30px;

}


/* ============================================================
   TOPBAR
============================================================ */

.topbar {

  display:
    flex;

  align-items:
    center;

  justify-content:
    space-between;

  gap:
    20px;

  margin-bottom:
    24px;

}


.topbar h2 {

  margin:
    0;

  font-size:
    28px;

  font-weight:
    850;

  color:
    #172554;

}


.topbar p {

  margin:
    6px 0 0;

  color:
    #64748b;

  font-size:
    13px;

}


.user-box {

  display:
    flex;

  align-items:
    center;

  gap:
    10px;

  padding:
    8px 12px;

  border-radius:
    14px;

  background:
    rgba(
      255,
      255,
      255,
      .85
    );

  border:
    1px solid
    #e2e8f0;

}


.user-avatar {

  width:
    40px;

  height:
    40px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    12px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #6366f1
    );

  color:
    white;

  font-weight:
    800;

}


.user-box strong,
.user-box small {

  display:
    block;

}


.user-box strong {

  font-size:
    13px;

}


.user-box small {

  margin-top:
    2px;

  color:
    #64748b;

  font-size:
    11px;

}


/* ============================================================
   STATISTICS
============================================================ */

.stats-grid {

  display:
    grid;

  grid-template-columns:
    repeat(
      4,
      minmax(
        0,
        1fr
      )
    );

  gap:
    16px;

  margin-bottom:
    20px;

}


.stat-card {

  min-height:
    110px;

  display:
    flex;

  align-items:
    center;

  gap:
    15px;

  padding:
    18px;

  border:
    1px solid
    rgba(
      226,
      232,
      240,
      .9
    );

  border-radius:
    18px;

  background:
    rgba(
      255,
      255,
      255,
      .92
    );

  box-shadow:
    0 8px 25px
    rgba(
      15,
      23,
      42,
      .05
    );

}


.stat-icon {

  width:
    48px;

  height:
    48px;

  flex-shrink:
    0;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    14px;

  font-size:
    21px;

}


.stat-icon.blue {

  background:
    #dbeafe;

}


.stat-icon.orange {

  background:
    #ffedd5;

}


.stat-icon.green {

  background:
    #dcfce7;

}


.stat-icon.purple {

  background:
    #ede9fe;

}


.stat-card span {

  display:
    block;

  color:
    #64748b;

  font-size:
    12px;

  font-weight:
    600;

}


.stat-card strong {

  display:
    block;

  margin-top:
    5px;

  color:
    #172554;

  font-size:
    21px;

  font-weight:
    850;

}


/* ============================================================
   CONTENT CARD
============================================================ */

.content-card {

  overflow:
    hidden;

  border:
    1px solid
    #e2e8f0;

  border-radius:
    20px;

  background:
    rgba(
      255,
      255,
      255,
      .95
    );

  box-shadow:
    0 10px 35px
    rgba(
      15,
      23,
      42,
      .06
    );

}


.card-header {

  display:
    flex;

  align-items:
    center;

  justify-content:
    space-between;

  gap:
    20px;

  padding:
    22px 24px;

  border-bottom:
    1px solid
    #edf2f7;

}


.card-header h3 {

  margin:
    0;

  color:
    #172554;

  font-size:
    18px;

}


.card-header p {

  margin:
    5px 0 0;

  color:
    #64748b;

  font-size:
    12px;

}


.header-actions {

  display:
    flex;

  gap:
    9px;

}


.download-button,
.refresh-button {

  height:
    42px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  gap:
    7px;

  padding:
    0 15px;

  border:
    0;

  border-radius:
    11px;

  color:
    white;

  cursor:
    pointer;

  font-size:
    12px;

  font-weight:
    750;

}


.download-button {

  background:
    linear-gradient(
      135deg,
      #7c3aed,
      #6366f1
    );

}


.refresh-button {

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #1d4ed8
    );

}


.download-button:disabled,
.refresh-button:disabled {

  opacity:
    .55;

  cursor:
    not-allowed;

}


.spinning {

  display:
    inline-block;

  animation:
    spin .8s
    linear
    infinite;

}


@keyframes spin {

  from {
    transform:
      rotate(0deg);
  }

  to {
    transform:
      rotate(360deg);
  }

}


/* ============================================================
   FILTER
============================================================ */

.filter-area {

  display:
    flex;

  gap:
    10px;

  padding:
    18px 24px;

  border-bottom:
    1px solid
    #edf2f7;

}


.search-box {

  min-width:
    300px;

  flex:
    1;

  height:
    42px;

  display:
    flex;

  align-items:
    center;

  gap:
    8px;

  padding:
    0 13px;

  border:
    1px solid
    #dbe3ef;

  border-radius:
    11px;

  background:
    white;

}


.search-box input {

  width:
    100%;

  border:
    0;

  outline:
    none;

  background:
    transparent;

  color:
    #172554;

  font-size:
    12px;

}


.status-filter {

  min-width:
    155px;

  height:
    42px;

  padding:
    0 12px;

  border:
    1px solid
    #dbe3ef;

  border-radius:
    11px;

  outline:
    none;

  background:
    white;

  color:
    #334155;

  cursor:
    pointer;

  font-size:
    12px;

}


/* ============================================================
   ERROR
============================================================ */

.error-message {

  margin:
    16px 24px;

  padding:
    13px 15px;

  display:
    flex;

  align-items:
    flex-start;

  gap:
    10px;

  border:
    1px solid
    #fecaca;

  border-radius:
    12px;

  background:
    #fef2f2;

  color:
    #991b1b;

}


.error-message strong {

  font-size:
    12px;

}


.error-message p {

  margin:
    3px 0 0;

  font-size:
    11px;

}


.error-message button {

  margin-left:
    auto;

  border:
    0;

  background:
    transparent;

  color:
    #991b1b;

  cursor:
    pointer;

  font-size:
    20px;

}


/* ============================================================
   LOADING
============================================================ */

.loading-box {

  min-height:
    360px;

  display:
    flex;

  flex-direction:
    column;

  align-items:
    center;

  justify-content:
    center;

  color:
    #64748b;

}


.loader {

  width:
    35px;

  height:
    35px;

  border:
    3px solid
    #dbeafe;

  border-top-color:
    #2563eb;

  border-radius:
    50%;

  animation:
    spin .8s
    linear
    infinite;

}


.loading-box p {

  margin:
    12px 0 0;

  font-size:
    12px;

}


/* ============================================================
   TABLE
============================================================ */

.table-wrapper {

  width:
    100%;

  overflow-x:
    auto;

}


table {

  width:
    100%;

  min-width:
    1250px;

  border-collapse:
    collapse;

}


thead th {

  padding:
    14px 13px;

  background:
    #f8fafc;

  border-bottom:
    1px solid
    #e2e8f0;

  color:
    #64748b;

  font-size:
    10px;

  font-weight:
    800;

  letter-spacing:
    .6px;

  text-align:
    left;

  white-space:
    nowrap;

}


tbody td {

  padding:
    15px 13px;

  border-bottom:
    1px solid
    #edf2f7;

  color:
    #334155;

  font-size:
    12px;

  white-space:
    nowrap;

}


tbody tr:hover {

  background:
    #f8fbff;

}


.number {

  width:
    27px;

  height:
    27px;

  display:
    inline-flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    8px;

  background:
    #f1f5f9;

  color:
    #64748b;

  font-size:
    11px;

  font-weight:
    700;

}


/* ============================================================
   TICKET
============================================================ */

.ticket-info {

  display:
    flex;

  align-items:
    center;

  gap:
    9px;

}


.ticket-icon {

  width:
    36px;

  height:
    36px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    10px;

  background:
    #eef2ff;

}


.ticket-info strong,
.ticket-info small {

  display:
    block;

}


.ticket-info strong {

  color:
    #1e293b;

  font-size:
    12px;

}


.ticket-info small {

  max-width:
    130px;

  margin-top:
    3px;

  overflow:
    hidden;

  color:
    #94a3b8;

  font-size:
    9px;

  text-overflow:
    ellipsis;

}


/* ============================================================
   VEHICLE
============================================================ */

.vehicle-badge {

  display:
    inline-flex;

  align-items:
    center;

  gap:
    6px;

  padding:
    7px 10px;

  border-radius:
    9px;

  font-size:
    10px;

  font-weight:
    750;

}


.vehicle-badge.motor {

  background:
    #fff7ed;

  color:
    #c2410c;

}


.vehicle-badge.mobil {

  background:
    #eff6ff;

  color:
    #1d4ed8;

}


/* ============================================================
   MEMBER
============================================================ */

.member-badge {

  display:
    inline-flex;

  align-items:
    center;

  gap:
    5px;

  padding:
    7px 10px;

  border-radius:
    9px;

  background:
    #ecfdf5;

  color:
    #15803d;

  font-size:
    10px;

  font-weight:
    750;

}


.nonmember-badge {

  display:
    inline-flex;

  align-items:
    center;

  gap:
    5px;

  padding:
    7px 10px;

  border-radius:
    9px;

  background:
    #f1f5f9;

  color:
    #475569;

  font-size:
    10px;

  font-weight:
    750;

}


/* ============================================================
   PLAT
============================================================ */

.plat {

  color:
    #172554;

  font-size:
    12px;

  letter-spacing:
    .5px;

}


/* ============================================================
   DATE
============================================================ */

.date-info strong,
.date-info small {

  display:
    block;

}


.date-info strong {

  color:
    #334155;

  font-size:
    11px;

}


.date-info small {

  margin-top:
    3px;

  color:
    #94a3b8;

  font-size:
    10px;

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
    #15803d;

  font-size:
    12px;

}


/* ============================================================
   STATUS
============================================================ */

.status {

  display:
    inline-flex;

  align-items:
    center;

  gap:
    5px;

  padding:
    7px 10px;

  border-radius:
    20px;

  font-size:
    10px;

  font-weight:
    750;

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
    #15803d;

}


/* ============================================================
   DETAIL BUTTON
============================================================ */

.detail-button {

  height:
    34px;

  padding:
    0 11px;

  border:
    1px solid
    #dbe3ef;

  border-radius:
    9px;

  background:
    white;

  color:
    #334155;

  cursor:
    pointer;

  font-size:
    10px;

  font-weight:
    750;

}


.detail-button:hover {

  background:
    #eff6ff;

  border-color:
    #bfdbfe;

  color:
    #1d4ed8;

}


/* ============================================================
   EMPTY
============================================================ */

.empty-cell {

  padding:
    0 !important;

}


.empty-box {

  min-height:
    280px;

  display:
    flex;

  flex-direction:
    column;

  align-items:
    center;

  justify-content:
    center;

}


.empty-icon {

  width:
    65px;

  height:
    65px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    20px;

  background:
    #f1f5f9;

  font-size:
    27px;

}


.empty-box h4 {

  margin:
    14px 0 5px;

  color:
    #334155;

}


.empty-box p {

  margin:
    0;

  color:
    #94a3b8;

  font-size:
    12px;

}


/* ============================================================
   FOOTER
============================================================ */

.table-footer {

  display:
    flex;

  justify-content:
    space-between;

  padding:
    15px 24px;

  border-top:
    1px solid
    #edf2f7;

  color:
    #64748b;

  font-size:
    11px;

}


.table-footer strong {

  color:
    #172554;

}


/* ============================================================
   MODAL
============================================================ */

.modal-overlay {

  position:
    fixed;

  inset:
    0;

  z-index:
    999;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  padding:
    20px;

  background:
    rgba(
      15,
      23,
      42,
      .55
    );

  backdrop-filter:
    blur(5px);

}


.detail-modal {

  position:
    relative;

  width:
    min(
      500px,
      100%
    );

  max-height:
    90vh;

  overflow-y:
    auto;

  padding:
    25px;

  border-radius:
    22px;

  background:
    white;

  box-shadow:
    0 25px 70px
    rgba(
      15,
      23,
      42,
      .25
    );

}


.modal-close {

  position:
    absolute;

  right:
    17px;

  top:
    17px;

  width:
    34px;

  height:
    34px;

  border:
    0;

  border-radius:
    9px;

  background:
    #f1f5f9;

  color:
    #64748b;

  cursor:
    pointer;

  font-size:
    20px;

}


.modal-title {

  display:
    flex;

  align-items:
    center;

  gap:
    12px;

  margin-bottom:
    20px;

}


.modal-icon {

  width:
    47px;

  height:
    47px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    13px;

  background:
    #eef2ff;

  font-size:
    22px;

}


.modal-title h3 {

  margin:
    0;

  color:
    #172554;

}


.modal-title p {

  margin:
    3px 0 0;

  color:
    #64748b;

  font-size:
    11px;

}


/* ============================================================
   DETAIL CODE
============================================================ */

.detail-code {

  padding:
    15px;

  margin-bottom:
    15px;

  border-radius:
    13px;

  background:
    linear-gradient(
      135deg,
      #eff6ff,
      #eef2ff
    );

}


.detail-code span {

  display:
    block;

  color:
    #64748b;

  font-size:
    9px;

  font-weight:
    800;

  letter-spacing:
    1px;

}


.detail-code strong {

  display:
    block;

  margin-top:
    5px;

  color:
    #1d4ed8;

  font-size:
    18px;

}


/* ============================================================
   DETAIL LIST
============================================================ */

.detail-list {

  border:
    1px solid
    #edf2f7;

  border-radius:
    13px;

  overflow:
    hidden;

}


.detail-row {

  display:
    flex;

  align-items:
    center;

  justify-content:
    space-between;

  gap:
    15px;

  min-height:
    48px;

  padding:
    9px 13px;

  border-bottom:
    1px solid
    #edf2f7;

}


.detail-row:last-child {

  border-bottom:
    0;

}


.detail-row > span:first-child {

  color:
    #64748b;

  font-size:
    11px;

}


.detail-row strong {

  color:
    #334155;

  font-size:
    11px;

  text-align:
    right;

}


.detail-row .token {

  max-width:
    260px;

  overflow:
    hidden;

  text-overflow:
    ellipsis;

}


.close-detail-button {

  width:
    100%;

  height:
    44px;

  margin-top:
    17px;

  border:
    0;

  border-radius:
    11px;

  background:
    #172554;

  color:
    white;

  cursor:
    pointer;

  font-weight:
    750;

}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (
  max-width: 1200px
) {

  .stats-grid {

    grid-template-columns:
      repeat(
        2,
        minmax(
          0,
          1fr
        )
      );

  }

}


@media (
  max-width: 900px
) {

  .sidebar {

    width:
      220px;

  }


  .main {

    margin-left:
      220px;

    width:
      calc(
        100% - 220px
      );

    padding:
      20px;

  }


  .filter-area {

    flex-wrap:
      wrap;

  }


  .search-box {

    min-width:
      100%;

  }

}


@media (
  max-width: 650px
) {

  .page {

    display:
      block;

  }


  .sidebar {

    position:
      relative;

    width:
      100%;

    min-height:
      auto;

  }


  .main {

    width:
      100%;

    margin:
      0;

    padding:
      15px;

  }


  .topbar {

    align-items:
      flex-start;

    flex-direction:
      column;

  }


  .stats-grid {

    grid-template-columns:
      1fr;

  }


  .card-header {

    align-items:
      flex-start;

    flex-direction:
      column;

  }


  .header-actions {

    width:
      100%;

  }


  .download-button,
  .refresh-button {

    flex:
      1;

  }


  .filter-area {

    flex-direction:
      column;

  }


  .status-filter {

    width:
      100%;

  }


  .table-footer {

    gap:
      10px;

    flex-direction:
      column;

  }

}

</style>