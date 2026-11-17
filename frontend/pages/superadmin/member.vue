<template>
  <div class="page">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar">

      <div class="brand">
        <div class="brand-icon">
          P
        </div>

        <div>
          <h1>ParkirKu</h1>
          <p>Parkir Plaza Andalas</p>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-avatar">
          👑
        </div>

        <div>
          <strong>Super Admin</strong>
          <span>{{ userName }}</span>
        </div>
      </div>

      <nav class="menu">

        <NuxtLink
          to="/superadmin"
          class="menu-item"
        >
          <span>🏠</span>
          <span>Dashboard</span>
        </NuxtLink>

        <NuxtLink
          to="/superadmin/member"
          class="menu-item active"
        >
          <span>👨‍💼</span>
          <span>Kelola Petugas</span>
        </NuxtLink>

        <NuxtLink
          to="/superadmin/laporan"
          class="menu-item"
        >
          <span>📊</span>
          <span>Laporan</span>
        </NuxtLink>

      </nav>

      <button
        class="logout-button"
        @click="logout"
      >
        <span>🚪</span>
        <span>Keluar</span>
      </button>

    </aside>


    <!-- =====================================================
         MAIN
    ====================================================== -->
    <main class="main">

      <!-- HEADER -->
      <header class="header">

        <div>
          <h2>Kelola Petugas</h2>
          <p>Kelola akun petugas Parkir Plaza Andalas</p>
        </div>

        <button
          class="add-button"
          @click="openAddModal"
        >
          <span>＋</span>
          Tambah Petugas
        </button>

      </header>


      <!-- =====================================================
           STATISTICS
      ====================================================== -->
      <section class="stats">

        <div class="stat-card">

          <div class="stat-icon blue">
            👨‍💼
          </div>

          <div>
            <span>Total Petugas</span>
            <strong>{{ totalPetugas }}</strong>
          </div>

        </div>

      </section>


      <!-- =====================================================
           DATA
      ====================================================== -->
      <section class="content-card">

        <div class="content-header">

          <div>
            <h3>Daftar Petugas</h3>
            <p>Data akun petugas sistem</p>
          </div>

          <div class="search-box">

            <span>🔍</span>

            <input
              v-model="search"
              type="text"
              placeholder="Cari petugas..."
            >

          </div>

        </div>


        <!-- LOADING -->
        <div
          v-if="loading"
          class="loading"
        >
          <div class="spinner"></div>
          <p>Memuat data petugas...</p>
        </div>


        <!-- ERROR -->
        <div
          v-else-if="error"
          class="error-box"
        >

          <div>
            <strong>Terjadi kesalahan</strong>
            <p>{{ error }}</p>
          </div>

          <button @click="loadPetugas">
            Coba Lagi
          </button>

        </div>


        <!-- EMPTY -->
        <div
          v-else-if="filteredPetugas.length === 0"
          class="empty"
        >

          <div class="empty-icon">
            👨‍💼
          </div>

          <h4>
            {{ search ? 'Petugas tidak ditemukan' : 'Belum ada petugas' }}
          </h4>

          <p>
            {{
              search
                ? 'Coba gunakan kata pencarian lain.'
                : 'Tambahkan akun petugas baru.'
            }}
          </p>

          <button
            v-if="!search"
            class="add-small"
            @click="openAddModal"
          >
            ＋ Tambah Petugas
          </button>

        </div>


        <!-- TABLE -->
        <div
          v-else
          class="table-wrapper"
        >

          <table>

            <thead>

              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Aksi</th>
              </tr>

            </thead>

            <tbody>

              <tr
                v-for="(item, index) in filteredPetugas"
                :key="item.id"
              >

                <td>
                  {{ index + 1 }}
                </td>

                <td>

                  <div class="user-cell">

                    <div class="user-avatar">
                      {{ getInitial(item.name) }}
                    </div>

                    <div>
                      <strong>
                        {{ item.name || '-' }}
                      </strong>

                      <small>
                        ID #{{ item.id }}
                      </small>
                    </div>

                  </div>

                </td>

                <td>
                  <span class="username">
                    {{ item.username || '-' }}
                  </span>
                </td>

                <td>
                  {{ item.email || '-' }}
                </td>

                <td>
                  {{ item.no_telepon || '-' }}
                </td>

                <td>

                  <div class="actions">

                    <button
                      class="action edit"
                      title="Edit"
                      @click="openEditModal(item)"
                    >
                      ✏️
                    </button>

                    <button
                      class="action delete"
                      title="Hapus"
                      @click="deletePetugas(item)"
                    >
                      🗑️
                    </button>

                  </div>

                </td>

              </tr>

            </tbody>

          </table>

        </div>

      </section>

    </main>


    <!-- =====================================================
         MODAL TAMBAH / EDIT
    ====================================================== -->
    <div
      v-if="showModal"
      class="modal-overlay"
      @click.self="closeModal"
    >

      <div class="modal">

        <div class="modal-header">

          <div>
            <h3>
              {{ editMode ? 'Edit Petugas' : 'Tambah Petugas' }}
            </h3>

            <p>
              {{
                editMode
                  ? 'Ubah data akun petugas'
                  : 'Buat akun petugas baru'
              }}
            </p>
          </div>

          <button
            class="close-button"
            @click="closeModal"
          >
            ×
          </button>

        </div>


        <form
          class="form"
          @submit.prevent="savePetugas"
        >

          <!-- NAMA -->
          <div class="form-group">

            <label>
              Nama Lengkap
            </label>

            <input
              v-model="form.name"
              type="text"
              placeholder="Contoh: Petugas Parkir"
              required
            >

          </div>


          <!-- USERNAME -->
          <div class="form-group">

            <label>
              Username
            </label>

            <input
              v-model="form.username"
              type="text"
              placeholder="Contoh: petugas01"
              required
            >

          </div>


          <!-- EMAIL -->
          <div class="form-group">

            <label>
              Email
            </label>

            <input
              v-model="form.email"
              type="email"
              placeholder="Contoh: petugas@gmail.com"
              required
            >

          </div>


          <!-- TELEPON -->
          <div class="form-group">

            <label>
              No. Telepon
            </label>

            <input
              v-model="form.no_telepon"
              type="text"
              placeholder="Contoh: 081234567890"
            >

          </div>


          <!-- PASSWORD -->
          <div class="form-group">

            <label>
              Password
              <span v-if="editMode">
                (kosongkan jika tidak diubah)
              </span>
            </label>

            <input
              v-model="form.password"
              type="password"
              :placeholder="
                editMode
                  ? 'Kosongkan jika tidak diubah'
                  : 'Minimal 6 karakter'
              "
              :required="!editMode"
            >

          </div>


          <!-- ERROR -->
          <div
            v-if="formError"
            class="form-error"
          >
            {{ formError }}
          </div>


          <!-- BUTTON -->
          <div class="modal-actions">

            <button
              type="button"
              class="cancel-button"
              @click="closeModal"
              :disabled="saving"
            >
              Batal
            </button>

            <button
              type="submit"
              class="save-button"
              :disabled="saving"
            >

              <span v-if="saving">
                Menyimpan...
              </span>

              <span v-else>
                {{ editMode ? 'Simpan Perubahan' : 'Tambah Petugas' }}
              </span>

            </button>

          </div>

        </form>

      </div>

    </div>


    <!-- =====================================================
         TOAST
    ====================================================== -->
    <div
      v-if="toast.show"
      class="toast"
      :class="toast.type"
    >

      <span>
        {{ toast.type === 'success' ? '✓' : '!' }}
      </span>

      <div>
        <strong>
          {{ toast.type === 'success' ? 'Berhasil' : 'Gagal' }}
        </strong>

        <p>
          {{ toast.message }}
        </p>
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


