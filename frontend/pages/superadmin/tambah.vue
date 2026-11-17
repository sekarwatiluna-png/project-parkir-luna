<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100">

    <!-- ================= HEADER ================= -->
    <header
      class="sticky top-0 z-40 border-b border-white/60 bg-white/80 backdrop-blur-xl shadow-sm"
    >
      <div
        class="max-w-7xl mx-auto px-5 md:px-8 py-4 flex items-center justify-between"
      >

        <div class="flex items-center gap-3">
          <div
            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-600 flex items-center justify-center text-2xl shadow-lg"
          >
            🅿️
          </div>

          <div>
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-800">
              ParkirKu
            </h1>

            <p class="text-xs md:text-sm text-slate-500">
              Super Admin
            </p>
          </div>
        </div>

        <button
          type="button"
          @click="kembali"
          class="flex items-center gap-2 px-4 md:px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold shadow-lg transition"
        >
          ←
          <span class="hidden sm:inline">
            Kembali
          </span>
        </button>

      </div>
    </header>


    <!-- ================= CONTENT ================= -->
    <main class="max-w-5xl mx-auto px-5 md:px-8 py-8">

      <!-- BREADCRUMB -->
      <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <button
          type="button"
          @click="router.push('/superadmin')"
          class="hover:text-indigo-600 transition"
        >
          Dashboard
        </button>

        <span>›</span>

        <button
          type="button"
          @click="router.push('/superadmin/petugas')"
          class="hover:text-indigo-600 transition"
        >
          Kelola Petugas
        </button>

        <span>›</span>

        <span class="font-semibold text-slate-700">
          Tambah Petugas
        </span>
      </div>


      <!-- ================= TITLE ================= -->
      <section class="mb-7">

        <div class="flex items-start gap-4">

          <div
            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-600 text-white flex items-center justify-center text-2xl shadow-xl shrink-0"
          >
            👨‍💼
          </div>

          <div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">
              Tambah Petugas
            </h2>

            <p class="mt-2 text-slate-500">
              Buat akun petugas baru untuk mengelola sistem parkir Plaza Andalas.
            </p>
          </div>

        </div>

      </section>


      <!-- ================= FORM CARD ================= -->
      <section
        class="bg-white/90 backdrop-blur-xl rounded-3xl border border-white shadow-2xl overflow-hidden"
      >

        <!-- CARD HEADER -->
        <div
          class="bg-gradient-to-r from-indigo-700 via-blue-600 to-cyan-500 px-6 md:px-8 py-6 text-white"
        >

          <div class="flex items-center gap-3">

            <div
              class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center text-xl"
            >
              📝
            </div>

            <div>
              <h3 class="text-lg md:text-xl font-bold">
                Informasi Akun Petugas
              </h3>

              <p class="text-sm text-blue-100 mt-1">
                Isi semua data dengan lengkap.
              </p>
            </div>

          </div>

        </div>


        <!-- FORM -->
        <form
          @submit.prevent="simpanPetugas"
          class="p-6 md:p-8"
        >

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- NAMA -->
            <div>

              <label class="block text-sm font-bold text-slate-700 mb-2">
                Nama Petugas
                <span class="text-red-500">*</span>
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-lg"
                >
                  👤
                </span>

                <input
                  v-model="form.name"
                  type="text"
                  autocomplete="name"
                  placeholder="Masukkan nama petugas"
                  class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition"
                />

              </div>

              <p
                v-if="errors.name"
                class="mt-2 text-sm text-red-500"
              >
                {{ errors.name }}
              </p>

            </div>


            <!-- USERNAME -->
            <div>

              <label class="block text-sm font-bold text-slate-700 mb-2">
                Username
                <span class="text-red-500">*</span>
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-lg"
                >
                  🔑
                </span>

                <input
                  v-model="form.username"
                  type="text"
                  autocomplete="username"
                  placeholder="Masukkan username"
                  class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition"
                />

              </div>

              <p
                v-if="errors.username"
                class="mt-2 text-sm text-red-500"
              >
                {{ errors.username }}
              </p>

            </div>


            <!-- EMAIL -->
            <div>

              <label class="block text-sm font-bold text-slate-700 mb-2">
                Email
                <span class="text-red-500">*</span>
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-lg"
                >
                  ✉️
                </span>

                <input
                  v-model="form.email"
                  type="email"
                  autocomplete="email"
                  placeholder="contoh@email.com"
                  class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition"
                />

              </div>

              <p
                v-if="errors.email"
                class="mt-2 text-sm text-red-500"
              >
                {{ errors.email }}
              </p>

            </div>


            <!-- PASSWORD -->
            <div>

              <label class="block text-sm font-bold text-slate-700 mb-2">
                Password
                <span class="text-red-500">*</span>
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-lg"
                >
                  🔒
                </span>

                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  autocomplete="new-password"
                  placeholder="Minimal 6 karakter"
                  class="w-full pl-12 pr-12 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition"
                />

                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition"
                >
                  {{ showPassword ? '🙈' : '👁️' }}
                </button>

              </div>

              <p
                v-if="errors.password"
                class="mt-2 text-sm text-red-500"
              >
                {{ errors.password }}
              </p>

            </div>


            <!-- STATUS -->
            <div>

              <label class="block text-sm font-bold text-slate-700 mb-2">
                Status
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-lg"
                >
                  ⚡
                </span>

                <select
                  v-model="form.status"
                  class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition appearance-none"
                >
                  <option value="aktif">
                    Aktif
                  </option>

                  <option value="nonaktif">
                    Nonaktif
                  </option>
                </select>

              </div>

            </div>


            <!-- ROLE -->
            <div>

              <label class="block text-sm font-bold text-slate-700 mb-2">
                Role
              </label>

              <div
                class="flex items-center gap-3 px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-100"
              >

                <span class="text-lg">
                  👨‍💼
                </span>

                <div>
                  <p class="font-bold text-slate-700">
                    Petugas
                  </p>

                  <p class="text-xs text-slate-500">
                    Role otomatis sebagai petugas parkir
                  </p>
                </div>

              </div>

            </div>

          </div>


          <!-- INFO -->
          <div
            class="mt-7 p-4 rounded-2xl bg-blue-50 border border-blue-100"
          >

            <div class="flex gap-3">

              <div class="text-xl">
                ℹ️
              </div>

              <div>

                <p class="font-bold text-blue-800">
                  Informasi
                </p>

                <p class="text-sm text-blue-700 mt-1">
                  Username dan email harus berbeda dari akun yang sudah terdaftar.
                </p>

              </div>

            </div>

          </div>


          <!-- ERROR SERVER -->
          <div
            v-if="serverError"
            class="mt-5 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700"
          >

            <div class="flex gap-3">

              <span class="text-xl">
                ⚠️
              </span>

              <div>
                <p class="font-bold">
                  Gagal menyimpan data
                </p>

                <p class="text-sm mt-1 whitespace-pre-line">
                  {{ serverError }}
                </p>
              </div>

            </div>

          </div>


          <!-- BUTTON -->
          <div
            class="mt-8 pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3"
          >

            <button
              type="button"
              @click="kembali"
              :disabled="loading"
              class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition disabled:opacity-50"
            >
              Batal
            </button>

            <button
              type="submit"
              :disabled="loading"
              class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold shadow-lg hover:shadow-xl transition disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >

              <span
                v-if="loading"
                class="w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin"
              ></span>

              <span v-if="loading">
                Menyimpan...
              </span>

              <span v-else>
                ✓ Simpan Petugas
              </span>

            </button>

          </div>

        </form>

      </section>

    </main>

  </div>
