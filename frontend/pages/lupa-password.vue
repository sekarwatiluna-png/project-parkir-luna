<template>
  <div class="forgot-page">

    <!-- BACKGROUND -->
    <div class="bg-circle circle-one"></div>
    <div class="bg-circle circle-two"></div>
    <div class="bg-circle circle-three"></div>

    <div class="grid-bg"></div>


    <!-- MAIN -->
    <main class="container">

      <!-- BRAND -->
      <div class="brand">

        <div class="brand-logo">
          <div class="logo-inner">
            🅿️
          </div>
        </div>

        <h1>PARKIR PLAZA ANDALAS</h1>

        <p>
          Sistem Manajemen Parkir
        </p>

      </div>


      <!-- CARD -->
      <section class="card">

        <div class="top-line"></div>


        <!-- HEADER -->
        <div class="card-header">

          <div class="header-icon">
            🔐
          </div>

          <div>
            <h2>Lupa Password?</h2>

            <p>
              Pulihkan akses akun Anda dengan aman.
            </p>
          </div>

        </div>


        <!-- PROGRESS -->
        <div class="steps">

          <div
            class="step-item"
            :class="{
              active: step >= 1,
              current: step === 1
            }"
          >

            <div class="step-circle">
              <span v-if="step > 1">✓</span>
              <span v-else>1</span>
            </div>

            <span>Email</span>

          </div>


          <div
            class="step-connector"
            :class="{ active: step >= 2 }"
          ></div>


          <div
            class="step-item"
            :class="{
              active: step >= 2,
              current: step === 2
            }"
          >

            <div class="step-circle">

              <span v-if="step > 2">✓</span>

              <span v-else>2</span>

            </div>

            <span>WhatsApp</span>

          </div>


          <div
            class="step-connector"
            :class="{ active: step >= 3 }"
          ></div>


          <div
            class="step-item"
            :class="{
              active: step >= 3,
              current: step === 3
            }"
          >

            <div class="step-circle">

              <span v-if="completed">✓</span>

              <span v-else>3</span>

            </div>

            <span>Password</span>

          </div>

        </div>


        <!-- ERROR -->
        <transition name="alert">

          <div
            v-if="error"
            class="alert error"
          >

            <div class="alert-icon">
              !
            </div>

            <div>
              <strong>Terjadi Kesalahan</strong>

              <p>{{ error }}</p>
            </div>

          </div>

        </transition>


        <!-- SUCCESS -->
        <transition name="alert">

          <div
            v-if="infoMessage"
            class="alert success"
          >

            <div class="alert-icon">
              ✓
            </div>

            <div>
              <strong>Berhasil</strong>

              <p>{{ infoMessage }}</p>
            </div>

          </div>

        </transition>


        <!-- =================================================
             STEP 1
        ================================================== -->

        <div
          v-if="step === 1 && !completed"
          class="content"
        >

          <div class="section-heading">

            <div class="section-icon">
              ✉️
            </div>

            <div>
              <h3>Masukkan Email</h3>

              <p>
                Masukkan email yang terdaftar
                pada akun ParkirKu.
              </p>
            </div>

          </div>


          <div class="form-group">

            <label>Email</label>

            <div class="input-box">

              <span>✉️</span>

              <input
                v-model="email"
                type="email"
                placeholder="Masukkan email Anda"
                autocomplete="email"
                @keyup.enter="checkEmail"
              />

            </div>

          </div>


          <button
            class="main-button"
            type="button"
            :disabled="loading"
            @click="checkEmail"
          >

            <span v-if="loading">

              <span class="spinner"></span>

              Memeriksa email...

            </span>

            <span v-else>

              Lanjutkan

              <b>→</b>

            </span>

          </button>

        </div>


        <!-- =================================================
             STEP 2
        ================================================== -->

        <div
          v-else-if="step === 2 && !completed"
          class="content"
        >

          <div class="section-heading">

            <div class="section-icon whatsapp">
              📱
            </div>

            <div>

              <h3>Verifikasi WhatsApp</h3>

              <p>
                Masukkan nomor WhatsApp yang
                terdaftar pada akun Anda.
              </p>

            </div>

          </div>


          <!-- EMAIL INFO -->

          <div class="account-info">

            <div class="account-icon">
              ✉️
            </div>

            <div>

              <small>Email akun</small>

              <strong>
                {{ email }}
              </strong>

            </div>

          </div>


          <!-- PHONE -->

          <div class="form-group">

            <label>Nomor WhatsApp</label>

            <div class="input-box">

              <span>📱</span>

              <input
                v-model="noTelepon"
                type="tel"
                placeholder="Contoh: 081234567890"
                autocomplete="tel"
              />

            </div>

            <small class="input-help">
              Nomor harus sama dengan nomor
              WhatsApp yang terdaftar.
            </small>

          </div>


          <!-- SEND OTP -->

          <button
            class="whatsapp-button"
            type="button"
            :disabled="loading"
            @click="sendOtp"
          >

            <span v-if="loading">

              <span class="spinner blue"></span>

              Mengirim OTP ke WhatsApp...

            </span>

            <span v-else>

              💬
              Kirim OTP ke WhatsApp

            </span>

          </button>


          <!-- OTP AREA -->

          <div
            v-if="otpSent"
            class="otp-area"
          >

            <div class="otp-title">

              <div class="otp-icon">
                🔑
              </div>

              <div>

                <strong>
                  OTP sudah dikirim
                </strong>

                <p>
                  Cek WhatsApp Anda untuk
                  mendapatkan kode 6 digit.
                </p>

              </div>

            </div>


            <div class="form-group">

              <label>Kode OTP</label>

              <input
                v-model="otp"
                class="otp-input"
                type="text"
                inputmode="numeric"
                maxlength="6"
                placeholder="000000"
                autocomplete="one-time-code"
                @input="
                  otp = otp
                    .replace(/\D/g, '')
                    .slice(0, 6)
                "
              />

            </div>


            <div class="otp-expired">

              <span>⏱️</span>

              Kode OTP berlaku selama
              <strong>5 menit</strong>.

            </div>


            <button
              class="main-button"
              type="button"
              :disabled="loading"
              @click="verifyOtp"
            >

              <span v-if="loading">

                <span class="spinner"></span>

                Memverifikasi OTP...

              </span>

              <span v-else>

                Verifikasi OTP

                <b>→</b>

              </span>

            </button>

          </div>


          <!-- RESEND -->

          <button
            v-if="otpSent && !loading"
            type="button"
            class="resend-button"
            @click="sendOtp"
          >

            ↻ Kirim ulang OTP

          </button>

        </div>


        <!-- =================================================
             STEP 3
        ================================================== -->

        <div
          v-else-if="step === 3 && !completed"
          class="content"
        >

          <div class="section-heading">

            <div class="section-icon password">
              🔒
            </div>

            <div>

              <h3>Buat Password Baru</h3>

              <p>
                Buat password baru untuk akun Anda.
              </p>

            </div>

          </div>


          <!-- PASSWORD -->

          <div class="form-group">

            <label>Password Baru</label>

            <div class="input-box">

              <span>🔒</span>

              <input
                v-model="password"
                :type="
                  showPassword
                    ? 'text'
                    : 'password'
                "
                placeholder="Minimal 6 karakter"
                autocomplete="new-password"
              />

              <button
                type="button"
                class="show-button"
                @click="
                  showPassword = !showPassword
                "
              >
                {{ showPassword ? 'SEMBUNYI' : 'LIHAT' }}
              </button>

            </div>

          </div>


          <!-- CONFIRM -->

          <div class="form-group">

            <label>Konfirmasi Password</label>

            <div class="input-box">

              <span>🔐</span>

              <input
                v-model="passwordConfirmation"
                type="password"
                placeholder="Ulangi password baru"
                autocomplete="new-password"
              />

            </div>

          </div>


          <!-- REQUIREMENTS -->

          <div class="requirements">

            <div>

              <span>✓</span>

              Minimal 6 karakter

            </div>

            <div>

              <span>✓</span>

              Password harus sama

            </div>

          </div>


          <button
            class="main-button"
            type="button"
            :disabled="loading"
            @click="resetPassword"
          >

            <span v-if="loading">

              <span class="spinner"></span>

              Menyimpan password...

            </span>

            <span v-else>

              Simpan Password Baru

              <b>→</b>

            </span>

          </button>

        </div>


        <!-- =================================================
             COMPLETED
        ================================================== -->

        <div
          v-if="completed"
          class="completed"
        >

          <div class="completed-icon">
            ✓
          </div>

          <h3>Password Berhasil Diubah!</h3>

          <p>
            Password akun Anda sudah berhasil
            diperbarui. Sekarang Anda dapat
            masuk kembali menggunakan password
            baru.
          </p>

          <button
            type="button"
            class="main-button"
            @click="goToLogin"
          >

            ← Kembali ke Login

          </button>

        </div>


        <!-- BACK LOGIN -->

        <div
          v-if="!completed"
          class="back-login"
        >

          <button
            type="button"
            @click="goToLogin"
          >

            ← Kembali ke Login

          </button>

        </div>

      </section>


      <!-- FOOTER -->

      <footer>

        <p>
          © 2026
          <strong>PARKIR PLAZA ANDALAS</strong>
        </p>

        <span>
          Sistem Manajemen Parkir
        </span>

      </footer>

    </main>

  </div>
