<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import UploadMultiFile from '@/Components/UploadMultiFile.vue'
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { router, Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const toast = useToast();
const confirm = useConfirm();

const props = defineProps({ 
    data: Object,
    kategori: Object,
    errors: Object
});

const uploadMultiFile = ref();

const formData = ref({
    nama: null,
    deskripsi: null,
    kategori_produk_id: null,
    listProduk: [{}],
})

function update() {

    if (typeof formData.value.kategori_produk_id == 'object') {
        formData.value.kategori_produk_id = formData.value.kategori_produk_id.id;
    }

    formData.value.listProduk.forEach(produk => {
        produk.ukuran = JSON.stringify(produk.listUkuran);
    });
 
    router.put(route('produk.update', {produk: props.data.id}), formData.value, {
        onError: () => {
            toast.add({ severity: 'error', summary: 'Gagal menyimpan', detail: 'Cek kembali data produk', life: 3000 });
        }
    })
}

function addSize(i) {
    
    if (formData.value.listProduk[i].listUkuran) {
        if (! formData.value.listProduk[i].listUkuran.includes(formData.value.listProduk[i].ukuran)) {
            formData.value.listProduk[i].listUkuran.push(formData.value.listProduk[i].ukuran)
        }
    } else {
        formData.value.listProduk[i].listUkuran = [formData.value.listProduk[i].ukuran]
    }

    formData.value.listProduk[i].ukuran = null;
}

function removeSize(i, ukuran) {
    formData.value.listProduk[i].listUkuran = 
        formData.value.listProduk[i].listUkuran.filter(val => (val != ukuran));
}

function addProduct() {
    formData.value.listProduk.push({});

    setTimeout(() => {
        var objDiv = document.getElementById("varian");
        objDiv.scrollTop = objDiv.scrollHeight;
    }, 200)
}

function confirmDelete(i) {
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
            removeProduct(i);
        },
        reject: () => {
            // toast.add({ severity: 'error', summary: 'Gagal', detail: 'You have rejected', life: 3000 });
        }
    });
}

function removeProduct(i) {
    const produk = formData.value.listProduk.filter((el, index) => (index == i))[0];
    
    if (produk.id) {
        router.delete(route('produk.destroy', {id: produk.id}), {
            onSuccess: () => {
                toast.add({ 
                    severity: page.props.flash.message.type, 
                    summary: page.props.flash.message.title, 
                    detail: page.props.flash.message.detail, 
                    life: 3000 
                });

                if (page.props.flash.message.type == 'success') {
                    formData.value.listProduk = 
                        formData.value.listProduk.filter((el, index) => (index != i));
                }

            }
        });
    } else {
        formData.value.listProduk = 
            formData.value.listProduk.filter((el, index) => (index != i));
    }

    
}

function uploaded(response, i) {
    if (! formData.value.listProduk[i].pathImages) {
        formData.value.listProduk[i].pathImages = response;
    } else {
        formData.value.listProduk[i].pathImages = formData.value.listProduk[i].pathImages.concat(response)
    }
}

function deleteProdukImage(gambarId) {
    confirm.require({
        message: 'Apakah anda yakin akan menghapus gambar ini?',
        header: 'Konfirmasi Hapus',
        icon: 'fa-solid fa-triangle-exclamation text-red-400',
        acceptClass: 'p-button-danger',
        acceptLabel: "Ya, hapus",
        rejectLabel: "Batal",
        acceptIcon: "fa fa-trash",
        rejectIcon: "fa fa-times",
        accept: () => {
            router.delete(route('gambar-produk.destroy', {id: gambarId}));
        },
        reject: () => {
            // toast.add({ severity: 'error', summary: 'Gagal', detail: 'You have rejected', life: 3000 });
        }
    });
}

