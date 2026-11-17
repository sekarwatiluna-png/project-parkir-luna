<template>
  <div class="page">

    <!-- =====================================================
         HEADER
    ====================================================== -->
    <header class="header">

      <button
        type="button"
        class="back-button"
        @click="kembali"
      >
        ← Kembali
      </button>

      <div class="header-title">
        <h1>Edit Pembayaran Member</h1>
        <p>Perbarui pembayaran member Parkir Plaza Andalas.</p>
      </div>

      <div class="role-badge">
        👮 Petugas
      </div>

    </header>


    <!-- =====================================================
         LOADING
    ====================================================== -->
    <div
      v-if="loadingData"
      class="loading-card"
    >
      <div class="spinner"></div>

      <h3>Memuat data member...</h3>

      <p>
        Mohon tunggu sebentar.
      </p>
    </div>


    <!-- =====================================================
         ERROR
    ====================================================== -->
    <div
      v-else-if="errorMessage"
      class="error-card"
    >
      <div class="error-icon">
        ⚠️
      </div>

      <h3>
        Gagal Memuat Data
      </h3>

      <p>
        {{ errorMessage }}
      </p>

      <button
        type="button"
        class="back-main-button"
        @click="kembali"
      >
        Kembali ke Kelola Member
      </button>
    </div>


    <!-- =====================================================
         FORM
    ====================================================== -->
    <main
      v-else
      class="container"
    >

      <!-- ===================================================
           DATA MEMBER
      ==================================================== -->
      <section class="card">

        <div class="card-header">

          <div class="icon-box">
            👤
          </div>

          <div>
            <h2>Data Member</h2>

            <p>
              Informasi member
            </p>
          </div>

        </div>


        <div class="member-grid">

          <!-- KODE -->
          <div class="field">

            <label>
              Kode Member
            </label>

            <div class="readonly-box">
              {{ form.kode_member || "-" }}
            </div>

          </div>


          <!-- TOKEN -->
          <div class="field">

            <label>
              Token
            </label>

            <div class="readonly-box">
              {{ form.token || "-" }}
            </div>

          </div>


          <!-- NAMA -->
          <div class="field">

            <label>
              Nama Member
            </label>

            <div class="readonly-box">
              {{ form.nama_member || "-" }}
            </div>

          </div>


          <!-- PERUSAHAAN -->
          <div class="field">

            <label>
              Nama Perusahaan
            </label>

            <div class="readonly-box">
              {{ form.nama_perusahaan || "-" }}
            </div>

          </div>

        </div>

      </section>


      <!-- ===================================================
           PEMBAYARAN
      ==================================================== -->
      <section class="card payment-card">

        <div class="card-header">

          <div class="icon-box green">
            💰
          </div>

          <div>
            <h2>Pembayaran</h2>

            <p>
              Perbarui pembayaran member
            </p>
          </div>

        </div>


        <!-- ================================================
             TAGIHAN
        ================================================= -->
        <div class="payment-summary">

          <div class="summary-item">

            <span>
              Total Tagihan
            </span>

            <strong>
              Rp {{ formatRupiah(form.total_harga) }}
            </strong>

          </div>


          <div class="summary-item">

            <span>
              Pembayaran Saat Ini
            </span>

            <strong>
              Rp {{ formatRupiah(form.jumlah_bayar) }}
            </strong>

          </div>


          <div class="summary-item">

            <span>
              Kembalian
            </span>

            <strong>
              Rp {{ formatRupiah(kembalian) }}
            </strong>

          </div>

        </div>


        <!-- ================================================
             STATUS
        ================================================= -->
        <div class="status-area">

          <span class="status-label">
            Status Pembayaran
          </span>

          <span
            class="status-badge"
            :class="statusSekarang === 'lunas'
              ? 'status-lunas'
              : 'status-belum'"
          >

            <span class="status-dot"></span>

            {{
              statusSekarang === 'lunas'
                ? 'Lunas'
                : 'Belum Lunas'
            }}

          </span>

        </div>


        <!-- ================================================
             INPUT PEMBAYARAN
        ================================================= -->
        <div class="input-group">

          <label>
            Jumlah Pembayaran
          </label>

          <div class="input-wrapper">

            <span class="currency">
              Rp
            </span>

            <input
              v-model.number="form.jumlah_bayar"
              type="number"
              min="0"
              :max="Number(form.total_harga || 150000)"
              :disabled="wasLunas || saving"
              placeholder="Masukkan jumlah pembayaran"
              @input="hitungPembayaran"
            />

          </div>

          <small>
            Maksimal pembayaran:
            Rp {{ formatRupiah(form.total_harga) }}
          </small>

        </div>


        <!-- ================================================
             KEMBALIAN
        ================================================= -->
        <div class="kembalian-box">

          <div>
            <span>
              Kembalian
            </span>

            <strong>
              Rp {{ formatRupiah(kembalian) }}
            </strong>
          </div>

          <div class="kembalian-icon">
            💵
          </div>

        </div>


        <!-- ================================================
             INFO MEMBER SUDAH LUNAS
        ================================================= -->
        <div
          v-if="wasLunas"
          class="locked-box"
        >

          <span class="locked-icon">
            🔒
          </span>

          <div>
            <strong>
              Member Sudah Lunas
            </strong>

            <p>
              Pembayaran member yang sudah lunas
              tidak dapat diubah lagi.
            </p>
          </div>

        </div>


        <!-- ================================================
             ERROR
        ================================================= -->
        <div
          v-if="paymentError"
          class="payment-error"
        >
          ⚠️ {{ paymentError }}
        </div>


        <!-- ================================================
             TOMBOL
        ================================================= -->
        <div class="button-area">

          <button
            type="button"
            class="cancel-button"
            :disabled="saving"
            @click="kembali"
          >
            Batal
          </button>


          <button
            type="button"
            class="save-button"
            :disabled="saving || wasLunas"
            @click="simpanPembayaran"
          >

            <span v-if="saving">
              Menyimpan...
            </span>

            <span v-else>
              💾 Simpan Pembayaran
            </span>

          </button>

        </div>

      </section>

    </main>

  </div>