/* ==========================================================
   API
========================================================== */

const API_URL =
  'http://127.0.0.1:8000/api'


/* ==========================================================
   STATE
========================================================== */

const petugas =
  ref([])

const loading =
  ref(false)

const error =
  ref('')

const search =
  ref('')

const userName =
  ref('Superadmin')

const showModal =
  ref(false)

const editMode =
  ref(false)

const editId =
  ref(null)

const saving =
  ref(false)

const formError =
  ref('')


/* ==========================================================
   FORM
========================================================== */

const form = ref({

  name: '',

  username: '',

  email: '',

  no_telepon: '',

  password: '',

  role: 'petugas'

})


/* ==========================================================
   TOAST
========================================================== */

const toast = ref({

  show: false,

  type: 'success',

  message: ''

})


let toastTimer = null


function showToast(
  message,
  type = 'success'
) {

  toast.value = {

    show: true,

    type,

    message

  }

  clearTimeout(
    toastTimer
  )

  toastTimer =
    setTimeout(() => {

      toast.value.show =
        false

    }, 3000)

}


/* ==========================================================
   TOKEN
========================================================== */

function getToken() {

  if (!process.client) {
    return ''
  }

  return (
    localStorage.getItem('token') ||
    ''
  )

}


/* ==========================================================
   LOAD USER
========================================================== */

function loadCurrentUser() {

  if (!process.client) {
    return
  }

  try {

    const user =
      JSON.parse(
        localStorage.getItem('user') ||
        'null'
      )

    if (user) {

      userName.value =
        user.name ||
        user.username ||
        'Superadmin'

    }

  } catch (e) {

    console.error(
      'Gagal membaca user:',
      e
    )

  }

}