</template>


<script setup lang="ts">

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

const router = useRouter()


/*
|--------------------------------------------------------------------------
| CONFIG
|--------------------------------------------------------------------------
*/

const config = useRuntimeConfig()

const apiBase =
  config.public.apiBase ||
  'http://127.0.0.1:8000/api'


/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const step = ref(1)

const email = ref('')

const userId = ref<number | null>(null)

const noTelepon = ref('')

const otp = ref('')

const password = ref('')

const passwordConfirmation = ref('')

const loading = ref(false)

const error = ref('')

const infoMessage = ref('')

const otpSent = ref(false)

const completed = ref(false)

const showPassword = ref(false)


/*
|--------------------------------------------------------------------------
| CLEAR MESSAGE
|--------------------------------------------------------------------------
*/

const clearMessages = () => {

  error.value = ''

  infoMessage.value = ''

}


/*
|--------------------------------------------------------------------------
| GET ERROR MESSAGE
|--------------------------------------------------------------------------
*/

const getErrorMessage = (
  err: any,
  fallback: string
) => {

  return (
    err?.data?.message ||
    err?.message ||
    fallback
  )

}


/*
|--------------------------------------------------------------------------
| STEP 1
| CHECK EMAIL
|--------------------------------------------------------------------------
*/

const checkEmail = async () => {

  clearMessages()

  if (!email.value.trim()) {

    error.value =
      'Email wajib diisi.'

    return

  }


  loading.value = true

  try {

    const response: any =
      await $fetch(
        `${apiBase}/forgot-password/check-email`,
        {
          method: 'POST',

          body: {
            email:
              email.value.trim()
          }
        }
      )


    if (!response?.success) {

      error.value =
        response?.message ||
        'Email tidak ditemukan.'

      return

    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN USER ID
    |--------------------------------------------------------------------------
    */

    userId.value =
      Number(response.user_id)


    if (!userId.value) {

      error.value =
        'ID akun tidak ditemukan.'

      return

    }


    /*
    |--------------------------------------------------------------------------
    | LANJUT KE NOMOR WHATSAPP
    |--------------------------------------------------------------------------
    */

    step.value = 2

    infoMessage.value =
      'Email ditemukan. Silakan masukkan nomor WhatsApp Anda.'

  } catch (err: any) {

    error.value =
      getErrorMessage(
        err,
        'Email tidak ditemukan.'
      )

  } finally {

    loading.value = false

  }

}


/*
|--------------------------------------------------------------------------
| STEP 2
| SEND OTP TO WHATSAPP
|--------------------------------------------------------------------------
*/

const sendOtp = async () => {

  clearMessages()

  if (!userId.value) {

    error.value =
      'Data akun tidak ditemukan. Silakan ulangi dari awal.'

    return

  }


  if (!noTelepon.value.trim()) {

    error.value =
      'Nomor WhatsApp wajib diisi.'

    return

  }


  loading.value = true

  try {

    const response: any =
      await $fetch(
        `${apiBase}/forgot-password/check-phone`,
        {
          method: 'POST',

          body: {

            user_id:
              userId.value,

            no_telepon:
              noTelepon.value.trim()

          }
        }
      )


    if (!response?.success) {

      error.value =
        response?.message ||
        'Nomor WhatsApp tidak sesuai.'

      return

    }


    /*
    |--------------------------------------------------------------------------
    | PENTING
    |--------------------------------------------------------------------------
    | Di sini OTP BELUM dianggap terverifikasi.
    |
    | Backend baru mengirim OTP ke WhatsApp.
    |
    */

    otpSent.value = true

    infoMessage.value =
      'OTP sudah dikirim ke WhatsApp. Silakan buka WhatsApp dan masukkan kode OTP 6 digit.'

  } catch (err: any) {

    error.value =
      getErrorMessage(
        err,
        'OTP gagal dikirim ke WhatsApp.'
      )

  } finally {

    loading.value = false

  }

}


/*
|--------------------------------------------------------------------------
| VERIFY OTP
|--------------------------------------------------------------------------
*/

const verifyOtp = async () => {

  clearMessages()


  if (!userId.value) {

    error.value =
      'Data akun tidak ditemukan.'

    return

  }


  if (!otp.value.trim()) {

    error.value =
      'Kode OTP wajib diisi.'

    return

  }


  if (otp.value.length !== 6) {

    error.value =
      'Kode OTP harus terdiri dari 6 digit.'

    return

  }


  loading.value = true

  try {

    const response: any =
      await $fetch(
        `${apiBase}/forgot-password/verify-otp`,
        {
          method: 'POST',

          body: {

            user_id:
              userId.value,

            otp:
              otp.value.trim()

          }
        }
      )


    if (!response?.success) {

      error.value =
        response?.message ||
        'OTP salah.'

      return

    }


    /*
    |--------------------------------------------------------------------------
    | BARU SEKARANG OTP BENAR-BENAR VERIFIED
    |--------------------------------------------------------------------------
    */

    step.value = 3

    infoMessage.value =
      'OTP benar. Silakan buat password baru.'

  } catch (err: any) {

    error.value =
      getErrorMessage(
        err,
        'OTP salah atau sudah kadaluarsa.'
      )

  } finally {

    loading.value = false

  }

}


/*
|--------------------------------------------------------------------------
| STEP 3
| RESET PASSWORD
|--------------------------------------------------------------------------
*/

const resetPassword = async () => {

  clearMessages()


  if (!userId.value) {

    error.value =
      'Data akun tidak ditemukan.'

    return

  }


  if (!password.value) {

    error.value =
      'Password baru wajib diisi.'

    return

  }


  if (password.value.length < 6) {

    error.value =
      'Password minimal 6 karakter.'

    return

  }


  if (!passwordConfirmation.value) {

    error.value =
      'Konfirmasi password wajib diisi.'

    return

  }


  if (
    password.value !==
    passwordConfirmation.value
  ) {

    error.value =
      'Password dan konfirmasi password tidak sama.'

    return

  }


  loading.value = true

  try {

    const response: any =
      await $fetch(
        `${apiBase}/forgot-password/reset`,
        {
          method: 'POST',

          body: {

            user_id:
              userId.value,

            password:
              password.value,

            password_confirmation:
              passwordConfirmation.value

          }
        }
      )


    if (!response?.success) {

      error.value =
        response?.message ||
        'Gagal mengubah password.'

      return

    }


    /*
    |--------------------------------------------------------------------------
    | BERHASIL
    |--------------------------------------------------------------------------
    */

    completed.value = true

    infoMessage.value = ''

  } catch (err: any) {

    error.value =
      getErrorMessage(
        err,
        'Gagal mengubah password.'
      )

  } finally {

    loading.value = false

  }

}


/*
|--------------------------------------------------------------------------
| KEMBALI KE LOGIN
|--------------------------------------------------------------------------
| pages/index.vue = /
|--------------------------------------------------------------------------
*/

const goToLogin = async () => {

  await router.replace('/')

}

</script>


<style scoped>

/* =========================================================
   RESET
========================================================= */

* {
  box-sizing: border-box;
}


/* =========================================================
   PAGE
========================================================= */

.forgot-page {
  min-height: 100vh;
  width: 100%;

  position: relative;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 40px 18px;

  overflow: hidden;

  background:
    radial-gradient(
      circle at 15% 15%,
      rgba(59, 130, 246, 0.25),
      transparent 30%
    ),
    radial-gradient(
      circle at 90% 85%,
      rgba(37, 99, 235, 0.25),
      transparent 30%
    ),
    linear-gradient(
      135deg,
      #06142f 0%,
      #0b2454 45%,
      #123b82 100%
    );
}


/* =========================================================
   BACKGROUND
========================================================= */

.grid-bg {
  position: absolute;
  inset: 0;

  opacity: 0.08;

  background-image:
    linear-gradient(
      rgba(255,255,255,.25) 1px,
      transparent 1px
    ),
    linear-gradient(
      90deg,
      rgba(255,255,255,.25) 1px,
      transparent 1px
    );

  background-size: 40px 40px;
}

.bg-circle {
  position: absolute;

  border-radius: 50%;

  pointer-events: none;

  filter: blur(4px);

  animation: floating 8s ease-in-out infinite;
}

.circle-one {
  width: 330px;
  height: 330px;

  top: -150px;
  left: -100px;

  background:
    radial-gradient(
      circle,
      rgba(96,165,250,.35),
      transparent 70%
    );
}

.circle-two {
  width: 450px;
  height: 450px;

  right: -200px;
  bottom: -180px;

  background:
    radial-gradient(
      circle,
      rgba(37,99,235,.30),
      transparent 70%
    );

  animation-delay: 2s;
}

.circle-three {
  width: 200px;
  height: 200px;

  top: 45%;
  right: 10%;

  background:
    radial-gradient(
      circle,
      rgba(147,197,253,.16),
      transparent 70%
    );

  animation-delay: 4s;
}

@keyframes floating {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-18px);
  }

}