</template>


<script setup>
/* =========================================================
   ROUTER
========================================================= */

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()


/* =========================================================
   API
========================================================= */

const API_URL = String(
  config.public?.apiBase ||
  "http://127.0.0.1:8000/api"
).replace(/\/$/, "")


/* =========================================================
   STATE
========================================================= */

const loadingData = ref(true)
const saving = ref(false)

const errorMessage = ref("")
const paymentError = ref("")

/*
 * wasLunas:
 * status MEMBER SAAT PERTAMA KALI halaman dibuka.
 *
 * Kalau sejak awal sudah lunas:
 * - input dikunci
 * - tombol simpan dikunci
 * - tidak bisa diedit lagi
 */
const wasLunas = ref(false)


/* =========================================================
   FORM
========================================================= */

const form = ref({

  id: "",

  kode_member: "",

  token: "",

  nama_member: "",

  nama_perusahaan: "",

  total_harga: 150000,

  jumlah_bayar: 0,

  kembalian: 0,

  status: "belum lunas",

  tanggal_bayar: "",

  tanggal_mulai: "",

  tanggal_expired: ""

})


/* =========================================================
   TOKEN
========================================================= */

function getToken() {

  if (process.client) {

    return (
      localStorage.getItem("token") ||
      localStorage.getItem("access_token") ||
      ""
    )

  }

  return ""

}


/* =========================================================
   HEADERS
========================================================= */

function getHeaders() {

  const token = getToken()

  return {

    "Content-Type": "application/json",

    "Accept": "application/json",

    ...(token
      ? {
          "Authorization": `Bearer ${token}`
        }
      : {})

  }

}


/* =========================================================
   ID MEMBER
========================================================= */

const memberId = computed(() => {

  return route.params.id

})


/* =========================================================
   TOTAL
========================================================= */

const totalHarga = computed(() => {

  return Number(
    form.value.total_harga || 150000
  )

})


/* =========================================================
   JUMLAH BAYAR
========================================================= */

const jumlahBayar = computed(() => {

  return Number(
    form.value.jumlah_bayar || 0
  )

})


/* =========================================================
   KEMBALIAN
========================================================= */

const kembalian = computed(() => {

  const total = totalHarga.value

  const bayar = jumlahBayar.value

  if (bayar <= total) {
    return 0
  }

  return bayar - total

})


/* =========================================================
   STATUS SEKARANG
========================================================= */

