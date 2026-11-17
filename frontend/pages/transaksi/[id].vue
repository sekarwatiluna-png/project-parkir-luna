<template>
  <div class="transaksi-page">

    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

      <div class="brand">
        <div class="brand-icon">🚗</div>

        <div>
          <h1>Parkir Plaza<br />Andalas</h1>
          <p>Panel Petugas</p>
        </div>
      </div>

      <div class="menu-title">
        MENU UTAMA
      </div>

      <nav class="menu">

        <NuxtLink
          to="/petugas"
          class="menu-item"
        >
          📊
          <span>Dashboard</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/transaksi"
          class="menu-item active"
        >
          👥
          <span>Kelola Transaksi</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/member"
          class="menu-item"
        >
          📥
          <span>Kelola Member</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/scan-keluar"
          class="menu-item"
        >
          📤
          <span>Scan Keluar</span>
        </NuxtLink>

        <NuxtLink
          to="/petugas/laporan"
          class="menu-item"
        >
          📋
          <span>Laporan</span>
        </NuLink>

      </nav>

      <div class="sidebar-bottom">

        <div class="petugas-card">

          <div class="petugas-icon">
            👨‍✈️
          </div>

          <div>
            <strong>Petugas Parkir</strong>
            <span>Petugas</span>
          </div>

        </div>

        <button
          class="logout-button"
          @click="logout"
        >
          🚪 Keluar
        </button>

      </div>

    </aside>


    <!-- ================= MAIN ================= -->

    <main class="main">

      <div class="top">

        <div>
          <button
            class="back-button"
            @click="router.push('/petugas')"
          >
            ← Kembali
          </button>

          <div class="breadcrumb">
            Dashboard / Kelola Transaksi
          </div>

          <h2>Kelola Transaksi</h2>

          <p>
            Kelola pembayaran member dan kendaraan non-member.
          </p>
        </div>

        <div class="role">
          👨‍✈️ Petugas
        </div>

      </div>


      <!-- ================= TAB ================= -->

      <div class="tabs">

        <button
          :class="{ selected: tab === 'nonmember' }"
          @click="tab = 'nonmember'"
        >
          🚗 Pembayaran Non-Member
        </button>

        <button
          :class="{ selected: tab === 'member' }"
          @click="tab = 'member'"
        >
          👥 Penagihan Member
        </button>

      </div>


      <!-- ================================================= -->
      <!-- NON MEMBER -->
      <!-- ================================================= -->

      <template v-if="tab === 'nonmember'">

        <div class="stats">

          <div class="stat">

            <div class="stat-icon orange">
              !
            </div>

            <div>
              <span>Belum Bayar</span>
              <strong>{{ belumBayar }}</strong>
              <small>Kendaraan masih di dalam</small>
            </div>

          </div>


          <div class="stat">

            <div class="stat-icon green">
              ✓
            </div>

            <div>
              <span>Sudah Bayar</span>
              <strong>{{ sudahBayar }}</strong>
              <small>Pembayaran berhasil</small>
            </div>

          </div>


          <div class="stat">

            <div class="stat-icon purple">
              💰
            </div>

            <div>
              <span>Total Pendapatan</span>

              <strong>
                {{ rupiah(totalPendapatan) }}
              </strong>

              <small>Pendapatan non-member</small>
            </div>

          </div>

        </div>


        <div class="card">

          <div class="card-head">

            <div>
              <h3>Pembayaran Non-Member</h3>

              <p>
                Daftar transaksi kendaraan non-member
              </p>
            </div>

            <span class="count">
              {{ tickets.length }} Transaksi
            </span>

          </div>


          <div
            v-if="loading"
            class="loading"
          >
            Memuat transaksi...
          </div>


          <div
            v-else-if="tickets.length === 0"
            class="empty"
          >
            🚗
            <h3>Belum ada transaksi</h3>
          </div>


          <div
            v-else
            class="table-wrapper"
          >

            <table>

              <thead>

                <tr>
                  <th>Kode Tiket</th>
                  <th>Plat Nomor</th>
                  <th>Waktu Masuk</th>
                  <th>Status</th>
                  <th>Biaya</th>
                  <th>Aksi</th>
                </tr>

              </thead>

              <tbody>

                <tr
                  v-for="ticket in tickets"
                  :key="ticket.id"
                >

                  <td>
                    <strong class="kode">
                      {{ ticket.kode_tiket }}
                    </strong>
                  </td>

                  <td>
                    {{ ticket.plat_nomor || '-' }}
                  </td>

                  <td>
                    {{ formatDate(ticket.waktu_masuk) }}
                  </td>

                  <td>

                    <span
                      v-if="ticket.status_bayar === 'sudah_bayar'"
                      class="badge paid"
                    >
                      ● SUDAH BAYAR
                    </span>

                    <span
                      v-else
                      class="badge unpaid"
                    >
                      ● BELUM BAYAR
                    </span>

                  </td>

                  <td>
                    <strong>
                      {{
                        ticket.biaya
                          ? rupiah(ticket.biaya)
                          : '-'
                      }}
                    </strong>
                  </td>

                  <td>

                    <!-- BELUM BAYAR -->
                    <button
                      v-if="ticket.status_bayar !== 'sudah_bayar'"
                      class="pay-button"
                      @click="openPayment(ticket)"
                    >
                      💳 Proses Bayar
                    </button>

                    <!-- SUDAH BAYAR -->
                    <span
                      v-else
                      class="done"
                    >
                      ✓ Selesai
                    </span>

                  </td>

                </tr>

              </tbody>

            </table>

          </div>

        </div>

      </template>


      <!-- ================================================= -->
      <!-- MEMBER -->
      <!-- ================================================= -->

      <template v-if="tab === 'member'">

        <div class="member-stats">

          <div class="stat">

            <div class="stat-icon orange">
              !
            </div>

            <div>
              <span>Belum Lunas</span>
              <strong>{{ belumLunas }}</strong>
              <small>Menunggu pembayaran</small>
            </div>

          </div>


          <div class="stat">

            <div class="stat-icon green">
              ✓
            </div>

            <div>
              <span>Sudah Lunas</span>
              <strong>{{ sudahLunas }}</strong>
              <small>Pembayaran lunas</small>
            </div>

          </div>


          <div class="stat">

            <div class="stat-icon purple">
              💰
            </div>

            <div>
              <span>Total Pendapatan</span>

              <strong>
                {{ rupiah(totalMemberPayment) }}
              </strong>

              <small>Pendapatan member</small>
            </div>

          </div>

        </div>


        <div class="card">

          <div class="card-head">

            <div>
              <h3>Penagihan Member</h3>

              <p>
                Daftar tagihan member parkir
              </p>
            </div>

            <span class="count">
              {{ members.length }} Member
            </span>

          </div>


          <div
            v-if="loadingMembers"
            class="loading"
          >
            Memuat member...
          </div>


          <div
            v-else
            class="table-wrapper"
          >

            <table>

              <thead>

                <tr>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Perusahaan</th>
                  <th>Tagihan</th>
                  <th>Status</th>
                  <th>Expired</th>
                  <th>Aksi</th>
                </tr>

              </thead>


              <tbody>

                <tr
                  v-for="member in members"
                  :key="member.id"
                >

                  <td>
                    <strong class="kode">
                      {{ member.kode_member }}
                    </strong>
                  </td>

                  <td>
                    <strong>
                      {{ member.nama_member }}
                    </strong>
                  </td>

                  <td>
                    {{ member.nama_perusahaan || '-' }}
                  </td>

                  <td>
                    <strong>
                      {{ rupiah(member.total_harga) }}
                    </strong>
                  </td>

                  <td>

                    <span
                      v-if="isLunas(member)"
                      class="badge paid"
                    >
                      ● LUNAS
                    </span>

                    <span
                      v-else
                      class="badge unpaid"
                    >
                      ● BELUM LUNAS
                    </span>

                  </td>

                  <td>
                    {{ formatDate(member.tanggal_expired) }}
                  </td>

                  <td>

                    <button
                      v-if="!isLunas(member)"
                      class="pay-button"
                      @click="openMemberPayment(member)"
                    >
                      💳 Tagih / Bayar
                    </button>

                    <span
                      v-else
                      class="done"
                    >
                      ✓ Lunas
                    </span>

                  </td>

                </tr>

              </tbody>

            </table>

          </div>

        </div>

      </template>

    </main>


    <!-- ================================================= -->
    <!-- MODAL PEMBAYARAN -->
    <!-- ================================================= -->

    <div
      v-if="showPayment"
      class="modal-overlay"
      @click.self="closePayment"
    >

      <div class="modal">

        <button
          class="close"
          @click="closePayment"
        >
          ×
        </button>

        <div class="modal-icon">
          💳
        </div>

        <h3>
          {{ paymentType === 'member'
            ? 'Pembayaran Member'
            : 'Pembayaran Parkir'
          }}
        </h3>

        <p class="modal-subtitle">
          Silakan masukkan jumlah uang yang dibayarkan.
        </p>


        <!-- INFO -->

        <div class="payment-info">

          <div>
            <span>Kode</span>

            <strong>
              {{
                paymentType === 'member'
                  ? selectedMember?.kode_member
                  : selectedTicket?.kode_tiket
              }}
            </strong>
          </div>

          <div>
            <span>Nama</span>

            <strong>
              {{
                paymentType === 'member'
                  ? selectedMember?.nama_member
                  : selectedTicket?.plat_nomor || '-'
              }}
            </strong>
          </div>

          <div>
            <span>Total Tagihan</span>

            <strong class="total">
              {{
                rupiah(
                  paymentType === 'member'
                    ? selectedMember?.total_harga
                    : selectedTicket?.biaya
                )
              }}
            </strong>
          </div>

        </div>


        <!-- JUMLAH BAYAR -->

        <label>
          Jumlah Bayar
        </label>

        <input
          v-model.number="jumlahBayar"
          type="number"
          min="0"
          placeholder="Masukkan jumlah uang"
          class="payment-input"
        />


        <!-- KEMBALIAN -->

        <div
          v-if="jumlahBayar > 0"
          class="change-box"
          :class="{
            kurang:
              jumlahBayar < paymentAmount,
            cukup:
              jumlahBayar >= paymentAmount
          }"
        >

          <span>
            {{
              jumlahBayar < paymentAmount
                ? 'Kekurangan'
                : 'Kembalian'
            }}
          </span>

          <strong>
            {{
              rupiah(
                Math.abs(jumlahBayar - paymentAmount)
              )
            }}
          </strong>

        </div>


        <!-- ERROR -->

        <div
          v-if="paymentError"
          class="payment-error"
        >
          ⚠️ {{ paymentError }}
        </div>


        <!-- BUTTON -->

        <button
          class="confirm-pay"
          :disabled="
            paymentLoading ||
            jumlahBayar < paymentAmount
          "
          @click="processPayment"
        >

          <span v-if="paymentLoading">
            Memproses...
          </span>

          <span v-else>
            💳 Bayar Sekarang
          </span>

        </button>

      </div>

    </div>

  </div>
