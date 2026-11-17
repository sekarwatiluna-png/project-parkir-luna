<template>

  <div class="page">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">

      <div>

        <div class="logo">

          <div class="logo-icon">
            🅿️
          </div>

          <div>
            <h2>ParkirKu</h2>
            <span>Petugas</span>
          </div>

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
            to="/petugas/transaksi"
            class="menu-item"
          >
            <span class="menu-icon">🎫</span>
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
            class="menu-item active"
          >
            <span class="menu-icon">🚗</span>
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

      </div>


      <button
        type="button"
        class="logout-btn"
        @click="logout"
      >

        <span>↪</span>
        <span>Keluar</span>

      </button>

    </aside>



    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="main">

      <!-- ================= TOPBAR ================= -->

      <header class="topbar">

        <div>

          <h1>
            Scan Kendaraan Keluar
          </h1>

          <p>
            Masukkan plat nomor dan scan kode tiket kendaraan
          </p>

        </div>


        <div class="petugas-info">

          <div class="avatar">
            👤
          </div>

          <div>

            <strong>
              Petugas
            </strong>

            <small>
              Panel Petugas
            </small>

          </div>

        </div>

      </header>



      <!-- =====================================================
           CONTENT
      ====================================================== -->

      <section class="content">

        <div class="scan-card">


          <!-- =================================================
               HEADER
          ================================================== -->

          <div class="scan-header">

            <div class="scan-icon">
              🚗
            </div>

            <div>

              <h2>
                Proses Kendaraan Keluar
              </h2>

              <p>
                Isi plat nomor lalu scan QR atau masukkan kode tiket.
              </p>

            </div>

          </div>



          <!-- =================================================
               PLAT NOMOR
          ================================================== -->

          <div class="form-group">

            <label for="platNomor">
              Plat Nomor Kendaraan
            </label>


            <div class="input-wrapper">

              <span class="input-icon">
                🚘
              </span>


              <input
                id="platNomor"
                ref="platInput"
                v-model="platNomor"
                type="text"
                placeholder="Contoh: BA1234LU"
                autocomplete="off"
                autocapitalize="characters"
                spellcheck="false"
                @keyup.enter="fokusKode"
              />

            </div>


            <small>
              Untuk member juga tetap isi plat nomor.
              Plat dapat diisi sesuai kendaraan.
            </small>

          </div>



          <!-- =================================================
               KODE TIKET / QR MEMBER
          ================================================== -->

          <div class="form-group">

            <label for="kodeTiket">
              Kode Tiket / QR Member
            </label>


            <div class="input-wrapper">

              <span class="input-icon">
                🎫
              </span>


              <input
                id="kodeTiket"
                ref="kodeInput"
                v-model="kodeTiket"
                type="text"
                placeholder="Scan QR atau masukkan kode tiket"
                autocomplete="off"
                spellcheck="false"
                @keyup.enter="cekTiket"
              />

            </div>


            <small>
              Scan QR member atau masukkan kode tiket kendaraan.
            </small>

          </div>



          <!-- =================================================
               CEK TIKET
          ================================================== -->

          <button
            type="button"
            class="cek-btn"
            :disabled="loading"
            @click="cekTiket"
          >

            <span v-if="!loading">
              🔍
              Cek Tiket
            </span>

            <span v-else>
              ⏳
              Memproses...
            </span>

          </button>



          <!-- =================================================
               ERROR
          ================================================== -->

          <div
            v-if="errorMessage"
            class="message error"
          >

            <span class="message-icon">
              ❌
            </span>


            <div>

              <strong>
                Gagal
              </strong>

              <p>
                {{ errorMessage }}
              </p>

            </div>

          </div>



          <!-- =================================================
               INFO
          ================================================== -->

          <div class="info-box">

            <div class="info-icon">
              ℹ️
            </div>


            <div>

              <strong>
                Petunjuk
              </strong>

              <p>
                Isi plat nomor terlebih dahulu, kemudian scan QR
                member atau masukkan kode tiket. Setelah data
                ditemukan, konfirmasi kendaraan sebelum masuk
                ke proses pembayaran.
              </p>

            </div>

          </div>

        </div>

      </section>

    </main>



    <!-- =====================================================
         MODAL 1
         MEMBER TERDETEKSI / TIKET DITEMUKAN
    ====================================================== -->

    <div
      v-if="showDetection && ticket"
      class="flow-overlay"
    >

      <div class="flow-modal">

        <div class="flow-check">
          ✓
        </div>


        <h2>
          {{
            ticket.member
              ? 'Member Terdeteksi'
              : 'Tiket Ditemukan'
          }}
        </h2>


        <p class="flow-subtitle">
          Data kendaraan berhasil ditemukan.
        </p>



        <!-- MEMBER -->

        <div
          v-if="ticket.member"
          class="detected-card"
        >

          <div class="detected-row">

            <span>
              Nama
            </span>

            <strong>
              {{
                ticket.member.nama_member ||
                'Member'
              }}
            </strong>

          </div>


          <div class="detected-row">

            <span>
              Kode Member
            </span>

            <strong>
              {{
                ticket.member.kode_member ||
                ticket.kode_member ||
                kodeTiket
              }}
            </strong>

          </div>


          <div class="detected-row">

            <span>
              Jenis Kendaraan
            </span>

            <select
              v-model="memberJenisKendaraan"
              class="vehicle-select"
              aria-label="Pilih jenis kendaraan member"
            >
              <option value="Motor">Motor</option>
              <option value="Mobil">Mobil</option>
            </select>

          </div>


          <div class="detected-row">

            <span>
              Nomor Polisi
            </span>

            <strong>
              {{
                ticket.plat_nomor ||
                platNomor.toUpperCase()
              }}
            </strong>

          </div>

        </div>



        <!-- NON MEMBER -->

        <div
          v-else
          class="detected-card"
        >

          <div class="detected-row">

            <span>
              Kode Tiket
            </span>

            <strong>
              {{
                ticket.kode_tiket ||
                kodeTiket
              }}
            </strong>

          </div>


          <div class="detected-row">

            <span>
              Jenis Kendaraan
            </span>

            <strong>
              {{
                ticket.jenis_kendaraan ||
                '-'
              }}
            </strong>

          </div>


          <div class="detected-row">

            <span>
              Nomor Polisi
            </span>

            <strong>
              {{
                ticket.plat_nomor ||
                platNomor.toUpperCase()
              }}
            </strong>

          </div>


          <div class="detected-row">

            <span>
              Total Biaya
            </span>

            <strong class="green-text">
              {{
                formatRupiah(
                  ticket.biaya || 0
                )
              }}
            </strong>

          </div>

        </div>



        <button
          type="button"
          class="green-btn"
          @click="lanjutKePembayaran"
        >
          OK
        </button>

      </div>

    </div>



    <!-- =====================================================
         MODAL 2
         PEMBAYARAN PARKIR
    ====================================================== -->

    <div
      v-if="showPayment && ticket"
      class="flow-overlay"
    >

      <div class="flow-modal payment-modal">

        <div class="rupiah-icon">
          Rp
        </div>


        <h2>
          Pembayaran Parkir
        </h2>


        <p class="flow-subtitle">

          {{
            ticket.member
              ? 'Member'
              : 'Non Member'
          }}

        </p>



        <!-- INFORMASI PEMBAYARAN -->

        <div class="payment-box">

          <div class="payment-row">

            <span>
              Tiket
            </span>

            <strong>

              {{
                ticket.member
                  ? (
                      ticket.member.kode_member ||
                      ticket.kode_member ||
                      kodeTiket
                    )
                  : (
                      ticket.kode_tiket ||
                      kodeTiket
                    )
              }}

            </strong>

          </div>


          <div class="payment-row">

            <span>
              Durasi
            </span>

            <strong>
              {{ hitungDurasi(ticket.waktu_masuk) }}
            </strong>

          </div>


          <div class="payment-row">

            <span>
              Tarif
            </span>

            <strong>

              {{
                ticket.member
                  ? 'Rp 0'
                  : formatRupiah(
                      ticket.biaya || 0
                    )
              }}

            </strong>

          </div>

        </div>



        <!-- TOTAL BAYAR -->

        <div class="total-payment">

          <span>
            Total Bayar
          </span>

          <strong>

            {{
              ticket.member
                ? 'Rp 0'
                : formatRupiah(
                    ticket.biaya || 0
                  )
            }}

          </strong>

        </div>



        <!-- UANG DIBAYAR NON MEMBER -->

        <div
          v-if="!ticket.member"
          class="payment-form"
        >

          <label for="uangDibayar">
            Uang Dibayar
          </label>


          <div class="money-input">

            <span>
              Rp
            </span>


            <input
              id="uangDibayar"
              v-model.number="uangDibayar"
              type="number"
              min="0"
              placeholder="Masukkan uang"
              @input="paymentError = ''"
            />

          </div>

        </div>



        <!-- KEMBALIAN -->

        <div class="change-box">

          <span>
            Kembalian
          </span>


          <strong
            :class="{
              negative:
                kembalianPembayaran < 0
            }"
          >

            {{
              formatRupiah(
                Math.max(
                  kembalianPembayaran,
                  0
                )
              )
            }}

          </strong>

        </div>



        <!-- ERROR PEMBAYARAN -->

        <div
          v-if="paymentError"
          class="payment-error"
        >

          ❌
          {{ paymentError }}

        </div>



        <!-- OK PEMBAYARAN -->

        <button
          type="button"
          class="green-btn"
          :disabled="processing"
          @click="selesaikanKeluar"
        >

          <span v-if="!processing">
            OK
          </span>

          <span v-else>
            ⏳ Memproses...
          </span>

        </button>


        <button
          type="button"
          class="back-btn"
          :disabled="processing"
          @click="kembaliKeDeteksi"
        >
          Kembali
        </button>

      </div>

    </div>



    <!-- =====================================================
         MODAL 3
         PINTU TERBUKA
    ====================================================== -->

    <div
      v-if="showGate && gateData"
      class="flow-overlay"
    >

      <div class="flow-modal gate-modal">

        <div class="flow-check">
          ✓
        </div>


        <h2>
          Pintu Terbuka
        </h2>



        <div class="gate-info">

          <div>

            <span>
              Tipe
            </span>

            <strong>
              {{ gateData.tipe }}
            </strong>

          </div>


          <div>

            <span>
              Kode
            </span>

            <strong>
              {{ gateData.kode }}
            </strong>

          </div>


          <div>

            <span>
              Jenis Kendaraan
            </span>

            <strong>
              {{ gateData.jenis_kendaraan }}
            </strong>

          </div>


          <div>

            <span>
              NOMOR POLISI
            </span>

            <strong>
              {{ gateData.plat_nomor }}
            </strong>

          </div>


          <div>

            <span>
              Kembalian
            </span>

            <strong class="green-text">
              {{
                formatRupiah(
                  gateData.kembalian || 0
                )
              }}
            </strong>

          </div>

        </div>



        <div class="sudah-keluar">
          ✓ SUDAH KELUAR
        </div>



        <button
          type="button"
          class="green-btn"
          @click="lanjutKeInvoice"
        >
          OK
        </button>

      </div>

    </div>



    <!-- =====================================================
         MODAL 4
         INVOICE
    ====================================================== -->

    <div
      v-if="showInvoice"
      class="invoice-overlay"
      @click.self="tutupInvoice"
    >

      <div class="invoice-modal">


        <!-- HEADER -->

        <div class="invoice-header">

          <div>

            <span class="invoice-badge">

              {{
                invoiceData?.isMember
                  ? 'INVOICE MEMBER'
                  : 'INVOICE PARKIR'
              }}

            </span>


            <h2>
              Invoice Kendaraan Keluar
            </h2>


            <p>
              ParkirKu - Parkir Plaza Andalas
            </p>

          </div>


          <button
            type="button"
            class="invoice-close"
            @click="tutupInvoice"
          >
            ×
          </button>

        </div>



        <!-- STATUS -->

        <div class="invoice-status">

          <div class="invoice-check">
            ✓
          </div>


          <div>

            <strong>
              Kendaraan Berhasil Keluar
            </strong>


            <span v-if="invoiceData?.isMember">

              Member aktif -
              parkir sudah termasuk masa berlangganan.

            </span>


            <span v-else>

              Transaksi parkir berhasil diselesaikan.

            </span>

          </div>

        </div>



        <!-- NOMOR INVOICE -->

        <div class="invoice-number">

          <span>
            No. Invoice
          </span>

          <strong>
            {{
              invoiceData?.invoiceNumber ||
              '-'
            }}
          </strong>

        </div>



        <!-- DATA KENDARAAN -->

        <div class="invoice-section">

          <h3>
            Data Kendaraan
          </h3>


          <div class="invoice-grid">

            <div>

              <span>
                Plat Nomor
              </span>

              <strong>
                {{
                  invoiceData?.plat_nomor ||
                  '-'
                }}
              </strong>

            </div>


            <div>

              <span>

                {{
                  invoiceData?.isMember
                    ? 'Kode Member'
                    : 'Kode Tiket'
                }}

              </span>

              <strong>
                {{
                  invoiceData?.kode ||
                  '-'
                }}
              </strong>

            </div>


            <div>

              <span>
                Jenis Kendaraan
              </span>

              <strong>
                {{
                  invoiceData?.jenis_kendaraan ||
                  '-'
                }}
              </strong>

            </div>


            <div>

              <span>
                Status
              </span>

              <strong>

                {{
                  invoiceData?.isMember
                    ? 'Member'
                    : 'Non Member'
                }}

              </strong>

            </div>


            <div>

              <span>
                Waktu Masuk
              </span>

              <strong>
                {{
                  formatTanggal(
                    invoiceData?.waktu_masuk
                  )
                }}
              </strong>

            </div>


            <div>

              <span>
                Waktu Keluar
              </span>

              <strong>
                {{
                  formatTanggal(
                    invoiceData?.waktu_keluar
                  )
                }}
              </strong>

            </div>

          </div>

        </div>



        <!-- TOTAL -->

        <div class="invoice-total">

          <div>

            <span>

              {{
                invoiceData?.isMember
                  ? 'Biaya Parkir'
                  : 'Total Pembayaran'
              }}

            </span>


            <small
              v-if="invoiceData?.isMember"
            >
              Sudah termasuk dalam biaya member
            </small>

          </div>


          <strong>

            {{
              formatRupiah(
                invoiceData?.biaya || 0
              )
            }}

          </strong>

        </div>



        <!-- PEMBAYARAN -->

        <div
          v-if="
            invoiceData &&
            !invoiceData.isMember
          "
          class="invoice-payment-detail"
        >

          <div>

            <span>
              Pembayaran
            </span>

            <strong>
              {{
                formatRupiah(
                  invoiceData?.pembayaran ||
                  0
                )
              }}
            </strong>

          </div>


          <div>

            <span>
              Kembalian
            </span>

            <strong>
              {{
                formatRupiah(
                  invoiceData?.kembalian ||
                  0
                )
              }}
            </strong>

          </div>

        </div>



        <!-- BUTTON -->

        <div class="invoice-actions">

          <button
            type="button"
            class="invoice-print"
            @click="cetakInvoice"
          >
            🖨️ Cetak Invoice
          </button>


          <button
            type="button"
            class="invoice-done"
            @click="tutupInvoice"
          >
            Selesai
          </button>

        </div>

      </div>

    </div>

  </div>