const statusSekarang = computed(() => {

  const total = Number(
    form.value.total_harga || 150000
  )

  const bayar = Number(
    form.value.jumlah_bayar || 0
  )

  if (
    String(form.value.status || "")
      .trim()
      .toLowerCase() === "lunas"
  ) {

    return "lunas"

  }

  if (
    total > 0 &&
    bayar >= total
  ) {

    return "lunas"

  }

  return "belum lunas"

})


/* =========================================================
   FORMAT RUPIAH
========================================================= */

function formatRupiah(value) {

  return new Intl.NumberFormat(
    "id-ID"
  ).format(
    Number(value || 0)
  )

}


/* =========================================================
   HITUNG PEMBAYARAN
========================================================= */

function hitungPembayaran() {

  if (wasLunas.value) {
    return
  }

  let bayar = Number(
    form.value.jumlah_bayar || 0
  )

  const total = Number(
    form.value.total_harga || 150000
  )


  /*
   * Tidak boleh minus
   */
  if (bayar < 0) {

    bayar = 0

  }


  /*
   * Tidak boleh lebih dari tagihan.
   *
   * Kalau kamu bayar lebih dari Rp150.000,
   * otomatis dibatasi Rp150.000.
   */
  if (bayar > total) {

    bayar = total

  }


  form.value.jumlah_bayar = bayar

  form.value.kembalian =
    Math.max(
      bayar - total,
      0
    )


  /*
   * Status hanya untuk tampilan saat ini.
   */
  form.value.status =
    bayar >= total
      ? "lunas"
      : "belum lunas"

}


/* =========================================================
   AMBIL DATA MEMBER
========================================================= */

async function loadMember() {

  loadingData.value = true

  errorMessage.value = ""

  paymentError.value = ""


  try {

    if (!memberId.value) {

      throw new Error(
        "ID member tidak ditemukan."
      )

    }


    const response = await $fetch(
      `${API_URL}/members/${memberId.value}`,
      {
        method: "GET",

        headers: getHeaders()
      }
    )


    /*
     * Backend bisa mengirim:
     *
     * {
     *   status: true,
     *   data: {...}
     * }
     *
     * atau langsung data member.
     */
    const data =
      response?.data ||
      response?.member ||
      response


    if (!data?.id) {

      throw new Error(
        response?.message ||
        "Data member tidak ditemukan."
      )

    }


    /*
     * Masukkan data.
     */
    form.value = {

      id: data.id || "",

      kode_member:
        data.kode_member || "",

      token:
        data.token || "",

      nama_member:
        data.nama_member || "",

      nama_perusahaan:
        data.nama_perusahaan || "",

      total_harga:
        Number(
          data.total_harga || 150000
        ),

      jumlah_bayar:
        Number(
          data.jumlah_bayar || 0
        ),

      kembalian:
        Number(
          data.kembalian || 0
        ),

      status:
        String(
          data.status || "belum lunas"
        )
          .trim()
          .toLowerCase(),

      tanggal_bayar:
        data.tanggal_bayar || "",

      tanggal_mulai:
        data.tanggal_mulai || "",

      tanggal_expired:
        data.tanggal_expired || ""

    }


    /*
     * =====================================================
     * PENTING
     *
     * Cek status AWAL.
     * Jangan menggunakan status yang berubah setelah input.
     * =====================================================
     */
    const statusDatabase =
      String(
        data.status || ""
      )
        .trim()
        .toLowerCase()


    const total =
      Number(
        data.total_harga || 150000
      )


    const bayar =
      Number(
        data.jumlah_bayar || 0
      )


    wasLunas.value =
      statusDatabase === "lunas" ||
      (
        total > 0 &&
        bayar >= total
      )


    /*
     * Sinkronkan status tampilan.
     */
    form.value.status =
      wasLunas.value
        ? "lunas"
        : (
            bayar >= total
              ? "lunas"
              : "belum lunas"
          )


  } catch (error) {

    console.error(
      "Gagal mengambil data member:",
      error
    )


    errorMessage.value =
      error?.data?.message ||
      error?.response?.data?.message ||
      error?.message ||
      "Gagal mengambil data member."

  } finally {

    loadingData.value = false

  }

}


/* =========================================================
   SIMPAN PEMBAYARAN
========================================================= */