</template>


<script setup>

import { computed, onMounted, ref } from 'vue'

const router = useRouter()

const API_URL = 'http://127.0.0.1:8000/api'


// =====================================================
// TAB
// =====================================================

const tab = ref('nonmember')


// =====================================================
// TICKET
// =====================================================

const tickets = ref([])
const loading = ref(false)


// =====================================================
// MEMBER
// =====================================================

const members = ref([])
const loadingMembers = ref(false)


// =====================================================
// PAYMENT
// =====================================================

const showPayment = ref(false)

const paymentType = ref('ticket')

const selectedTicket = ref(null)
const selectedMember = ref(null)

const jumlahBayar = ref(0)

const paymentLoading = ref(false)

const paymentError = ref('')


// =====================================================
// TOKEN
// =====================================================

const token = () => {
  return localStorage.getItem('token') || ''
}


// =====================================================
// FETCH TICKET
// =====================================================

const getTickets = async () => {

  loading.value = true

  try {

    const response = await fetch(
      `${API_URL}/tiketparkir`,
      {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${token()}`
        }
      }
    )

    if (!response.ok) {
      throw new Error(
        `Gagal mengambil tiket (${response.status})`
      )
    }

    const result = await response.json()

    if (Array.isArray(result)) {
      tickets.value = result
    } else if (Array.isArray(result.data)) {
      tickets.value = result.data
    } else {
      tickets.value = []
    }

  } catch (error) {

    console.error(error)

    alert(
      error.message ||
      'Gagal mengambil data tiket.'
    )

  } finally {

    loading.value = false

  }
}


// =====================================================
// FETCH MEMBER
// =====================================================

const getMembers = async () => {

  loadingMembers.value = true

  try {

    const response = await fetch(
      `${API_URL}/members`,
      {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${token()}`
        }
      }
    )

    if (!response.ok) {
      throw new Error(
        `Gagal mengambil member (${response.status})`
      )
    }

    const result = await response.json()

    if (Array.isArray(result)) {
      members.value = result
    } else if (Array.isArray(result.data)) {
      members.value = result.data
    } else {
      members.value = []
    }

  } catch (error) {

    console.error(error)

    alert(
      error.message ||
      'Gagal mengambil data member.'
    )

  } finally {

    loadingMembers.value = false

  }
}