</template>



<script setup>

const config =
  useRuntimeConfig()


const apiBase =
  config.public?.apiBase ||
  'http://127.0.0.1:8000/api'



// =====================================================
// FORM
// =====================================================

const platNomor =
  ref('')

const kodeTiket =
  ref('')


const platInput =
  ref(null)

const kodeInput =
  ref(null)



// =====================================================
// STATE
// =====================================================

const loading =
  ref(false)

const processing =
  ref(false)

const errorMessage =
  ref('')

const ticket =
  ref(null)


// =====================================================
// JENIS KENDARAAN MEMBER
// =====================================================

const memberJenisKendaraan =
  ref('Mobil')



// =====================================================
// MODAL STATE
// =====================================================

const showDetection =
  ref(false)

const showPayment =
  ref(false)

const showGate =
  ref(false)

const showInvoice =
  ref(false)



// =====================================================
// PEMBAYARAN
// =====================================================

const uangDibayar =
  ref(0)

const paymentError =
  ref('')



// =====================================================
// GATE
// =====================================================

const gateData =
  ref(null)



// =====================================================
// INVOICE
// =====================================================

const invoiceData =
  ref(null)



// =====================================================
// FOTO MEMBER
// =====================================================

const fotoMember =
  computed(() => {

    const member =
      ticket.value?.member


    if (!member) {
      return ''
    }


    const foto =
      member.foto ||
      member.foto_member ||
      member.photo ||
      member.photo_member ||
      member.gambar ||
      ''


    if (!foto) {
      return ''
    }


    if (
      foto.startsWith('http://') ||
      foto.startsWith('https://') ||
      foto.startsWith('data:image')
    ) {

      return foto

    }


    return `http://127.0.0.1:8000/storage/${foto
      .replace(/^\/+/, '')
      .replace(/^storage\/+/, '')}`

  })