/* ==========================================================
   LOAD PETUGAS
========================================================== */

async function loadPetugas() {

  loading.value = true

  error.value = ''

  try {

    const token =
      getToken()

    const response =
      await $fetch(
        `${API_URL}/petugas`,
        {

          method: 'GET',

          headers: {

            Accept:
              'application/json',

            ...(token
              ? {
                  Authorization:
                    `Bearer ${token}`
                }
              : {})

          }

        }
      )


    console.log(
      'RESPON PETUGAS:',
      response
    )


    if (
      Array.isArray(response)
    ) {

      petugas.value =
        response

    } else if (
      response &&
      Array.isArray(
        response.data
      )
    ) {

      petugas.value =
        response.data

    } else if (
      response &&
      Array.isArray(
        response.petugas
      )
    ) {

      petugas.value =
        response.petugas

    } else {

      petugas.value =
        []

    }


    /*
     * Hanya role petugas.
     */

    petugas.value =
      petugas.value.filter(
        item =>
          String(
            item.role || ''
          )
            .toLowerCase() ===
          'petugas'
      )

  } catch (err) {

    console.error(
      'ERROR LOAD PETUGAS:',
      err
    )


    if (
      err?.status === 401
    ) {

      error.value =
        'Token login sudah tidak valid. Silakan login kembali.'

    } else if (
      err?.status === 403
    ) {

      error.value =
        'Kamu tidak memiliki akses untuk melihat data petugas.'

    } else if (
      err?.status === 404
    ) {

      error.value =
        'Endpoint /api/petugas tidak ditemukan.'

    } else {

      error.value =
        err?.data?.message ||
        err?.message ||
        'Gagal mengambil data petugas.'

    }

  } finally {

    loading.value =
      false

  }

}


/* ==========================================================
   COMPUTED
========================================================== */

const filteredPetugas =
  computed(() => {

    const keyword =
      search.value
        .trim()
        .toLowerCase()


    if (!keyword) {

      return petugas.value

    }


    return petugas.value.filter(
      item => {

        return [

          item.name,

          item.username,

          item.email,

          item.no_telepon

        ]
          .filter(Boolean)
          .some(
            value =>
              String(value)
                .toLowerCase()
                .includes(
                  keyword
                )
          )

      }
    )

  })


const totalPetugas =
  computed(() => {

    return petugas.value.length

  })


/* ==========================================================
   INITIAL
========================================================== */

function getInitial(name) {

  if (!name) {
    return 'P'
  }

  return String(name)
    .trim()
    .charAt(0)
    .toUpperCase()

}


/* ==========================================================
   RESET FORM
========================================================== */

function resetForm() {

  form.value = {

    name: '',

    username: '',

    email: '',

    no_telepon: '',

    password: '',

    role: 'petugas'

  }

  formError.value =
    ''

  editId.value =
    null

}


/* ==========================================================
   TAMBAH
========================================================== */

function openAddModal() {

  editMode.value =
    false

  resetForm()

  showModal.value =
    true

}


/* ==========================================================
   EDIT
========================================================== */

function openEditModal(item) {

  editMode.value =
    true

  editId.value =
    item.id

  form.value = {

    name:
      item.name || '',

    username:
      item.username || '',

    email:
      item.email || '',

    no_telepon:
      item.no_telepon || '',

    password:
      '',

    role:
      'petugas'

  }

  formError.value =
    ''

  showModal.value =
    true

}


/* ==========================================================
   CLOSE MODAL
========================================================== */

function closeModal() {

  if (saving.value) {
    return
  }

  showModal.value =
    false

  editMode.value =
    false

  resetForm()

}


/* ==========================================================
   NORMALISASI EMAIL
========================================================== */

function normalizeEmail(value) {

  return String(
    value || ''
  )
    .trim()
    .toLowerCase()

}


/* ==========================================================
   NORMALISASI NO TELEPON
========================================================== */

function normalizePhone(value) {

  return String(
    value || ''
  )
    .replace(/\D/g, '')

}


/* ==========================================================
   CEK EMAIL DUPLIKAT
========================================================== */

function isEmailDuplicate(
  email
) {

  const currentEmail =
    normalizeEmail(
      email
    )

  if (!currentEmail) {
    return false
  }


  return petugas.value.some(
    item => {

      /*
       * Saat EDIT, jangan bandingkan
       * dengan data dirinya sendiri.
       */

      if (
        editMode.value &&
        Number(item.id) ===
        Number(editId.value)
      ) {

        return false

      }


      const existingEmail =
        normalizeEmail(
          item.email
        )


      return (
        existingEmail ===
        currentEmail
      )

    }
  )

}