async function simpanPembayaran() {

  paymentError.value = ""


  /*
   * Member yang dari awal sudah lunas
   * tidak boleh diedit.
   */
  if (wasLunas.value) {

    paymentError.value =
      "Member yang sudah lunas tidak dapat diedit."

    return

  }


  const total =
    Number(
      form.value.total_harga || 150000
    )


  const bayar =
    Number(
      form.value.jumlah_bayar || 0
    )


  /*
   * Validasi pembayaran.
   */
  if (bayar < 0) {

    paymentError.value =
      "Pembayaran tidak boleh kurang dari Rp0."

    return

  }


  if (bayar > total) {

    paymentError.value =
      "Pembayaran tidak boleh lebih dari Rp " +
      formatRupiah(total)

    return

  }


  /*
   * Harus membayar penuh supaya lunas.
   */
  if (bayar < total) {

    paymentError.value =
      "Pembayaran belum mencukupi. " +
      "Minimal Rp " +
      formatRupiah(total) +
      " untuk menjadi lunas."

    return

  }


  if (!memberId.value) {

    paymentError.value =
      "ID member tidak ditemukan."

    return

  }


  saving.value = true


  try {

    /*
     * =====================================================
     * REQUEST KE LARAVEL
     * =====================================================
     *
     * PUT:
     * /api/members/{id}/pembayaran
     *
     * Body:
     * jumlah_bayar
     */
    const response = await $fetch(
      `${API_URL}/members/${memberId.value}/pembayaran`,
      {
        method: "PUT",

        headers: getHeaders(),

        body: {

          jumlah_bayar: bayar

        }

      }
    )


    /*
     * Cek response.
     */
    if (
      response?.status === false
    ) {

      throw new Error(
        response?.message ||
        "Pembayaran gagal disimpan."
      )

    }


    /*
     * Ambil data hasil dari backend.
     */
    const hasil =
      response?.data ||
      {}


    /*
     * Pastikan hasil memang lunas.
     */
    const statusHasil =
      String(
        hasil.status ||
        (
          bayar >= total
            ? "lunas"
            : "belum lunas"
        )
      )
        .trim()
        .toLowerCase()


    if (
      bayar >= total &&
      statusHasil !== "lunas"
    ) {

      throw new Error(
        "Pembayaran tersimpan tetapi status belum menjadi lunas."
      )

    }


    /*
     * Update data lokal.
     */
    form.value.jumlah_bayar =
      Number(
        hasil.jumlah_bayar ?? bayar
      )


    form.value.kembalian =
      Number(
        hasil.kembalian ?? 0
      )


    form.value.status =
      statusHasil


    /*
     * Beri tahu user.
     */
    alert(
      "Pembayaran berhasil diperbarui.\n\n" +
      "Pembayaran: Rp " +
      formatRupiah(bayar) +
      "\n" +
      "Status: LUNAS"
    )


    /*
     * =====================================================
     * KEMBALI KE KELOLA MEMBER
     * =====================================================
     */
    await router.push(
      "/petugas/member"
    )


  } catch (error) {

    console.error(
      "Gagal menyimpan pembayaran:",
      error
    )


    paymentError.value =
      error?.data?.message ||
      error?.response?.data?.message ||
      error?.message ||
      "Gagal menyimpan pembayaran."


  } finally {

    saving.value = false

  }

}


/* =========================================================
   KEMBALI
========================================================= */

function kembali() {

  if (saving.value) {
    return
  }

  router.push(
    "/petugas/member"
  )

}


/* =========================================================
   ON MOUNTED
========================================================= */

onMounted(() => {

  loadMember()

})
</script>


<style scoped>

/* =========================================================
   PAGE
========================================================= */

.page {
  min-height: 100vh;
  background:
    linear-gradient(
      135deg,
      #f8fafc 0%,
      #eef2ff 50%,
      #f8fafc 100%
    );

  padding: 32px;
  font-family:
    Inter,
    Arial,
    sans-serif;
}


/* =========================================================
   HEADER
========================================================= */

.header {
  max-width: 1100px;
  margin: 0 auto 28px;

  display: flex;
  align-items: center;
  gap: 20px;
}


.back-button {
  border: none;
  background: white;

  color: #2563eb;

  font-weight: 700;

  padding: 12px 18px;

  border-radius: 12px;

  cursor: pointer;

  box-shadow:
    0 4px 15px rgba(15, 23, 42, .08);

  transition: .2s;
}


.back-button:hover {
  transform: translateY(-2px);
}


.header-title {
  flex: 1;
}


.header-title h1 {
  margin: 0;

  font-size: 30px;

  font-weight: 800;

  color: #0f172a;
}