// =====================================================
// FOKUS KODE
// =====================================================

const fokusKode =
  () => {

    nextTick(() => {

      kodeInput.value?.focus()

    })

  }



// =====================================================
// NORMALISASI JENIS KENDARAAN MEMBER
// =====================================================

const getMemberJenisKendaraan =
  (value) => {

    const jenis =
      String(value || '')
        .trim()
        .toLowerCase()

    if (jenis.includes('motor')) {
      return 'Motor'
    }

    if (jenis.includes('mobil') || jenis.includes('car')) {
      return 'Mobil'
    }

    return 'Mobil'
  }


// =====================================================
// CEK TIKET
// =====================================================

const cekTiket =
  async () => {

    errorMessage.value =
      ''

    ticket.value =
      null

    paymentError.value =
      ''

    showDetection.value =
      false

    showPayment.value =
      false

    showGate.value =
      false

    showInvoice.value =
      false


    const plat =
      platNomor.value.trim()

    const kode =
      kodeTiket.value.trim()


    // ================================================
    // PLAT WAJIB
    // ================================================

    if (!plat) {

      errorMessage.value =
        'Plat nomor wajib diisi.'


      nextTick(() => {

        platInput.value?.focus()

      })

      return

    }


    // ================================================
    // KODE WAJIB
    // ================================================

    if (!kode) {

      errorMessage.value =
        'Silakan scan QR atau masukkan kode tiket.'


      nextTick(() => {

        kodeInput.value?.focus()

      })

      return

    }


    loading.value =
      true


    try {

      const response =
        await $fetch(
          `${apiBase}/scan/keluar`,
          {

            method: 'POST',

            headers: {

              Accept:
                'application/json',

              'Content-Type':
                'application/json'

            },

            body: {

              plat_nomor:
                plat.toUpperCase(),

              kode_tiket:
                kode

            },

            timeout:
              15000

          }
        )


      if (
        response?.success === true
      ) {

        // ==========================================
        // SIMPAN DATA TIKET
        // ==========================================

        ticket.value =
          response.data


        if (ticket.value?.member) {

          memberJenisKendaraan.value =
            getMemberJenisKendaraan(
              ticket.value?.jenis_kendaraan
            )

          ticket.value.jenis_kendaraan =
            memberJenisKendaraan.value

        }


        errorMessage.value =
          ''


        // ==========================================
        // PENTING:
        // BELUM PROSES KELUAR
        // HANYA TAMPILKAN MODAL TERDETEKSI
        // ==========================================

        showDetection.value =
          true

        showPayment.value =
          false

        showGate.value =
          false

        showInvoice.value =
          false


        // ==========================================
        // SET UANG AWAL
        // ==========================================

        uangDibayar.value =
          ticket.value?.member
            ? 0
            : Number(
                ticket.value?.biaya ||
                0
              )

      } else {

        errorMessage.value =
          response?.message ||
          'Tiket kendaraan tidak ditemukan.'

      }


    } catch (error) {

      console.error(
        'ERROR SCAN KELUAR:',
        error
      )


      errorMessage.value =
        error?.data?.message ||
        error?.message ||
        'Tiket kendaraan tidak ditemukan.'

    } finally {

      loading.value =
        false

    }

  }



// =====================================================
// LANJUT KE PEMBAYARAN
// =====================================================

const lanjutKePembayaran =
  () => {

    if (!ticket.value) {
      return
    }


    if (ticket.value?.member) {

      ticket.value.jenis_kendaraan =
        memberJenisKendaraan.value

    }


    paymentError.value =
      ''


    showDetection.value =
      false

    showPayment.value =
      true

    showGate.value =
      false

    showInvoice.value =
      false


    uangDibayar.value =
      ticket.value?.member
        ? 0
        : Number(
            ticket.value?.biaya ||
            0
          )

  }