/* =========================================================
   CONTAINER
========================================================= */

.container {
  width: 100%;

  max-width: 510px;

  position: relative;

  z-index: 5;
}


/* =========================================================
   BRAND
========================================================= */

.brand {
  text-align: center;

  color: white;

  margin-bottom: 24px;

  animation: fadeDown .6s ease;
}

.brand-logo {
  width: 78px;
  height: 78px;

  padding: 4px;

  margin:
    0 auto 14px;

  border-radius: 23px;

  background:
    linear-gradient(
      135deg,
      #ffffff,
      #bfdbfe
    );

  box-shadow:
    0 20px 45px rgba(0,0,0,.3);
}

.logo-inner {
  width: 100%;
  height: 100%;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 19px;

  background:
    linear-gradient(
      135deg,
      #0b2454,
      #2563eb
    );

  font-size: 34px;
}

.brand h1 {
  margin: 0;

  font-size: 22px;

  font-weight: 900;

  letter-spacing: 1.3px;
}

.brand p {
  margin: 7px 0 0;

  color: #bfdbfe;

  font-size: 11px;
}


/* =========================================================
   CARD
========================================================= */

.card {
  position: relative;

  width: 100%;

  padding: 32px;

  border-radius: 29px;

  background:
    rgba(255,255,255,.97);

  border:
    1px solid rgba(255,255,255,.9);

  box-shadow:
    0 35px 90px rgba(0,0,0,.35);

  backdrop-filter: blur(20px);

  animation: cardIn .65s ease;
}

