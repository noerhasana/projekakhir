<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Pagination from "@/Components/Pagination.vue";
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from 'primevue/usetoast';

import { router } from '@inertiajs/vue3'

const page = usePage()
const toast = useToast();
const confirm = useConfirm();

const props = defineProps({ 
    data: Object,
});

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

    router.visit(route('produk.index', params), {
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
    router.visit(route('produk.show', {id: id}));
}

function edit(id) {
    router.visit(route('produk.edit', {id: id}));
}

function confirmDelete(id) {
    confirm.require({
        message: 'Apakah anda yakin akan menghapus data ini?',
        header: 'Konfirmasi Hapus',
        icon: 'fa-solid fa-triangle-exclamation text-red-400',
        acceptClass: 'p-button-danger',
        acceptLabel: "Ya, hapus",
        rejectLabel: "Batal",
        acceptIcon: "fa fa-trash",
        rejectIcon: "fa fa-times",
        accept: () => {
            router.delete(route('produk.destroy', {id: id}), {
                onFinish: visit => {
                    toast.add({ 
                        severity: page.props.flash.message.type, 
                        summary: page.props.flash.message.title, 
                        detail: page.props.flash.message.detail, 
                        life: 3000 
                    });
                },
            })
        },
        reject: () => {
            // toast.add({ severity: 'error', summary: 'Gagal', detail: 'You have rejected', life: 3000 });
        }
    });
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
</script>


<template>
    <Head title="Produk" />

    <AuthenticatedLayout>
        <template #name>Produk</template>
        
        <div class="mb-8">
            <Link :href="route('produk.create')">
                <Button label="Tambah" icon="fa fa-plus" severity="success"/>
            </Link>
        </div>

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
                                <Button type="submit" label="Cari" icon="fas fa-magnifying-glass" severity="danger"/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="my-2">
                    <p v-if="searchMessage">Menampilkan pencarian <b>{{searchMessage}}</b></p>
                </div>
                <div class="bg-white border rounded-lg p-1 my-2">
                    <DataTable stripedRows :value="data.data" :rows="meta.total">
                        <Column field="id" header="Gambar">
                            <template #body="{data}">
                                <img v-if="data.gambar.length > 0" :src="data.gambar[0].url" class="h-[56px] rounded" alt="">
                                <span v-else class="text-xs text-gray-600">Gambar tidak ada</span>
                            </template>
                        </Column>
                        <Column field="nama" header="Nama">
                            <template #body="{data}">
                                <span class="capitalize">{{ data.nama }}</span>
                            </template>
                        </Column>
                        <Column field="id" header="Kategori">
                            <template #body="{data}">
                                <span class="capitalize">{{ data.kategori.nama }}</span>
                            </template>
                        </Column>
                        <Column field="id" header="" class="w-min">
                            <template #body="{data}">
                                <div class="p-inputgroup flex-1">
                                    <Button @click="showDetail(data.id)" icon="fas fa-eye" severity="info" />
                                    <Button @click="edit(data.id)" icon="fa fa-pen-to-square" severity="warning" />
                                    <Button @click="confirmDelete(data.id)" icon="fas fa-trash" severity="danger" />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
                <div class="flex flex-row lg:flex-col justify-between items-center">
                    <p>Menampilkan {{ meta.from }} sampai {{ meta.to }} dari total data {{ meta.total }}</p>
                    <Pagination :meta="meta" @changePage="changePage"/>
                </div>
        
            </div>
        </div>
    </AuthenticatedLayout>
    <ConfirmDialog />
    <Toast />
</template>
