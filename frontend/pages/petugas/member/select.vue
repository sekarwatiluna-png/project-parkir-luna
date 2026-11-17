<template>
  <div class="page">

    <aside class="sidebar">

      <div class="logo">
        <div class="logo-icon">🅿️</div>

        <div>
          <h2>ParkirKu</h2>
          <span>Petugas</span>
        </div>
      </div>

      <div class="menu-title">
        MENU UTAMA
      </div>

      <button
        class="menu"
        type="button"
        @click="keDashboard"
      >
        📊 Dashboard
      </button>

      <button
        class="menu active"
        type="button"
        @click="keMember"
      >
        👥 Kelola Member
      </button>

      <button
        class="menu"
        type="button"
        @click="keScanMasuk"
      >
        📥 Scan Masuk
      </button>

      <button
        class="menu"
        type="button"
        @click="keScanKeluar"
      >
        📤 Scan Keluar
      </button>

      <button
        class="menu"
        type="button"
        @click="keTicket"
      >
        🎫 Ticket
      </button>

      <button
        class="menu"
        type="button"
        @click="keLaporan"
      >
        📋 Laporan
      </button>

      <div class="sidebar-bottom">

        <div class="user-box">

          <div class="avatar">
            👮
          </div>

          <div>
            <strong>{{ userName }}</strong>
            <small>Petugas</small>
          </div>

        </div>

        <button
          class="logout"
          type="button"
          @click="logout"
        >
          🚪 Keluar
        </button>

      </div>

    </aside>


    <main class="content">

      <div class="topbar">

        <div>

          <button
            class="back-button"
            type="button"
            @click="keMember"
          >
            ← Kembali
          </button>

          <h1>Edit Member</h1>

          <p>
            Ubah data member parkir.
          </p>

        </div>

        <div class="role-badge">
          👮 Petugas
        </div>

      </div>


      <div
        v-if="loading"
        class="loading"
      >

        <div class="spinner"></div>

        <p>
          Memuat data member...
        </p>

      </div>


      <div
        v-else
        class="form-card"
      >

        <div
          v-if="errorMessage"
          class="alert error-alert"
        >
          ⚠️ {{ errorMessage }}
        </div>


        <div
          v-if="successMessage"
          class="alert success-alert"
        >
          ✅ {{ successMessage }}
        </div>


        <div class="card-title">
          <div class="title-icon">
            ✏️
          </div>

          <div>
            <h2>
              Data Member
            </h2>

            <p>
              Kode member:
              <strong>
                {{ form.kode_member || "-" }}
              </strong>
            </p>
          </div>
        </div>


        <div
          v-if="isLunas"
          class="warning-box"
        >
          🔒 Member ini sudah lunas sehingga datanya tidak dapat diedit.
        </div>


        <div class="form-grid">

          <!-- NAMA -->

          <div class="form-group">

            <label>
              Nama Member
            </label>

            <input
              v-model="form.nama_member"
              type="text"
              placeholder="Masukkan nama member"
              :disabled="isLunas"
            />

          </div>


          <!-- PERUSAHAAN -->

          <div class="form-group">

            <label>
              Nama Perusahaan
            </label>

            <input
              v-model="form.nama_perusahaan"
              type="text"
              placeholder="Masukkan nama perusahaan"
              :disabled="isLunas"
            />

          </div>


          <!-- TOTAL -->

          <div class="form-group">

            <label>
              Total Harga
            </label>

            <input
              v-model.number="form.total_harga"
              type="number"
              min="0"
              :disabled="isLunas"
            />

          </div>


          <!-- BAYAR -->

          <div class="form-group">

            <label>
              Jumlah Bayar
            </label>

            <input
              v-model.number="form.jumlah_bayar"
              type="number"
              min="0"
              :disabled="isLunas"
            />

          </div>


          <!-- STATUS -->

          <div class="form-group">

            <label>
              Status
            </label>

            <select
              v-model="form.status"
              :disabled="isLunas"
            >

              <option value="belum lunas">
                Belum Lunas
              </option>

              <option value="lunas">
                Lunas
              </option>

            </select>

          </div>


          <!-- TANGGAL MULAI -->

          <div class="form-group">

            <label>
              Tanggal Mulai
            </label>

            <input
              v-model="form.tanggal_mulai"
              type="date"
              :disabled="isLunas"
            />

          </div>


          <!-- TANGGAL EXPIRED -->

          <div class="form-group">

            <label>
              Tanggal Expired
            </label>

            <input
              v-model="form.tanggal_expired"
              type="date"
              :disabled="isLunas"
            />

          </div>

        </div>


        <div class="info-box">

          <strong>
            💡 Informasi
          </strong>

          <p>
            Total member bulanan adalah Rp 150.000.
          </p>

          <p>
            Member yang sudah lunas tidak dapat diedit.
          </p>

        </div>


        <div class="form-actions">

          <button
            type="button"
            class="cancel-button"
            @click="keMember"
          >
            Batal
          </button>

          <button
            type="button"
            class="save-button"
            @click="simpan"
          >
            💾 Simpan Perubahan
          </button>

        </div>

      </div>

    </main>

  </div>