// =====================================================
// KEMBALI KE DETEKSI
// =====================================================

const kembaliKeDeteksi =
  () => {

    paymentError.value =
      ''

    showPayment.value =
      false

    showDetection.value =
      true

  }



// =====================================================
// DURASI
// =====================================================

const hitungDurasi =
  (waktuMasuk) => {

    if (!waktuMasuk) {
      return '0 Jam'
    }


    const masuk =
      new Date(
        String(
          waktuMasuk
        ).replace(
          ' ',
          'T'
        )
      )


    if (
      isNaN(
        masuk.getTime()
      )
    ) {

      return '0 Jam'

    }


    const sekarang =
      new Date()


    const selisih =
      Math.max(
        0,
        sekarang.getTime() -
        masuk.getTime()
      )


    const jam =
      Math.max(
        1,
        Math.ceil(
          selisih /
          (1000 * 60 * 60)
        )
      )


    return `${jam} Jam`

  }



// =====================================================
// KEMBALIAN
// =====================================================

const kembalianPembayaran =
  computed(() => {

    if (
      ticket.value?.member
    ) {

      return 0

    }


    return (
      Number(
        uangDibayar.value ||
        0
      ) -
      Number(
        ticket.value?.biaya ||
        0
      )
    )

  })



// =====================================================
// SELESAIKAN KELUAR
// =====================================================

const selesaikanKeluar =
  async () => {

    if (!ticket.value) {
      return
    }


    if (processing.value) {
      return
    }


    paymentError.value =
      ''


    const plat =
      platNomor.value.trim()

    const kode =
      kodeTiket.value.trim()


    if (!plat) {

      paymentError.value =
        'Plat nomor wajib diisi.'

      return

    }


    if (!kode) {

      paymentError.value =
        'Kode tiket/member tidak ditemukan.'

      return

    }


    const dataSebelumReset =
      ticket.value


    const isMember =
      !!dataSebelumReset?.member


    const biaya =
      isMember
        ? 0
        : Number(
            dataSebelumReset?.biaya ||
            0
          )


    const pembayaran =
      isMember
        ? 0
        : Number(
            uangDibayar.value ||
            0
          )


    // ================================================
    // CEK UANG
    // ================================================

    if (
      !isMember &&
      pembayaran < biaya
    ) {

      paymentError.value =
        `Pembayaran kurang ${formatRupiah(
          biaya -
          pembayaran
        )}.`

      return

    }


    processing.value =
      true


    try {

      // ==============================================
      // PROSES KE BACKEND
      // ==============================================

      const response =
        await $fetch(
          `${apiBase}/scan/keluar/selesaikan`,
          {

            method: 'POST',

            headers: {

              Accept:
                'application/json',

              'Content-Type':
                'application/json'

            },

            body: {

              plat_nomor:
                plat.toUpperCase(),

              kode_tiket:
                kode

            },

            timeout:
              15000

          }
        )


      if (
        response?.success === true
      ) {

        // ==========================================
        // BIAYA FINAL
        // ==========================================

        const biayaFinal =
          isMember
            ? 0
            : Number(
                response?.data?.biaya ??
                response?.biaya ??
                dataSebelumReset?.biaya ??
                0
              )


        // ==========================================
        // KEMBALIAN FINAL
        // ==========================================

        const kembalianFinal =
          isMember
            ? 0
            : Math.max(
                pembayaran -
                biayaFinal,
                0
              )


        // ==========================================
        // DATA INVOICE
        // ==========================================

        invoiceData.value = {

          invoiceNumber:
            `INV-${Date.now()}`,

          isMember:
            isMember,

          plat_nomor:
            dataSebelumReset?.plat_nomor ||
            plat.toUpperCase(),

          kode:
            isMember
              ? (
                  dataSebelumReset
                    ?.member
                    ?.kode_member ||

                  dataSebelumReset
                    ?.kode_member ||

                  kode
                )
              : (
                  dataSebelumReset
                    ?.kode_tiket ||

                  kode
                ),

          jenis_kendaraan:
            isMember
              ? memberJenisKendaraan.value
              : (
                  dataSebelumReset
                    ?.jenis_kendaraan ||
                  '-'
                ),

          waktu_masuk:
            dataSebelumReset
              ?.waktu_masuk ||

            dataSebelumReset
              ?.member
              ?.tanggal_mulai ||

            null,

          waktu_keluar:
            response
              ?.data
              ?.waktu_keluar ||

            response
              ?.waktu_keluar ||

            new Date().toISOString(),

          biaya:
            biayaFinal,

          pembayaran:
            pembayaran,

          kembalian:
            kembalianFinal

        }



        // ==========================================
        // DATA PINTU
        // ==========================================

        gateData.value = {

          tipe:
            isMember
              ? 'Member'
              : 'Non Member',

          kode:
            isMember
              ? (
                  dataSebelumReset
                    ?.member
                    ?.kode_member ||

                  dataSebelumReset
                    ?.kode_member ||

                  kode
                )
              : (
                  dataSebelumReset
                    ?.kode_tiket ||

                  kode
                ),

          jenis_kendaraan:
            isMember
              ? memberJenisKendaraan.value
              : (
                  dataSebelumReset
                    ?.jenis_kendaraan ||
                  '-'
                ),

          plat_nomor:
            dataSebelumReset
              ?.plat_nomor ||
            plat.toUpperCase(),

          kembalian:
            kembalianFinal

        }



        // ==========================================
        // PINDAH KE PINTU TERBUKA
        // ==========================================

        showDetection.value =
          false

        showPayment.value =
          false

        showGate.value =
          true

        showInvoice.value =
          false


      } else {

        paymentError.value =
          response?.message ||
          'Kendaraan gagal diproses.'

      }


    } catch (error) {

      console.error(
        'ERROR SELESAIKAN KELUAR:',
        error
      )


      paymentError.value =
        error?.data?.message ||
        error?.message ||
        'Kendaraan gagal diproses.'

    } finally {

      processing.value =
        false

    }

  }



// =====================================================
// PINTU TERBUKA -> INVOICE
// =====================================================

const lanjutKeInvoice =
  () => {

    showGate.value =
      false

    showInvoice.value =
      true

  }



// =====================================================
// TUTUP INVOICE
// =====================================================

const tutupInvoice =
  () => {

    showInvoice.value =
      false

    showDetection.value =
      false

    showPayment.value =
      false

    showGate.value =
      false


    invoiceData.value =
      null

    gateData.value =
      null

    ticket.value =
      null


    platNomor.value =
      ''

    kodeTiket.value =
      ''

    uangDibayar.value =
      0

    memberJenisKendaraan.value =
      'Mobil'

    paymentError.value =
      ''

    errorMessage.value =
      ''


    nextTick(() => {

      platInput.value?.focus()

    })

  }



// =====================================================
// CETAK INVOICE
// =====================================================

