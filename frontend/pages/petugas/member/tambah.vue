<template>
  <div class="page">

    <!-- BACKGROUND -->
    <div class="background-shape shape-1"></div>
    <div class="background-shape shape-2"></div>

    <div class="container">

      <!-- HEADER -->
      <div class="top-header">

        <button
          type="button"
          class="back-button"
          @click="kembali"
        >
          ← Kembali
        </button>

        <div class="header-title">

          <div class="title-icon">
            👤
          </div>

          <div>
            <h1>Tambah Member</h1>
            <p>Tambahkan data member parkir baru</p>
          </div>

        </div>

      </div>


      <!-- FORM CARD -->
      <div class="form-card">

        <!-- CARD HEADER -->
        <div class="card-header">

          <div>
            <h2>Data Member Baru</h2>
            <p>Isi data member dengan lengkap dan benar</p>
          </div>

          <div class="member-badge">
            MEMBER
          </div>

        </div>


        <!-- ERROR -->
        <div
          v-if="errorMessage"
          class="error-box"
        >

          <div class="error-icon">
            !
          </div>

          <div>
            <strong>Gagal menyimpan member</strong>
            <p>{{ errorMessage }}</p>
          </div>

        </div>


        <!-- FORM -->
        <div class="form-body">

          <!-- NAMA MEMBER -->
          <div class="form-group">

            <label>
              Nama Member
              <span>*</span>
            </label>

            <div class="input-wrapper">

              <span class="input-icon">
                👤
              </span>

              <input
                v-model="form.nama_member"
                type="text"
                placeholder="Masukkan nama member"
                autocomplete="off"
              />

            </div>

          </div>


          <!-- PERUSAHAAN -->
          <div class="form-group">

            <label>
              Nama Perusahaan
              <span>*</span>
            </label>

            <div class="input-wrapper">

              <span class="input-icon">
                🏢
              </span>

              <input
                v-model="form.nama_perusahaan"
                type="text"
                placeholder="Masukkan nama perusahaan"
                autocomplete="off"
              />

            </div>

          </div>


          <!-- HARGA MEMBER -->
          <div class="price-card">

            <div class="price-left">

              <div class="price-icon">
                💳
              </div>

              <div>

                <p class="price-label">
                  Harga Member / Bulan
                </p>

                <h3>
                  Rp {{ formatRupiah(totalHarga) }}
                </h3>

              </div>

            </div>

            <div class="price-info">
              Tetap
            </div>

          </div>


          <!-- UANG BAYAR -->
          <div class="form-group">

            <label>
              Uang Bayar
              <span>*</span>
            </label>

            <div class="input-wrapper">

              <span class="currency">
                Rp
              </span>

              <input
                v-model.number="form.jumlah_bayar"
                type="number"
                min="0"
                step="1000"
                placeholder="Masukkan jumlah pembayaran"
              />

            </div>

            <p class="helper-text">
              Minimal pembayaran Rp150.000 agar member dapat disimpan.
            </p>

          </div>


          <!-- RINGKASAN PEMBAYARAN -->
          <div class="payment-summary">

            <div class="summary-title">
              <span>Ringkasan Pembayaran</span>
            </div>


            <div class="summary-row">

              <span>
                Total Harga
              </span>

              <strong>
                Rp {{ formatRupiah(totalHarga) }}
              </strong>

            </div>


            <div class="summary-row">

              <span>
                Uang Bayar
              </span>

              <strong class="blue">
                Rp {{ formatRupiah(form.jumlah_bayar) }}
              </strong>

            </div>


            <div class="summary-line"></div>


            <div class="summary-row">

              <span class="return-label">
                Kembalian
              </span>

              <strong class="green">
                Rp {{ formatRupiah(kembalian) }}
              </strong>

            </div>

          </div>


          <!-- STATUS -->
          <div
            class="status-card"
            :class="
              status === 'lunas'
                ? 'status-success'
                : 'status-warning'
            "
          >

            <div
              class="status-icon"
              :class="
                status === 'lunas'
                  ? 'icon-success'
                  : 'icon-warning'
              "
            >

              {{ status === 'lunas' ? '✓' : '!' }}

            </div>


            <div class="status-text">

              <span>
                Status Pembayaran
              </span>

              <strong>
                {{
                  status === 'lunas'
                    ? 'Lunas'
                    : 'Belum Lunas'
                }}
              </strong>

            </div>

          </div>


          <!-- BUTTON -->
          <div class="button-area">

            <button
              type="button"
              class="save-button"
              :disabled="loading"
              @click="simpan"
            >

              <span v-if="!loading">
                ✓
              </span>

              <span
                v-else
                class="small-spinner"
              ></span>

              {{
                loading
                  ? 'Menyimpan...'
                  : 'Simpan Member'
              }}

            </button>


            <button
              type="button"
              class="cancel-button"
              :disabled="loading"
              @click="kembali"
            >
              Batal
            </button>

          </div>

        </div>

      </div>


      <!-- FOOTER -->
      <div class="footer-text">
        Sistem Parkir • Manajemen Member
      </div>

    </div>

  </div>