.header-title p {
  margin: 5px 0 0;

  color: #64748b;

  font-size: 14px;
}


.role-badge {
  background: #eef2ff;

  color: #4338ca;

  padding: 12px 18px;

  border-radius: 999px;

  font-weight: 700;

  white-space: nowrap;
}


/* =========================================================
   CONTAINER
========================================================= */

.container {
  max-width: 1100px;
  margin: 0 auto;

  display: grid;

  gap: 24px;
}


/* =========================================================
   CARD
========================================================= */

.card {
  background: white;

  border-radius: 22px;

  padding: 28px;

  box-shadow:
    0 15px 45px rgba(15, 23, 42, .08);

  border: 1px solid #e2e8f0;
}


.card-header {
  display: flex;
  align-items: center;

  gap: 14px;

  margin-bottom: 25px;
}


.icon-box {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 14px;

  background: #eef2ff;

  font-size: 23px;
}


.icon-box.green {
  background: #dcfce7;
}


.card-header h2 {
  margin: 0;

  color: #0f172a;

  font-size: 20px;

  font-weight: 800;
}


.card-header p {
  margin: 4px 0 0;

  color: #64748b;

  font-size: 13px;
}


/* =========================================================
   MEMBER GRID
========================================================= */

.member-grid {
  display: grid;

  grid-template-columns:
    repeat(2, minmax(0, 1fr));

  gap: 20px;
}


.field label {
  display: block;

  margin-bottom: 8px;

  color: #475569;

  font-size: 13px;

  font-weight: 700;
}


.readonly-box {
  background: #f8fafc;

  border: 1px solid #e2e8f0;

  border-radius: 12px;

  padding: 13px 15px;

  color: #1e293b;

  font-weight: 600;

  min-height: 46px;

  box-sizing: border-box;
}


/* =========================================================
   PAYMENT SUMMARY
========================================================= */

.payment-summary {
  display: grid;

  grid-template-columns:
    repeat(3, minmax(0, 1fr));

  gap: 15px;

  margin-bottom: 22px;
}


.summary-item {
  background: #f8fafc;

  border: 1px solid #e2e8f0;

  border-radius: 14px;

  padding: 17px;
}


.summary-item span {
  display: block;

  color: #64748b;

  font-size: 13px;

  margin-bottom: 7px;
}


.summary-item strong {
  display: block;

  color: #0f172a;

  font-size: 18px;

  font-weight: 800;
}


/* =========================================================
   STATUS
========================================================= */

.status-area {
  display: flex;

  align-items: center;

  justify-content: space-between;

  padding: 17px;

  background: #f8fafc;

  border-radius: 14px;

  margin-bottom: 24px;
}


.status-label {
  color: #475569;

  font-weight: 700;
}


.status-badge {
  display: inline-flex;

  align-items: center;

  gap: 7px;

  padding: 8px 14px;

  border-radius: 999px;

  font-size: 13px;

  font-weight: 800;
}


.status-lunas {
  background: #dcfce7;

  color: #15803d;
}


.status-belum {
  background: #fef3c7;

  color: #b45309;
}


.status-dot {
  width: 8px;
  height: 8px;

  border-radius: 50%;

  background: currentColor;
}


/* =========================================================
   INPUT
========================================================= */

.input-group {
  margin-bottom: 20px;
}


.input-group label {
  display: block;

  color: #334155;

  font-size: 14px;

  font-weight: 800;

  margin-bottom: 9px;
}


.input-wrapper {
  position: relative;
}


.currency {
  position: absolute;

  left: 16px;

  top: 50%;

  transform: translateY(-50%);

  color: #64748b;

  font-weight: 800;

  pointer-events: none;
}


.input-wrapper input {
  width: 100%;

  box-sizing: border-box;

  padding:
    15px
    15px
    15px
    48px;

  border: 1px solid #cbd5e1;

  border-radius: 13px;

  outline: none;

  font-size: 16px;

  font-weight: 700;

  color: #0f172a;

  transition: .2s;
}


.input-wrapper input:focus {
  border-color: #22c55e;

  box-shadow:
    0 0 0 3px rgba(34, 197, 94, .12);
}


.input-wrapper input:disabled {
  background: #f1f5f9;

  cursor: not-allowed;

  color: #64748b;
}


.input-group small {
  display: block;

  margin-top: 7px;

  color: #64748b;

  font-size: 12px;
}