.top-line {
  position: absolute;

  top: 0;
  left: 15%;

  width: 70%;
  height: 4px;

  border-radius:
    0 0 20px 20px;

  background:
    linear-gradient(
      90deg,
      #1d4ed8,
      #3b82f6,
      #60a5fa
    );
}


/* =========================================================
   HEADER
========================================================= */

.card-header {
  display: flex;

  align-items: center;

  gap: 14px;

  margin-bottom: 27px;
}

.header-icon {
  width: 52px;
  height: 52px;

  flex-shrink: 0;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 15px;

  background:
    linear-gradient(
      135deg,
      #dbeafe,
      #eff6ff
    );

  font-size: 23px;

  box-shadow:
    0 8px 20px rgba(37,99,235,.10);
}

.card-header h2 {
  margin: 0;

  color: #111827;

  font-size: 23px;

  font-weight: 850;
}

.card-header p {
  margin: 4px 0 0;

  color: #6b7280;

  font-size: 11px;
}


/* =========================================================
   STEPS
========================================================= */

.steps {
  display: flex;

  align-items: flex-start;

  justify-content: space-between;

  margin-bottom: 28px;
}

.step-item {
  display: flex;

  flex-direction: column;

  align-items: center;

  gap: 6px;

  min-width: 65px;
}