</template>


<script setup>

import {
  ref,
  onMounted
} from "vue";


const route =
  useRoute();

const userName =
  ref("Petugas");

const loading =
  ref(true);

const errorMessage =
  ref("");

const successMessage =
  ref("");

const isLunas =
  ref(false);


const form =
  ref({

    id: "",

    kode_member: "",

    token: "",

    nama_member: "",

    nama_perusahaan: "",

    total_harga: 150000,

    jumlah_bayar: 0,

    kembalian: 0,

    status: "belum lunas",

    tanggal_mulai: "",

    tanggal_expired: ""

  });


const API_URL =
  "http://127.0.0.1:8000/api";


/* ================= TOKEN ================= */

const getToken = () => {

  if (!process.client) {
    return "";
  }

  return localStorage.getItem("token") || "";

};


/* ================= USER ================= */

const getUser = () => {

  try {

    const data =
      JSON.parse(
        localStorage.getItem("user") || "{}"
      );

    userName.value =
      data?.name ||
      data?.username ||
      data?.nama ||
      "Petugas";

  } catch {

    userName.value =
      "Petugas";

  }

};


/* ================= FORMAT DATE ================= */

const formatDateInput = (value) => {

  if (!value) {
    return "";
  }

  return String(value).substring(0, 10);

};


/* ================= LOAD ================= */

const loadMember = async () => {

  loading.value = true;

  errorMessage.value = "";

  const id =
    String(route.params.id || "").trim();


  if (!id) {

    errorMessage.value =
      "ID member tidak ditemukan.";

    loading.value = false;

    return;

  }


  try {

    const token =
      getToken();


    const response =
      await $fetch(
        `${API_URL}/members/${id}`,
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
      "DATA EDIT:",
      response
    );


    const data =
      response?.data ||
      response?.member ||
      response;


    if (!data?.id) {

      throw new Error(
        "Data member tidak ditemukan."
      );

    }


    form.value = {

      id:
        data.id,

      kode_member:
        data.kode_member || "",

      token:
        data.token || "",

      nama_member:
        data.nama_member || "",

      nama_perusahaan:
        data.nama_perusahaan || "",

      total_harga:
        Number(data.total_harga || 150000),

      jumlah_bayar:
        Number(data.jumlah_bayar || 0),

      kembalian:
        Number(data.kembalian || 0),

      status:
        String(
          data.status || "belum lunas"
        ).toLowerCase(),

      tanggal_mulai:
        formatDateInput(
          data.tanggal_mulai
        ),

      tanggal_expired:
        formatDateInput(
          data.tanggal_expired
        )

    };


    isLunas.value =
      String(
        data.status || ""
      )
        .toLowerCase()
        .trim()
      === "lunas";


  } catch (error) {

    console.error(
      "ERROR LOAD EDIT:",
      error
    );

    errorMessage.value =
      error?.data?.message ||
      error?.message ||
      "Gagal mengambil data member.";

  } finally {

    loading.value =
      false;

  }

};