</template>


<script setup lang="ts">

import {
  reactive,
  computed,
  ref
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
// STATE
// =====================================================

const loading =
  ref(false);

const errorMessage =
  ref("");


// =====================================================
// HARGA MEMBER
// =====================================================

const totalHarga =
  150000;


// =====================================================
// FORM
// =====================================================

const form = reactive({

  nama_member: "",

  nama_perusahaan: "",

  jumlah_bayar: 0

});


// =====================================================
// KEMBALIAN
// =====================================================

const kembalian =
  computed(() => {

    const bayar =
      Number(
        form.jumlah_bayar || 0
      );

    if (
      bayar > totalHarga
    ) {

      return bayar - totalHarga;

    }

    return 0;

  });


// =====================================================
// STATUS
// =====================================================

const status =
  computed(() => {

    const bayar =
      Number(
        form.jumlah_bayar || 0
      );

    if (
      bayar >= totalHarga
    ) {

      return "lunas";

    }

    return "belum lunas";

  });


// =====================================================
// FORMAT RUPIAH
// =====================================================

const formatRupiah =
  (value: any) => {

    return new Intl.NumberFormat(
      "id-ID"
    ).format(
      Number(value || 0)
    );

  };


// =====================================================
// SIMPAN
// =====================================================

const simpan =
  async () => {

    // =================================================
    // RESET ERROR
    // =================================================

    errorMessage.value = "";


    // =================================================
    // VALIDASI NAMA MEMBER
    // =================================================

    if (
      !form.nama_member.trim()
    ) {

      errorMessage.value =
        "Nama member wajib diisi.";

      return;

    }


    // =================================================
    // VALIDASI PERUSAHAAN
    // =================================================

    if (
      !form.nama_perusahaan.trim()
    ) {

      errorMessage.value =
        "Nama perusahaan wajib diisi.";

      return;

    }


    // =================================================
    // AMBIL JUMLAH BAYAR
    // =================================================

    const jumlahBayar =
      Number(
        form.jumlah_bayar || 0
      );


    // =================================================
    // VALIDASI UANG BAYAR TIDAK BOLEH NEGATIF
    // =================================================

    if (
      jumlahBayar < 0
    ) {

      errorMessage.value =
        "Uang bayar tidak boleh kurang dari 0.";

      return;

    }


    // =================================================
    // VALIDASI MINIMAL RP150.000
    // =================================================

    if (
      jumlahBayar < totalHarga
    ) {

      errorMessage.value =
        "Member tidak dapat disimpan. Uang bayar minimal Rp150.000 agar pembayaran Lunas.";

      return;

    }


    // =================================================
    // MULAI LOADING
    // =================================================

    try {

      loading.value = true;


      // =================================================
      // PAYLOAD
      // =================================================

      const payload = {

        nama_member:
          form.nama_member.trim(),

        nama_perusahaan:
          form.nama_perusahaan.trim(),

        jumlah_bayar:
          jumlahBayar

      };


      console.log(
        "DATA YANG DIKIRIM:",
        payload
      );


      // =================================================
      // POST KE LARAVEL
      // =================================================

      const response: any =
        await $fetch(
          `${API_URL}/members`,
          {

            method: "POST",

            headers: {

              "Content-Type":
                "application/json",

              "Accept":
                "application/json"

            },

            body: payload

          }
        );


      // =================================================
      // CEK RESPONSE
      // =================================================

      console.log(
        "RESPON LARAVEL:",
        response
      );


      // =================================================
      // TAMPILKAN PESAN BERHASIL
      // =================================================

      alert(
        response?.message ||
        "Member berhasil ditambahkan!"
      );


      // =================================================
      // REDIRECT
      // =================================================

      await router.push(
        "/petugas/member"
      );

    }


    // =================================================
    // ERROR
    // =================================================

    catch (
      error: any
    ) {

      console.error(
        "ERROR TAMBAH MEMBER:",
        error
      );


      console.error(
        "DETAIL ERROR:",
        error?.data
      );


      // =================================================
      // ERROR VALIDASI LARAVEL
      // =================================================

      if (
        error?.data?.errors
      ) {

        const errors =
          error.data.errors;


        const pesan =
          Object.values(errors)
            .flat()
            .join(", ");


        errorMessage.value =
          String(pesan);

      }


      // =================================================
      // ERROR MESSAGE DARI LARAVEL
      // =================================================

      else if (
        error?.data?.message
      ) {

        errorMessage.value =
          error.data.message;

      }


      // =================================================
      // ERROR RESPONSE
      // =================================================

      else if (
        error?.response?.data?.message
      ) {

        errorMessage.value =
          error.response.data.message;

      }


      // =================================================
      // ERROR UMUM
      // =================================================

      else if (
        error?.message
      ) {

        errorMessage.value =
          error.message;

      }


      // =================================================
      // ERROR DEFAULT
      // =================================================

      else {

        errorMessage.value =
          "Tidak dapat terhubung ke server Laravel.";

      }

    }


    // =================================================
    // SELESAI
    // =================================================

    finally {

      loading.value =
        false;

    }

  };


// =====================================================
// KEMBALI
// =====================================================

const kembali =
  () => {

    router.push(
      "/petugas/member/select"
    );

  };

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

.page {

  min-height: 100vh;

  position: relative;

  overflow: hidden;

  background:
    linear-gradient(
      135deg,
      #eff6ff 0%,
      #f8fafc 45%,
      #e0f2fe 100%
    );

  padding: 35px 20px 50px;

  font-family:
    Arial,
    Helvetica,
    sans-serif;

  color: #1e293b;

}


/* ===================================================== */
/* BACKGROUND SHAPE */
/* ===================================================== */

.background-shape {

  position: fixed;

  border-radius: 50%;

  pointer-events: none;

  opacity: 0.5;

}


.shape-1 {

  width: 380px;

  height: 380px;

  background: #dbeafe;

  top: -180px;

  right: -120px;

}


.shape-2 {

  width: 320px;

  height: 320px;

  background: #e0f2fe;

  bottom: -150px;

  left: -120px;

}


/* ===================================================== */
/* CONTAINER */
/* ===================================================== */

.container {

  position: relative;

  z-index: 2;

  width: 100%;

  max-width: 850px;

  margin: 0 auto;

}


/* ===================================================== */
/* HEADER */
/* ===================================================== */

.top-header {

  margin-bottom: 20px;

}


.back-button {

  border: none;

  background: white;

  color: #475569;

  padding: 10px 15px;

  border-radius: 10px;

  font-size: 14px;

  font-weight: 600;

  cursor: pointer;

  box-shadow:
    0 3px 10px
    rgba(15, 23, 42, 0.08);

  margin-bottom: 20px;

}


.back-button:hover {

  background: #f8fafc;

  color: #2563eb;

}


.header-title {

  display: flex;

  align-items: center;

  gap: 15px;

}


.title-icon {

  width: 55px;

  height: 55px;

  border-radius: 15px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );

  color: white;

  display: flex;

  align-items: center;

  justify-content: center;

  font-size: 25px;

  box-shadow:
    0 7px 18px
    rgba(37, 99, 235, 0.22);

}