.step-circle {
  width: 34px;
  height: 34px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 50%;

  color: #9ca3af;

  background: #f3f4f6;

  border: 3px solid white;

  box-shadow:
    0 2px 6px rgba(0,0,0,.08);

  font-size: 11px;

  font-weight: 900;

  transition: .3s;
}

.step-item.active .step-circle {
  color: white;

  background:
    linear-gradient(
      135deg,
      #1d4ed8,
      #3b82f6
    );

  box-shadow:
    0 6px 17px rgba(37,99,235,.25);
}

.step-item.current .step-circle {
  transform: scale(1.1);

  box-shadow:
    0 0 0 5px
    rgba(59,130,246,.13);
}

.step-item > span {
  color: #9ca3af;

  font-size: 9px;

  font-weight: 800;
}

.step-item.active > span {
  color: #2563eb;
}

.step-connector {
  flex: 1;

  height: 3px;

  margin:
    16px 5px 0;

  border-radius: 10px;

  background: #e5e7eb;

  transition: .3s;
}

.step-connector.active {
  background:
    linear-gradient(
      90deg,
      #1d4ed8,
      #3b82f6
    );
}


/* =========================================================
   CONTENT
========================================================= */

.content {
  animation: slideIn .35s ease;
}

.section-heading {
  display: flex;

  align-items: center;

  gap: 12px;

  margin-bottom: 22px;
}