const cetakInvoice =
  () => {

    if (!invoiceData.value) {
      return
    }


    const data =
      invoiceData.value


    const popup =
      window.open(
        '',
        '_blank',
        'width=800,height=900'
      )


    if (!popup) {

      alert(
        'Popup diblokir browser. Izinkan popup untuk mencetak invoice.'
      )

      return

    }


    const biaya =
      formatRupiah(
        data.biaya || 0
      )


    const waktuMasuk =
      formatTanggal(
        data.waktu_masuk
      )


    const waktuKeluar =
      formatTanggal(
        data.waktu_keluar
      )


    popup.document.write(`

      <!DOCTYPE html>

      <html lang="id">

      <head>

        <meta charset="UTF-8">

        <title>
          ${data.invoiceNumber}
        </title>


        <style>

          * {
            box-sizing: border-box;
          }


          body {

            margin: 0;

            padding: 30px;

            font-family:
              Arial,
              Helvetica,
              sans-serif;

            background: #ffffff;

            color: #111827;

          }


          .invoice {

            width: 100%;

            max-width: 700px;

            margin: 0 auto;

            border:
              1px solid #dbe3ef;

            border-radius: 16px;

            padding: 30px;

          }


          .header {

            display: flex;

            justify-content:
              space-between;

            align-items:
              flex-start;

            border-bottom:
              2px solid #2563eb;

            padding-bottom: 20px;

            margin-bottom: 20px;

          }


          .brand {

            font-size: 25px;

            font-weight: 800;

            color: #2563eb;

          }


          .subtitle {

            margin-top: 5px;

            color: #6b7280;

            font-size: 13px;

          }


          .invoice-title {

            text-align: right;

          }


          .invoice-title h2 {

            margin: 0;

            font-size: 22px;

          }


          .invoice-title p {

            margin: 6px 0 0;

            color: #6b7280;

            font-size: 13px;

          }


          .success {

            background: #ecfdf5;

            border:
              1px solid #a7f3d0;

            color: #047857;

            padding: 14px 16px;

            border-radius: 10px;

            margin-bottom: 20px;

            font-weight: 700;

          }


          .section-title {

            font-size: 15px;

            font-weight: 800;

            margin:
              22px 0 12px;

          }


          table {

            width: 100%;

            border-collapse:
              collapse;

          }


          td {

            padding: 10px 0;

            border-bottom:
              1px solid #eef2f7;

            font-size: 14px;

          }


          td:first-child {

            color: #6b7280;

            width: 45%;

          }


          td:last-child {

            text-align: right;

            font-weight: 700;

          }


          .total {

            display: flex;

            justify-content:
              space-between;

            align-items:
              center;

            margin-top: 20px;

            padding: 18px;

            background: #f8fafc;

            border-radius: 12px;

          }


          .total span {

            color: #6b7280;

          }


          .total strong {

            font-size: 22px;

            color: #2563eb;

          }


          .payment-detail {

            margin-top: 10px;

            padding: 15px;

            background: #f8fafc;

            border-radius: 12px;

          }


          .payment-detail div {

            display: flex;

            justify-content:
              space-between;

            padding: 7px 0;

          }


          .payment-detail span {

            color: #6b7280;

          }


          .payment-detail strong {

            color: #111827;

          }


          .footer {

            text-align: center;

            margin-top: 30px;

            padding-top: 20px;

            border-top:
              1px dashed #cbd5e1;

            color: #6b7280;

            font-size: 12px;

          }


          @media print {

            body {
              padding: 0;
            }

            .invoice {

              border: none;

              max-width: none;

            }

          }

        </style>

      </head>


      <body>

        <div class="invoice">


          <div class="header">

            <div>

              <div class="brand">
                ParkirKu
              </div>

              <div class="subtitle">
                Parkir Plaza Andalas
              </div>

            </div>


            <div class="invoice-title">

              <h2>

                ${
                  data.isMember
                    ? 'INVOICE MEMBER'
                    : 'INVOICE PARKIR'
                }

              </h2>

              <p>
                ${data.invoiceNumber}
              </p>

            </div>

          </div>



          <div class="success">

            ✓
            Kendaraan berhasil diproses keluar

          </div>



          <div class="section-title">
            Data Kendaraan
          </div>



          <table>

            <tr>

              <td>
                Plat Nomor
              </td>

              <td>
                ${data.plat_nomor || '-'}
              </td>

            </tr>


            <tr>

              <td>

                ${
                  data.isMember
                    ? 'Kode Member'
                    : 'Kode Tiket'
                }

              </td>

              <td>
                ${data.kode || '-'}
              </td>

            </tr>


            <tr>

              <td>
                Jenis Kendaraan
              </td>

              <td>
                ${data.jenis_kendaraan || '-'}
              </td>

            </tr>


            <tr>

              <td>
                Status
              </td>

              <td>

                ${
                  data.isMember
                    ? 'Member'
                    : 'Non Member'
                }

              </td>

            </tr>


            <tr>

              <td>
                Waktu Masuk
              </td>

              <td>
                ${waktuMasuk}
              </td>

            </tr>


            <tr>

              <td>
                Waktu Keluar
              </td>

              <td>
                ${waktuKeluar}
              </td>

            </tr>

          </table>



          <div class="total">

            <div>

              <span>

                ${
                  data.isMember
                    ? 'Biaya Parkir'
                    : 'Total Pembayaran'
                }

              </span>


              ${
                data.isMember
                  ? `
                    <div
                      style="
                        margin-top:5px;
                        font-size:11px;
                        color:#6b7280;
                      "
                    >
                      Termasuk dalam biaya member
                    </div>
                  `
                  : ''
              }

            </div>


            <strong>
              ${biaya}
            </strong>

          </div>



          ${
            !data.isMember
              ? `

                <div class="payment-detail">

                  <div>

                    <span>
                      Pembayaran
                    </span>

                    <strong>
                      ${formatRupiah(
                        data.pembayaran || 0
                      )}
                    </strong>

                  </div>


                  <div>

                    <span>
                      Kembalian
                    </span>

                    <strong>
                      ${formatRupiah(
                        data.kembalian || 0
                      )}
                    </strong>

                  </div>

                </div>

              `
              : ''
          }



          <div class="footer">

            Terima kasih telah menggunakan
            layanan ParkirKu - Plaza Andalas.

          </div>


        </div>


        <script>

          window.onload = function () {

            window.print();

          };

        <\/script>

      </body>

      </html>

    `)


    popup.document.close()

  }



// =====================================================
// FORMAT RUPIAH
// =====================================================

const formatRupiah =
  (value) => {

    const angka =
      Number(
        value || 0
      )


    return new Intl.NumberFormat(
      'id-ID',
      {

        style: 'currency',

        currency: 'IDR',

        minimumFractionDigits: 0

      }
    ).format(angka)

  }



// =====================================================
// FORMAT TANGGAL
// =====================================================

const formatTanggal =
  (tanggal) => {

    if (!tanggal) {
      return '-'
    }


    const date =
      new Date(
        String(
          tanggal
        ).replace(
          ' ',
          'T'
        )
      )


    if (
      isNaN(
        date.getTime()
      )
    ) {

      return tanggal

    }


    return date.toLocaleString(
      'id-ID',
      {

        day: '2-digit',

        month: '2-digit',

        year: 'numeric',

        hour: '2-digit',

        minute: '2-digit'

      }
    )

  }



