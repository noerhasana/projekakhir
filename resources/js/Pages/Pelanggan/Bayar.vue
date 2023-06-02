<script setup>
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Menu from 'primevue/menu';
import Textarea from 'primevue/textarea';
import Carousel from 'primevue/carousel';
import RadioButton from 'primevue/radiobutton';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const toast = useToast();
const page = usePage()
const props = defineProps({ 
    listAlamat: Object,
    data: Object,
    midtransClientKey: String,
});

const listPaketPengiriman = ref([]) ;
const selectedPengiriman = ref() ;
const selectedAlamat = ref(props.listAlamat[0]);

const totalHargaBarang = computed(() => 
    props.data.map(val => (val.total_pesanan * val.produk.harga))
        .reduce((a, b) => (a + b), 0)
        )

function selectPengiriman(paket) {
    selectedPengiriman.value = paket;
}

function fetchOngkir() {
    listPaketPengiriman.value = [];
    selectedPengiriman.value = null;

    axios.post(route('cek-ongkir'), {
            'desa_id': selectedAlamat.value.desa_id,
            'list_barang': props.data,
        })
        .then(response => {
            response = response.data;
            listPaketPengiriman.value = response;
        })
}

function pay() {
    axios.post(route('pay.store'), {
            alamat_pelanggan_id: selectedAlamat.value.id,
            list_produk: props.data,
            pilihan_paket_pengiriman: selectedPengiriman.value,
        })
        .then((response) => {
            response = response.data
            window.snap.pay(response.token);
        });
}

function initSnapPaymentMidTrans() {
    // <--- For Development only --->
    const src = 'https://app.sandbox.midtrans.com/snap/snap.js';

    // <--- For Production --->
    // const src = 'https://app.midtrans.com/snap/snap.js';

    let snapPayment = document.createElement('script')
    snapPayment.setAttribute('src', src)
    snapPayment.setAttribute('data-client-key', props.midtransClientKey)
    document.head.appendChild(snapPayment)

}

onMounted(() => {
    initSnapPaymentMidTrans();

    if (selectedAlamat.value) {
        fetchOngkir();
    }
})

</script>

