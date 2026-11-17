<template>
  <div class="page">

    <!-- =====================================================
         HEADER
    ====================================================== -->
    <header class="header">

      <div class="brand">

        <div class="brand-icon">
          🅿️
        </div>

        <div>
          <h1>ParkirKu</h1>
          <p>Parkir Plaza Andalas</p>
        </div>

      </div>

      <NuxtLink
        to="/login"
        class="login-btn"
      >
        🔐 Login
      </NuxtLink>

    </header>


    <!-- =====================================================
         CONTENT
    ====================================================== -->
    <main class="container">

      <!-- =====================================================
           HERO
      ====================================================== -->
      <section class="hero">

        <div class="badge">
          🚗 SISTEM PARKIR DIGITAL
        </div>

        <h2>
          Selamat Datang di
          <span>Parkir Plaza Andalas</span>
        </h2>

        <p>
          Ambil tiket parkir dengan mudah dan cepat.
        </p>

      </section>


      <!-- =====================================================
           NON MEMBER
      ====================================================== -->
      <section class="card">

        <div class="section-heading">

          <div class="heading-icon">
            🎫
          </div>

          <div>

            <h3>
              Parkir Non Member
            </h3>

            <p>
              Pilih jenis kendaraan untuk mengambil tiket.
            </p>

          </div>

        </div>


        <!-- =====================================================
             PILIHAN MOTOR & MOBIL
        ====================================================== -->
        <div class="nonmember-buttons">

          <!-- MOTOR -->
          <button
            type="button"
            class="vehicle-button motor"
            :disabled="loading"
            @click="ambilTiket('Motor')"
          >

            <div class="vehicle-button-icon">
              🏍️
            </div>

            <div class="vehicle-button-content">

              <strong>
                Motor
              </strong>

              <span>
                Rp3.000 / jam
              </span>

              <small>
                {{
                  loading && jenisKendaraan === 'Motor'
                    ? 'Sedang membuat tiket...'
                    : 'Klik untuk ambil tiket'
                }}
              </small>

            </div>

            <div class="vehicle-button-arrow">
              {{
                loading && jenisKendaraan === 'Motor'
                  ? '⏳'
                  : '→'
              }}
            </div>

          </button>


          <!-- MOBIL -->
          <button
            type="button"
            class="vehicle-button mobil"
            :disabled="loading"
            @click="ambilTiket('Mobil')"
          >

            <div class="vehicle-button-icon">
              🚗
            </div>

            <div class="vehicle-button-content">

              <strong>
                Mobil
              </strong>

              <span>
                Rp5.000 / jam
              </span>

              <small>
                {{
                  loading && jenisKendaraan === 'Mobil'
                    ? 'Sedang membuat tiket...'
                    : 'Klik untuk ambil tiket'
                }}
              </small>

            </div>

            <div class="vehicle-button-arrow">
              {{
                loading && jenisKendaraan === 'Mobil'
                  ? '⏳'
                  : '→'
              }}
            </div>

          </button>

        </div>


      </section>


      <!-- =====================================================
           MEMBER
      ====================================================== -->
      <section class="card">

        <div class="section-heading">

          <div class="heading-icon member">
            💳
          </div>

          <div>

            <h3>
              Parkir Member
            </h3>

            <p>
              Masukkan kode member untuk memeriksa status.
            </p>

          </div>

        </div>


        <!-- =====================================================
             FORM MEMBER
        ====================================================== -->
        <form
          class="member-form"
          @submit.prevent="checkMember"
        >

          <div class="member-input">

            <span>
              🔍
            </span>

            <input
              v-model="memberCode"
              type="text"
              placeholder="Masukkan kode member..."
              autocomplete="off"
            />

          </div>


          <button
            type="submit"
            class="member-button"
            :disabled="memberLoading"
          >

            {{
              memberLoading
                ? 'Memeriksa...'
                : 'Periksa Member'
            }}

          </button>

        </form>


        <!-- =====================================================
             MEMBER BERHASIL
        ====================================================== -->
        <div
          v-if="memberSuccess"
          class="member-success"
        >

          <!-- GERBANG TERBUKA -->
          <div class="gate-animation">

            <div class="gate-icon">

              <div class="gate-post left"></div>

              <div class="gate-bar left-bar">
                🚧
              </div>

              <div class="gate-post right"></div>

              <div class="gate-bar right-bar">
              </div>

            </div>

            <div class="gate-open-text">
              TERBUKA
            </div>

          </div>


          <div class="member-success-content">

            <strong>
              Member Berhasil Ditemukan
            </strong>

            <span>
              ✓ {{ memberSuccess }}
            </span>

            <small>
              Member aktif dan dapat masuk ke area parkir.
            </small>

          </div>

        </div>


        <!-- =====================================================
             MEMBER ERROR
        ====================================================== -->
        <div
          v-if="memberError"
          class="member-error"
        >

          <div class="error-icon">
            ⚠️
          </div>

          <div>

            <strong>
              Member Tidak Ditemukan
            </strong>

            <span>
              {{ memberError }}
            </span>

          </div>

        </div>

      </section>


      <!-- =====================================================
           ERROR UMUM
      ====================================================== -->
      <div
        v-if="errorMessage"
        class="error-box"
      >

        <span>
          ⚠️
        </span>

        <p>
          {{ errorMessage }}
        </p>

        <button
          type="button"
          @click="errorMessage = ''"
        >
          ×
        </button>

      </div>


      <!-- =====================================================
           INFO
      ====================================================== -->
      <section class="info-grid">

        <div class="info-card">

          <div>
            🎫
          </div>

          <section>

            <h4>
              Tiket Otomatis
            </h4>

            <p>
              Tiket dibuat otomatis oleh sistem.
            </p>

          </section>

        </div>


        <div class="info-card">

          <div>
            📱
          </div>

          <section>

            <h4>
              QR Code
            </h4>

            <p>
              Gunakan QR Code saat kendaraan keluar.
            </p>

          </section>

        </div>


        <div class="info-card">

          <div>
            🖨️
          </div>

          <section>

            <h4>
              Cetak Tiket
            </h4>

            <p>
              Tiket dapat langsung dicetak.
            </p>

          </section>

        </div>

      </section>

    </main>


    <!-- =====================================================
         FOOTER
    ====================================================== -->
    <footer class="footer">

      <span>
        © 2026 Parkir Plaza Andalas
      </span>

      <span>
        Sistem Parkir Digital
      </span>

    </footer>


    <!-- =====================================================
         MODAL TIKET
    ====================================================== -->
    <div
      v-if="ticket"
      class="modal-background"
      @click.self="tutupTiket"
    >

      <div class="ticket-modal">

        <button
          type="button"
          class="close-button no-print"
          @click="tutupTiket"
        >
          ×
        </button>


        <!-- =====================================================
             TIKET
        ====================================================== -->
        <div
          id="ticket-print"
          class="ticket"
        >

          <!-- HEADER -->
          <div class="ticket-header">

            <div class="ticket-logo">
              🅿️
            </div>

            <div>

              <h2>
                Parkir Plaza Andalas
              </h2>

              <p>
                TIKET PARKIR NON MEMBER
              </p>

            </div>

          </div>


          <!-- SUCCESS -->
          <div class="ticket-success">

            <div class="success-circle">
              ✓
            </div>

            <h3>
              Tiket Berhasil Dibuat
            </h3>

            <p>
              Simpan tiket sampai kendaraan keluar.
            </p>

          </div>


          <!-- KODE -->
          <div class="kode-box">

            <span>
              KODE TIKET
            </span>

            <strong>
              {{ getKodeTiket() }}
            </strong>

          </div>


          <!-- QR -->
          <div class="qr-area">

            <h4>
              QR CODE TIKET
            </h4>

            <canvas
              ref="qrElement"
              class="qr"
            ></canvas>

            <p class="qr-value">
              {{ getBarcodeValue() }}
            </p>

            <small>
              Scan QR Code saat kendaraan keluar
            </small>

          </div>


          <!-- DETAIL -->
          <div class="ticket-detail">

            <div class="detail-row">

              <span>
                Jenis Kendaraan
              </span>

              <strong>
                {{ ticket?.jenis_kendaraan || '-' }}
              </strong>

            </div>


            <div class="detail-row">

              <span>
                Plat Nomor
              </span>

              <strong>
                {{ ticket?.plat_nomor || '-' }}
              </strong>

            </div>


            <div class="detail-row">

              <span>
                Tarif
              </span>

              <strong>
                {{ getTarif() }}
              </strong>

            </div>


            <div class="detail-row">

              <span>
                Jam Masuk
              </span>

              <strong>
                {{ getJamMasuk() }}
              </strong>

            </div>

          </div>


          <div class="divider"></div>


          <!-- PERINGATAN -->
          <div class="warning">

            <strong>
              ⚠️ PERHATIAN
            </strong>

            <p>
              Jangan buang tiket ini.
              Gunakan tiket atau QR Code saat kendaraan keluar.
            </p>

          </div>


          <!-- FOOTER -->
          <div class="ticket-footer">

            <p>
              Terima kasih telah menggunakan
              Parkir Plaza Andalas.
            </p>

            <small>
              Dicetak: {{ tanggalCetak }}
            </small>

          </div>

        </div>


        <!-- =====================================================
             BUTTON TIKET
        ====================================================== -->
        <div class="ticket-actions no-print">

          <button
            type="button"
            class="print-button"
            @click="cetakTiket"
          >
            🖨️ Cetak Tiket
          </button>


          <button
            type="button"
            class="download-button"
            @click="downloadTiket"
          >
            ⬇️ Simpan Tiket
          </button>


          <button
            type="button"
            class="done-button"
            @click="tutupTiket"
          >
            ✓ Selesai
          </button>

        </div>

      </div>

    </div>

  </div>