.section-icon {
  width: 43px;
  height: 43px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 13px;

  background: #eff6ff;

  font-size: 18px;
}

.section-icon.whatsapp {
  background: #eff6ff;
}

.section-icon.password {
  background: #eef2ff;
}

.section-heading h3 {
  margin: 0;

  color: #1f2937;

  font-size: 16px;

  font-weight: 850;
}

.section-heading p {
  margin: 3px 0 0;

  color: #7b8490;

  font-size: 11px;

  line-height: 1.5;
}


/* =========================================================
   FORM
========================================================= */

.form-group {
  margin-bottom: 17px;
}

.form-group label {
  display: block;

  margin-bottom: 7px;

  color: #374151;

  font-size: 11px;

  font-weight: 850;
}

.input-box {
  position: relative;
}

.input-box > span {
  position: absolute;

  left: 15px;
  top: 50%;

  transform: translateY(-50%);

  font-size: 15px;

  pointer-events: none;
}

.input-box input {
  width: 100%;

  height: 51px;

  padding:
    0 16px 0 44px;

  border:
    1px solid #dce2ea;

  border-radius: 13px;

  outline: none;

  color: #172033;

  background: #f8fafc;

  font-size: 12px;

  transition: .2s;
}

.input-box input:focus {
  background: white;

  border-color: #3b82f6;

  box-shadow:
    0 0 0 4px
    rgba(59,130,246,.10);
}

.input-box input::placeholder {
  color: #a3aab4;
}

.input-help {
  display: block;

  margin-top: 6px;

  color: #8b95a3;

  font-size: 9px;
}


/* =========================================================
   ACCOUNT INFO
========================================================= */

.account-info {
  display: flex;

  align-items: center;

  gap: 10px;

  padding: 11px 13px;

  margin-bottom: 17px;

  border-radius: 12px;

  background: #eff6ff;

  border:
    1px solid #dbeafe;
}

.account-icon {
  width: 32px;
  height: 32px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 9px;

  background: white;

  font-size: 14px;
}

.account-info small {
  display: block;

  color: #64748b;

  font-size: 8px;

  margin-bottom: 2px;
}

.account-info strong {
  color: #1e40af;

  font-size: 11px;

  word-break: break-all;
}