<template>
    <Head title="Pemesanan" />

    <main class="container py-16 mx-auto">
        <div class="mb-8">
            <Link :href="route('cart.index')">
                <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
            </Link>
        </div>
        <div class="mb-12">
            <h2 class="text-5xl font-bold text-center">Pemesanan</h2>
        </div>
        <div class="container card mb-8">
            <h2 class="text-xl font-semibold mb-5 text-gray-500">Alamat Tujuan</h2>
            <p class="ml-2 mb-2 text-gray-700 ">Pilih alamat</p>
            <Dropdown @change="fetchOngkir" v-model="selectedAlamat" :options="listAlamat" placeholder="Pilih alamat" class="w-full bg-green-300">
                <template #value="slotProps">
                    <div v-if="slotProps.value" class="flex flex-row items-start gap-4">
                        <h3 class="text-md font-semibold w-40 capitalize">{{ slotProps.value.nama }}</h3>
                        <div class="">
                            <p class="text-md uppercase">{{ slotProps.value.alamat }}</p>
                            <p class="text-sm text-gray-700">Provinsi : {{ slotProps.value.provinsi }}</p>
                            <p class="text-sm text-gray-700">Kabupaten/Kota : {{ slotProps.value.kabupaten }}</p>
                            <p class="text-sm text-gray-700">Desa : {{ slotProps.value.desa }}</p>
                        </div>
                    </div>
                    <span v-else>
                        {{ slotProps.placeholder }}
                    </span>
                </template>
                <template #option="slotProps">
                    <h3 class="text-md font-semibold w-40 capitalize">{{ slotProps.option.nama }}</h3>
                    <div class="">
                        <p class="text-md uppercase">{{ slotProps.option.alamat }}</p>
                        <p class="text-sm text-gray-700">Provinsi : {{ slotProps.option.provinsi }}</p>
                        <p class="text-sm text-gray-700">Kabupaten/Kota : {{ slotProps.option.kabupaten }}</p>
                        <p class="text-sm text-gray-700">Desa : {{ slotProps.option.desa }}</p>
                    </div>
                </template>
            </Dropdown>
        </div>
        <div class="container card mb-8">
            <h2 class="text-xl font-semibold mb-5 text-gray-500">Pilihan Kurir</h2>
            <p class="ml-2 mb-2 text-gray-700 ">Pilih kurir</p>
            <Dropdown v-if="listPaketPengiriman" v-model="selectedPengiriman" :options="listPaketPengiriman" placeholder="Pilih kurir" class="w-full bg-green-300">
                <template #value="slotProps">
                    <div v-if="slotProps.value" class="">
                        <h3 class="text-md font-semibold w-40 uppercase">{{ slotProps.value.logistic.name }} - {{ slotProps.value.rate.type }}</h3>
                        <div class="">
                            <p class="text-md">Rp {{ slotProps.value.final_price.toLocaleString('id-ID') }}</p>
                            <p class="text-sm text-gray-700">Estimasi waktu : {{ slotProps.value.min_day }} - {{ slotProps.value.max_day }} Hari</p>
                        </div>
                    </div>
                    <span v-else>
                        {{ slotProps.placeholder }}
                    </span>
                </template>
                <template #option="slotProps">
                    <h3 class="text-md font-semibold w-40 uppercase">{{ slotProps.option.logistic.name }} - {{ slotProps.option.rate.type }}</h3>
                    <div class="">
                        <p class="text-md">Rp {{ slotProps.option.final_price.toLocaleString('id-ID') }}</p>
                        <p class="text-sm text-gray-700">Estimasi waktu : {{ slotProps.option.min_day }} - {{ slotProps.option.max_day }} Hari</p>
                    </div>
                </template>
            </Dropdown>
        </div>
        <div class="container card">
            <h2 class="text-xl font-semibold mb-5 text-gray-500">Daftar barang</h2>
            <DataTable :value="data" dataKey="id">
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
                        <h2 class="text-sm text-gray-500">Harga: Rp {{ data.produk.harga.toLocaleString('id-ID') }}</h2>
                    </template>
                </Column>
                <Column field="ukuran" header="Ukuran"></Column>
                <Column field="total_pesanan" header="Jumlah Pemesanan" />
                <Column field="total_pesanan" header="Total harga">
                    <template #body="{data}">
                        <p>Rp {{ (data.produk.harga * data.total_pesanan).toLocaleString('id-ID') }}</p>
                    </template>
                </Column>
            </DataTable>
            <div class="flex flex-col gap-6 items-end justify-between pt-4">
                
                <div class="grid grid-cols-2 place-content-between gap-x-4 gap-y-0">
                    <p class="m-0 p-0  text-gray-600">Total harga barang</p>
                    <p class="m-0 p-0 text-end">Rp {{ totalHargaBarang.toLocaleString('id-ID') }}</p>
                    <p class="m-0 p-0  text-gray-600">Total ongkos kirim</p>
                    <p class="m-0 p-0 text-end">Rp {{ (selectedPengiriman) ? selectedPengiriman.final_price.toLocaleString('id-ID') : '-' }}</p>
                    <p class="font-semibold  text-gray-600">Total tagihan</p>
                    <p class="font-bold text-end">Rp {{ (selectedPengiriman) ? (selectedPengiriman.final_price + totalHargaBarang).toLocaleString('id-ID') : '-' }}</p>
                </div>
                <div class="">
                    <Button @click="pay" :disabled="!selectedPengiriman" label="Bayar" severity="success" icon="fa fa-check"/>
                </div>
            </div>
        </div>
    </main>
    <Toast />
</template>
