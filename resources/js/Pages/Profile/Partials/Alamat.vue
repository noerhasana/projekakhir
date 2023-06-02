<script setup>
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from 'primevue/usetoast';
import { ref, computed, onMounted } from 'vue';
import Dropdown from 'primevue/dropdown';
import axios from 'axios';

const page = usePage();
const toast = useToast();
const confirm = useConfirm();
const props = defineProps({
    listAlamat: Array,
    errors: Object,
});

const listProvinsi = ref([]);
const listKabupaten = ref([]);
const listKecamatan = ref([]);
const listDesa = ref([]);

const addAlamatDialog = ref(false);
const formAddAlamat = useForm({
    nama: null,
    alamat: null,
    provinsi: null,
    kabupaten: null,
    kecamatan: null,
    desa: null,
});

function closeAddDialog() {
    addAlamatDialog.value = false;
    formAddAlamat.reset();
}

function showAddDialog() {
    props.errors = {};
    addAlamatDialog.value = true;
}

const selectedAlamat = ref();
const editAlamatDialog = ref(false);
const formEditAlamat = useForm({
    nama: null,
    alamat: null,
    provinsi: null,
    kabupaten: null,
    kecamatan: null,
    desa: null,
});


function closeEditDialog() {
    editAlamatDialog.value = false;
    formEditAlamat.reset();
}

function showEditDialog() {
    props.errors = null;
    editAlamatDialog.value = true;
}

function edit(val) {
    selectedAlamat.value = {...val};

    console.log(val);

    formEditAlamat.defaults({
        id: 'val.id',
        nama: 'val.nama',
        alamat: 'val.alamat',
    });

    showEditDialog();
}

function showToast() {
    toast.add({ 
            severity: page.props.flash.message.type, 
            summary: page.props.flash.message.title, 
            detail: page.props.flash.message.detail, 
            life: 3000 
        });
}

async function fetchProvinsi() {
    const response = await axios.get(`/provinsi`);
    listProvinsi.value = response.data;
}

function selectProvinsi(event) {
    listKabupaten.value = [];
    listKecamatan.value = [];
    listDesa.value = [];

    fetchKabupaten(event.value.id);
}


async function fetchKabupaten(id) {
    const response = await axios.get(`/kabupaten?provinsi_id=${id}`);
    listKabupaten.value = response.data;
}

function selectKabupaten(event) {
    listKecamatan.value = [];
    listDesa.value = [];

    fetchKecamatan(event.value.id);
}

async function fetchKecamatan(id) {
    const response = await axios.get(`/kecamatan?kabupaten_id=${id}`);
    listKecamatan.value = response.data;
}

function selectKecamatan(event) {
    listDesa.value = [];

    fetchDesa(event.value.id);
}

async function fetchDesa(id) {
    const response = await axios.get(`/desa?kecamatan_id=${id}`);
    listDesa.value = response.data;
}


function store() {
    formAddAlamat.transform((data) => ({
        nama: data.nama,
        alamat: data.alamat,
        provinsi: data.provinsi,
        kabupaten: data.kabupaten,
        kecamatan: data.kecamatan,
        desa: data.desa,
    }))
    .post(route('alamat.store'), { 
        preserveScroll: true, 
        onSuccess: () => { 
            closeAddDialog(); 
            showToast() 
        } 
    });
}