</template>


<script setup>

import QRCode from 'qrcode'


// ============================================================
// API
// ============================================================

const API_URL =
  'http://127.0.0.1:8000/api'


// ============================================================
// NON MEMBER
// ============================================================

const loading =
  ref(false)

const jenisKendaraan =
  ref('')

const ticket =
  ref(null)

const errorMessage =
  ref('')


// ============================================================
// QR
// ============================================================

const qrElement =
  ref(null)


// ============================================================
// MEMBER
// ============================================================

const memberCode =
  ref('')

const memberLoading =
  ref(false)

const memberSuccess =
  ref('')

const memberError =
  ref('')


// ============================================================
// GENERATE PLAT RANDOM
// ============================================================

function generatePlatDummy() {

  const angka =
    Math.floor(
      1000 +
      Math.random() * 9000
    )


  const huruf = [
    'AA',
    'AB',
    'AC',
    'AD',
    'AE',
    'AF',
    'AG',
    'AH'
  ]


  const randomHuruf =
    huruf[
      Math.floor(
        Math.random() *
        huruf.length
      )
    ]


  return `NM-${angka}-${randomHuruf}`

}


// ============================================================
// AMBIL TIKET
// ============================================================

async function ambilTiket(jenis) {

  if (loading.value) {
    return
  }


  errorMessage.value =
    ''


  // Jenis kendaraan langsung ditentukan
  // dari tombol Motor / Mobil
  jenisKendaraan.value =
    jenis


  loading.value =
    true


  ticket.value =
    null


  try {

    const platNomor =
      generatePlatDummy()


    const response =
      await $fetch(
        `${API_URL}/tiket`,
        {

          method:
            'POST',

          headers: {

            Accept:
              'application/json',

            'Content-Type':
              'application/json'

          },

          body: {

            tipe:
              'nonmember',

            plat_nomor:
              platNomor,

            jenis_kendaraan:
              jenisKendaraan.value

          },

          timeout:
            15000

        }
      )


    console.log(
      'RESPONSE TIKET:',
      response
    )


    let data =
      response?.data ||
      response?.tiket ||
      response


    if (Array.isArray(data)) {

      data =
        data[0]

    }


    if (
      !data ||
      typeof data !== 'object'
    ) {

      errorMessage.value =
        'Data tiket tidak ditemukan.'

      return

    }


    ticket.value =
      data


    await nextTick()

    await generateQRCode()


  } catch (error) {

    console.error(
      'ERROR TIKET:',
      error
    )


    console.error(
      'ERROR DATA:',
      error?.data
    )


    errorMessage.value =
      error?.data?.message ||
      error?.data?.errors?.plat_nomor?.[0] ||
      error?.data?.errors?.jenis_kendaraan?.[0] ||
      error?.message ||
      'Gagal membuat tiket.'


  } finally {

    loading.value =
      false

  }

}