/* ==========================================================
   CEK NO TELEPON DUPLIKAT
========================================================== */

function isPhoneDuplicate(
  phone
) {

  const currentPhone =
    normalizePhone(
      phone
    )

  /*
   * Kalau nomor kosong,
   * tidak dianggap duplikat.
   */

  if (!currentPhone) {
    return false
  }


  return petugas.value.some(
    item => {

      /*
       * Saat EDIT, jangan bandingkan
       * dengan data dirinya sendiri.
       */

      if (
        editMode.value &&
        Number(item.id) ===
        Number(editId.value)
      ) {

        return false

      }


      const existingPhone =
        normalizePhone(
          item.no_telepon
        )


      /*
       * Nomor yang kosong tidak
       * dibandingkan.
       */

      if (!existingPhone) {
        return false
      }


      return (
        existingPhone ===
        currentPhone
      )

    }
  )

}


/* ==========================================================
   VALIDASI DUPLIKAT
========================================================== */

function validateDuplicate() {

  /*
   * EMAIL
   * Hanya ditolak kalau email
   * sama dengan petugas lain.
   */

  if (
    isEmailDuplicate(
      form.value.email
    )
  ) {

    formError.value =
      'Email/Gmail ini sudah digunakan oleh petugas lain.'

    return false

  }


  /*
   * NO TELEPON
   * Hanya ditolak kalau nomor
   * sama dengan petugas lain.
   */

  if (
    isPhoneDuplicate(
      form.value.no_telepon
    )
  ) {

    formError.value =
      'No. telepon ini sudah digunakan oleh petugas lain.'

    return false

  }


  /*
   * Kalau email berbeda DAN
   * nomor telepon berbeda,
   * maka boleh disimpan.
   */

  return true

}


/* ==========================================================
   SAVE PETUGAS
========================================================== */

async function savePetugas() {

  formError.value =
    ''

  saving.value =
    true


  try {

    const token =
      getToken()


    if (!token) {

      formError.value =
        'Token login tidak ditemukan.'

      return

    }


    /*
     * VALIDASI DUPLIKAT
     *
     * Email sama  -> TOLAK
     * Telepon sama -> TOLAK
     * Email beda + telepon beda -> BOLEH
     */

    if (
      !validateDuplicate()
    ) {

      return

    }


    const payload = {

      name:
        form.value.name
          .trim(),

      username:
        form.value.username
          .trim(),

      email:
        form.value.email
          .trim(),

      no_telepon:
        form.value.no_telepon
          .trim(),

      role:
        'petugas'

    }


    /*
     * Password:
     *
     * TAMBAH -> wajib dikirim
     * EDIT   -> hanya dikirim jika diisi
     */

    if (
      !editMode.value ||
      form.value.password
        .trim()
    ) {

      payload.password =
        form.value.password

    }


    let response


    /* ======================================================
       EDIT
    ====================================================== */

    if (
      editMode.value
    ) {

      response =
        await $fetch(
          `${API_URL}/petugas/${editId.value}`,
          {

            method: 'PUT',

            headers: {

              Accept:
                'application/json',

              'Content-Type':
                'application/json',

              Authorization:
                `Bearer ${token}`

            },

            body:
              payload

          }
        )

    }


    /* ======================================================
       TAMBAH
    ====================================================== */

    else {

      response =
        await $fetch(
          `${API_URL}/petugas`,
          {

            method: 'POST',

            headers: {

              Accept:
                'application/json',

              'Content-Type':
                'application/json',

              Authorization:
                `Bearer ${token}`

            },

            body:
              payload

          }
        )

    }


    console.log(
      'RESPON SAVE PETUGAS:',
      response
    )


    showModal.value =
      false


    showToast(
      editMode.value
        ? 'Data petugas berhasil diperbarui.'
        : 'Petugas berhasil ditambahkan.',
      'success'
    )


    editMode.value =
      false

    resetForm()


    await loadPetugas()


  } catch (err) {

    console.error(
      'ERROR SAVE PETUGAS:',
      err
    )


    /*
     * Laravel validation error
     */

    if (
      err?.status === 422 &&
      err?.data?.errors
    ) {

      const errors =
        err.data.errors

      const firstKey =
        Object.keys(
          errors
        )[0]


      if (
        firstKey &&
        errors[firstKey]?.length
      ) {

        formError.value =
          errors[firstKey][0]

      } else {

        formError.value =
          'Data yang dimasukkan tidak valid.'

      }

    } else {

      formError.value =
        err?.data?.message ||
        err?.message ||
        'Gagal menyimpan data petugas.'

    }

  } finally {

    saving.value =
      false

  }

}


