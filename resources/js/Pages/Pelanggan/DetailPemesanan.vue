<script setup>
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Carousel from 'primevue/carousel';
import Rating from 'primevue/rating';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const showModalDialog = ref(false);
const toast = useToast();
const page = usePage()
const props = defineProps({ 
    pemesanan: Object,
});

const statusColors = ref({
    SUDAH_DIBAYAR:  '#84cc16',
    PROSES:  '#f97316',
    MENUNGGU_PENJEMPUTAN_KURIR:  '#0ea5e9',
    DI_ANTAR:  '#d946ef',
    SELESAI:  '#22c55e',
})

const errors = ref();
const selectedPesanan = ref({
    rating: null,
});

const ratingName = ref({
    1: 'Kecewa',
    2: 'Cukup kecewa',
    3: 'Biasa',
    4: 'Puas',
    5: 'Sangat puas',
})
const trackings = ref([]);
const pilihanKurir = computed(() => JSON.parse(props.pemesanan.pilihan_paket_pengiriman))
const expandedRows = ref();

const formatDate = (date) => (new Date(date))
    .toLocaleDateString('id-ID', {
        timeZone: 'Asia/Jakarta',
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
    });

onMounted(() => {
    const status = [
        'MENUNGGU_PENJEMPUTAN_KURIR',
        'DI_ANTAR',
        'SELESAI',
    ];

    if (status.includes(props.pemesanan.kode_status)) {
        axios.get(route('order.tracking', {id: props.pemesanan.id}))
            .then(response => {
                trackings.value = Object.values(response.data).reverse();
            });
    }
})

function openModalKomentar(data) {
    showModalDialog.value = true;
    selectedPesanan.value = data;
    selectedPesanan.value.rating = null;
    selectedPesanan.value.komentar = null;
    errors.value = null;
}

function storeKomentar() {
    axios.post(route('komentar.store'), {
            detail_pemesanan_id: selectedPesanan.value.id,
            produk_id: selectedPesanan.value.produk.id,
            komentar: selectedPesanan.value.komentar,
            rating: selectedPesanan.value.rating,
        })
        .then(response => {
            response = response.data;
            selectedPesanan.value = response;
            toast.add({ severity: "info", summary: "Berhasil", detail: "Komentar berhasil ditambah", life: 3000 });
        })
        .catch((error) => {
            errors.value = error.response.data.errors;
        })
        .finally(() => {
            showModalDialog.value = false;
        });
}

</script>