// ============================================================
// GENERATE QR
// ============================================================

async function generateQRCode() {

  if (!qrElement.value) {
    return
  }


  if (!ticket.value) {
    return
  }


  const value =
    getBarcodeValue()


  if (
    !value ||
    value === '-'
  ) {

    return

  }


  try {

    await QRCode.toCanvas(
      qrElement.value,
      value,
      {

        width:
          220,

        margin:
          2,

        errorCorrectionLevel:
          'H',

        color: {

          dark:
            '#000000',

          light:
            '#ffffff'

        }

      }
    )


  } catch (error) {

    console.error(
      'QR ERROR:',
      error
    )

  }

}


// ============================================================
// WATCH TICKET
// ============================================================

watch(
  ticket,
  async (value) => {

    if (!value) {
      return
    }


    await nextTick()

    await generateQRCode()

  }
)


// ============================================================
// NILAI QR
// ============================================================

function getBarcodeValue() {

  if (!ticket.value) {
    return '-'
  }


  return (
    ticket.value.token ||
    ticket.value.qr_code ||
    ticket.value.qrCode ||
    ticket.value.kode_tiket ||
    ticket.value.kode ||
    '-'
  )

}


// ============================================================
// KODE TIKET
// ============================================================

function getKodeTiket() {

  if (!ticket.value) {
    return '-'
  }


  return (
    ticket.value.kode_tiket ||
    ticket.value.kode_ticket ||
    ticket.value.kode ||
    '-'
  )

}


// ============================================================
// TARIF
// ============================================================

function getTarif() {

  if (!ticket.value) {
    return '-'
  }


  let harga =
    ticket.value.biaya


  if (
    harga === null ||
    harga === undefined ||
    harga === ''
  ) {

    const jenis =
      String(
        ticket.value.jenis_kendaraan ||
        ''
      ).toLowerCase()


    if (jenis === 'motor') {

      harga =
        3000

    }


    if (jenis === 'mobil') {

      harga =
        5000

    }

  }


  const angka =
    Number(
      String(harga)
        .replace(/[^\d]/g, '')
    )


  if (Number.isNaN(angka)) {
    return '-'
  }


  return (
    'Rp' +
    angka.toLocaleString('id-ID') +
    '/jam'
  )

}


// ============================================================
// JAM MASUK
// ============================================================