/* ==========================================================
   DELETE
========================================================== */

async function deletePetugas(
  item
) {

  const konfirmasi =
    window.confirm(
      `Yakin ingin menghapus petugas "${item.name}"?`
    )


  if (!konfirmasi) {
    return
  }


  const token =
    getToken()


  if (!token) {

    showToast(
      'Token login tidak ditemukan.',
      'error'
    )

    return

  }


  try {

    await $fetch(
      `${API_URL}/petugas/${item.id}`,
      {

        method:
          'DELETE',

        headers: {

          Accept:
            'application/json',

          Authorization:
            `Bearer ${token}`

        }

      }
    )


    showToast(
      'Petugas berhasil dihapus.',
      'success'
    )


    await loadPetugas()


  } catch (err) {

    console.error(
      'ERROR DELETE PETUGAS:',
      err
    )


    showToast(
      err?.data?.message ||
      err?.message ||
      'Gagal menghapus petugas.',
      'error'
    )

  }

}


/* ==========================================================
   LOGOUT
========================================================== */

function logout() {

  if (!process.client) {
    return
  }


  localStorage.removeItem(
    'token'
  )

  localStorage.removeItem(
    'user'
  )

  localStorage.removeItem(
    'role'
  )


  navigateTo('/')

}


/* ==========================================================
   ON MOUNTED
========================================================== */

onMounted(() => {

  loadCurrentUser()

  loadPetugas()

})

</script>


<style scoped>

/* ==========================================================
   PAGE
========================================================== */

* {
  box-sizing: border-box;
}


.page {
  min-height: 100vh;

  display: flex;

  background:
    linear-gradient(
      135deg,
      #f7f8ff 0%,
      #f3f0ff 50%,
      #fff5fb 100%
    );

  color: #172554;

  font-family:
    Georgia,
    'Times New Roman',
    serif;
}


/* ==========================================================
   SIDEBAR
========================================================== */

.sidebar {
  width: 290px;

  min-height: 100vh;

  position: fixed;

  left: 0;
  top: 0;
  bottom: 0;

  padding: 30px 20px;

  display: flex;

  flex-direction: column;

  background:
    linear-gradient(
      180deg,
      #172554 0%,
      #312e81 52%,
      #581c87 100%
    );

  color: white;

  box-shadow:
    8px 0 30px rgba(30, 41, 59, .12);

  z-index: 20;
}


.brand {
  display: flex;

  align-items: center;

  gap: 14px;

  padding: 4px 10px 30px;
}


.brand-icon {
  width: 50px;
  height: 50px;

  border-radius: 15px;

  display: flex;

  align-items: center;
  justify-content: center;

  background:
    linear-gradient(
      135deg,
      #60a5fa,
      #a78bfa
    );

  font-size: 26px;

  font-weight: bold;

  box-shadow:
    0 8px 20px rgba(0,0,0,.2);
}


.brand h1 {
  margin: 0;

  font-size: 24px;

  color: white;
}


.brand p {
  margin: 3px 0 0;

  font-size: 12px;

  color: #dbeafe;
}


.admin-card {
  display: flex;

  align-items: center;

  gap: 12px;

  padding: 15px;

  margin-bottom: 25px;

  border-radius: 17px;

  background:
    rgba(255,255,255,.12);

  border:
    1px solid rgba(255,255,255,.08);
}


.admin-avatar {
  width: 45px;
  height: 45px;

  border-radius: 13px;

  display: flex;

  align-items: center;
  justify-content: center;

  background:
    rgba(255,255,255,.15);

  font-size: 21px;
}


.admin-card strong {
  display: block;

  color: white;

  font-size: 14px;
}


.admin-card span {
  display: block;

  margin-top: 3px;

  color: #c7d2fe;

  font-size: 12px;
}


.menu {
  display: flex;

  flex-direction: column;

  gap: 8px;
}


.menu-item {
  display: flex;

  align-items: center;

  gap: 13px;

  padding: 14px 16px;

  border-radius: 15px;

  color: #e0e7ff;

  text-decoration: none;

  font-size: 15px;

  transition:
    .2s ease;
}


.menu-item:hover {
  background:
    rgba(255,255,255,.10);

  color: white;

  transform:
    translateX(3px);
}


.menu-item.active {
  background:
    rgba(255,255,255,.18);

  color: white;

  box-shadow:
    inset 3px 0 0 white;
}


.menu-item span:first-child {
  width: 24px;

  text-align: center;

  font-size: 17px;
}


.logout-button {
  margin-top: auto;

  border: none;

  cursor: pointer;

  display: flex;

  align-items: center;

  gap: 13px;

  padding: 15px 17px;

  border-radius: 15px;

  background:
    rgba(255,255,255,.10);

  color: white;

  font-family: inherit;

  font-size: 15px;

  text-align: left;

  transition: .2s;
}