/* =========================================================
   BUTTONS
========================================================= */

.main-button,
.whatsapp-button {
  width: 100%;

  min-height: 51px;

  border-radius: 13px;

  cursor: pointer;

  display: flex;

  align-items: center;
  justify-content: center;

  font-size: 12px;

  font-weight: 850;

  transition: .2s;
}

.main-button {
  border: none;

  color: white;

  background:
    linear-gradient(
      135deg,
      #1d4ed8,
      #2563eb,
      #3b82f6
    );

  box-shadow:
    0 10px 25px
    rgba(37,99,235,.22);
}

.main-button:hover:not(:disabled) {
  transform: translateY(-2px);

  box-shadow:
    0 15px 30px
    rgba(37,99,235,.28);
}

.main-button b {
  margin-left: 6px;

  font-size: 17px;
}

.whatsapp-button {
  margin-bottom: 18px;

  border:
    1px solid #93c5fd;

  color: #1d4ed8;

  background: #eff6ff;
}

.whatsapp-button:hover:not(:disabled) {
  background: #dbeafe;

  transform: translateY(-1px);
}

.main-button:disabled,
.whatsapp-button:disabled {
  cursor: not-allowed;

  opacity: .55;

  transform: none;
}


/* =========================================================
   OTP
========================================================= */

.otp-area {
  margin-top: 5px;

  padding:
    17px;

  border-radius: 16px;

  background:
    linear-gradient(
      135deg,
      #eff6ff,
      #f8fbff
    );

  border:
    1px solid #dbeafe;

  animation: slideIn .3s ease;
}

.otp-title {
  display: flex;

  align-items: center;

  gap: 10px;

  margin-bottom: 15px;
}

.otp-icon {
  width: 37px;
  height: 37px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 10px;

  background: white;

  font-size: 17px;
}

.otp-title strong {
  display: block;

  color: #1e3a8a;

  font-size: 11px;
}

.otp-title p {
  margin: 3px 0 0;

  color: #64748b;

  font-size: 9px;

  line-height: 1.5;
}

.otp-input {
  width: 100%;

  height: 59px;

  border:
    1px solid #bfdbfe;

  border-radius: 13px;

  outline: none;

  text-align: center;

  letter-spacing: 10px;

  font-size: 23px;

  font-weight: 900;

  color: #1d4ed8;

  background: white;

  transition: .2s;
}

.otp-input:focus {
  border-color: #3b82f6;

  box-shadow:
    0 0 0 4px
    rgba(59,130,246,.10);
}

.otp-input::placeholder {
  color: #cbd5e1;

  letter-spacing: 8px;
}

.otp-expired {
  display: flex;

  align-items: center;

  gap: 5px;

  margin:
    -5px 0 14px;

  color: #64748b;

  font-size: 9px;
}

.otp-expired strong {
  color: #2563eb;
}

.resend-button {
  display: block;

  margin:
    12px auto 0;

  border: none;

  cursor: pointer;

  color: #2563eb;

  background: transparent;

  font-size: 10px;

  font-weight: 800;
}

.resend-button:hover {
  text-decoration: underline;
}


/* =========================================================
   PASSWORD
========================================================= */

.show-button {
  position: absolute;

  right: 10px;
  top: 50%;

  transform: translateY(-50%);

  border: none;

  background: transparent;

  color: #2563eb;

  cursor: pointer;

  font-size: 8px;

  font-weight: 900;
}

.requirements {
  display: flex;

  flex-wrap: wrap;

  gap: 15px;

  margin:
    -2px 0 18px;
}

.requirements div {
  display: flex;

  align-items: center;

  gap: 5px;

  color: #64748b;

  font-size: 9px;
}

.requirements span {
  width: 16px;
  height: 16px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 50%;

  color: #2563eb;

  background: #dbeafe;

  font-size: 8px;

  font-weight: 900;
}


/* =========================================================
   ALERT
========================================================= */

.alert {
  display: flex;

  align-items: flex-start;

  gap: 10px;

  padding: 11px 12px;

  margin-bottom: 18px;

  border-radius: 12px;

  font-size: 10px;
}