function getJamMasuk() {

  if (!ticket.value) {
    return '-'
  }


  const waktu =
    ticket.value.waktu_masuk ||
    ticket.value.created_at


  if (!waktu) {
    return '-'
  }


  try {

    return new Date(waktu)
      .toLocaleString(
        'id-ID',
        {

          day:
            '2-digit',

          month:
            '2-digit',

          year:
            'numeric',

          hour:
            '2-digit',

          minute:
            '2-digit'

        }
      )

  } catch {

    return String(waktu)

  }

}


// ============================================================
// TANGGAL CETAK
// ============================================================

const tanggalCetak =
  computed(() => {

    return new Date()
      .toLocaleString(
        'id-ID',
        {

          day:
            '2-digit',

          month:
            '2-digit',

          year:
            'numeric',

          hour:
            '2-digit',

          minute:
            '2-digit'

        }
      )

  })


// ============================================================
// CHECK MEMBER + SCAN MASUK
// ============================================================

async function checkMember() {

  if (memberLoading.value) {
    return
  }


  const kodeMember =
    memberCode.value.trim()


  memberSuccess.value =
    ''

  memberError.value =
    ''

  errorMessage.value =
    ''


  if (!kodeMember) {

    memberError.value =
      'Silakan masukkan kode member terlebih dahulu.'

    return

  }


  memberLoading.value =
    true


  try {

    /*
    |--------------------------------------------------------------------------
    | MEMBER LANGSUNG SCAN MASUK
    |--------------------------------------------------------------------------
    | Jangan menggunakan /member/check di sini.
    |
    | /member/check hanya melakukan pengecekan member.
    |
    | /scan/masuk akan:
    | 1. mencari member
    | 2. mengecek status lunas
    | 3. mengecek tanggal expired
    | 4. mengecek apakah member sudah berada di dalam
    | 5. membuat tiket member
    | 6. mengubah sedang_parkir menjadi true
    |--------------------------------------------------------------------------
    */

    const response =
      await $fetch(
        `${API_URL}/scan/masuk`,
        {

          method:
            'POST',

          headers: {

            Accept:
              'application/json',

            'Content-Type':
              'application/json'

          },

          body: {

            tipe:
              'member',

            kode_member:
              kodeMember

          },

          timeout:
            15000

        }
      )


    console.log(
      'RESPONSE SCAN MASUK MEMBER:',
      response
    )


    /*
    |--------------------------------------------------------------------------
    | CEK RESPONSE
    |--------------------------------------------------------------------------
    */

    if (
      response?.success !== true
    ) {

      memberError.value =
        response?.message ||
        'Member gagal masuk ke area parkir.'

      return

    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA MEMBER
    |--------------------------------------------------------------------------
    */

    const data =
      response?.data ||
      {}


    const member =
      data?.member ||
      response?.member ||
      {}


    const nama =
      member?.nama_member ||
      'Member'


    /*
    |--------------------------------------------------------------------------
    | BERHASIL
    |--------------------------------------------------------------------------
    */

    memberSuccess.value =
      `${nama} berhasil masuk ke area parkir.`


    memberError.value =
      ''


    /*
    |--------------------------------------------------------------------------
    | KOSONGKAN INPUT
    |--------------------------------------------------------------------------
    */

    memberCode.value =
      ''


  } catch (error) {

    console.error(
      'SCAN MASUK MEMBER ERROR:',
      error
    )


    console.error(
      'SCAN MASUK MEMBER ERROR DATA:',
      error?.data
    )


    memberError.value =
      error?.data?.message ||
      error?.data?.errors?.kode_member?.[0] ||
      error?.data?.errors?.token?.[0] ||
      error?.message ||
      'Gagal melakukan scan masuk member.'


  } finally {

    memberLoading.value =
      false

  }

}


// ============================================================
// TUTUP TIKET
// ============================================================

function tutupTiket() {

  ticket.value =
    null

}


// ============================================================
// CETAK TIKET
// ============================================================

async function cetakTiket() {

  await nextTick()

  await generateQRCode()


  setTimeout(() => {

    window.print()

  }, 300)

}


// ============================================================
// DOWNLOAD TIKET
// ============================================================

async function downloadTiket() {

  if (!ticket.value) {
    return
  }


  const qr =
    qrElement.value


  let qrImage =
    ''


  if (qr) {

    try {

      qrImage =
        qr.toDataURL(
          'image/png'
        )

    } catch {

      qrImage =
        ''

    }

  }


  const kode =
    getKodeTiket()


  const html = `
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>
Tiket-${kode}
</title>

<style>

body {
  font-family: Arial, sans-serif;
  background: white;
  color: #172033;
  padding: 30px;
}

.ticket {
  width: 400px;
  margin: auto;
}

.header {
  text-align: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 15px;
}

.header h2 {
  margin: 0;
}

.header p {
  color: #64748b;
}

.success {
  text-align: center;
  padding: 20px;
}

.kode {
  text-align: center;
  padding: 15px;
  background: #eff6ff;
  border-radius: 10px;
}

.kode span {
  display: block;
  font-size: 10px;
}

.kode strong {
  font-size: 25px;
  color: #2563eb;
}

.qr {
  text-align: center;
  margin: 20px 0;
}

.qr img {
  width: 220px;
}

.detail {
  margin-top: 20px;
}

.row {
  display: flex;
  justify-content: space-between;
  padding: 9px 0;
  border-bottom: 1px solid #eee;
}

.warning {
  margin-top: 20px;
  padding: 12px;
  background: #fff7ed;
  border-radius: 10px;
  color: #9a3412;
}

.footer {
  margin-top: 20px;
  text-align: center;
  font-size: 10px;
  color: #64748b;
}

</style>

</head>

<body>

<div class="ticket">

  <div class="header">

    <h2>
      🅿️ PARKIR PLAZA ANDALAS
    </h2>

    <p>
      TIKET PARKIR NON MEMBER
    </p>

  </div>


  <div class="success">

    <h3>
      ✓ Tiket Berhasil Dibuat
    </h3>

    <p>
      Simpan tiket sampai kendaraan keluar.
    </p>

  </div>


  <div class="kode">

    <span>
      KODE TIKET
    </span>

    <strong>
      ${kode}
    </strong>

  </div>


  <div class="qr">

    <h4>
      QR CODE TIKET
    </h4>

    ${
      qrImage
        ? `<img src="${qrImage}">`
        : ''
    }

    <p>
      ${getBarcodeValue()}
    </p>

  </div>


  <div class="detail">

    <div class="row">

      <span>
        Jenis Kendaraan
      </span>

      <strong>
        ${ticket.value?.jenis_kendaraan || '-'}
      </strong>

    </div>


    <div class="row">

      <span>
        Plat Nomor
      </span>

      <strong>
        ${ticket.value?.plat_nomor || '-'}
      </strong>

    </div>


    <div class="row">

      <span>
        Tarif
      </span>

      <strong>
        ${getTarif()}
      </strong>

    </div>


    <div class="row">

      <span>
        Jam Masuk
      </span>

      <strong>
        ${getJamMasuk()}
      </strong>

    </div>

  </div>


  <div class="warning">

    <strong>
      ⚠️ PERHATIAN
    </strong>

    <p>
      Jangan buang tiket ini.
      Gunakan QR Code saat kendaraan keluar.
    </p>

  </div>


  <div class="footer">

    Terima kasih telah menggunakan
    Parkir Plaza Andalas.

  </div>

</div>

</body>

</html>
`


  const blob =
    new Blob(
      [html],
      {
        type:
          'text/html;charset=utf-8'
      }
    )


  const url =
    URL.createObjectURL(blob)


  const link =
    document.createElement('a')


  link.href =
    url


  link.download =
    `Tiket-${kode}.html`


  document.body.appendChild(
    link
  )


  link.click()


  document.body.removeChild(
    link
  )


  URL.revokeObjectURL(
    url
  )

}

</script>


<style scoped>

/* ============================================================
   PAGE
============================================================ */

.page {

  min-height: 100vh;

  background:
    linear-gradient(
      135deg,
      #eef6ff,
      #f8faff,
      #f5f0ff
    );

  color: #172033;

}


/* ============================================================
   HEADER
============================================================ */

.header {

  height: 78px;

  padding:
    0 7%;

  display: flex;

  align-items: center;

  justify-content: space-between;

  background:
    rgba(255,255,255,.96);

  border-bottom:
    1px solid #e2e8f0;

}


.brand {

  display: flex;

  align-items: center;

  gap: 12px;

}


.brand-icon {

  width: 47px;

  height: 47px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  font-size: 23px;

}


.brand h1 {

  margin: 0;

  color: #1e3a8a;

  font-size: 20px;

}


.brand p {

  margin:
    2px 0 0;

  color: #64748b;

  font-size: 11px;

}


.login-btn {

  padding:
    10px 16px;

  border:
    1px solid #dbe4ef;

  border-radius: 10px;

  background: white;

  color: #334155;

  text-decoration: none;

  font-size: 12px;

  font-weight: 700;

}


/* ============================================================
   CONTAINER
============================================================ */

.container {

  width:
    min(1050px, 90%);

  margin: auto;

  padding:
    50px 0 60px;

}


/* ============================================================
   HERO
============================================================ */

.hero {

  margin-bottom: 35px;

  text-align: center;

}


.badge {

  display: inline-block;

  padding:
    7px 13px;

  border-radius: 999px;

  background: #e8f1ff;

  color: #2563eb;

  font-size: 10px;

  font-weight: 800;

}


.hero h2 {

  margin:
    15px 0 0;

  font-size: 40px;

  line-height: 1.2;

}


.hero h2 span {

  display: block;

  background:
    linear-gradient(
      90deg,
      #2563eb,
      #7c3aed
    );

  -webkit-background-clip: text;

  background-clip: text;

  color: transparent;

}


.hero p {

  color: #64748b;

  font-size: 14px;

}


/* ============================================================
   CARD
============================================================ */

.card {

  margin-bottom: 20px;

  padding: 25px;

  border:
    1px solid #e2e8f0;

  border-radius: 20px;

  background:
    rgba(255,255,255,.95);

  box-shadow:
    0 12px 35px
    rgba(15,23,42,.06);

}


.section-heading {

  display: flex;

  align-items: center;

  gap: 13px;

  margin-bottom: 22px;

}


.heading-icon {

  width: 50px;

  height: 50px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 14px;

  background: #dbeafe;

  font-size: 23px;

}


.heading-icon.member {

  background: #ede9fe;

}


.section-heading h3 {

  margin: 0;

  font-size: 17px;

}


.section-heading p {

  margin:
    4px 0 0;

  color: #64748b;

  font-size: 11px;

}


/* ============================================================
   NON MEMBER BUTTONS
============================================================ */

.nonmember-buttons {

  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 15px;

}


.vehicle-button {

  position: relative;

  min-height: 145px;

  display: flex;

  align-items: center;

  gap: 15px;

  padding: 20px;

  border:
    1px solid #bfdbfe;

  border-radius: 17px;

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #f8fbff
    );

  cursor: pointer;

  text-align: left;

  transition:
    transform .2s,
    box-shadow .2s,
    border-color .2s;

}