.logout-button:hover {
  background:
    rgba(255,255,255,.18);
}


/* ==========================================================
   MAIN
========================================================== */

.main {
  width:
    calc(100% - 290px);

  margin-left:
    290px;

  min-height: 100vh;

  padding:
    38px 32px 60px;
}


.header {
  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 20px;

  margin-bottom: 28px;
}


.header h2 {
  margin: 0;

  font-size: 32px;

  color: #172554;
}


.header p {
  margin: 7px 0 0;

  color: #64748b;

  font-size: 15px;
}


.add-button {
  border: none;

  cursor: pointer;

  padding:
    15px 22px;

  border-radius: 15px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  color: white;

  font-family: inherit;

  font-weight: bold;

  font-size: 15px;

  box-shadow:
    0 10px 22px
    rgba(79,70,229,.22);

  transition: .2s;
}


.add-button:hover {
  transform:
    translateY(-2px);

  box-shadow:
    0 13px 28px
    rgba(79,70,229,.30);
}


.add-button span {
  font-size: 19px;
}


/* ==========================================================
   STATS
========================================================== */

.stats {
  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 20px;

  margin-bottom: 25px;
}


.stat-card {
  min-height: 105px;

  display: flex;

  align-items: center;

  gap: 16px;

  padding: 22px;

  background:
    rgba(255,255,255,.88);

  border:
    1px solid
    rgba(226,232,240,.8);

  border-radius: 20px;

  box-shadow:
    0 10px 35px
    rgba(51,65,85,.06);
}


.stat-icon {
  width: 55px;
  height: 55px;

  flex-shrink: 0;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 16px;

  font-size: 23px;

  font-family:
    Arial,
    sans-serif;
}


.stat-icon.blue {
  background:
    #dbeafe;
}


.stat-icon.green {
  background:
    #dcfce7;
}


.stat-icon.red {
  background:
    #fee2e2;
}


.stat-card span {
  display: block;

  color: #64748b;

  font-size: 13px;

  margin-bottom: 5px;
}


.stat-card strong {
  font-size: 25px;

  color: #0f172a;
}


/* ==========================================================
   CONTENT
========================================================== */

.content-card {
  background:
    rgba(255,255,255,.92);

  border:
    1px solid
    rgba(226,232,240,.8);

  border-radius: 22px;

  box-shadow:
    0 12px 40px
    rgba(51,65,85,.07);

  overflow: hidden;
}


.content-header {
  min-height: 92px;

  padding: 22px;

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 20px;

  border-bottom:
    1px solid
    #e2e8f0;
}


.content-header h3 {
  margin: 0;

  color: #172554;

  font-size: 18px;
}


.content-header p {
  margin: 5px 0 0;

  color: #64748b;

  font-size: 13px;
}


.search-box {
  width: 300px;

  height: 46px;

  display: flex;

  align-items: center;

  gap: 10px;

  padding:
    0 15px;

  border:
    1px solid
    #dbe2ea;

  border-radius: 13px;

  background:
    white;
}


.search-box span {
  font-size: 16px;
}


.search-box input {
  width: 100%;

  border: none;

  outline: none;

  background:
    transparent;

  font-family: inherit;

  font-size: 14px;

  color: #172554;
}


.search-box input::placeholder {
  color: #94a3b8;
}


/* ==========================================================
   TABLE
========================================================== */

.table-wrapper {
  width: 100%;

  overflow-x: auto;
}


table {
  width: 100%;

  border-collapse:
    collapse;

  min-width:
    950px;
}


thead {
  background:
    #f8fafc;
}


th {
  padding:
    15px 17px;

  text-align:
    left;

  color:
    #64748b;

  font-size:
    12px;

  font-weight:
    bold;

  text-transform:
    uppercase;

  letter-spacing:
    .3px;
}


td {
  padding:
    17px;

  border-top:
    1px solid
    #eef2f7;

  color:
    #475569;

  font-size:
    13px;

  vertical-align:
    middle;
}


tbody tr {
  transition:
    .15s;
}


tbody tr:hover {
  background:
    #fafbff;
}


.user-cell {
  display:
    flex;

  align-items:
    center;

  gap:
    11px;
}


.user-avatar {
  width:
    40px;

  height:
    40px;

  flex-shrink:
    0;

  border-radius:
    12px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  background:
    linear-gradient(
      135deg,
      #dbeafe,
      #ede9fe
    );

  color:
    #4338ca;

  font-weight:
    bold;

  font-family:
    Arial,
    sans-serif;
}