</template>


<script setup lang="ts">

import { ref } from 'vue'


/* =========================================================
   NUXT
========================================================= */

const router = useRouter()

const { $api } = useNuxtApp()


/* =========================================================
   FORM
========================================================= */

const form = ref({
  name: '',
  username: '',
  email: '',
  password: '',
  status: 'aktif',
})


/* =========================================================
   STATE
========================================================= */

const loading = ref(false)

const showPassword = ref(false)

const serverError = ref('')


const errors = ref<Record<string, string>>({
  name: '',
  username: '',
  email: '',
  password: '',
})


/* =========================================================
   CLEAR ERROR
========================================================= */

const clearErrors = () => {

  errors.value = {
    name: '',
    username: '',
    email: '',
    password: '',
  }

  serverError.value = ''

}


/* =========================================================
   VALIDASI
========================================================= */

const validateForm = () => {

  clearErrors()

  let valid = true


  if (!form.value.name.trim()) {

    errors.value.name =
      'Nama petugas wajib diisi.'

    valid = false

  }


  if (!form.value.username.trim()) {

    errors.value.username =
      'Username wajib diisi.'

    valid = false

  }


  if (!form.value.email.trim()) {

    errors.value.email =
      'Email wajib diisi.'

    valid = false

  } else {

    const emailRegex =
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/

    if (!emailRegex.test(form.value.email)) {

      errors.value.email =
        'Format email tidak valid.'

      valid = false

    }

  }


  if (!form.value.password) {

    errors.value.password =
      'Password wajib diisi.'

    valid = false

  } else if (form.value.password.length < 6) {

    errors.value.password =
      'Password minimal 6 karakter.'

    valid = false

  }


  return valid

}