<template>
    <Head title="Pemesanan" />

        <main class="container py-16 mx-auto">
            <div class="mb-8">
                <Link :href="route('order.index')">
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
                        <p class="m-0 p-0 text-end">Rp {{ pemesanan.jumlah_harga_barang.toLocaleString('id-ID') }}</p>
                        <p class="m-0 p-0 text-gray-600">Total ongkos kirim</p>
                        <p class="m-0 p-0 text-end">Rp {{ pemesanan.jumlah_ongkir.toLocaleString('id-ID') }}</p>
                        <p class="font-semibold  text-gray-600">Total tagihan</p>
                        <p class="font-bold text-end">Rp {{ (pemesanan.jumlah_harga_barang + pemesanan.jumlah_ongkir).toLocaleString('id-ID') }}</p>
                    </div>
                </div>
                <div class="card">
                    <h2 class="text-xl font-semibold mb-5 text-gray-500">Status pemesanan</h2>
                    <div class="mb-4 ">
                        <p class="w-fit p-3 rounded text-sm text-white capitalize" :style="'background-color: ' +statusColors[pemesanan.kode_status]">
                            {{pemesanan.kode_status.replaceAll('_', ' ')}}
                        </p>
                    </div>
                    <div v-if="trackings.length > 0">
                        <h2 class="text-xl font-semibold mb-5 text-gray-500">Tracking</h2>
                        <ul class="grid gap-4 list-disc pl-5">
                            <li v-for="tracking in trackings" class="text-gray-400 first:text-gray-700">
                                <p class="m-0 p-0">{{ formatDate(tracking.created_date)}}</p>
                                <p class="m-0 p-0 font-semibold">{{ tracking.shipper_status.name}}</p>
                                <p class="m-0 p-0">{{ tracking.shipper_status.description}}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <h2 class="text-xl font-semibold mb-5 text-gray-500">Daftar barang</h2>
                <DataTable v-model:expandedRows="expandedRows" id="data-pemesanan" :value="pemesanan.list_detail_pemesanan" dataKey="id" >
                    <Column v-if="pemesanan.kode_status == 'SELESAI'" expander style="width: 5rem" />
                    <Column field="id" header="">
                        <template #body="{data}">
                            <img v-if="data.produk.gambar[0]" :src="data.produk.gambar[0].url" alt="" class="h-[56px] object-contain rounded-lg">
                            <p v-else class="p-4 w-fit rounded bg-gray-200 text-gray-600">Gambar tidak tersedia</p>
                        </template>
                    </Column>
                    <Column field="id" header="Nama">
                        <template #body="{data}">
                            <h2 class="capitalize">{{ data.produk.nama }}</h2>
                            <h2 class="text-sm text-gray-500">Harga: {{ data.produk.harga }}</h2>
                        </template>
                    </Column>
                    <Column field="ukuran" header="Ukuran"></Column>
                    <Column field="total_pesanan" header="Jumlah Pemesanan"></Column>
                    <Column field="total_pesanan" header="Total harga">
                        <template #body="{data}">
                            <p>Rp {{ (data.produk.harga * data.total_pesanan).toLocaleString('id-ID') }}</p>
                        </template>
                    </Column>
                    <Column v-if="pemesanan.kode_status == 'SELESAI'" field="id">
                        <template #body="{data}">
                            <div v-if="! data.rating" class="">
                                <Button @click="openModalKomentar(data)" label="Beri Komentar" icon="fa fa-comment" severity="warning" iconPos="right" />
                            </div>
                        </template>
                    </Column>
                    <template #expansion="{data}">
                        <div v-if="data.rating" class="card">
                            <div class="mb-5 flex flex-col gap-2">
                                <label for="bintang" class="text-gray-500">Bintang</label>
                                <Rating v-model="data.rating.rating" :cancel="false"  readonly/>
                            </div>
                            <div class="mb-5 flex flex-col gap-0">
                                <label for="kometar" class="text-gray-500">Komentar</label>
                                <p>{{ data.komentar.komentar }}</p>
                            </div>
                        </div>
                    </template>
                </DataTable>
            </div>
        </main>

    <Dialog v-model:visible="showModalDialog" modal :header="'Beri Komentar ' + (selectedPesanan.produk ? selectedPesanan.produk.nama :'') " :draggable="false" :style="{ width: '50vw' }" :breakpoints="{ '960px': '75vw', '641px': '100vw' }">
        <div class="mb-5 flex flex-col gap-2">
            <label for="bintang">Bintang</label>
            <div class="flex gap-5">
                <Rating v-model="selectedPesanan.rating" :cancel="false" />
                <p>{{ratingName[selectedPesanan.rating] }}</p>
            </div>
            <div v-if="errors && errors.rating" class="">
                <p v-for="error in errors.rating" class="text-red-500">{{ error }}</p>
            </div>
        </div>
        <div class="mb-5 flex flex-col gap-2">
            <label for="kometar">Beri Komentar</label>
            <Textarea v-model="selectedPesanan.komentar" autoResize="true" class="w-full" rows="5" />
            <div v-if="errors && errors.komentar" class="">
                <p v-for="error in errors.komentar" class="text-red-500">{{ error }}</p>
            </div>
        </div>
        <div class="mb-5 flex justify-end gap-2">
            <Button @click="storeKomentar" label="Simpan" icon="fa fa-check" />
        </div>
    </Dialog>
    <Toast />
</template>