.user-cell strong {
  display:
    block;

  color:
    #172554;

  font-size:
    14px;
}


.user-cell small {
  display:
    block;

  margin-top:
    3px;

  color:
    #94a3b8;

  font-size:
    11px;
}


.username {
  font-weight:
    bold;

  color:
    #334155;
}


.actions {
  display:
    flex;

  align-items:
    center;

  gap:
    7px;
}


.action {
  width:
    35px;

  height:
    35px;

  border:
    none;

  cursor:
    pointer;

  border-radius:
    10px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  background:
    #f1f5f9;

  transition:
    .15s;
}


.action:hover {
  transform:
    translateY(-2px);
}


.action.edit:hover {
  background:
    #dbeafe;
}


.action.delete:hover {
  background:
    #fee2e2;
}


/* ==========================================================
   LOADING
========================================================== */

.loading {
  min-height:
    300px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  flex-direction:
    column;

  gap:
    13px;

  color:
    #64748b;
}


.spinner {
  width:
    38px;

  height:
    38px;

  border-radius:
    50%;

  border:
    4px solid
    #e2e8f0;

  border-top-color:
    #4f46e5;

  animation:
    spin .8s linear infinite;
}


@keyframes spin {

  to {
    transform:
      rotate(360deg);
  }

}


/* ==========================================================
   ERROR
========================================================== */

.error-box {
  margin:
    25px;

  padding:
    22px;

  border-radius:
    15px;

  background:
    #fff1f2;

  border:
    1px solid
    #fecdd3;

  display:
    flex;

  align-items:
    center;

  justify-content:
    space-between;

  gap:
    20px;

  color:
    #991b1b;
}


.error-box strong {
  font-size:
    15px;
}


.error-box p {
  margin:
    5px 0 0;

  font-size:
    13px;
}


.error-box button {
  border:
    none;

  cursor:
    pointer;

  padding:
    10px 16px;

  border-radius:
    9px;

  background:
    #dc2626;

  color:
    white;

  font-family:
    inherit;

  font-weight:
    bold;
}


/* ==========================================================
   EMPTY
========================================================== */

.empty {
  min-height:
    330px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  flex-direction:
    column;

  text-align:
    center;

  padding:
    30px;
}


.empty-icon {
  width:
    70px;

  height:
    70px;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    20px;

  background:
    #eef2ff;

  font-size:
    30px;

  margin-bottom:
    15px;
}


.empty h4 {
  margin:
    0;

  font-size:
    18px;

  color:
    #334155;
}


.empty p {
  margin:
    7px 0 17px;

  color:
    #94a3b8;

  font-size:
    13px;
}


.add-small {
  border:
    none;

  cursor:
    pointer;

  padding:
    11px 17px;

  border-radius:
    10px;

  background:
    #4f46e5;

  color:
    white;

  font-family:
    inherit;

  font-weight:
    bold;
}


/* ==========================================================
   MODAL
========================================================== */

.modal-overlay {
  position:
    fixed;

  inset:
    0;

  z-index:
    100;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  padding:
    20px;

  background:
    rgba(15,23,42,.55);

  backdrop-filter:
    blur(5px);
}


.modal {
  width:
    100%;

  max-width:
    570px;

  max-height:
    92vh;

  overflow-y:
    auto;

  background:
    white;

  border-radius:
    22px;

  box-shadow:
    0 25px 70px
    rgba(15,23,42,.25);

  animation:
    modalIn .2s ease;
}


@keyframes modalIn {

  from {
    opacity:
      0;

    transform:
      translateY(15px)
      scale(.98);
  }

  to {
    opacity:
      1;

    transform:
      translateY(0)
      scale(1);
  }

}


.modal-header {
  padding:
    23px 25px;

  display:
    flex;

  justify-content:
    space-between;

  gap:
    20px;

  border-bottom:
    1px solid
    #e2e8f0;
}


.modal-header h3 {
  margin:
    0;

  color:
    #172554;

  font-size:
    21px;
}


.modal-header p {
  margin:
    5px 0 0;

  color:
    #64748b;

  font-size:
    13px;
}


.close-button {
  width:
    35px;

  height:
    35px;

  border:
    none;

  cursor:
    pointer;

  border-radius:
    10px;

  background:
    #f1f5f9;

  color:
    #64748b;

  font-size:
    24px;

  line-height:
    1;
}


.close-button:hover {
  background:
    #e2e8f0;
}


.form {
  padding:
    25px;
}


.form-group {
  margin-bottom:
    17px;
}


.form-group label {
  display:
    block;

  margin-bottom:
    7px;

  color:
    #334155;

  font-size:
    13px;

  font-weight:
    bold;
}


.form-group label span {
  color:
    #94a3b8;

  font-weight:
    normal;

  font-size:
    11px;
}


