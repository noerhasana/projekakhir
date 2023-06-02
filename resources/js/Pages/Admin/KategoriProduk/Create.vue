<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

import { router, Link } from '@inertiajs/vue3'

const props = defineProps({ 
    errors: Object
});

const formData = ref({
    nama: null,
    deskripsi: null,
})

function update() {
    router.post(route('kategori-produk.store'), formData.value)
}

</script>

<template>
    <Head title="Edit Kategori Produk" />

    <AuthenticatedLayout>
        <template #name>Tambah Kategori Produk</template>
        
        <div class="mb-8">
            <Link :href="route('kategori-produk.index')">
                <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
            </Link>
        </div>

        <div class="w-full lg:w-1/2">
            <div class="card">
                <form @submit.prevent="update">
                    <div class="mb-5">
                        <div class="mb-2">
                            <label for="nama" class="capitalize">Nama Kategori Produk</label>
                        </div>
                        <InputText id="nama" class="w-full" v-model="formData.nama" type="text" placeholder="Nama Kategori Produk" />
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
                        <Button label="Simpan" type="submit" icon="fa fa-check" iconPos="right"  />
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>