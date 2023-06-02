<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Carousel from 'primevue/carousel';
import Dropdown from 'primevue/dropdown';

import { router, Link } from '@inertiajs/vue3'

const selectedImage = ref(props.listGambar[0]);
const selectedProduk = ref(props.data.listProduk[0]);

const props = defineProps({ 
    data: Object,
    errors: Object,
    listGambar: Array,
});

function changeImage(img) {
    selectedImage.value = img;
    selectedProduk.value = props.data.listProduk.filter(val => (val.id == selectedImage.value.produk_id))[0];
}

function selectProduk() {
    selectedImage.value = selectedProduk.value.gambar[0];
}

onMounted(() => {
})

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Edit Kategori Produk" />
        <div class="mb-8">
            <Link :href="route('produk.index')">
                <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
            </Link>
        </div>
        <div class="container mx-auto pt-12">
            <div class="grid grid-cols-3 gap-8">
                <div class="">
                    <div v-if="selectedImage" class="flex flex-col gap-3">
                        <div class="w-full h-[400px] border rounded-lg flex justify-center items-center p-1">
                            <img :src="selectedImage.url" alt="" class="h-[400px] object-contain rounded-lg">
                        </div>
                        <div class="relative">
                            <Carousel :value="listGambar" :numVisible="3" :numScroll="3" :responsiveOptions="responsiveOptions">
                                <template #item="slotProps">
                                    <div class="border-1 surface-border border-round m-2 text-center py-5 px-3">
                                        <div class="mb-3">
                                            <img @click="changeImage(slotProps.data)" :src="slotProps.data.url" :alt="slotProps.data.name" class="w-[72px] shadow-2 cursor-pointer" />
                                        </div>
                                    </div>
                                </template>
                            </Carousel>
                        </div>
                    </div>
                    <div v-else class="w-full h-[300px] bg-gray-300 rounded flex justify-center items-center">
                        Gambar tidak tersedia
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <h2 class="text-4xl uppercase font-bold">{{ data.nama }}</h2>
                    <div class="">
                        <p class="text-gray-700">{{ data.kategori.nama }}</p>
                        <div class=" grid grid-cols-2 gap-x-3 text-gray-500 text-sm">
                            <p>Panjang: {{selectedProduk.panjang}} cm</p>
                            <p>Lebar: {{selectedProduk.lebar}} cm</p>
                            <p>Tinggi: {{selectedProduk.tinggi}} cm</p>
                            <p>Berat: {{selectedProduk.berat}} kg</p>
                        </div>
                    </div>
                    <p>
                        {{ data.deskripsi }}
                    </p>
                </div>
                <div class="">
                    <div class="card">
                        <div class="mb-5">
                            <div class="mb-2">
                                <label for="warna" class="capitalize">Warna Produk</label>
                            </div>
                            <Dropdown @change="selectProduk" v-model="selectedProduk" :options="data.listProduk" optionLabel="warna" placeholder="Warna Produk" class="w-full capitalize" />
                            <p v-if="errors.warna" class="text-red-500">{{ errors.warna }}</p>
                        </div>
                        <div class="mb-3">
                            <div class="mb-2">
                                <label for="ukuran" class="capitalize">Ukuran yang tersedia</label>
                            </div>
                            <p class="text-lg font-semibol uppercase">{{ JSON.parse(selectedProduk.ukuran) }}</p>
                        </div>
                        <div class="mb-3">
                            <div class="mb-2">
                                <label for="" class="capitalize">Harga</label>
                            </div>
                            <p class="text-lg font-semibol">Rp {{ selectedProduk.harga.toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="mb-3">
                            <div class="mb-2">
                                <label for="" class="capitalize">Stok Tersedia</label>
                            </div>
                            <p class="text-lg font-semibol uppercase">{{ selectedProduk.jumlah_stok }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>