.alert-icon {
  width: 21px;
  height: 21px;

  flex-shrink: 0;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 50%;

  color: white;

  font-size: 10px;

  font-weight: 900;
}

.alert strong {
  display: block;

  margin-bottom: 2px;

  font-size: 10px;
}

.alert p {
  margin: 0;

  line-height: 1.5;
}

.alert.error {
  color: #b91c1c;

  background: #fef2f2;

  border:
    1px solid #fecaca;
}

.alert.error .alert-icon {
  background: #dc2626;
}

.alert.success {
  color: #1d4ed8;

  background: #eff6ff;

  border:
    1px solid #bfdbfe;
}

.alert.success .alert-icon {
  background: #2563eb;
}


/* =========================================================
   COMPLETED
========================================================= */

.completed {
  text-align: center;

  padding:
    8px 5px 3px;

  animation: slideIn .4s ease;
}

.completed-icon {
  width: 78px;
  height: 78px;

  margin:
    0 auto 19px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 50%;

  color: white;

  background:
    linear-gradient(
      135deg,
      #1d4ed8,
      #3b82f6
    );

  font-size: 35px;

  font-weight: 900;

  box-shadow:
    0 15px 35px
    rgba(37,99,235,.25);
}

.completed h3 {
  margin: 0 0 8px;

  color: #111827;

  font-size: 20px;

  font-weight: 900;
}

.completed p {
  max-width: 350px;

  margin:
    0 auto 22px;

  color: #6b7280;

  font-size: 11px;

  line-height: 1.7;
}


/* =========================================================
   BACK LOGIN
========================================================= */

.back-login {
  margin-top: 21px;

  padding-top: 19px;

  border-top:
    1px solid #edf0f4;

  text-align: center;
}

.back-login button {
  border: none;

  background: transparent;

  color: #2563eb;

  cursor: pointer;

  font-size: 10px;

  font-weight: 800;

  transition: .2s;
}

.back-login button:hover {
  transform: translateX(-3px);

  color: #1d4ed8;
}


/* =========================================================
   SPINNER
========================================================= */

.spinner {
  display: inline-block;

  width: 14px;
  height: 14px;

  margin-right: 6px;

  vertical-align: -2px;

  border:
    2px solid
    rgba(255,255,255,.35);

  border-top-color: white;

  border-radius: 50%;

  animation:
    spin .7s linear infinite;
}

.spinner.blue {
  border-color:
    rgba(37,99,235,.2);

  border-top-color:
    #2563eb;
}

@keyframes spin {

  to {
    transform: rotate(360deg);
  }

}


/* =========================================================
   FOOTER
========================================================= */

footer {
  text-align: center;

  margin-top: 18px;

  color: #bfdbfe;
}

footer p {
  margin: 0;

  font-size: 9px;
}

footer span {
  display: block;

  margin-top: 4px;

  color:
    rgba(191,219,254,.55);

  font-size: 8px;
}


/* =========================================================
   ANIMATION
========================================================= */

@keyframes fadeDown {

  from {
    opacity: 0;
    transform: translateY(-15px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }

}

@keyframes cardIn {

  from {
    opacity: 0;
    transform:
      translateY(25px)
      scale(.98);
  }

  to {
    opacity: 1;
    transform:
      translateY(0)
      scale(1);
  }

}

@keyframes slideIn {

  from {
    opacity: 0;
    transform: translateX(10px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }

}

.alert-enter-active,
.alert-leave-active {
  transition: .25s ease;
}

.alert-enter-from,
.alert-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 600px) {

  .forgot-page {
    padding:
      25px 14px;
  }

  .card {
    padding:
      27px 20px;

    border-radius: 24px;
  }

  .brand h1 {
    font-size: 18px;
  }

  .brand-logo {
    width: 68px;
    height: 68px;
  }

  .logo-inner {
    font-size: 29px;
  }

  .card-header h2 {
    font-size: 21px;
  }

  .step-item > span {
    font-size: 8px;
  }

}

@media (max-width: 380px) {

  .card {
    padding:
      24px 16px;
  }

  .requirements {
    flex-direction: column;

    gap: 7px;
  }

  .step-item {
    min-width: 55px;
  }

}

</style>