/* ================= SIMPAN ================= */

const simpan = async () => {

  errorMessage.value = "";

  successMessage.value = "";


  if (isLunas.value) {

    errorMessage.value =
      "Member yang sudah lunas tidak dapat diedit.";

    return;

  }


  if (
    !form.value.nama_member
      .trim()
  ) {

    errorMessage.value =
      "Nama member wajib diisi.";

    return;

  }


  const total =
    Number(
      form.value.total_harga || 0
    );

  const bayar =
    Number(
      form.value.jumlah_bayar || 0
    );


  if (bayar < 0) {

    errorMessage.value =
      "Jumlah bayar tidak boleh negatif.";

    return;

  }


  if (bayar > total) {

    errorMessage.value =
      "Jumlah bayar tidak boleh lebih besar dari total harga.";

    return;

  }


  const status =
    bayar >= total
      ? "lunas"
      : "belum lunas";


  try {

    const token =
      getToken();

    const id =
      String(
        route.params.id
      );


    await $fetch(
      `${API_URL}/members/${id}`,
      {

        method: "PUT",

        headers: {

          Accept:
            "application/json",

          "Content-Type":
            "application/json",

          ...(token
            ? {
                Authorization:
                  `Bearer ${token}`
              }
            : {})

        },

        body: {

          nama_member:
            form.value.nama_member,

          nama_perusahaan:
            form.value.nama_perusahaan,

          total_harga:
            total,

          jumlah_bayar:
            bayar,

          kembalian:
            Math.max(
              total - bayar,
              0
            ),

          status:
            status,

          tanggal_mulai:
            form.value.tanggal_mulai ||
            null,

          tanggal_expired:
            form.value.tanggal_expired ||
            null

        }

      }
    );


    successMessage.value =
      "Data member berhasil diperbarui.";


    setTimeout(() => {

      navigateTo(
        "/petugas/member/select"
      );

    }, 700);


  } catch (error) {

    console.error(
      "ERROR UPDATE:",
      error
    );

    errorMessage.value =
      error?.data?.message ||
      error?.message ||
      "Gagal menyimpan perubahan.";

  }

};


/* ================= NAVIGASI ================= */

const keDashboard = () => {

  navigateTo(
    "/petugas"
  );

};


const keMember = () => {

  navigateTo(
    "/petugas/member/select"
  );

};


const keScanMasuk = () => {

  navigateTo(
    "/petugas/scan-masuk"
  );

};


const keScanKeluar = () => {

  navigateTo(
    "/petugas/scan-keluar"
  );

};


const keTicket = () => {

  navigateTo(
    "/petugas/transaksi"
  );

};


const keLaporan = () => {

  navigateTo(
    "/petugas/laporan"
  );

};


/* ================= LOGOUT ================= */

const logout = () => {

  localStorage.removeItem(
    "token"
  );

  localStorage.removeItem(
    "user"
  );

  localStorage.removeItem(
    "role"
  );

  navigateTo("/");

};


/* ================= START ================= */

onMounted(() => {

  getUser();

  loadMember();

});

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
      #eff6ff,
      #f8fafc,
      #f5f3ff
    );
  color: #1e293b;
  font-family: Arial, Helvetica, sans-serif;
}


/* SIDEBAR */

.sidebar {
  width: 255px;
  min-height: 100vh;
  padding: 24px 16px;
  background:
    linear-gradient(
      180deg,
      #172554,
      #1e3a8a
    );
  color: white;
  display: flex;
  flex-direction: column;
  position: fixed;
  inset: 0 auto 0 0;
  z-index: 100;
}

.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 10px 28px;
}