.vehicle-button:hover:not(:disabled) {

  transform:
    translateY(-3px);

  border-color:
    #60a5fa;

  box-shadow:
    0 14px 30px
    rgba(37,99,235,.14);

}


.vehicle-button:active:not(:disabled) {

  transform:
    translateY(0);

}


.vehicle-button:disabled {

  opacity: .6;

  cursor: not-allowed;

}


.vehicle-button.motor {

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #eff6ff
    );

}


.vehicle-button.mobil {

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #f5f3ff
    );

}


.vehicle-button-icon {

  width: 62px;

  height: 62px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 16px;

  background:
    linear-gradient(
      135deg,
      #dbeafe,
      #ede9fe
    );

  font-size: 31px;

}


.vehicle-button-content {

  flex: 1;

}


.vehicle-button-content strong {

  display: block;

  color: #172554;

  font-size: 19px;

}


.vehicle-button-content span {

  display: block;

  margin-top: 4px;

  color: #2563eb;

  font-size: 13px;

  font-weight: 800;

}


.vehicle-button-content small {

  display: block;

  margin-top: 7px;

  color: #64748b;

  font-size: 10px;

}


.vehicle-button-arrow {

  flex-shrink: 0;

  color: #2563eb;

  font-size: 27px;

  font-weight: bold;

}