.header-title h1 {

  margin: 0;

  font-size: 30px;

  font-weight: 800;

  color: #0f172a;

}


.header-title p {

  margin: 5px 0 0;

  font-size: 14px;

  color: #64748b;

}


/* ===================================================== */
/* FORM CARD */
/* ===================================================== */

.form-card {

  background: white;

  border-radius: 20px;

  overflow: hidden;

  box-shadow:
    0 12px 35px
    rgba(15, 23, 42, 0.10);

  border:
    1px solid #e2e8f0;

}


/* ===================================================== */
/* CARD HEADER */
/* ===================================================== */

.card-header {

  background:
    linear-gradient(
      135deg,
      #1d4ed8,
      #2563eb
    );

  padding: 23px 28px;

  color: white;

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 20px;

}


.card-header h2 {

  margin: 0;

  font-size: 19px;

  font-weight: 800;

}


.card-header p {

  margin: 5px 0 0;

  font-size: 13px;

  color: #dbeafe;

}


.member-badge {

  background:
    rgba(255,255,255,0.15);

  border:
    1px solid
    rgba(255,255,255,0.25);

  padding: 8px 13px;

  border-radius: 20px;

  font-size: 10px;

  font-weight: 800;

  letter-spacing: 1px;

}


/* ===================================================== */
/* FORM BODY */
/* ===================================================== */

