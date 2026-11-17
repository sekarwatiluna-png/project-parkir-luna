<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <div class="mx-auto max-w-4xl">

      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
          Payment Member
        </h1>

        <p class="text-gray-500">
          Halaman pembayaran transaksi member
        </p>
      </div>

      <div class="rounded-xl bg-white p-6 shadow">

        <div class="mb-4">
          <label class="mb-2 block text-sm font-medium text-gray-700">
            ID Transaksi
          </label>

          <input
            v-model="form.transaksi_id"
            type="text"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-blue-500"
            placeholder="Masukkan ID transaksi"
          />
        </div>

        <div class="mb-4">
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Nominal Pembayaran
          </label>

          <input
            v-model="form.nominal"
            type="number"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-blue-500"
            placeholder="Masukkan nominal pembayaran"
          />
        </div>

        <div class="mb-6">
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Metode Pembayaran
          </label>

          <select
            v-model="form.metode_pembayaran"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-blue-500"
          >
            <option value="">Pilih metode pembayaran</option>
            <option value="cash">Cash</option>
            <option value="transfer">Transfer</option>
          </select>
        </div>

        <div class="flex gap-3">
          <NuxtLink
            to="/petugas/member/select"
            class="rounded-lg bg-gray-500 px-5 py-2 text-white hover:bg-gray-600"
          >
            Kembali
          </NuxtLink>

          <button
            @click="bayar"
            class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"
          >
            Bayar
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

const form = ref({
  transaksi_id: '',
  nominal: '',
  metode_pembayaran: ''
})

const bayar = () => {
  if (
    !form.value.transaksi_id ||
    !form.value.nominal ||
    !form.value.metode_pembayaran
  ) {
    alert('Semua data harus diisi!')
    return
  }

  alert('Pembayaran berhasil diproses!')

  console.log(form.value)
}

</script>
