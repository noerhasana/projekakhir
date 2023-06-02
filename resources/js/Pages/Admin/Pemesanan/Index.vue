<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Pagination from "@/Components/Pagination.vue";
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


const currentPage = ref(1);
const selectedSize = ref(25);
const sizeList = [25, 50, 100];

const searchQuery = ref();
const searchMessage = ref();
const meta = computed(() => {
    const obj = {...props.data};
    delete obj.data;
    return obj;
});

function fetchData(params) {

    router.visit(route('pemesanan.index', params), {
        only: [
            'data',
        ],
    })
}

function search() {
    searchMessage.value = null;
    fetchData({search: searchQuery.value, page: 1, size: selectedSize.value});
}

function changePage(page) {
    const params = {page: page, size: selectedSize.value};

    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    
    fetchData(params);
}

function showDetail(id) {
    router.visit(route('pemesanan.show', {id: id}));
}


onMounted(() => {

    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());

    if (params.search) {
        searchQuery.value = params.search
        searchMessage.value = params.search
    }

    if (params.size) {
        selectedSize.value = parseInt(params.size);
    }
    
});

onMounted(() => {
});

</script>

<template>
    <Head title="Pemesanan" />

    <AuthenticatedLayout>
        <template #name>Pemesanan</template>

        <div class="card">
            <div class="mt-10">
                <div class="flex flex-row justify-between items-end">
                    <div class="">
                        <label for="paging" class="block mb-2">Tampilkan Per</label>
                        <Dropdown id="paging" v-model="selectedSize" :options="sizeList" @change="changePage(1)" />
                    </div>
                    <div class="w-1/2 lg:w-96">
                        <form @submit.prevent="search">
                            <label for="search" class="block mb-2">Cari</label>
                            <div class="p-inputgroup">
                                <InputText id="search" type="text" placeholder="Cari" v-model="searchQuery" />
                                <Button type="submit" label="Cari" icon="fas fa-magnifying-glass" severity="danger" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="my-2">
                    <p v-if="searchMessage">Menampilkan pencarian <b>{{ searchMessage }}</b></p>
                </div>
                <div class="bg-white border rounded-lg p-1 my-2">
                    <DataTable v-model:expandedRows="expandedRows" :value="pemesanan.data" dataKey="id" @rowExpand="onRowExpand"
                        @rowCollapse="onRowCollapse" tableStyle="min-width: 60rem">
                        <Column expander style="width: 5rem" />
                        <Column field="jumlah_harga_barang" header="Total harga barang"></Column>
                        <Column field="id" header="Total tagihan">
                            <template #body="slotProps">
                                {{ slotProps.data.jumlah_harga_barang + slotProps.data.jumlah_ongkir }}
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
                                <Link :href="route('pemesanan.show', {id: data.id})">
                                    <Button label="Detail" icon="fa fa-chevron-right" severity="success" iconPos="right" />
                                </Link>
                            </template>
                        </Column>
                        <template #expansion="slotProps">
                            <div class="p-3">
                                <DataTable :value="slotProps.data.list_detail_pemesanan">
                                    <Column field="id" header="">
                                        <template #body="{ data }">
                                            <img v-if="data.produk.gambar[0]" :src="data.produk.gambar[0].url" alt=""
                                                class="h-[56px] object-contain rounded-lg">
                                            <p v-else class="p-4 w-fit rounded bg-gray-200 text-gray-600">Gambar tidak
                                                tersedia</p>
                                        </template>
                                    </Column>
                                    <Column field="id" header="Nama">
                                        <template #body="{ data }">
                                            <h2 class="capitalize">{{ data.produk.nama }}</h2>
                                            <h2 class="text-sm font-semibold text-red-400">Stok tersedia: {{
                                                data.produk.jumlah_stok }}</h2>
                                            <h2 class="text-sm text-gray-500">Harga: {{ data.produk.harga }}</h2>
                                        </template>
                                    </Column>
                                    <Column field="ukuran" header="Ukuran"></Column>
                                    <Column field="total_pesanan" header="Jumlah Pemesanan" />
                                    <Column field="total_pesanan" header="Total harga">
                                        <template #body="{ data }">
                                            <p>Rp {{ data.produk.harga * data.total_pesanan }}</p>
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>
                        </template>
                    </DataTable>
                </div>
                <div class="flex flex-row lg:flex-col justify-between items-center">
                    <p>Menampilkan {{ meta.from }} sampai {{ meta.to }} dari total data {{ meta.total }}</p>
                    <Pagination :meta="meta" @changePage="changePage"/>
                </div>
        
            </div>

        </div>
    </AuthenticatedLayout>
    <Toast /></template>