// =====================================================
// LOGOUT
// =====================================================

const logout =
  () => {

    localStorage.removeItem(
      'token'
    )

    localStorage.removeItem(
      'user'
    )


    navigateTo(
      '/login'
    )

  }

</script>



<style scoped>

* {
  box-sizing: border-box;
}


.page {

  min-height: 100vh;

  display: flex;

  background:
    linear-gradient(
      135deg,
      #f4f7ff 0%,
      #eef2ff 45%,
      #f8faff 100%
    );

  color: #172033;

}


/* =====================================================
   SIDEBAR
===================================================== */

.sidebar {

  width: 250px;

  min-height: 100vh;

  padding: 24px 16px;

  display: flex;

  flex-direction: column;

  justify-content:
    space-between;

  background:
    linear-gradient(
      180deg,
      #111827 0%,
      #1e1b4b 55%,
      #312e81 100%
    );

  color: white;

  position: fixed;

  left: 0;

  top: 0;

  bottom: 0;

  z-index: 100;

}


.logo {

  display: flex;

  align-items: center;

  gap: 12px;

  padding:
    4px 8px 28px;

}


.logo-icon {

  width: 45px;

  height: 45px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #6366f1,
      #8b5cf6
    );

  font-size: 23px;

}


.logo h2 {

  margin: 0;

  font-size: 20px;

}


.logo span {

  display: block;

  margin-top: 3px;

  color: #c7d2fe;

  font-size: 12px;

}


.menu {

  display: flex;

  flex-direction: column;

  gap: 7px;

}


.menu-item {

  display: flex;

  align-items: center;

  gap: 13px;

  padding:
    13px 14px;

  color: #cbd5e1;

  text-decoration: none;

  border-radius: 12px;

  transition: .2s;

}


.menu-item:hover {

  background:
    rgba(255,255,255,.10);

  color: white;

}


.menu-item.active {

  background:
    linear-gradient(
      135deg,
      #6366f1,
      #7c3aed
    );

  color: white;

  box-shadow:
    0 8px 20px
    rgba(79,70,229,.28);

}


.menu-icon {

  width: 22px;

  text-align: center;

  flex-shrink: 0;

}


.logout-btn {

  width: 100%;

  border: 0;

  padding:
    13px 14px;

  display: flex;

  align-items: center;

  gap: 12px;

  border-radius: 12px;

  background:
    rgba(255,255,255,.08);

  color: #e2e8f0;

  cursor: pointer;

  font-size: 14px;

}


.logout-btn:hover {

  background:
    rgba(239,68,68,.18);

  color: #fecaca;

}



/* =====================================================
   MAIN
===================================================== */

.main {

  margin-left: 250px;

  width:
    calc(100% - 250px);

  min-height: 100vh;

}



/* =====================================================
   TOPBAR
===================================================== */

.topbar {

  height: 88px;

  padding:
    18px 36px;

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  background:
    rgba(255,255,255,.75);

  border-bottom:
    1px solid #e5e7eb;

  backdrop-filter:
    blur(12px);

}


.topbar h1 {

  margin: 0;

  font-size: 24px;

  font-weight: 800;

}


.topbar p {

  margin: 5px 0 0;

  color: #64748b;

  font-size: 13px;

}


.petugas-info {

  display: flex;

  align-items: center;

  gap: 10px;

}


.avatar {

  width: 42px;

  height: 42px;

  border-radius: 50%;

  display: flex;

  align-items: center;

  justify-content: center;

  background: #e0e7ff;

  font-size: 19px;

}


.petugas-info strong {

  display: block;

  font-size: 14px;

}


.petugas-info small {

  color: #64748b;

  font-size: 11px;

}



/* =====================================================
   CONTENT
===================================================== */

.content {

  padding: 40px;

  display: flex;

  justify-content:
    center;

}


.scan-card {

  width: 100%;

  max-width: 760px;

  padding: 30px;

  background:
    rgba(255,255,255,.90);

  border:
    1px solid #e5e7eb;

  border-radius: 24px;

  box-shadow:
    0 20px 50px
    rgba(30,41,59,.10);

}



/* =====================================================
   HEADER SCAN
===================================================== */

.scan-header {

  display: flex;

  align-items: center;

  gap: 16px;

  margin-bottom: 30px;

}


.scan-icon {

  width: 58px;

  height: 58px;

  border-radius: 17px;

  display: flex;

  align-items: center;

  justify-content: center;

  background:
    linear-gradient(
      135deg,
      #6366f1,
      #8b5cf6
    );

  color: white;

  font-size: 27px;

  box-shadow:
    0 10px 25px
    rgba(99,102,241,.25);

}


.scan-header h2 {

  margin: 0;

  font-size: 21px;

}


.scan-header p {

  margin: 5px 0 0;

  color: #64748b;

  font-size: 13px;

}



/* =====================================================
   FORM
===================================================== */

.form-group {

  margin-bottom: 22px;

}


.form-group label {

  display: block;

  margin-bottom: 9px;

  font-size: 14px;

  font-weight: 700;

  color: #334155;

}


.form-group small {

  display: block;

  margin-top: 7px;

  color: #94a3b8;

  font-size: 11px;

}


.input-wrapper {

  position: relative;

  width: 100%;

}


.input-icon {

  position: absolute;

  left: 16px;

  top: 50%;

  transform:
    translateY(-50%);

  font-size: 18px;

  pointer-events: none;

  z-index: 2;

}


.input-wrapper input {

  width: 100%;

  height: 54px;

  padding:
    0
    16px
    0
    50px;

  border:
    1.5px solid #dbe2ea;

  border-radius: 14px;

  background: white;

  color: #111827;

  font-size: 15px;

  outline: none;

  cursor: text;

  position: relative;

  z-index: 1;

  transition: .2s;

}


.input-wrapper input::placeholder {

  color: #94a3b8;

}


.input-wrapper input:hover {

  border-color: #a5b4fc;

}


.input-wrapper input:focus {

  border-color: #6366f1;

  box-shadow:
    0 0 0 4px
    rgba(99,102,241,.10);

}



/* =====================================================
   CEK
===================================================== */

.cek-btn {

  width: 100%;

  min-height: 54px;

  border: 0;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #4f46e5,
      #7c3aed
    );

  color: white;

  font-size: 15px;

  font-weight: 800;

  cursor: pointer;

  transition: .2s;

  box-shadow:
    0 10px 25px
    rgba(79,70,229,.22);

}


.cek-btn:hover:not(:disabled) {

  transform:
    translateY(-1px);

}


.cek-btn:disabled {

  opacity: .65;

  cursor: not-allowed;

}



/* =====================================================
   ERROR
===================================================== */

.message {

  margin-top: 20px;

  padding: 15px;

  display: flex;

  align-items:
    flex-start;

  gap: 12px;

  border-radius: 14px;

}


.message.error {

  background: #fef2f2;

  border:
    1px solid #fecaca;

  color: #991b1b;

}


.message-icon {

  font-size: 18px;

}


