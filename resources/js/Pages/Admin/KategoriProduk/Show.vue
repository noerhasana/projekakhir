<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

import { router, Link } from '@inertiajs/vue3'

const props = defineProps({ 
    data: Object,
    errors: Object
});

</script>

<template>
    <Head title="Edit Kategori Produk" />

    <AuthenticatedLayout>
        <template #name>Edit Kategori Produk</template>
        
        <div class="mb-8">
            <Link :href="route('kategori-produk.index')">
                <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
            </Link>
        </div>

        <div class="w-full lg:w-1/2">
            <div class="card">
                <div class="mb-5">
                    <div class="">
                        <label for="nama" class="capitalize font-bold">Nama Kategori Produk</label>
                    </div>
                    <p>{{ data.nama }}</p>
                </div>
                <div class="mb-5">
                    <div class="">
                        <label for="deskripsi" class="capitalize font-bold">Deskripsi</label>
                    </div>
                    <p>{{ data.deskripsi }}</p>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>