function update() {
    formEditAlamat.transform((data) => ({
            nama: selectedAlamat.value.nama,
            alamat: selectedAlamat.value.alamat,
            provinsi: selectedAlamat.value.provinsi,
            kabupaten: selectedAlamat.value.kabupaten,
            kecamatan: selectedAlamat.value.kecamatan,
            desa: selectedAlamat.value.desa,
        }))
        .put(`/alamat/${selectedAlamat.value.id}`, {
            preserveScroll: true, 
            onSuccess: () => { 
                closeEditDialog(); 
                showToast();
            } 
        });
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
            router.delete(route('alamat.destroy', {id: id}), {
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
    fetchProvinsi();
});
</script>

<template>
    <section id="list-alamat">
        <header>
            <h2 class="text-lg font-medium text-gray-900 mb-6">Alamat</h2>
        </header>

        <Button @click="showAddDialog" label="Tambah" icon="fa fa-plus" severity="success"/>
        <div class="card mt-6">
            <div class="">
                <div class="bg-white border rounded-lg p-1 my-2">
                    <DataTable stripedRows :value="listAlamat" :rows="listAlamat.length">
                        <Column field="nama" header="Nama alamat" />
                        <Column field="alamat" header="Alamat lengkap" >
                            <template #body="{data}">
                                <p>{{ data.alamat }}</p>
                            </template>
                        </Column>
                        <Column field="id" header="" class="w-min">
                            <template #body="{data}">
                                <div class="p-inputgroup flex-1">
                                    <Button @click="edit(data)" icon="fa fa-pen-to-square" severity="warning" />
                                    <Button @click="confirmDelete(data.id)" icon="fas fa-trash" severity="danger" />
                                </div>
                            </template>
                        </Column>

                    </DataTable>
                </div>
            </div>
        </div>
        
    </section>

    <Dialog @hide="closeAddDialog" v-model:visible="addAlamatDialog" :style="{width: '450px'}" header="Tambah alamat" :modal="true" class="p-fluid">
        <div class="">
            <form @submit.prevent="store">
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="nama" class="capitalize">Nama Alamat</label>
                    </div>
                    <InputText id="nama" class="w-full" v-model="formAddAlamat.nama" type="text" placeholder="Nama Alamat" />
                    <p class="text-gray-500 text-sm">Contoh: <b>Alamat rumah</b> </p>
                    <p v-if="errors.nama" class="text-red-500">{{ errors.nama }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="alamat" class="capitalize">Alamat lengkap</label>
                    </div>
                    <Textarea id="alamat" class="w-full" v-model="formAddAlamat.alamat" placeholder="Alamat lengkap" autoResize rows="5" cols="30" />
                    <p class="text-gray-500 text-sm">Masukkan lengkap alamat. Anda juga dapat menuliskan patokan alamat tersebut.</p>
                    <p v-if="errors.alamat" class="text-red-500">{{ errors.alamat }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="provinsi" class="capitalize">Provinsi</label>
                    </div>
                    <Dropdown :disabled="listProvinsi.length == 0" @change="selectProvinsi" id="provinsi" filter class="w-full" :options="listProvinsi" optionLabel="nama" v-model="formAddAlamat.provinsi" placeholder="Provinsi" />
                    <p v-if="errors.provinsi" class="text-red-500">{{ errors.provinsi }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="kabupaten" class="capitalize">Kabupaten/Kota</label>
                    </div>
                    <Dropdown :disabled="listKabupaten.length == 0" @change="selectKabupaten" id="kabupaten" filter class="w-full" :options="listKabupaten" optionLabel="nama" v-model="formAddAlamat.kabupaten" placeholder="Kabupaten/Kota" />
                    <p v-if="errors.kabupaten" class="text-red-500">{{ errors.kabupaten }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="kecamatan" class="capitalize">Kecamatan</label>
                    </div>
                    <Dropdown :disabled="listKecamatan.length == 0" @change="selectKecamatan" id="kecamatan" filter class="w-full" :options="listKecamatan" optionLabel="nama" v-model="formAddAlamat.kecamatan" placeholder="Kecamatan" />
                    <p v-if="errors.kecamatan" class="text-red-500">{{ errors.kecamatan }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="desa" class="capitalize">Desa</label>
                    </div>
                    <Dropdown :disabled="listDesa.length == 0" @change="selectDesa" id="desa" filter class="w-full" :options="listDesa" optionLabel="nama" v-model="formAddAlamat.desa" placeholder="Desa" />
                    <p v-if="errors.desa" class="text-red-500">{{ errors.desa }}</p>
                </div>
        
                <div class="flex flex-row gap-4 justify-end">
                    <div class="">
                        <Button label="Batal" icon="fa fa-times" severity="warning" @click="closeAddDialog"/>
                    </div>
                    <div class="">
                        <Button type="submit" label="Simpan" severity="success" icon="fa fa-check"/>
                    </div>
                </div>
            </form>
        </div>
    </Dialog>

    <Dialog @hide="closeEditDialog" v-model:visible="editAlamatDialog" :style="{width: '450px'}" header="Ubah alamat" :modal="true" class="p-fluid">
        <form @submit.prevent="update">
            <div class="mb-5">
                <div class="mb-2">
                    <label for="nama" class="capitalize">Nama Alamat</label>
                </div>
                <InputText id="nama" class="w-full" v-model="selectedAlamat.nama" type="text" placeholder="Nama Alamat" />
                <p class="text-gray-500 text-sm">Contoh: <b>Alamat rumah</b> </p>
                <p v-if="formEditAlamat.errors.nama" class="text-red-500">{{ formEditAlamat.errors.nama }}</p>
            </div>
            <div class="mb-5">
                    <div class="mb-2">
                        <label for="alamat" class="capitalize">Alamat lengkap</label>
                    </div>
                    <Textarea id="alamat" class="w-full" v-model="selectedAlamat.alamat" placeholder="Alamat lengkap" autoResize rows="5" cols="30" />
                    <p class="text-gray-500 text-sm">Masukkan lengkap alamat. Anda juga dapat menuliskan patokan alamat tersebut.</p>
                    <p v-if="errors.alamat" class="text-red-500">{{ errors.alamat }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="provinsi" class="capitalize">Provinsi</label>
                    </div>
                    <Dropdown :disabled="listProvinsi.length == 0" @change="selectProvinsi" id="provinsi" filter class="w-full" :options="listProvinsi" optionLabel="nama" v-model="selectedAlamat.provinsi" placeholder="Provinsi" />
                    <p v-if="errors.provinsi" class="text-red-500">{{ errors.provinsi }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="kabupaten" class="capitalize">Kabupaten/Kota</label>
                    </div>
                    <Dropdown :disabled="listKabupaten.length == 0" @change="selectKabupaten" id="kabupaten" filter class="w-full" :options="listKabupaten" optionLabel="nama" v-model="selectedAlamat.kabupaten" placeholder="Kabupaten/Kota" />
                    <p v-if="errors.kabupaten" class="text-red-500">{{ errors.kabupaten }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="kecamatan" class="capitalize">Kecamatan</label>
                    </div>
                    <Dropdown :disabled="listKecamatan.length == 0" @change="selectKecamatan" id="kecamatan" filter class="w-full" :options="listKecamatan" optionLabel="nama" v-model="selectedAlamat.kecamatan" placeholder="Kecamatan" />
                    <p v-if="errors.kecamatan" class="text-red-500">{{ errors.kecamatan }}</p>
                </div>
                <div class="mb-5">
                    <div class="mb-2">
                        <label for="desa" class="capitalize">Desa</label>
                    </div>
                    <Dropdown :disabled="listDesa.length == 0" id="desa" filter class="w-full" :options="listDesa" optionLabel="nama" v-model="selectedAlamat.desa" placeholder="Desa" />
                    <p v-if="errors.desa" class="text-red-500">{{ errors.desa }}</p>
                </div>
    
            <div class="flex flex-row gap-4 justify-end">
                <div class="">
                    <Button label="Batal" icon="fa fa-times" severity="warning" @click="closeEditDialog"/>
                </div>
                <div class="">
                    <Button type="submit" label="Simpan" severity="success" icon="fa fa-check"/>
                </div>
            </div>
        </form>
    </Dialog>

    <ConfirmDialog />
    <Toast />
</template>