// =====================================================
// STAT TICKET
// =====================================================

const belumBayar = computed(() => {

  return tickets.value.filter(
    ticket =>
      ticket.status_bayar !== 'sudah_bayar'
  ).length

})


const sudahBayar = computed(() => {

  return tickets.value.filter(
    ticket =>
      ticket.status_bayar === 'sudah_bayar'
  ).length

})


const totalPendapatan = computed(() => {

  return tickets.value
    .filter(
      ticket =>
        ticket.status_bayar === 'sudah_bayar'
    )
    .reduce(
      (total, ticket) =>
        total + Number(ticket.biaya || 0),
      0
    )

})


// =====================================================
// STAT MEMBER
// =====================================================

const isLunas = (member) => {

  return String(
    member?.status || ''
  ).toLowerCase() === 'lunas'

}


const belumLunas = computed(() => {

  return members.value.filter(
    member => !isLunas(member)
  ).length

})


const sudahLunas = computed(() => {

  return members.value.filter(
    member => isLunas(member)
  ).length

})


const totalMemberPayment = computed(() => {

  return members.value
    .filter(member => isLunas(member))
    .reduce(
      (total, member) =>
        total + Number(member.jumlah_bayar || 0),
      0
    )

})


// =====================================================
// PAYMENT AMOUNT
// =====================================================