/* ============================================================
   MEMBER FORM
============================================================ */

.member-form {

  display: flex;

  gap: 10px;

}


.member-input {

  flex: 1;

  height: 48px;

  display: flex;

  align-items: center;

  border:
    1px solid #cbd5e1;

  border-radius: 11px;

  overflow: hidden;

  background: white;

  transition: .2s;

}


.member-input:focus-within {

  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(37,99,235,.08);

}


.member-input span {

  padding-left: 14px;

  font-size: 17px;

}


.member-input input {

  flex: 1;

  height: 100%;

  padding:
    0 14px;

  border: none;

  outline: none;

  background: transparent;

  color: #172033;

  font-size: 12px;

}


.member-input input::placeholder {

  color: #94a3b8;

}


.member-button {

  min-width: 160px;

  border: none;

  border-radius: 11px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  color: white;

  cursor: pointer;

  font-weight: 700;

  font-size: 12px;

  transition: .2s;

}


.member-button:hover:not(:disabled) {

  transform:
    translateY(-1px);

  box-shadow:
    0 8px 20px
    rgba(37,99,235,.20);

}


.member-button:disabled {

  opacity: .6;

  cursor: not-allowed;

}


/* ============================================================
   MEMBER SUCCESS
============================================================ */

.member-success {

  display: flex;

  align-items: center;

  gap: 15px;

  margin-top: 16px;

  padding: 16px;

  border:
    1px solid #86efac;

  border-radius: 16px;

  background:
    linear-gradient(
      135deg,
      #f0fdf4,
      #ecfdf5
    );

  animation:
    memberSuccess .35s ease;

}


@keyframes memberSuccess {

  from {

    opacity: 0;

    transform:
      translateY(-7px);

  }

  to {

    opacity: 1;

    transform:
      translateY(0);

  }

}


/* ============================================================
   GERBANG TERBUKA
============================================================ */

.gate-animation {

  position: relative;

  width: 72px;

  height: 72px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 16px;

  background:
    linear-gradient(
      135deg,
      #dcfce7,
      #bbf7d0
    );

  border:
    1px solid #86efac;

  overflow: hidden;

}


.gate-icon {

  position: relative;

  width: 55px;

  height: 45px;

}


.gate-post {

  position: absolute;

  bottom: 0;

  width: 6px;

  height: 35px;

  border-radius: 4px;

  background: #15803d;

}


.gate-post.left {

  left: 5px;

}


.gate-post.right {

  right: 5px;

}


.gate-bar {

  position: absolute;

  top: 7px;

  width: 38px;

  height: 7px;

  border-radius: 6px;

  background:
    linear-gradient(
      90deg,
      #dc2626 0%,
      #dc2626 25%,
      white 25%,
      white 50%,
      #dc2626 50%,
      #dc2626 75%,
      white 75%
    );

  transform-origin:
    right center;

}