.logo-icon {
  width: 45px;
  height: 45px;
  border-radius: 12px;
  background:
    linear-gradient(
      135deg,
      #2563eb,
      #60a5fa
    );
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

.logo h2 {
  margin: 0;
}

.logo span {
  font-size: 11px;
  color: #bfdbfe;
}

.menu-title {
  padding: 10px;
  color: #93c5fd;
  font-size: 10px;
  font-weight: 800;
}

.menu {
  width: 100%;
  border: none;
  background: transparent;
  color: #dbeafe;
  padding: 13px 14px;
  margin-bottom: 5px;
  border-radius: 11px;
  text-align: left;
  cursor: pointer;
  font-weight: 700;
}

.menu:hover,
.menu.active {
  background:
    linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
  color: white;
}

.sidebar-bottom {
  margin-top: auto;
}

.user-box {
  display: flex;
  gap: 10px;
  align-items: center;
  padding: 12px;
  margin-bottom: 10px;
  background:
    rgba(255,255,255,.08);
  border-radius: 12px;
}

.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #dbeafe;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-box strong,
.user-box small {
  display: block;
}

.user-box small {
  color: #bfdbfe;
}

.logout {
  width: 100%;
  padding: 11px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,.15);
  background: rgba(255,255,255,.06);
  color: white;
  cursor: pointer;
}


/* CONTENT */

.content {
  margin-left: 255px;
  width: calc(100% - 255px);
  padding: 30px;
}

.topbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 24px;
}

.back-button {
  border: none;
  background: white;
  padding: 9px 14px;
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 16px;
}

.topbar h1 {
  margin: 0;
  font-size: 29px;
}

.topbar p {
  color: #64748b;
}

.role-badge {
  background: white;
  color: #2563eb;
  padding: 9px 14px;
  border-radius: 20px;
  height: fit-content;
  font-weight: 800;
}


/* FORM */

.form-card {
  max-width: 950px;
  background: white;
  border-radius: 20px;
  padding: 28px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 30px rgba(15,23,42,.06);
}

.card-title {
  display: flex;
  align-items: center;
  gap: 14px;
  padding-bottom: 20px;
  border-bottom: 1px solid #e2e8f0;
  margin-bottom: 22px;
}

.title-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: #dbeafe;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

.card-title h2 {
  margin: 0;
}

.card-title p {
  margin: 5px 0 0;
  color: #64748b;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group label {
  font-weight: 800;
  font-size: 13px;
}

.form-group input,
.form-group select {
  height: 44px;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  padding: 0 12px;
  outline: none;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #2563eb;
}

.form-group input:disabled,
.form-group select:disabled {
  background: #f1f5f9;
  cursor: not-allowed;
}

.warning-box {
  margin-bottom: 20px;
  padding: 13px;
  border-radius: 10px;
  background: #fff7ed;
  color: #c2410c;
}

.info-box {
  margin-top: 22px;
  padding: 15px;
  background: #eff6ff;
  border-radius: 12px;
  color: #1e40af;
}

.info-box p {
  margin: 5px 0;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 25px;
}

.cancel-button,
.save-button {
  padding: 12px 18px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-weight: 800;
}

.cancel-button {
  background: #e2e8f0;
  color: #334155;
}

.save-button {
  background:
    linear-gradient(
      135deg,
      #2563eb,
      #4f46e5
    );
  color: white;
}


/* ALERT */

.alert {
  padding: 13px;
  border-radius: 10px;
  margin-bottom: 15px;
}

.error-alert {
  background: #fef2f2;
  color: #b91c1c;
}

.success-alert {
  background: #f0fdf4;
  color: #15803d;
}


/* LOADING */

.loading {
  min-height: 400px;
  background: white;
  border-radius: 18px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.spinner {
  width: 38px;
  height: 38px;
  border: 4px solid #dbeafe;
  border-top-color: #2563eb;
  border-radius: 50%;
  animation: spin .8s linear infinite;
}

@keyframes spin {

  to {
    transform: rotate(360deg);
  }

}

@media (max-width: 800px) {

  .sidebar {
    width: 210px;
  }

  .content {
    margin-left: 210px;
    width: calc(100% - 210px);
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

}

</style>