const paymentAmount = computed(() => {

  if (paymentType.value === 'member') {

    return Number(
      selectedMember.value?.total_harga || 0
    )

  }

  return Number(
    selectedTicket.value?.biaya || 0
  )

})


// =====================================================
// OPEN TICKET PAYMENT
// =====================================================

const openPayment = (ticket) => {

  if (ticket.status_bayar === 'sudah_bayar') {
    return
  }

  selectedTicket.value = ticket
  selectedMember.value = null

  paymentType.value = 'ticket'

  jumlahBayar.value = 0

  paymentError.value = ''

  showPayment.value = true

}


// =====================================================
// OPEN MEMBER PAYMENT
// =====================================================

const openMemberPayment = (member) => {

  if (isLunas(member)) {
    alert(
      'Member ini sudah lunas.'
    )

    return
  }

  selectedMember.value = member
  selectedTicket.value = null

  paymentType.value = 'member'

  jumlahBayar.value = 0

  paymentError.value = ''

  showPayment.value = true

}


// =====================================================
// CLOSE
// =====================================================

const closePayment = () => {

  if (paymentLoading.value) {
    return
  }

  showPayment.value = false

  selectedTicket.value = null
  selectedMember.value = null

  jumlahBayar.value = 0

  paymentError.value = ''

}


// =====================================================
// PROCESS PAYMENT
// =====================================================