.gate-bar.left-bar {

  left: 4px;

  transform:
    rotate(-32deg);

  animation:
    gateOpen .7s ease;

}


@keyframes gateOpen {

  from {

    transform:
      rotate(0deg);

  }

  to {

    transform:
      rotate(-32deg);

  }

}


.gate-open-text {

  position: absolute;

  bottom: 3px;

  left: 0;

  right: 0;

  text-align: center;

  color: #15803d;

  font-size: 7px;

  font-weight: 900;

  letter-spacing: .5px;

}


.member-success-content {

  flex: 1;

}


.member-success-content strong {

  display: block;

  margin-bottom: 4px;

  color: #166534;

  font-size: 13px;

}


.member-success-content span {

  display: block;

  color: #15803d;

  font-size: 11px;

  font-weight: 600;

}


.member-success-content small {

  display: block;

  margin-top: 5px;

  color: #4d7c0f;

  font-size: 9px;

}


/* ============================================================
   MEMBER ERROR
============================================================ */

.member-error {

  display: flex;

  align-items: center;

  gap: 12px;

  margin-top: 15px;

  padding: 14px;

  border:
    1px solid #fecaca;

  border-radius: 14px;

  background:
    linear-gradient(
      135deg,
      #fef2f2,
      #fff1f2
    );

  color: #b91c1c;

}


.error-icon {

  width: 42px;

  height: 42px;

  flex-shrink: 0;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 12px;

  background: #fee2e2;

  font-size: 20px;

}


.member-error strong {

  display: block;

  margin-bottom: 3px;

  color: #991b1b;

  font-size: 12px;

}


.member-error span {

  display: block;

  color: #b91c1c;

  font-size: 10px;

}


/* ============================================================
   ERROR UMUM
============================================================ */

.error-box {

  display: flex;

  align-items: center;

  gap: 10px;

  margin-bottom: 20px;

  padding: 13px;

  border:
    1px solid #fecaca;

  border-radius: 11px;

  background: #fef2f2;

  color: #b91c1c;

}


.error-box p {

  flex: 1;

  margin: 0;

  font-size: 12px;

}


.error-box button {

  border: none;

  background: transparent;

  color: #b91c1c;

  font-size: 20px;

  cursor: pointer;

}


/* ============================================================
   INFO
============================================================ */

.info-grid {

  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 14px;

}


.info-card {

  display: flex;

  gap: 10px;

  padding: 17px;

  border:
    1px solid #e2e8f0;

  border-radius: 15px;

  background: white;

}


.info-card > div {

  font-size: 20px;

}


.info-card h4 {

  margin:
    0 0 4px;

  font-size: 12px;

}


.info-card p {

  margin: 0;

  color: #64748b;

  font-size: 10px;

}


/* ============================================================
   FOOTER
============================================================ */

.footer {

  padding:
    20px 7%;

  display: flex;

  justify-content: space-between;

  border-top:
    1px solid #e2e8f0;

  color: #94a3b8;

  font-size: 10px;

}


/* ============================================================
   MODAL
============================================================ */

.modal-background {

  position: fixed;

  inset: 0;

  z-index: 9999;

  display: flex;

  align-items: center;

  justify-content: center;

  padding: 20px;

  background:
    rgba(15,23,42,.72);

  backdrop-filter:
    blur(7px);

}


.ticket-modal {

  position: relative;

  width:
    min(450px, 100%);

  max-height: 92vh;

  overflow-y: auto;

  padding: 27px;

  border-radius: 20px;

  background: white;

  box-shadow:
    0 30px 70px
    rgba(0,0,0,.25);

}


.close-button {

  position: absolute;

  top: 12px;

  right: 12px;

  width: 31px;

  height: 31px;

  border: none;

  border-radius: 50%;

  background: #f1f5f9;

  color: #64748b;

  font-size: 20px;

  cursor: pointer;

}


/* ============================================================
   TICKET HEADER
============================================================ */

.ticket-header {

  display: flex;

  align-items: center;

  justify-content: center;

  gap: 11px;

  padding-bottom: 15px;

  border-bottom:
    2px solid #e2e8f0;

}


.ticket-logo {

  width: 45px;

  height: 45px;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 12px;

  background: #2563eb;

  font-size: 21px;

}


.ticket-header h2 {

  margin: 0;

  font-size: 15px;

  text-transform: uppercase;

}


.ticket-header p {

  margin:
    3px 0 0;

  color: #64748b;

  font-size: 9px;

}


/* ============================================================
   TICKET SUCCESS
============================================================ */

.ticket-success {

  padding:
    17px 0;

  text-align: center;

}


.success-circle {

  width: 48px;

  height: 48px;

  margin: auto;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 50%;

  background: #dcfce7;

  color: #16a34a;

  font-size: 24px;

  font-weight: bold;

}


.ticket-success h3 {

  margin:
    8px 0 3px;

  font-size: 17px;

}


.ticket-success p {

  margin: 0;

  color: #64748b;

  font-size: 10px;

}