.message strong {

  display: block;

  margin-bottom: 3px;

}


.message p {

  margin: 0;

  font-size: 13px;

}



/* =====================================================
   INFO
===================================================== */

.info-box {

  margin-top: 25px;

  display: flex;

  gap: 13px;

  padding: 16px;

  border-radius: 15px;

  background: #eff6ff;

  border:
    1px solid #bfdbfe;

}


.info-icon {

  flex-shrink: 0;

  font-size: 18px;

}


.info-box strong {

  display: block;

  margin-bottom: 5px;

  color: #1e40af;

}


.info-box p {

  margin: 0;

  color: #475569;

  font-size: 12px;

  line-height: 1.6;

}



/* =====================================================
   MODAL OVERLAY
===================================================== */

.flow-overlay {

  position: fixed;

  inset: 0;

  z-index: 10000;

  display: flex;

  align-items: center;

  justify-content: center;

  padding: 20px;

  background:
    rgba(15,23,42,.68);

  backdrop-filter:
    blur(8px);

  overflow-y: auto;

}


.flow-modal {

  width: 100%;

  max-width: 500px;

  padding: 30px;

  background: white;

  border-radius: 25px;

  box-shadow:
    0 30px 90px
    rgba(15,23,42,.30);

  text-align: center;

  animation:
    modalIn
    .22s
    ease;

}


@keyframes modalIn {

  from {

    opacity: 0;

    transform:
      translateY(15px)
      scale(.97);

  }

  to {

    opacity: 1;

    transform:
      translateY(0)
      scale(1);

  }

}


.flow-check {

  width: 64px;

  height: 64px;

  margin:
    0 auto 16px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 50%;

  background: #ecfdf5;

  color: #16a34a;

  border:
    1px solid #bbf7d0;

  font-size: 34px;

  font-weight: 900;

}


.flow-modal h2 {

  margin: 0;

  color: #1f2937;

  font-size: 25px;

  font-weight: 800;

}


.flow-subtitle {

  margin:
    8px 0 20px;

  color: #64748b;

  font-size: 13px;

}



/* =====================================================
   DETECTED
===================================================== */

.detected-card {

  display: grid;

  gap: 9px;

  margin-bottom: 20px;

  text-align: left;

}


.detected-row {

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  gap: 15px;

  padding:
    13px 15px;

  border-radius: 12px;

  background: #f8fafc;

  border:
    1px solid #e2e8f0;

}


.detected-row span {

  color: #64748b;

  font-size: 12px;

}


.detected-row strong {

  color: #1f2937;

  font-size: 13px;

  text-align: right;

}


.vehicle-select {

  min-width: 150px;

  padding: 9px 34px 9px 12px;

  border: 1px solid #cbd5e1;

  border-radius: 10px;

  background: #ffffff;

  color: #1f2937;

  font-size: 13px;

  font-weight: 700;

  text-align: left;

  cursor: pointer;

  outline: none;

}

.vehicle-select:focus {

  border-color: #6366f1;

  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);

}


.green-text {

  color: #16a34a !important;

}



/* =====================================================
   GREEN BUTTON
===================================================== */

.green-btn {

  width: 100%;

  min-height: 52px;

  border: 0;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #16a34a,
      #22c55e
    );

  color: white;

  font-size: 15px;

  font-weight: 800;

  cursor: pointer;

  box-shadow:
    0 10px 24px
    rgba(34,197,94,.20);

  transition: .2s;

}


.green-btn:hover:not(:disabled) {

  transform:
    translateY(-1px);

}


.green-btn:disabled {

  opacity: .65;

  cursor: not-allowed;

}



/* =====================================================
   PAYMENT
===================================================== */

.payment-modal {

  max-width: 500px;

}


.rupiah-icon {

  color: #64748b;

  font-size: 28px;

  font-weight: 900;

}


.payment-box {

  display: grid;

  margin-top: 20px;

  text-align: left;

  border:
    1px solid #e2e8f0;

  border-radius: 14px;

  overflow: hidden;

}


.payment-row {

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  gap: 15px;

  padding:
    13px 15px;

  border-bottom:
    1px solid #eef2f7;

}


.payment-row:last-child {

  border-bottom: 0;

}


.payment-row span {

  color: #64748b;

  font-size: 12px;

}


.payment-row strong {

  color: #1f2937;

  font-size: 13px;

  text-align: right;

}


.total-payment {

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  gap: 15px;

  margin-top: 14px;

  padding: 16px;

  border-radius: 14px;

  background: #f8fafc;

  border:
    1px solid #e2e8f0;

}


.total-payment span {

  color: #475569;

  font-size: 13px;

  font-weight: 700;

}


.total-payment strong {

  color: #16a34a;

  font-size: 20px;

}


.payment-form {

  margin-top: 15px;

  text-align: left;

}


.payment-form label {

  display: block;

  margin-bottom: 7px;

  color: #475569;

  font-size: 12px;

  font-weight: 700;

}


.money-input {

  display: flex;

  align-items: center;

  gap: 8px;

  min-height: 52px;

  padding:
    0 14px;

  border:
    1px solid #cbd5e1;

  border-radius: 13px;

  background: white;

}


.money-input:focus-within {

  border-color: #22c55e;

  box-shadow:
    0 0 0 3px
    rgba(34,197,94,.10);

}


.money-input span {

  color: #64748b;

  font-size: 13px;

  font-weight: 700;

}


.money-input input {

  width: 100%;

  border: 0;

  outline: 0;

  background: transparent;

  color: #1f2937;

  font-size: 15px;

  font-weight: 700;

}


.change-box {

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  gap: 15px;

  margin-top: 12px;

  padding:
    13px 15px;

  border-radius: 13px;

  background: #ecfdf5;

  border:
    1px solid #bbf7d0;

}


.change-box span {

  color: #166534;

  font-size: 12px;

  font-weight: 700;

}


.change-box strong {

  color: #16a34a;

  font-size: 16px;

}


.change-box strong.negative {

  color: #dc2626;

}


.payment-error {

  margin-top: 12px;

  padding:
    11px 13px;

  border-radius: 11px;

  background: #fef2f2;

  border:
    1px solid #fecaca;

  color: #b91c1c;

  text-align: left;

  font-size: 12px;

  font-weight: 600;

}


.back-btn {

  width: 100%;

  min-height: 48px;

  margin-top: 10px;

  border: 0;

  border-radius: 14px;

  background: #f1f5f9;

  color: #475569;

  font-size: 14px;

  font-weight: 700;

  cursor: pointer;

}



/* =====================================================
   GATE
===================================================== */

.gate-modal {

  max-width: 450px;

}


.gate-info {

  display: grid;

  gap: 9px;

  text-align: left;

}


.gate-info > div {

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  gap: 15px;

  padding:
    13px 14px;

  border-radius: 12px;

  background: #f8fafc;

  border:
    1px solid #e2e8f0;

}


.gate-info span {

  color: #64748b;

  font-size: 12px;

}


.gate-info strong {

  color: #1f2937;

  font-size: 13px;

  text-align: right;

}