.form-group input,
.form-group select {
  width:
    100%;

  height:
    45px;

  padding:
    0 13px;

  border:
    1px solid
    #dbe2ea;

  outline:
    none;

  border-radius:
    11px;

  background:
    white;

  color:
    #172554;

  font-family:
    inherit;

  font-size:
    13px;

  transition:
    .15s;
}


.form-group input:focus,
.form-group select:focus {
  border-color:
    #6366f1;

  box-shadow:
    0 0 0 3px
    rgba(99,102,241,.10);
}


.form-error {
  padding:
    12px 14px;

  margin-bottom:
    15px;

  border-radius:
    10px;

  background:
    #fff1f2;

  color:
    #be123c;

  font-size:
    13px;
}


.modal-actions {
  display:
    flex;

  justify-content:
    flex-end;

  gap:
    10px;

  padding-top:
    7px;
}


.cancel-button,
.save-button {
  min-width:
    120px;

  height:
    44px;

  border:
    none;

  border-radius:
    11px;

  cursor:
    pointer;

  font-family:
    inherit;

  font-weight:
    bold;

  font-size:
    13px;
}


.cancel-button {
  background:
    #f1f5f9;

  color:
    #475569;
}


.cancel-button:hover {
  background:
    #e2e8f0;
}


.save-button {
  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  color:
    white;

  box-shadow:
    0 7px 15px
    rgba(79,70,229,.20);
}


.save-button:hover {
  transform:
    translateY(-1px);
}


.save-button:disabled,
.cancel-button:disabled {
  opacity:
    .6;

  cursor:
    not-allowed;

  transform:
    none;
}


/* ==========================================================
   TOAST
========================================================== */

.toast {
  position:
    fixed;

  right:
    25px;

  bottom:
    25px;

  z-index:
    200;

  min-width:
    300px;

  max-width:
    400px;

  display:
    flex;

  align-items:
    flex-start;

  gap:
    12px;

  padding:
    15px 17px;

  border-radius:
    15px;

  background:
    white;

  box-shadow:
    0 15px 40px
    rgba(15,23,42,.18);

  border-left:
    4px solid
    #22c55e;

  animation:
    toastIn .25s ease;
}


.toast.error {
  border-left-color:
    #ef4444;
}


.toast > span {
  width:
    30px;

  height:
    30px;

  flex-shrink:
    0;

  display:
    flex;

  align-items:
    center;

  justify-content:
    center;

  border-radius:
    50%;

  background:
    #dcfce7;

  color:
    #166534;

  font-family:
    Arial,
    sans-serif;

  font-weight:
    bold;
}


.toast.error > span {
  background:
    #fee2e2;

  color:
    #991b1b;
}


.toast strong {
  color:
    #172554;

  font-size:
    13px;
}


.toast p {
  margin:
    3px 0 0;

  color:
    #64748b;

  font-size:
    12px;
}


@keyframes toastIn {

  from {
    opacity:
      0;

    transform:
      translateY(10px);
  }

  to {
    opacity:
      1;

    transform:
      translateY(0);
  }

}


/* ==========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 1100px) {

  .sidebar {
    width:
      240px;
  }

  .main {
    width:
      calc(100% - 240px);

    margin-left:
      240px;

    padding:
      30px 22px;
  }

  .stats {
    gap:
      12px;
  }

}


@media (max-width: 850px) {

  .sidebar {
    width:
      80px;

    padding:
      25px 12px;
  }

  .brand {
    justify-content:
      center;

    padding-left:
      0;

    padding-right:
      0;
  }

  .brand > div:last-child,
  .admin-card > div:last-child,
  .menu-item span:last-child,
  .logout-button span:last-child {
    display:
      none;
  }

  .admin-card {
    justify-content:
      center;

    padding:
      10px;
  }

  .menu-item,
  .logout-button {
    justify-content:
      center;

    padding:
      14px 8px;
  }

  .main {
    width:
      calc(100% - 80px);

    margin-left:
      80px;
  }

  .stats {
    grid-template-columns:
      1fr;
  }

}


@media (max-width: 650px) {

  .main {
    padding:
      20px 13px 40px;
  }

  .header {
    align-items:
      flex-start;

    flex-direction:
      column;
  }

  .header h2 {
    font-size:
      26px;
  }

  .add-button {
    width:
      100%;
  }

  .content-header {
    align-items:
      stretch;

    flex-direction:
      column;
  }

  .search-box {
    width:
      100%;
  }

  .error-box {
    align-items:
      stretch;

    flex-direction:
      column;
  }

  .toast {
    left:
      15px;

    right:
      15px;

    min-width:
      0;
  }

}

</style>