/* =========================================================
   KEMBALI
========================================================= */

const kembali = () => {

  router.push('/superadmin/petugas')

}


/* =========================================================
   SIMPAN PETUGAS
========================================================= */

const simpanPetugas = async () => {

  if (!validateForm()) {
    return
  }


  loading.value = true

  serverError.value = ''


  try {

    /*
     * PENTING:
     * $api di project ini digunakan sebagai function.
     *
     * Jadi:
     * $api(...)
     *
     * BUKAN:
     * $api.post(...)
     */

    const payload = {
      name: form.value.name.trim(),
      username: form.value.username.trim(),
      email: form.value.email.trim(),
      password: form.value.password,
      role: 'petugas',
      status: form.value.status,
    }


    console.log(
      'PAYLOAD TAMBAH PETUGAS:',
      payload
    )


    const response = await $api(
      '/petugas',
      {
        method: 'POST',
        body: payload,
      }
    )


    console.log(
      'RESPONSE TAMBAH PETUGAS:',
      response
    )


    alert(
      response?.message ||
      'Petugas berhasil ditambahkan.'
    )


    await router.push(
      '/superadmin/petugas'
    )

  }

  catch (error: any) {

    console.error(
      'ERROR TAMBAH PETUGAS:',
      error
    )


    const data =
      error?.response?._data ||
      error?.response?.data ||
      error?.data ||
      {}


    /*
     * Laravel validation:
     *
     * {
     *   message: "...",
     *   errors: {
     *      username: ["..."],
     *      email: ["..."]
     *   }
     * }
     */

    if (data.errors) {

      const validationErrors =
        data.errors


      if (
        validationErrors.name
      ) {

        errors.value.name =
          Array.isArray(
            validationErrors.name
          )
            ? validationErrors.name[0]
            : validationErrors.name

      }


      if (
        validationErrors.username
      ) {

        errors.value.username =
          Array.isArray(
            validationErrors.username
          )
            ? validationErrors.username[0]
            : validationErrors.username

      }


      if (
        validationErrors.email
      ) {

        errors.value.email =
          Array.isArray(
            validationErrors.email
          )
            ? validationErrors.email[0]
            : validationErrors.email

      }


      if (
        validationErrors.password
      ) {

        errors.value.password =
          Array.isArray(
            validationErrors.password
          )
            ? validationErrors.password[0]
            : validationErrors.password

      }


      serverError.value =
        Object.values(validationErrors)
          .flat()
          .join('\n')

    } else {

      serverError.value =
        data.message ||
        error?.message ||
        'Gagal menambahkan petugas.'

    }

  }

  finally {

    loading.value = false

  }

}

</script>