onMounted(() => {
    formData.value = {...props.data};

    formData.value.kategori_produk_id = props.kategori.filter(val => (val.id == formData.value.kategori_produk_id))[0];

    formData.value.listProduk.forEach(produk => {
        produk.listUkuran = JSON.parse(produk.ukuran);
        produk.ukuran = null;
    });

});
</script>

<template>
    <Head title="Edit Produk" />

    <AuthenticatedLayout>
        <template #name>Edit Produk</template>
        <template #default>
            <div class="mb-8">
                <Link :href="route('produk.index')">
                    <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
                </Link>
            </div>
    
            <div class="w-full">
                <div class="card grid lg:grid-cols-2 gap-8">
                    <div id="varian" class="max-h-[75vh] overflow-auto pr-4">
                        <Button @click="addProduct" label="Tambah Variasi" icon="fa fa-plus" severity="success" size="small" />
                        <div :id="'listProduk.'+i" v-for="(produk, i) in formData.listProduk" class="pb-8 pt-4 first:pt-0 border-b last:border-0">
                            <div class="flex flex-row justify-between sticky top-0 bg-white z-50">
                                <h5 class="text-lg font-semibold uppercase">
                                    {{ `${formData.listProduk[i].warna ?? '-'} (${formData.listProduk[i].listUkuran ?? '-'}) - ${formData.listProduk[i].harga ?? '-'}` ?? (i + 1) }}
                                </h5>
                                <Button v-if="i > 0" @click="confirmDelete(i)" label="Hapus" icon="fa fa-trash" severity="danger" size="small" />
                            </div>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="warna" class="capitalize">Warna Produk</label>
                                </div>
                                <InputText id="warna" class="w-full" v-model="formData.listProduk[i].warna" type="text" placeholder="Warna Produk" />
                                <p v-if="errors[`listProduk.${i}.warna`]" class="text-red-500">{{ errors[`listProduk.${i}.warna`] }}</p>
                            </div>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="ukuran" class="capitalize">Ukuran</label>
                                </div>
                                <div class="mb-1 flex flex-wrap gap-2">
                                    <div v-for="ukuran in formData.listProduk[i].listUkuran" class="rounded bg-gray-300 w-fit">
                                        <span class="p-2">{{ ukuran }}</span>&nbsp; <button @click="removeSize(i, ukuran)" class="hover:bg-gray-400 p-2 rounded"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <InputText @keypress.enter="addSize(i)" id="ukuran" class="w-full" v-model="formData.listProduk[i].ukuran" type="text" placeholder="Ukuran" />
                                <p class="p-0 m-0 text-gray-500">Ketikan ukuran lalu tekan enter untuk menambah daftar ukuran</p>
                                <p v-if="errors[`listProduk.${i}.ukuran`]" class="text-red-500">{{ errors[`listProduk.${i}.ukuran`] }}</p>
                            </div>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="harga" class="capitalize">Harga (Rp)</label>
                                </div>
                                <InputText id="harga" class="w-full" v-model="formData.listProduk[i].harga" type="number" placeholder="Harga (Rp)" />
                                <p v-if="errors[`listProduk.${i}.harga`]" class="text-red-500">{{ errors[`listProduk.${i}.harga`] }}</p>
                            </div>
                            <div class="mb-5 grid grid-cols-2 gap-4">
                                <div class="">
                                    <div class="mb-2">
                                        <label for="panjang" class="capitalize">Panjang</label>
                                    </div>
                                    <div class="p-inputgroup">
                                        <InputText id="panjang" class="w-full" v-model="formData.listProduk[i].panjang" type="number" placeholder="Panjang" />
                                        <span class="p-inputgroup-addon">
                                            cm
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="mb-2">
                                        <label for="lebar" class="capitalize">Lebar</label>
                                    </div>
                                    <div class="p-inputgroup">
                                        <InputText id="lebar" class="w-full" v-model="formData.listProduk[i].lebar" type="number" placeholder="Lebar" />
                                        <span class="p-inputgroup-addon">
                                            cm
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="mb-2">
                                        <label for="tinggi" class="capitalize">Tinggi</label>
                                    </div>
                                    <div class="p-inputgroup">
                                        <InputText id="tinggi" class="w-full" v-model="formData.listProduk[i].tinggi" type="number" placeholder="Tinggi" />
                                        <span class="p-inputgroup-addon">
                                            cm
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="mb-2">
                                        <label for="berat" class="capitalize">Berat</label>
                                    </div>
                                    <div class="p-inputgroup">
                                        <InputText id="berat" class="w-full" v-model="formData.listProduk[i].berat" type="number" placeholder="Berat" />
                                        <span class="p-inputgroup-addon">
                                            kg
                                        </span>
                                    </div>
                                </div>
                                <p v-if="errors[`listProduk.${i}.panjang`]" class="text-red-500">{{ errors[`listProduk.${i}.panjang`] }}</p>
                                <p v-if="errors[`listProduk.${i}.lebar`]" class="text-red-500">{{ errors[`listProduk.${i}.lebar`] }}</p>
                                <p v-if="errors[`listProduk.${i}.tinggi`]" class="text-red-500">{{ errors[`listProduk.${i}.tinggi`] }}</p>
                                <p v-if="errors[`listProduk.${i}.berat`]" class="text-red-500">{{ errors[`listProduk.${i}.berat`] }}</p>
                            </div>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="jumlah_stok" class="capitalize">Jumlah Stok</label>
                                </div>
                                <InputText id="jumlah_stok" class="w-full" v-model="formData.listProduk[i].jumlah_stok" type="number" placeholder="Jumlah Stok" />
                                <p v-if="errors[`listProduk.${i}.jumlah_stok`]" class="text-red-500">{{ errors[`listProduk.${i}.jumlah_stok`] }}</p>
                            </div>
                            <div class="mb-5">
                                <div class="mb-4">
                                    <label for="gambar" class="capitalize mb-2">Gambar</label>
                                    <div class="flex flex-wrap gap-2">
                                        <div v-for="gambar in formData.listProduk[i].gambar" class="flex flex-row items-center gap-3 p-1 bg-gray-200 w-fit rounded">
                                            <img :src="gambar.url" alt="" class="h-[56px]">
                                            <div class="">
                                                <Button @click="deleteProdukImage(gambar.id)" label="Hapus" icon="fa fa-trash" class="py-2" severity="danger" size="small" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <UploadMultiFile ref="uploadMultiFile" :url="route('temp-images.store')" @uploaded="uploaded($event, i)" />
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="">
                        <div class="mb-5">
                            <div class="mb-2">
                                <label for="nama" class="capitalize">Nama Produk</label>
                            </div>
                            <InputText id="nama" class="w-full" v-model="formData.nama" type="text" placeholder="Nama Produk" />
                            <p v-if="errors.nama" class="text-red-500">{{ errors.nama }}</p>
                        </div>
                        <div class="mb-5">
                            <div class="mb-2">
                                <label for="nama" class="capitalize">Kategori Produk</label>
                            </div>
                            <Dropdown v-model="formData.kategori_produk_id" :options="kategori" filter optionLabel="nama" placeholder="Kategori Produk" class="w-full capitalize" />
                            <p v-if="errors.nama" class="text-red-500">{{ errors.nama }}</p>
                        </div>
                        <div class="mb-5">
                            <div class="mb-2">
                                <label for="deskripsi" class="capitalize">Deskripsi</label>
                            </div>
                            <Textarea id="deskripsi" class="w-full" v-model="formData.deskripsi" placeholder="Deskripsi" autoResize rows="5" cols="30" />
                            <p v-if="errors.deskripsi" class="text-red-500">{{ errors.deskripsi }}</p>
                        </div>
                        <div class="mb-5 flex justify-end">
                            <Button @click="update" label="Simpan" type="submit" icon="fa fa-check" iconPos="right"  />
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
    <ConfirmDialog />
    <Toast />
</template>