const processPayment = async () => {

  paymentError.value = ''

  if (!jumlahBayar.value) {

    paymentError.value =
      'Jumlah pembayaran wajib diisi.'

    return

  }

  if (jumlahBayar.value < paymentAmount.value) {

    paymentError.value =
      'Jumlah uang masih kurang.'

    return

  }

  paymentLoading.value = true

  try {

    let url = ''

    if (paymentType.value === 'ticket') {

      url =
        `${API_URL}/tiketparkir/${selectedTicket.value.id}/bayar`

    } else {

      url =
        `${API_URL}/members/${selectedMember.value.id}/pembayaran`

    }


    const response = await fetch(
      url,
      {
        method:
          paymentType.value === 'ticket'
            ? 'POST'
            : 'PUT',

        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${token()}`
        },

        body: JSON.stringify({
          jumlah_bayar: jumlahBayar.value
        })
      }
    )


    const result =
      await response.json().catch(() => null)


    if (!response.ok) {

      throw new Error(
        result?.message ||
        'Pembayaran gagal.'
      )

    }


    alert(
      result?.message ||
      'Pembayaran berhasil!'
    )


    closePayment()


    await Promise.all([
      getTickets(),
      getMembers()
    ])


  } catch (error) {

    console.error(error)

    paymentError.value =
      error?.message ||
      'Pembayaran gagal diproses.'

  } finally {

    paymentLoading.value = false

  }

}


// =====================================================
// RUPIAH
// =====================================================

const rupiah = (value) => {

  return new Intl.NumberFormat(
    'id-ID',
    {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }
  ).format(
    Number(value) || 0
  )

}


// =====================================================
// DATE
// =====================================================

const formatDate = (value) => {

  if (!value) {
    return '-'
  }

  const date = new Date(value)

  if (isNaN(date.getTime())) {
    return value
  }

  return new Intl.DateTimeFormat(
    'id-ID',
    {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }
  ).format(date)

}


// =====================================================
// LOGOUT
// =====================================================

const logout = () => {

  localStorage.removeItem('token')
  localStorage.removeItem('user')

  router.push('/')

}


// =====================================================
// LOAD
// =====================================================

onMounted(() => {

  getTickets()
  getMembers()

})

</script>


<style scoped>

* {
  box-sizing: border-box;
}

.transaksi-page {
  min-height: 100vh;
  background: #f4f7fc;
  color: #172554;
  font-family: Arial, Helvetica, sans-serif;
}


/* SIDEBAR */

.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  bottom: 0;

  width: 263px;

  background: #1d3368;
  color: white;

  display: flex;
  flex-direction: column;

  z-index: 20;
}

.brand {
  display: flex;
  gap: 13px;
  align-items: center;

  padding: 23px;
  border-bottom: 1px solid rgba(255,255,255,.12);
}

.brand-icon {
  width: 48px;
  height: 48px;

  background: #2864ed;
  border-radius: 14px;

  display: flex;
  align-items: center;
  justify-content: center;

  font-size: 25px;
}

.brand h1 {
  margin: 0;

  font-size: 21px;
  line-height: 1.1;
}

.brand p {
  margin: 5px 0 0;

  font-size: 12px;
  color: #d5e2ff;
}

.menu-title {
  padding: 22px 23px 10px;

  font-size: 12px;
  font-weight: 800;
}

.menu {
  display: flex;
  flex-direction: column;
}

.menu-item {
  min-height: 61px;

  padding: 0 24px;

  display: flex;
  align-items: center;
  gap: 18px;

  color: white;
  text-decoration: none;

  font-size: 16px;
}

.menu-item.active {
  background: #2864ed;
}

.menu-item:hover {
  background: rgba(255,255,255,.08);
}

.sidebar-bottom {
  margin-top: auto;
  padding: 19px;
}

.petugas-card {
  padding: 11px;

  display: flex;
  align-items: center;
  gap: 10px;

  background: rgba(255,255,255,.09);
  border-radius: 13px;
}

.petugas-icon {
  width: 40px;
  height: 40px;

  border-radius: 50%;

  background: #2864ed;

  display: flex;
  align-items: center;
  justify-content: center;
}

.petugas-card strong,
.petugas-card span {
  display: block;
}

.petugas-card strong {
  font-size: 13px;
}

.petugas-card span {
  font-size: 11px;
  margin-top: 3px;
}

.logout-button {
  margin-top: 9px;

  width: 100%;
  height: 42px;

  background: transparent;
  border: 1px solid rgba(255,255,255,.15);

  color: #ffd0d0;

  border-radius: 9px;

  cursor: pointer;
}


/* MAIN */

.main {
  margin-left: 263px;
  padding: 32px 44px 60px;
}

.top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.back-button {
  border: none;
  background: transparent;

  color: #2563eb;

  font-weight: 700;

  cursor: pointer;
}

.breadcrumb {
  margin-top: 13px;

  font-size: 12px;
  color: #8996ab;
}

.top h2 {
  margin: 9px 0 3px;

  font-size: 28px;
}

.top p {
  margin: 0;

  color: #71809a;
  font-size: 14px;
}

.role {
  padding: 11px 19px;

  background: #e0edff;
  color: #2563eb;

  border-radius: 25px;

  font-size: 13px;
  font-weight: 700;
}


/* TABS */

.tabs {
  margin-top: 28px;

  padding: 7px;

  background: white;

  border-radius: 14px;

  display: grid;
  grid-template-columns: 1fr 1fr;

  box-shadow: 0 5px 18px rgba(30,60,100,.05);
}

.tabs button {
  height: 49px;

  border: none;
  background: transparent;

  border-radius: 10px;

  font-size: 14px;
  font-weight: 700;

  color: #64748b;

  cursor: pointer;
}

.tabs button.selected {
  background: linear-gradient(
    90deg,
    #2864ed,
    #4538d5
  );

  color: white;
}


/* STATS */

.stats,
.member-stats {
  margin-top: 20px;

  display: grid;
  grid-template-columns: repeat(3, 1fr);

  gap: 18px;
}

.stat {
  min-height: 118px;

  padding: 21px;

  background: white;

  border-radius: 15px;

  display: flex;
  align-items: center;

  gap: 15px;

  box-shadow: 0 5px 18px rgba(30,60,100,.05);
}

.stat-icon {
  width: 52px;
  height: 52px;

  border-radius: 14px;

  display: flex;
  align-items: center;
  justify-content: center;

  font-size: 22px;
  font-weight: 800;
}

.stat-icon.orange {
  background: #fff0d9;
  color: #f97316;
}

.stat-icon.green {
  background: #dcfce7;
  color: #16a34a;
}

.stat-icon.purple {
  background: #eee7ff;
}

.stat span,
.stat small {
  display: block;
}

.stat span {
  font-size: 12px;
  color: #64748b;
}

.stat strong {
  display: block;

  margin-top: 4px;

  font-size: 23px;
  color: #172554;
}

.stat small {
  margin-top: 5px;

  color: #9aa7bc;
  font-size: 10px;
}


/* CARD */

.card {
  margin-top: 21px;

  background: white;

  border-radius: 16px;

  padding: 25px;

  box-shadow: 0 5px 18px rgba(30,60,100,.05);
}

.card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;

  margin-bottom: 20px;
}

.card-head h3 {
  margin: 0;

  font-size: 19px;
}

.card-head p {
  margin: 5px 0 0;

  color: #9aa7bc;
  font-size: 12px;
}

.count {
  padding: 9px 14px;

  background: #edf5ff;

  color: #2563eb;

  border-radius: 20px;

  font-size: 11px;
  font-weight: 700;
}


/* TABLE */

.table-wrapper {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;

  min-width: 850px;
}

thead {
  background: linear-gradient(
    90deg,
    #2864ed,
    #4438ca
  );

  color: white;
}

th {
  padding: 14px;

  text-align: left;

  font-size: 11px;
}

th:first-child {
  border-radius: 10px 0 0 10px;
}

th:last-child {
  border-radius: 0 10px 10px 0;
}

td {
  padding: 17px 14px;

  border-bottom: 1px solid #edf1f6;

  font-size: 12px;
}

.kode {
  color: #2563eb;
}

.badge {
  display: inline-block;

  padding: 7px 10px;

  border-radius: 8px;

  font-size: 9px;
  font-weight: 800;
}

.badge.paid {
  background: #dcfce7;
  color: #15803d;
}

.badge.unpaid {
  background: #fff0dc;
  color: #c2410c;
}


/* PAY BUTTON */

.pay-button {
  min-width: 125px;

  padding: 9px 13px;

  border: none;

  border-radius: 9px;

  background: linear-gradient(
    90deg,
    #2864ed,
    #4438ca
  );

  color: white;

  font-size: 11px;
  font-weight: 700;

  cursor: pointer;

  transition: .2s;
}

.pay-button:hover {
  transform: translateY(-1px);

  box-shadow: 0 5px 12px rgba(37,99,235,.25);
}

.done {
  color: #16a34a;

  font-size: 11px;
  font-weight: 800;
}


/* LOADING */

.loading {
  padding: 60px;

  text-align: center;

  color: #64748b;
}

.empty {
  padding: 60px;

  text-align: center;

  color: #64748b;
}


/* MODAL */

.modal-overlay {
  position: fixed;
  inset: 0;

  background: rgba(15,23,42,.5);

  display: flex;
  align-items: center;
  justify-content: center;

  z-index: 100;
}

.modal {
  position: relative;

  width: 430px;
  max-width: calc(100vw - 30px);

  padding: 30px;

  background: white;

  border-radius: 20px;

  box-shadow: 0 25px 70px rgba(0,0,0,.2);
}

.close {
  position: absolute;

  right: 18px;
  top: 15px;

  width: 32px;
  height: 32px;

  border: none;

  background: #f1f5f9;

  border-radius: 50%;

  font-size: 22px;

  cursor: pointer;
}

.modal-icon {
  width: 58px;
  height: 58px;

  margin: 0 auto 14px;

  border-radius: 16px;

  background: #e0edff;

  display: flex;
  align-items: center;
  justify-content: center;

  font-size: 26px;
}

.modal h3 {
  margin: 0;

  text-align: center;

  font-size: 21px;
}

.modal-subtitle {
  text-align: center;

  color: #64748b;

  font-size: 12px;

  margin: 8px 0 22px;
}

.payment-info {
  background: #f8fafc;

  border-radius: 12px;

  padding: 15px;

  margin-bottom: 20px;
}

.payment-info div {
  display: flex;

  justify-content: space-between;

  padding: 7px 0;

  border-bottom: 1px solid #e8edf3;
}

.payment-info div:last-child {
  border-bottom: none;
}

.payment-info span {
  color: #64748b;

  font-size: 12px;
}

.payment-info strong {
  font-size: 12px;
}

.payment-info .total {
  color: #2563eb;

  font-size: 15px;
}

.modal label {
  display: block;

  margin-bottom: 8px;

  font-size: 13px;

  font-weight: 700;
}

.payment-input {
  width: 100%;

  height: 49px;

  padding: 0 15px;

  border: 1px solid #d7e0ec;

  border-radius: 10px;

  outline: none;

  font-size: 15px;
}

.payment-input:focus {
  border-color: #2563eb;
}

.change-box {
  margin-top: 12px;

  padding: 13px 15px;

  border-radius: 10px;

  display: flex;
  justify-content: space-between;
}

.change-box.cukup {
  background: #dcfce7;
  color: #15803d;
}

.change-box.kurang {
  background: #fee2e2;
  color: #dc2626;
}

.change-box span {
  font-size: 12px;
}

.change-box strong {
  font-size: 13px;
}

.payment-error {
  margin-top: 12px;

  padding: 11px;

  background: #fee2e2;

  color: #dc2626;

  border-radius: 9px;

  font-size: 12px;
}

.confirm-pay {
  width: 100%;

  height: 48px;

  margin-top: 17px;

  border: none;

  border-radius: 10px;

  background: linear-gradient(
    90deg,
    #2864ed,
    #4438ca
  );

  color: white;

  font-weight: 800;

  cursor: pointer;
}

.confirm-pay:disabled {
  opacity: .45;

  cursor: not-allowed;
}


/* RESPONSIVE */

@media (max-width: 1000px) {

  .sidebar {
    width: 220px;
  }

  .main {
    margin-left: 220px;
    padding: 25px;
  }

  .stats,
  .member-stats {
    grid-template-columns: 1fr;
  }

}

@media (max-width: 700px) {

  .sidebar {
    display: none;
  }

  .main {
    margin-left: 0;
  }

  .tabs {
    grid-template-columns: 1fr;
  }

}

</style>