/* =========================================================
   KEMBALIAN
========================================================= */

.kembalian-box {
  display: flex;

  align-items: center;

  justify-content: space-between;

  background:
    linear-gradient(
      135deg,
      #eff6ff,
      #ecfdf5
    );

  border: 1px solid #bfdbfe;

  border-radius: 15px;

  padding: 18px;

  margin-bottom: 20px;
}


.kembalian-box span {
  display: block;

  color: #64748b;

  font-size: 13px;

  margin-bottom: 5px;
}


.kembalian-box strong {
  color: #166534;

  font-size: 22px;

  font-weight: 900;
}


.kembalian-icon {
  font-size: 28px;
}


/* =========================================================
   LOCKED
========================================================= */

.locked-box {
  display: flex;

  align-items: flex-start;

  gap: 12px;

  padding: 15px;

  border-radius: 13px;

  background: #f1f5f9;

  border: 1px solid #cbd5e1;

  margin-bottom: 20px;
}


.locked-icon {
  font-size: 21px;
}


.locked-box strong {
  display: block;

  color: #334155;

  margin-bottom: 4px;
}


.locked-box p {
  margin: 0;

  color: #64748b;

  font-size: 13px;
}


/* =========================================================
   ERROR
========================================================= */

.payment-error {
  padding: 13px 15px;

  border-radius: 12px;

  background: #fef2f2;

  color: #b91c1c;

  border: 1px solid #fecaca;

  font-size: 13px;

  font-weight: 700;

  margin-bottom: 20px;
}


/* =========================================================
   BUTTON
========================================================= */

.button-area {
  display: grid;

  grid-template-columns:
    1fr 2fr;

  gap: 12px;

  margin-top: 25px;
}


.cancel-button,
.save-button {
  border: none;

  border-radius: 13px;

  padding: 14px;

  font-weight: 800;

  font-size: 14px;

  cursor: pointer;

  transition: .2s;
}


.cancel-button {
  background: #64748b;

  color: white;
}


.cancel-button:hover {
  background: #475569;
}


.save-button {
  background:
    linear-gradient(
      135deg,
      #16a34a,
      #22c55e
    );

  color: white;

  box-shadow:
    0 8px 20px rgba(34, 197, 94, .22);
}


.save-button:hover:not(:disabled) {
  transform: translateY(-2px);
}


.save-button:disabled,
.cancel-button:disabled {
  opacity: .55;

  cursor: not-allowed;

  transform: none;
}


/* =========================================================
   LOADING
========================================================= */

.loading-card {
  max-width: 600px;

  margin: 80px auto;

  background: white;

  padding: 50px;

  text-align: center;

  border-radius: 22px;

  box-shadow:
    0 15px 45px rgba(15, 23, 42, .08);
}


.spinner {
  width: 42px;
  height: 42px;

  margin: 0 auto 20px;

  border:
    4px solid #e2e8f0;

  border-top-color: #2563eb;

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
  margin: 0 0 7px;

  color: #0f172a;
}


.loading-card p {
  margin: 0;

  color: #64748b;
}


/* =========================================================
   ERROR CARD
========================================================= */

.error-card {
  max-width: 600px;

  margin: 80px auto;

  background: white;

  padding: 45px;

  text-align: center;

  border-radius: 22px;

  box-shadow:
    0 15px 45px rgba(15, 23, 42, .08);
}


.error-icon {
  font-size: 45px;

  margin-bottom: 15px;
}


.error-card h3 {
  margin: 0 0 8px;

  color: #0f172a;
}


.error-card p {
  color: #64748b;

  margin-bottom: 25px;
}


.back-main-button {
  border: none;

  background: #2563eb;

  color: white;

  padding: 13px 20px;

  border-radius: 12px;

  font-weight: 800;

  cursor: pointer;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 800px) {

  .page {
    padding: 18px;
  }


  .header {
    flex-wrap: wrap;
  }


  .role-badge {
    display: none;
  }


  .member-grid,
  .payment-summary {
    grid-template-columns: 1fr;
  }


  .button-area {
    grid-template-columns: 1fr;
  }

}


@media (max-width: 500px) {

  .header-title h1 {
    font-size: 23px;
  }


  .card {
    padding: 20px;
  }


  .status-area {
    flex-direction: column;

    align-items: flex-start;

    gap: 10px;
  }

}

</style>