.form-body {

  padding: 30px;

}


/* ===================================================== */
/* ERROR */
/* ===================================================== */

.error-box {

  margin: 22px 30px 0;

  padding: 14px 16px;

  border-radius: 12px;

  background: #fef2f2;

  border:
    1px solid #fecaca;

  color: #b91c1c;

  display: flex;

  gap: 12px;

}


.error-icon {

  width: 28px;

  height: 28px;

  flex-shrink: 0;

  border-radius: 50%;

  background: #dc2626;

  color: white;

  display: flex;

  align-items: center;

  justify-content: center;

  font-weight: 800;

}


.error-box strong {

  font-size: 14px;

}


.error-box p {

  margin: 4px 0 0;

  font-size: 13px;

}


/* ===================================================== */
/* FORM GROUP */
/* ===================================================== */

.form-group {

  margin-bottom: 22px;

}


.form-group label {

  display: block;

  margin-bottom: 8px;

  font-size: 14px;

  font-weight: 700;

  color: #334155;

}


.form-group label span {

  color: #dc2626;

}


/* ===================================================== */
/* INPUT */
/* ===================================================== */

.input-wrapper {

  position: relative;

  display: flex;

  align-items: center;

}


.input-wrapper input {

  width: 100%;

  height: 48px;

  border:
    1px solid #cbd5e1;

  border-radius: 11px;

  outline: none;

  padding:
    0 15px 0 45px;

  font-size: 14px;

  color: #1e293b;

  background: white;

  transition: 0.2s;

}


.input-wrapper input:focus {

  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(37, 99, 235, 0.10);

}


.input-wrapper input::placeholder {

  color: #94a3b8;

}


.input-icon {

  position: absolute;

  left: 15px;

  z-index: 2;

  font-size: 16px;

}


.currency {

  position: absolute;

  left: 15px;

  z-index: 2;

  font-weight: 800;

  color: #2563eb;

  font-size: 13px;

}


.input-wrapper input[type="number"] {

  padding-left: 48px;

}


.helper-text {

  margin: 7px 0 0;

  font-size: 12px;

  color: #94a3b8;

}


/* ===================================================== */
/* PRICE CARD */
/* ===================================================== */

.price-card {

  background:
    linear-gradient(
      135deg,
      #eff6ff,
      #f8fafc
    );

  border:
    1px solid #bfdbfe;

  border-radius: 14px;

  padding: 18px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  margin-bottom: 22px;

}


.price-left {

  display: flex;

  align-items: center;

  gap: 13px;

}


.price-icon {

  width: 45px;

  height: 45px;

  border-radius: 12px;

  background: #dbeafe;

  display: flex;

  align-items: center;

  justify-content: center;

  font-size: 20px;

}


.price-label {

  margin: 0 0 3px;

  color: #64748b;

  font-size: 12px;

}


.price-card h3 {

  margin: 0;

  color: #1d4ed8;

  font-size: 21px;

  font-weight: 800;

}


.price-info {

  background: #2563eb;

  color: white;

  padding: 5px 10px;

  border-radius: 15px;

  font-size: 10px;

  font-weight: 700;

}


/* ===================================================== */
/* PAYMENT SUMMARY */
/* ===================================================== */

.payment-summary {

  background: #f8fafc;

  border:
    1px solid #e2e8f0;

  border-radius: 14px;

  padding: 20px;

  margin-bottom: 20px;

}


