<script setup>
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
    pemesanan: Object
});

const expandedRows = ref();

const toJson = (str) => JSON.parse(str);

onMounted(() => {
    const status = [
        'MENUNGGU_PENJEMPUTAN_KURIR',
        'DI_ANTAR',
    ];

    props.pemesanan.forEach(value => {
        if (value.kode_status == 'BELUM_DIBAYAR') {
            value.kode_status = 'Loading...';
            axios.get(route('pay.status', {id: value.id}))
                .then((response) => {
                    response = response.data;
                    value.kode_status = response.kode_status;
                })
        }

        if (status.includes(value.kode_status)) {
            axios.get(route('order.tracking', {id: value.id}))
                .then(response => {
                    router.reload({
                        only: ['pemesanan']
                    })
                });
        }
    });
});

</script>

<template>
    <Head title="Pemesanan" />

    <main class="container py-16 mx-auto">
        <div class="mb-8">
            <Link :href="route('home')">
            <Button label="Kembali" icon="fa fa-arrow-left" severity="warning" />
            </Link>
        </div>
        <div class="mb-12">
            <h2 class="text-5xl font-bold text-center">Pemesanan</h2>
        </div>
        <div class="card">
            <DataTable v-model:expandedRows="expandedRows" :value="pemesanan" dataKey="id" tableStyle="min-width: 60rem">
                <Column expander style="width: 5rem" />
                <Column field="jumlah_harga_barang" header="Total harga barang">
                    <template #body="slotProps">
                        Rp {{ slotProps.data.jumlah_harga_barang.toLocaleString('id-ID') }}
                    </template>
                </Column>
                <Column field="id" header="Total tagihan">
                    <template #body="slotProps">
                        Rp {{ (slotProps.data.jumlah_harga_barang + slotProps.data.jumlah_ongkir).toLocaleString('id-ID') }}
                    </template>
                </Column>
                <Column field="id" header="Status pembayaran">
                    <template #body="slotProps">
                        {{ slotProps.data.kode_status.replaceAll('_', ' ') }}
                    </template>
                </Column>
                <Column field="id" header="Kurir">
                    <template #body="slotProps">
                        {{ toJson(slotProps.data.pilihan_paket_pengiriman).logistic.name }} - 
                        {{ toJson(slotProps.data.pilihan_paket_pengiriman).rate.type }}
                    </template>
                </Column>
                <Column field="id" header="">
                    <template #body="{data}">
                        <div v-if="data.kode_status == 'SELESAI' && data.total_rating < data.total_pemesanan" class="">
                            <Link :href="route('order.show', {id: data.id})+'#data-pemesanan'">
                                <Button label="Beri Komentar" icon="fa fa-comment" severity="warning" iconPos="right" />
                            </Link>
                        </div>
                        <div v-else class="">
                            <Link :href="route('order.show', {id: data.id})">
                                <Button label="Detail" icon="fa fa-chevron-right" severity="success" iconPos="right" />
                            </Link>
                        </div>
                    </template>
                </Column>
                <template #expansion="slotProps">
                    <div class="p-3">
                        <DataTable :value="slotProps.data.list_detail_pemesanan">
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
                            <Column field="total_pesanan" header="Jumlah Pemesanan" />
                            <Column field="total_pesanan" header="Total harga">
                                <template #body="{data}">
                                    <p>Rp {{ (data.produk.harga * data.total_pesanan).toLocaleString('id-ID') }}</p>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </template>
            </DataTable>
        </div>
</main>
<Toast /></template>