.sudah-keluar {

  margin:
    18px 0;

  color: #16a34a;

  font-size: 13px;

  font-weight: 900;

}



/* =====================================================
   INVOICE
===================================================== */

.invoice-overlay {

  position: fixed;

  inset: 0;

  z-index: 11000;

  display: flex;

  align-items: center;

  justify-content: center;

  padding: 25px;

  background:
    rgba(15,23,42,.62);

  backdrop-filter:
    blur(7px);

  overflow-y: auto;

}


.invoice-modal {

  width: 100%;

  max-width: 700px;

  max-height: 92vh;

  overflow-y: auto;

  background: white;

  border-radius: 24px;

  box-shadow:
    0 30px 80px
    rgba(15,23,42,.30);

  padding: 30px;

  animation:
    invoiceIn
    .25s
    ease;

}


@keyframes invoiceIn {

  from {

    opacity: 0;

    transform:
      translateY(15px)
      scale(.98);

  }

  to {

    opacity: 1;

    transform:
      translateY(0)
      scale(1);

  }

}


.invoice-header {

  display: flex;

  align-items:
    flex-start;

  justify-content:
    space-between;

  gap: 20px;

  padding-bottom: 20px;

  border-bottom:
    1px solid #e2e8f0;

}


.invoice-badge {

  display: inline-flex;

  padding:
    6px 10px;

  border-radius: 999px;

  background: #eef2ff;

  color: #4f46e5;

  font-size: 10px;

  font-weight: 800;

}


.invoice-header h2 {

  margin:
    9px 0 3px;

  color: #111827;

  font-size: 23px;

}


.invoice-header p {

  margin: 0;

  color: #64748b;

  font-size: 12px;

}


.invoice-close {

  width: 38px;

  height: 38px;

  border: 0;

  border-radius: 50%;

  background: #f1f5f9;

  color: #475569;

  font-size: 25px;

  cursor: pointer;

}


.invoice-close:hover {

  background: #fee2e2;

  color: #dc2626;

}


.invoice-status {

  display: flex;

  align-items: center;

  gap: 13px;

  margin-top: 20px;

  padding: 15px;

  border-radius: 15px;

  background: #ecfdf5;

  border:
    1px solid #bbf7d0;

}


.invoice-check {

  width: 42px;

  height: 42px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 50%;

  background: #22c55e;

  color: white;

  font-size: 20px;

  font-weight: 800;

}


.invoice-status strong {

  display: block;

  color: #166534;

  font-size: 14px;

  margin-bottom: 3px;

}


.invoice-status span {

  display: block;

  color: #15803d;

  font-size: 11px;

  line-height: 1.5;

}


.invoice-number {

  display: flex;

  justify-content:
    space-between;

  align-items: center;

  gap: 15px;

  margin-top: 20px;

  padding: 15px;

  border-radius: 14px;

  background: #f8fafc;

  border:
    1px dashed #cbd5e1;

}


.invoice-number span {

  color: #64748b;

  font-size: 12px;

}


.invoice-number strong {

  color: #334155;

  font-size: 14px;

}


.invoice-section {

  margin-top: 22px;

}


.invoice-section h3 {

  margin:
    0 0 12px;

  color: #1e293b;

  font-size: 15px;

}


.invoice-grid {

  display: grid;

  grid-template-columns:
    repeat(
      2,
      minmax(0,1fr)
    );

  gap: 10px;

}


.invoice-grid > div {

  padding: 13px;

  border-radius: 12px;

  background: #f8fafc;

  border:
    1px solid #e2e8f0;

}


.invoice-grid span {

  display: block;

  margin-bottom: 5px;

  color: #64748b;

  font-size: 10px;

}


.invoice-grid strong {

  display: block;

  color: #1e293b;

  font-size: 13px;

  word-break: break-word;

}


.invoice-total {

  display: flex;

  align-items: center;

  justify-content:
    space-between;

  gap: 20px;

  margin-top: 20px;

  padding: 18px;

  border-radius: 16px;

  background:
    linear-gradient(
      135deg,
      #eef2ff,
      #f5f3ff
    );

  border:
    1px solid #c7d2fe;

}


.invoice-total span {

  display: block;

  color: #475569;

  font-size: 12px;

  font-weight: 700;

}


.invoice-total small {

  display: block;

  margin-top: 4px;

  color: #64748b;

  font-size: 10px;

}


.invoice-total strong {

  color: #4f46e5;

  font-size: 23px;

  white-space: nowrap;

}


.invoice-payment-detail {

  display: grid;

  grid-template-columns:
    repeat(
      2,
      minmax(0,1fr)
    );

  gap: 10px;

  margin-top: 12px;

}


.invoice-payment-detail > div {

  padding: 13px;

  border-radius: 12px;

  background: #f8fafc;

  border:
    1px solid #e2e8f0;

}


.invoice-payment-detail span {

  display: block;

  margin-bottom: 5px;

  color: #64748b;

  font-size: 10px;

}


.invoice-payment-detail strong {

  color: #1e293b;

  font-size: 14px;

}


.invoice-actions {

  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 12px;

  margin-top: 20px;

}


.invoice-actions button {

  min-height: 50px;

  border: 0;

  border-radius: 13px;

  font-size: 14px;

  font-weight: 800;

  cursor: pointer;

}


.invoice-print {

  background:
    linear-gradient(
      135deg,
      #4f46e5,
      #7c3aed
    );

  color: white;

}


.invoice-done {

  background: #f1f5f9;

  color: #334155;

}



/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 900px) {

  .sidebar {

    width: 220px;

  }


  .main {

    margin-left: 220px;

    width:
      calc(100% - 220px);

  }


  .content {

    padding: 25px;

  }

}


@media (max-width: 720px) {

  .sidebar {

    position: relative;

    width: 100%;

    min-height: auto;

    padding: 15px;

  }


  .menu {

    display: none;

  }


  .logout-btn {

    width: auto;

  }


  .main {

    margin-left: 0;

    width: 100%;

  }


  .page {

    display: block;

  }


  .topbar {

    height: auto;

    padding: 20px;

  }


  .petugas-info {

    display: none;

  }


  .content {

    padding: 18px;

  }


  .scan-card {

    padding: 20px;

    border-radius: 18px;

  }


  .invoice-grid {

    grid-template-columns: 1fr;

  }


  .invoice-actions {

    grid-template-columns: 1fr;

  }


  .invoice-payment-detail {

    grid-template-columns: 1fr;

  }

}


@media (max-width: 480px) {

  .flow-modal {

    padding: 22px 18px;

    border-radius: 20px;

  }


  .flow-modal h2 {

    font-size: 21px;

  }


  .detected-row,
  .gate-info > div,
  .payment-row {

    align-items:
      flex-start;

    flex-direction:
      column;

  }


  .detected-row strong,
  .gate-info strong,
  .payment-row strong {

    text-align: left;

  }


  .invoice-modal {

    padding: 20px;

    border-radius: 18px;

  }


  .invoice-header h2 {

    font-size: 19px;

  }


  .invoice-number {

    align-items:
      flex-start;

    flex-direction:
      column;

  }

}

</style>