.summary-title {

  font-size: 14px;

  font-weight: 800;

  color: #334155;

  margin-bottom: 17px;

}


.summary-row {

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 20px;

  margin-bottom: 12px;

  font-size: 13px;

}


.summary-row span {

  color: #64748b;

}


.summary-row strong {

  color: #334155;

}


.summary-row .blue {

  color: #2563eb;

}


.summary-row .green {

  color: #16a34a;

}


.summary-line {

  border-top:
    1px dashed #cbd5e1;

  margin:
    15px 0;

}


.return-label {

  font-weight: 700;

  color: #334155 !important;

}


/* ===================================================== */
/* STATUS */
/* ===================================================== */

.status-card {

  border-radius: 14px;

  padding: 17px;

  display: flex;

  align-items: center;

  gap: 13px;

  margin-bottom: 25px;

}


.status-success {

  background: #f0fdf4;

  border:
    1px solid #bbf7d0;

}


.status-warning {

  background: #fffbeb;

  border:
    1px solid #fde68a;

}


.status-icon {

  width: 40px;

  height: 40px;

  border-radius: 50%;

  display: flex;

  align-items: center;

  justify-content: center;

  font-size: 18px;

  font-weight: 900;

}


.icon-success {

  background: #dcfce7;

  color: #16a34a;

}


.icon-warning {

  background: #fef3c7;

  color: #d97706;

}


.status-text {

  display: flex;

  flex-direction: column;

  gap: 3px;

}


.status-text span {

  font-size: 12px;

  color: #64748b;

}


.status-text strong {

  font-size: 16px;

}


.status-success .status-text strong {

  color: #16a34a;

}


.status-warning .status-text strong {

  color: #d97706;

}


/* ===================================================== */
/* BUTTON */
/* ===================================================== */

.button-area {

  display: flex;

  gap: 10px;

}


.save-button {

  flex: 1;

  height: 48px;

  border: none;

  border-radius: 11px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #1d4ed8
    );

  color: white;

  font-size: 14px;

  font-weight: 800;

  cursor: pointer;

  box-shadow:
    0 6px 15px
    rgba(37, 99, 235, 0.20);

  transition: 0.2s;

}


.save-button:hover {

  transform: translateY(-1px);

  box-shadow:
    0 8px 18px
    rgba(37, 99, 235, 0.25);

}


.save-button:disabled {

  background: #94a3b8;

  cursor: not-allowed;

  transform: none;

  box-shadow: none;

}


.cancel-button {

  width: 120px;

  height: 48px;

  border:
    1px solid #cbd5e1;

  border-radius: 11px;

  background: white;

  color: #475569;

  font-size: 14px;

  font-weight: 700;

  cursor: pointer;

}


.cancel-button:hover {

  background: #f8fafc;

}


.cancel-button:disabled {

  opacity: 0.5;

  cursor: not-allowed;

}


/* ===================================================== */
/* SPINNER */
/* ===================================================== */

.small-spinner {

  display: inline-block;

  width: 15px;

  height: 15px;

  border:
    2px solid
    rgba(255,255,255,0.4);

  border-top-color: white;

  border-radius: 50%;

  animation:
    spin 0.7s linear infinite;

  vertical-align: middle;

  margin-right: 5px;

}


@keyframes spin {

  to {

    transform:
      rotate(360deg);

  }

}


/* ===================================================== */
/* FOOTER */
/* ===================================================== */

.footer-text {

  text-align: center;

  margin-top: 20px;

  font-size: 12px;

  color: #94a3b8;

}


/* ===================================================== */
/* RESPONSIVE */
/* ===================================================== */

@media (max-width: 600px) {

  .page {

    padding:
      20px 12px 35px;

  }


  .header-title h1 {

    font-size: 25px;

  }


  .header-title p {

    font-size: 12px;

  }


  .card-header {

    padding: 20px;

  }


  .form-body {

    padding: 20px;

  }


  .error-box {

    margin:
      18px 20px 0;

  }


  .member-badge {

    display: none;

  }


  .price-card {

    align-items: flex-start;

  }


  .price-info {

    display: none;

  }


  .button-area {

    flex-direction: column;

  }


  .cancel-button {

    width: 100%;

  }

}

</style>