/* ============================================================
   KODE
============================================================ */

.kode-box {

  padding: 14px;

  border:
    1px dashed #93c5fd;

  border-radius: 12px;

  background: #eff6ff;

  text-align: center;

}


.kode-box span {

  display: block;

  color: #64748b;

  font-size: 9px;

  letter-spacing: 1px;

}


.kode-box strong {

  display: block;

  margin-top: 5px;

  color: #1d4ed8;

  font-size: 24px;

}


/* ============================================================
   QR
============================================================ */

.qr-area {

  margin:
    18px 0;

  padding:
    15px 10px;

  border:
    1px solid #e2e8f0;

  border-radius: 12px;

  text-align: center;

}


.qr-area h4 {

  margin:
    0 0 8px;

  font-size: 10px;

}


.qr {

  display: block;

  width: 220px;

  height: 220px;

  margin:
    10px auto;

}


.qr-value {

  margin: 0;

  color: #475569;

  font-family: monospace;

  font-size: 8px;

  word-break: break-all;

}


.qr-area small {

  display: block;

  margin-top: 6px;

  color: #94a3b8;

  font-size: 8px;

}


/* ============================================================
   DETAIL
============================================================ */

.ticket-detail {

  margin-top: 12px;

}


.detail-row {

  display: flex;

  justify-content: space-between;

  gap: 15px;

  padding: 8px 0;

  border-bottom:
    1px solid #f1f5f9;

  font-size: 11px;

}


.detail-row span {

  color: #64748b;

}


.detail-row strong {

  text-align: right;

}


/* ============================================================
   WARNING
============================================================ */

.divider {

  margin:
    15px 0;

  border-top:
    1px dashed #cbd5e1;

}


.warning {

  padding: 11px;

  border:
    1px solid #fed7aa;

  border-radius: 10px;

  background: #fff7ed;

  color: #9a3412;

}


.warning strong {

  font-size: 10px;

}


.warning p {

  margin:
    4px 0 0;

  font-size: 9px;

  line-height: 1.5;

}


/* ============================================================
   TICKET FOOTER
============================================================ */

.ticket-footer {

  margin-top: 13px;

  text-align: center;

}


.ticket-footer p {

  margin: 0;

  color: #64748b;

  font-size: 9px;

}


.ticket-footer small {

  display: block;

  margin-top: 4px;

  color: #94a3b8;

  font-size: 8px;

}


/* ============================================================
   BUTTON TIKET
============================================================ */

.ticket-actions {

  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 10px;

  margin-top: 18px;

}


.print-button,
.download-button,
.done-button {

  min-height: 44px;

  border: none;

  border-radius: 10px;

  color: white;

  font-weight: 700;

  cursor: pointer;

}


.print-button {

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

}


.download-button {

  background:
    linear-gradient(
      135deg,
      #059669,
      #16a34a
    );

}


.done-button {

  grid-column:
    1 / -1;

  background:
    #334155;

}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 700px) {

  .hero h2 {

    font-size: 30px;

  }


  .info-grid {

    grid-template-columns:
      1fr;

  }


  .nonmember-buttons {

    grid-template-columns:
      1fr;

  }


  .member-form {

    flex-direction:
      column;

  }


  .member-button {

    height: 45px;

  }


  .member-success {

    align-items: flex-start;

  }

}


@media (max-width: 500px) {

  .container {

    width: 92%;

    padding-top: 30px;

  }


  .header {

    padding:
      0 4%;

  }


  .ticket-modal {

    padding: 20px;

  }


  .ticket-actions {

    grid-template-columns:
      1fr;

  }


  .done-button {

    grid-column:
      auto;

  }


  .member-success {

    flex-direction: column;

    align-items: center;

    text-align: center;

  }


  .member-success-content {

    width: 100%;

  }


  .vehicle-button {

    min-height: 125px;

  }

}


/* ============================================================
   PRINT
============================================================ */

@media print {

  @page {

    size:
      A5 portrait;

    margin:
      8mm;

  }


  html,
  body {

    margin:
      0 !important;

    padding:
      0 !important;

    background:
      white !important;

  }


  body * {

    visibility:
      hidden !important;

  }


  #ticket-print,
  #ticket-print * {

    visibility:
      visible !important;

  }


  #ticket-print {

    position:
      absolute !important;

    top:
      0 !important;

    left:
      0 !important;

    width:
      100% !important;

    margin:
      0 !important;

    padding:
      0 !important;

    background:
      white !important;

  }


  .modal-background {

    position:
      static !important;

    display:
      block !important;

    padding:
      0 !important;

    background:
      white !important;

  }


  .ticket-modal {

    position:
      static !important;

    width:
      100% !important;

    max-height:
      none !important;

    overflow:
      visible !important;

    padding:
      0 !important;

    box-shadow:
      none !important;

  }


  .no-print {

    display:
      none !important;

  }


  .qr-area {

    border:
      none !important;

  }


  .qr {

    display:
      block !important;

    width:
      220px !important;

    height:
      220px !important;

    margin:
      10px auto !important;

  }

}

</style>