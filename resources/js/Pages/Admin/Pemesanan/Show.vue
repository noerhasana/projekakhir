<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Carousel from 'primevue/carousel';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const toast = useToast();
const page = usePage()
const props = defineProps({ 
    pemesanan: Object,
    statusPesanan: Object,
});

const statusColors = ref({
    SUDAH_DIBAYAR:  '#84cc16',
    PROSES:  '#f97316',
    MENUNGGU_PENJEMPUTAN_KURIR:  '#0ea5e9',
    DI_ANTAR:  '#d946ef',
    SELESAI:  '#22c55e',
})

const selectedStatus = ref();

const pilihanKurir = computed(() => JSON.parse(props.pemesanan.pilihan_paket_pengiriman))

function changeStatus(event) {

    axios.put(route('pemesanan.update', {pemesanan: props.pemesanan.id}), {
            'kode_status': event.value.kode,
        })
        .then(response => {
            response = response.data;
            props.pemesanan.kode_status = event.value.kode;
        });
}

onMounted(() => {
})

</script>

<template>
    <Head title="Pemesanan" />

    <AuthenticatedLayout>
        <template #name>Pemesanan</template>

        <main class="container py-16 mx-auto">
            <div class="mb-8">
                <Link :href="route('pemesanan.index')">
                    <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
                </Link>
            </div>
            <div class="mb-12">
                <h2 class="text-5xl font-bold text-center">Detail Pemesanan</h2>
            </div>
            <div class="grid lg:grid-cols-2 gap-8 py-8">
                <div class="card">
                    <h2 class="text-xl font-semibold mb-5 text-gray-500">Data Pelanggan</h2>
                    <div class="my-4 grid grid-cols-2 gap-y-4 w-fit">
                        <p class="m-0 p-0 text-gray-600">Nama</p>
                        <p class="m-0 p-0 text-gray-800 capitalize ">{{pemesanan.user.name}}</p>
                        <p class="m-0 p-0 text-gray-600">Email</p>
                        <p class="m-0 p-0 text-gray-800">{{pemesanan.user.email}}</p>
                        <p class="m-0 p-0 text-gray-600">No handphone</p>
                        <p class="m-0 p-0 text-gray-800">{{pemesanan.user.no_hp}}</p>
                        <p class="m-0 p-0 text-gray-600">Alamat Tujuan</p>
                        <div class="">
                            <p class="m-0 p-0 text-gray-800 font-semibold">{{pemesanan.alamat_tujuan.nama}}</p>
                            <p class="m-0 p-0 text-gray-800">{{pemesanan.alamat_tujuan.alamat}}</p>
                            <p class="m-0 p-0 text-gray-800">{{pemesanan.alamat_tujuan.provinsi}}</p>
                            <p class="m-0 p-0 text-gray-800">{{pemesanan.alamat_tujuan.kabupaten}}</p>
                            <p class="m-0 p-0 text-gray-800">{{pemesanan.alamat_tujuan.kecamatan}}</p>
                            <p class="m-0 p-0 text-gray-800">{{pemesanan.alamat_tujuan.desa}}</p>
                        </div>
                        <p class="m-0 p-0 text-gray-600">Pilihan Kurir</p>
                        <p class="m-0 p-0 text-gray-800 font-semibold">{{ pilihanKurir.logistic.name }} - {{ pilihanKurir.rate.name }}</p>
                        
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-0 mt-8 w-fit">
                        <p class="m-0 p-0 text-gray-600">Total harga barang</p>
                        <p class="m-0 p-0 text-end">Rp {{ pemesanan.jumlah_harga_barang }}</p>
                        <p class="m-0 p-0 text-gray-600">Total ongkos kirim</p>
                        <p class="m-0 p-0 text-end">Rp {{ pemesanan.jumlah_ongkir }}</p>
                        <p class="font-semibold  text-gray-600">Total tagihan</p>
                        <p class="font-bold text-end">Rp {{ pemesanan.jumlah_harga_barang + pemesanan.jumlah_ongkir }}</p>
                    </div>
                </div>
                <div class="card">
                    <h2 class="text-xl font-semibold mb-5 text-gray-500">Status pemesanan</h2>
                    <div class="mb-4 ">
                        <p class="w-fit p-3 rounded text-sm text-white capitalize" :style="'background-color: ' +statusColors[pemesanan.kode_status]">
                            {{pemesanan.kode_status.replaceAll('_', ' ')}}
                        </p>
                    </div>
                    <div v-if="['PROSES', 'SUDAH_DIBAYAR'].includes(pemesanan.kode_status)" class="">
                        <label for="status" class="block text-gray-600">Ubah status pemesanan</label>
                        <Dropdown @change="changeStatus" v-model="selectedStatus" :options="statusPesanan" optionLabel="nama" placeholder="Status pemesanan" />
                    </div>
                </div>
            </div>
            <div class="card">
                <h2 class="text-xl font-semibold mb-5 text-gray-500">Daftar barang</h2>
                <DataTable :value="pemesanan.list_detail_pemesanan" dataKey="id" >
                    <Column field="id" header="">
                        <template #body="{data}">
                            <img v-if="data.produk.gambar[0]" :src="data.produk.gambar[0].url" alt="" class="h-[56px] object-contain rounded-lg">
                            <p v-else class="p-4 w-fit rounded bg-gray-200 text-gray-600">Gambar tidak tersedia</p>
                        </template>
                    </Column>
                    <Column field="id" header="Nama">
                        <template #body="{data}">
                            <h2 class="capitalize">{{ data.produk.nama }}</h2>
                            <h2 class="text-sm font-semibold text-red-400">Stok tersedia: {{ data.produk.jumlah_stok }}</h2>
                            <h2 class="text-sm text-gray-500">Harga: {{ data.produk.harga }}</h2>
                        </template>
                    </Column>
                    <Column field="ukuran" header="Ukuran"></Column>
                    <Column field="total_pesanan" header="Jumlah Pemesanan"></Column>
                    <Column field="total_pesanan" header="Total harga">
                        <template #body="{data}">
                            <p>Rp {{ data.produk.harga * data.total_pesanan }}</p>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </main>
    </AuthenticatedLayout>

    <Toast />
</template>
