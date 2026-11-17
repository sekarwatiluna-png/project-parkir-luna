```vue
<template>
  <div class="login-page">

    <!-- BACKGROUND -->
    <div class="background-shape shape-one"></div>
    <div class="background-shape shape-two"></div>

    <!-- LOGIN CARD -->
    <div class="login-card">

      <!-- HEADER -->
      <div class="header">
        <div class="logo">
          🚗
        </div>

        <h1>
          PARKIR PLAZA ANDALAS
        </h1>

        <p>
          Sistem Informasi Parkir
        </p>
      </div>


      <!-- LOGIN FORM -->
      <form @submit.prevent="login">

        <!-- ROLE -->
        <div class="form-group">

          <label>
            Login Sebagai
          </label>

          <div class="role-container">

            <!-- PETUGAS -->
            <button
              type="button"
              class="role-button"
              :class="{
                active: form.role === 'petugas'
              }"
              @click="selectRole('petugas')"
            >
              <div class="role-icon">
                👮
              </div>

              <div class="role-info">
                <strong>
                  Petugas
                </strong>

                <span>
                  Petugas Parkir
                </span>
              </div>
            </button>


            <!-- SUPER ADMIN -->
            <button
              type="button"
              class="role-button"
              :class="{
                active: form.role === 'superadmin'
              }"
              @click="selectRole('superadmin')"
            >
              <div class="role-icon">
                👨‍💼
              </div>

              <div class="role-info">
                <strong>
                  Super Admin
                </strong>

                <span>
                  Administrator
                </span>
              </div>
            </button>

          </div>

        </div>


        <!-- EMAIL -->
        <div class="form-group">

          <label for="email">
            Email
          </label>

          <div class="input-wrapper">

            <span class="input-icon">
              ✉️
            </span>

            <input
              id="email"
              v-model.trim="form.email"
              type="email"
              placeholder="Masukkan email"
              autocomplete="email"
              required
            />

          </div>

        </div>


        <!-- PASSWORD -->
        <div class="form-group">

          <label for="password">
            Password
          </label>

          <div class="input-wrapper">

            <span class="input-icon">
              🔒
            </span>

            <input
              id="password"
              v-model="form.password"
              :type="
                showPassword
                  ? 'text'
                  : 'password'
              "
              placeholder="Masukkan password"
              autocomplete="current-password"
              required
            />

            <button
              type="button"
              class="password-toggle"
              @click="
                showPassword = !showPassword
              "
              :aria-label="
                showPassword
                  ? 'Sembunyikan password'
                  : 'Tampilkan password'
              "
            >
              {{
                showPassword
                  ? '🙈'
                  : '👁️'
              }}
            </button>

          </div>

        </div>


        <!-- LUPA PASSWORD -->
        <div class="forgot-password">

          <NuxtLink to="/lupa-password">
            Lupa Password?
          </NuxtLink>

        </div>


        <!-- ERROR -->
        <div
          v-if="errorMessage"
          class="message error-message"
        >

          <div class="message-icon">
            ⚠️
          </div>

          <div class="message-content">

            <strong>
              Login gagal
            </strong>

            <span>
              {{ errorMessage }}
            </span>

          </div>

        </div>


        <!-- SUCCESS -->
        <div
          v-if="successMessage"
          class="message success-message"
        >

          <div class="message-icon">
            ✅
          </div>

          <div class="message-content">

            <span>
              {{ successMessage }}
            </span>

          </div>

        </div>


        <!-- LOGIN BUTTON -->
        <button
          type="submit"
          class="login-button"
          :disabled="loading"
        >

          <span v-if="loading">
            ⏳
          </span>

          <span>
            {{
              loading
                ? 'Memproses...'
                : 'Login'
            }}
          </span>

        </button>

      </form>


      <!-- FOOTER -->
      <div class="footer">

        <p>
          © 2026 Parkir Plaza Andalas
        </p>

        <span>
          Sistem Informasi Parkir
        </span>

      </div>

    </div>

  </div>
</template>


<script setup>

import {
  reactive,
  ref
} from 'vue'


/* =========================================================
   STATE
========================================================= */

const loading = ref(false)

const errorMessage = ref('')

const successMessage = ref('')

const showPassword = ref(false)


const form = reactive({

  email: '',

  password: '',

  role: 'petugas'

})


/* =========================================================
   PILIH ROLE
========================================================= */

function selectRole(role) {

  form.role = role

  errorMessage.value = ''

  successMessage.value = ''

}


/* =========================================================
   LOGIN
========================================================= */

async function login() {

  if (loading.value) {
    return
  }


  /* RESET MESSAGE */

  errorMessage.value = ''

  successMessage.value = ''


  /* VALIDASI EMAIL */

  if (!form.email.trim()) {

    errorMessage.value =
      'Email wajib diisi.'

    return

  }


  /* VALIDASI FORMAT EMAIL */

  const emailPattern =
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/

  if (!emailPattern.test(form.email.trim())) {

    errorMessage.value =
      'Format email tidak valid.'

    return

  }


  /* VALIDASI PASSWORD */

  if (!form.password) {

    errorMessage.value =
      'Password wajib diisi.'

    return

  }


  loading.value = true


  try {

    /* =====================================================
       REQUEST KE LARAVEL
    ===================================================== */

    const response = await $fetch(
      'http://127.0.0.1:8000/api/login',
      {

        method: 'POST',

        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json'
        },

        body: {

          email: form.email.trim(),

          password: form.password

        },

        timeout: 15000

      }
    )


    console.log(
      'LOGIN RESPONSE:',
      response
    )


    /* =====================================================
       RESPONSE KOSONG
    ===================================================== */

    if (!response) {

      errorMessage.value =
        'Server tidak memberikan response.'

      return

    }


    /* =====================================================
       LOGIN GAGAL
    ===================================================== */

    if (response.success === false) {

      errorMessage.value =
        response.message ||
        'Email atau password salah.'

      return

    }


    /* =====================================================
       TOKEN TIDAK ADA
    ===================================================== */

    if (!response.token) {

      console.error(
        'TOKEN TIDAK DITEMUKAN:',
        response
      )

      errorMessage.value =
        'Login berhasil tetapi token tidak ditemukan.'

      return

    }


    /* =====================================================
       DATA USER
    ===================================================== */

    const user = response.user


    if (!user) {

      errorMessage.value =
        'Data user tidak ditemukan.'

      return

    }


    /* =====================================================
       ROLE DARI DATABASE
    ===================================================== */

    const userRole =
      String(
        user.role || ''
      )
        .toLowerCase()
        .trim()


    /* =====================================================
       CEK ROLE
    ===================================================== */

    if (
      userRole !== 'petugas' &&
      userRole !== 'superadmin' &&
      userRole !== 'super_admin'
    ) {

      errorMessage.value =
        'Role user tidak dikenali: ' +
        user.role

      return

    }


    /* =====================================================
       CEK ROLE PILIHAN LOGIN
    ===================================================== */

    const selectedRole =
      form.role.toLowerCase().trim()


    if (
      selectedRole === 'petugas' &&
      userRole !== 'petugas'
    ) {

      errorMessage.value =
        'Akun ini bukan akun Petugas.'

      return

    }


    if (
      selectedRole === 'superadmin' &&
      userRole !== 'superadmin' &&
      userRole !== 'super_admin'
    ) {

      errorMessage.value =
        'Akun ini bukan akun Super Admin.'

      return

    }


    /* =====================================================
       SIMPAN TOKEN
    ===================================================== */

    localStorage.setItem(
      'token',
      response.token
    )


    /* =====================================================
       SIMPAN USER
    ===================================================== */

    localStorage.setItem(
      'user',
      JSON.stringify(user)
    )


    /* =====================================================
       SIMPAN ROLE
    ===================================================== */

    localStorage.setItem(
      'role',
      userRole
    )


    /* =====================================================
       CEK TOKEN TERSIMPAN
    ===================================================== */

    const savedToken =
      localStorage.getItem('token')


    if (!savedToken) {

      errorMessage.value =
        'Token gagal disimpan.'

      return

    }


    /* =====================================================
       SUCCESS
    ===================================================== */

    successMessage.value =
      'Login berhasil. Mengalihkan...'


    /* =====================================================
       SUPER ADMIN
    ===================================================== */

    if (
      userRole === 'superadmin' ||
      userRole === 'super_admin'
    ) {

      await navigateTo(
        '/superadmin'
      )

      return

    }


    /* =====================================================
       PETUGAS
    ===================================================== */

    if (
      userRole === 'petugas'
    ) {

      await navigateTo(
        '/petugas'
      )

      return

    }


  } catch (error) {

    console.error(
      'LOGIN ERROR:',
      error
    )


    /* =====================================================
       VALIDATION 422
    ===================================================== */

    if (
      error?.status === 422 ||
      error?.response?.status === 422
    ) {

      const errors =
        error?.data?.errors ||
        error?.response?._data?.errors


      if (errors) {

        errorMessage.value =
          Object.values(errors)
            .flat()
            .join(' ')

      } else {

        errorMessage.value =
          error?.data?.message ||
          error?.response?._data?.message ||
          'Data login tidak valid.'

      }

      return

    }


    /* =====================================================
       UNAUTHORIZED 401
    ===================================================== */

    if (
      error?.status === 401 ||
      error?.response?.status === 401
    ) {

      errorMessage.value =
        error?.data?.message ||
        error?.response?._data?.message ||
        'Email atau password salah.'

      return

    }


    /* =====================================================
       FORBIDDEN 403
    ===================================================== */

    if (
      error?.status === 403 ||
      error?.response?.status === 403
    ) {

      errorMessage.value =
        error?.data?.message ||
        error?.response?._data?.message ||
        'Akun sedang nonaktif atau tidak memiliki izin.'

      return

    }


    /* =====================================================
       NOT FOUND 404
    ===================================================== */

    if (
      error?.status === 404 ||
      error?.response?.status === 404
    ) {

      errorMessage.value =
        'API login tidak ditemukan. Pastikan Laravel memiliki route POST /api/login.'

      return

    }


    /* =====================================================
       SERVER ERROR 500
    ===================================================== */

    if (
      error?.status === 500 ||
      error?.response?.status === 500
    ) {

      errorMessage.value =
        error?.data?.message ||
        error?.response?._data?.message ||
        'Terjadi error pada server Laravel.'

      return

    }


    /* =====================================================
       CONNECTION ERROR
    ===================================================== */

    const errorText =
      String(
        error?.message || ''
      )


    if (
      errorText.includes(
        'Failed to fetch'
      ) ||
      errorText.includes(
        'fetch failed'
      ) ||
      errorText.includes(
        'NetworkError'
      ) ||
      errorText.includes(
        'ECONNREFUSED'
      )
    ) {

      errorMessage.value =
        'Tidak dapat terhubung ke Laravel. Pastikan php artisan serve sedang berjalan.'

      return

    }


    /* =====================================================
       TIMEOUT
    ===================================================== */

    if (
      error?.name === 'TimeoutError' ||
      error?.name === 'AbortError'
    ) {

      errorMessage.value =
        'Laravel terlalu lama merespons.'

      return

    }


    /* =====================================================
       ERROR LAIN
    ===================================================== */

    errorMessage.value =
      error?.data?.message ||
      error?.response?._data?.message ||
      error?.message ||
      'Terjadi kesalahan saat login.'

  } finally {

    loading.value = false

  }

}

</script>


<style scoped>

* {
  box-sizing: border-box;
}

.login-page {
  min-height: 100vh;
  width: 100%;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px 20px;
  overflow: hidden;

  background:
    linear-gradient(
      135deg,
      #dbeafe 0%,
      #eff6ff 45%,
      #ede9fe 100%
    );
}

.background-shape {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}

.shape-one {
  width: 380px;
  height: 380px;
  top: -180px;
  left: -150px;

  background:
    rgba(
      37,
      99,
      235,
      0.12
    );
}

.shape-two {
  width: 430px;
  height: 430px;
  right: -190px;
  bottom: -200px;

  background:
    rgba(
      124,
      58,
      237,
      0.12
    );
}

.login-card {
  width: 100%;
  max-width: 460px;
  position: relative;
  z-index: 2;
  padding: 38px;

  border:
    1px solid
    rgba(
      255,
      255,
      255,
      0.8
    );

  border-radius: 26px;

  background:
    rgba(
      255,
      255,
      255,
      0.97
    );

  box-shadow:
    0 25px 60px
    rgba(
      30,
      64,
      175,
      0.16
    );
}

.header {
  text-align: center;
  margin-bottom: 30px;
}

.logo {
  width: 72px;
  height: 72px;
  margin: 0 auto 17px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 21px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  font-size: 32px;

  box-shadow:
    0 12px 28px
    rgba(
      37,
      99,
      235,
      0.24
    );
}

.header h1 {
  margin: 0;
  color: #1e3a8a;
  font-size: 22px;
  line-height: 1.3;
  font-weight: 800;
}

.header p {
  margin: 8px 0 0;
  color: #64748b;
  font-size: 14px;
}

.form-group {
  margin-bottom: 19px;
}

.form-group > label {
  display: block;
  margin-bottom: 8px;
  color: #334155;
  font-size: 14px;
  font-weight: 700;
}

.role-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 11px;
}

.role-button {
  min-height: 72px;
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 12px;

  border:
    1px solid
    #e2e8f0;

  border-radius: 13px;
  outline: none;
  background: #f8fafc;
  cursor: pointer;
  text-align: left;

  transition:
    all 0.2s ease;
}

.role-button:hover {
  border-color: #93c5fd;
  background: #f1f5f9;
}

.role-button.active {
  border-color: #2563eb;
  background: #eff6ff;

  box-shadow:
    0 0 0 2px
    rgba(
      37,
      99,
      235,
      0.08
    );
}

.role-icon {
  width: 39px;
  height: 39px;
  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 11px;
  background: #ffffff;
  font-size: 21px;
}

.role-info {
  min-width: 0;
}

.role-info strong {
  display: block;
  color: #1e293b;
  font-size: 13px;
  font-weight: 800;
}

.role-info span {
  display: block;
  margin-top: 3px;
  color: #64748b;
  font-size: 10px;
}

.input-wrapper {
  width: 100%;
  height: 50px;
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 14px;
  z-index: 2;
  font-size: 15px;
  pointer-events: none;
}

.input-wrapper input {
  width: 100%;
  height: 50px;
  padding: 0 44px;

  border:
    1px solid
    #cbd5e1;

  border-radius: 12px;
  outline: none;
  background: #ffffff;
  color: #1e293b;

  font-family: inherit;
  font-size: 14px;

  transition:
    all 0.2s ease;
}

.input-wrapper input:focus {
  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(
      37,
      99,
      235,
      0.10
    );
}

.input-wrapper input::placeholder {
  color: #94a3b8;
}

.password-toggle {
  position: absolute;
  right: 10px;

  width: 34px;
  height: 34px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: none;
  border-radius: 8px;
  background: transparent;
  cursor: pointer;
  font-size: 15px;
}

.password-toggle:hover {
  background: #f1f5f9;
}

.forgot-password {
  width: 100%;
  margin-top: -7px;
  margin-bottom: 17px;
  text-align: center;
}

.forgot-password a {
  display: inline-block;
  color: #2563eb;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;

  transition:
    color 0.2s ease,
    transform 0.2s ease;
}

.forgot-password a:hover {
  color: #7c3aed;
  text-decoration: underline;
}

.message {
  width: 100%;
  display: flex;
  align-items: flex-start;
  gap: 10px;

  margin-bottom: 15px;
  padding: 12px 14px;

  border-radius: 11px;
  font-size: 13px;
  line-height: 1.5;
}

.message-icon {
  flex-shrink: 0;
}

.message-content {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.error-message {
  border:
    1px solid
    #fecaca;

  background: #fef2f2;
  color: #b91c1c;
}

.success-message {
  border:
    1px solid
    #bbf7d0;

  background: #f0fdf4;
  color: #166534;
}

.login-button {
  width: 100%;
  height: 51px;

  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;

  border: none;
  border-radius: 12px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #7c3aed
    );

  color: #ffffff;

  font-family: inherit;
  font-size: 15px;
  font-weight: 800;

  cursor: pointer;

  transition:
    all 0.2s ease;
}

.login-button:hover:not(:disabled) {
  transform: translateY(-1px);

  box-shadow:
    0 10px 24px
    rgba(
      37,
      99,
      235,
      0.25
    );
}

.login-button:active:not(:disabled) {
  transform: translateY(0);
}

.login-button:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.footer {
  margin-top: 25px;
  text-align: center;
}

.footer p {
  margin: 0;
  color: #94a3b8;
  font-size: 11px;
}

.footer span {
  display: block;
  margin-top: 3px;
  color: #cbd5e1;
  font-size: 10px;
}

@media (max-width: 520px) {

  .login-page {
    padding: 20px 15px;
  }

  .login-card {
    padding: 28px 21px;
    border-radius: 21px;
  }

  .header h1 {
    font-size: 19px;
  }

  .role-container {
    grid-template-columns: 1fr;
  }

  .role-button {
    min-height: 65px;
  }

}

</style>
```
