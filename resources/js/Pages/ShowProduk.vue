<script setup>
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import Rating from 'primevue/rating';
import Carousel from 'primevue/carousel';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';

const page = usePage()

const selectedImage = ref(props.listGambar[0]);
const selectedProduk = ref({
    produk: props.data.listProduk[0],
    total_pesanan: 1,
});

const showDialog = ref(false);

const props = defineProps({ 
    data: Object,
    errors: Object,
    listGambar: Array,
});



const ukuranTersedia = computed(() => 
    ((selectedProduk.value.produk) ? JSON.parse(selectedProduk.value.produk.ukuran) : []));

function changeImage(img) {
    selectedImage.value = img;
    // selectedProduk.value = props.data.listProduk.filter(val => (val.id == selectedImage.value.produk_id))[0];
}

function selectProduk() {
    selectedImage.value = selectedProduk.value.produk.gambar[0];
    selectedProduk.value.total_pesanan = 1;
}

function tambahJumlah() {
    if (selectedProduk.value.total_pesanan < selectedProduk.value.produk.jumlah_stok) {
        selectedProduk.value.total_pesanan++;
    }
}
function kurangiJumlah() {
    if (selectedProduk.value.total_pesanan > 1) {
        selectedProduk.value.total_pesanan--;
    }
}

function dismissDialog() {
    showDialog.value = false;
}

function store() {
    router.post(route('cart.store'), {
        produk_id: selectedProduk.value.produk.id,
        ukuran: selectedProduk.value.ukuran,
        total_pesanan: selectedProduk.value.total_pesanan,
    }, {
        onSuccess: () => {
            showDialog.value = true;
        }
    });
}

onMounted(() => {
})

</script>

<template>
    <Head :title="data.nama.toUpperCase()" />

    <main class="container py-16 mx-auto">
        <div class="mb-12">
            <h2 class="text-5xl font-bold text-center">Meidy Busana Muslim</h2>
        </div>
        <div class="">
            <div class="mb-8">
                <Link :href="route('home')">
                    <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
                </Link>
            </div>
            <div class="container mx-auto pt-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                            <Rating v-if="data.ringkasanRating.avg > 0" v-model="data.ringkasanRating.avg" readonly :cancel="false" />
                            <p v-else class="text-gray-500">belum ada penilaian</p>
                        </div>
                        <div class="">
                            <p class="text-gray-700">{{ data.kategori.nama }}</p>
                            <p class="text-gray-500 text-sm">Dimensi paket</p>
                            <div class=" grid grid-cols-2 gap-x-3 text-gray-500 text-sm">
                                <p>Panjang: {{selectedProduk.produk.panjang}} cm</p>
                                <p>Lebar: {{selectedProduk.produk.lebar}} cm</p>
                                <p>Tinggi: {{selectedProduk.produk.tinggi}} cm</p>
                                <p>Berat: {{selectedProduk.produk.berat}} kg</p>
                            </div>
                        </div>
                        <p>
                            {{ data.deskripsi }}
                        </p>
                    </div>
                    <div class="">
                        <div class="card">
                            <h3 class="text-xl font-semibold mb-5">Masukkan ke keranjang</h3>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="warna_cart" class="capitalize">Pilih Warna</label>
                                </div>
                                <Dropdown @change="selectProduk" v-model="selectedProduk.produk" :options="data.listProduk" optionLabel="warna" placeholder="Pilih Warna" class="w-full capitalize" />
                                <p v-if="selectedProduk.produk" class="text-gray-700">Jumlah stok: {{ selectedProduk.produk.jumlah_stok }}</p>
                                <p v-if="errors.produk_id" class="text-red-500">{{ errors.produk_id }}</p>
                            </div>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="" class="capitalize">Pilih Ukuran</label>
                                </div>
                                <Dropdown v-model="selectedProduk.ukuran" :options="ukuranTersedia" placeholder="Pilih Ukuran" class="w-full capitalize" />
                                <p v-if="errors.ukuran" class="text-red-500">{{ errors.ukuran }}</p>
                            </div>
                            <div class="mb-5">
                                <div class="mb-2">
                                    <label for="" class="capitalize">Jumlah Pemesanan</label>
                                </div>
                                <div class="flex flex-row gap-4">
                                    <Button @click="kurangiJumlah" severity="warning" icon="fa fa-minus"/>
                                    <InputNumber v-model="selectedProduk.total_pesanan" :min="1" :max="selectedProduk.produk.jumlah_stok" placeholder="Jumlah" class="w-full capitalize" />
                                    <Button @click="tambahJumlah" severity="success" icon="fa fa-plus"/>
                                </div>
                                <p v-if="errors.total_pesanan" class="text-red-500">{{ errors.total_pesanan }}</p>
                            </div>
                            <div class="mb-3">
                                <div class="mb-2">
                                    <label for="" class="capitalize">Total harga</label>
                                </div>
                                <p class="text-lg font-semibol">
                                    Rp {{ selectedProduk.produk ? ((selectedProduk.produk.harga * (selectedProduk.total_pesanan ?? 1))).toLocaleString('id-ID') : '-' }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <Button @click="store" icon="fas fa-shopping-cart" label="Masukkan ke keranjang" class="w-full" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-8">
                    <h3 class="text-xl font-semibold mb-5">Komentar</h3>
                    <template v-for="produk in data.listProduk">
                        <template v-for="feedback in produk.komentar_pemesanan">
                            <div class="card">
                                <Rating v-if="feedback.rating" v-model="feedback.rating.rating" readonly :cancel="false" />
                                <p class="capitalize font-semibold my-2">{{ feedback.komentar.user.name}}</p>
                                <p class="">{{ feedback.komentar.komentar}}</p>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <Dialog v-model:visible="showDialog" modal header=" " :draggable="false" :style="{ width: 'fit' }">
        <div class="flex flex-col gap-5 justify-center items-center ">
            <div class="text-center w-[80px] h-[80px] bg-green-500 flex items-center justify-center rounded-full">
                <i class="text-6xl fa fa-check text-white"></i>
            </div>
            <h3 class="text-2xl text-center uppercase font-bold">Berhasil</h3>

            <p class="text-lg">Berhasil menambahkan ke keranjang</p>
            <div class="flex justify-center gap-4">
                <Button @click="dismissDialog" label="Oke"/>
                <Link :href="route('cart.index')">
                    <Button label="Ke keranjang" severity="warning" />
                </Link>
            </div>
        </div>
    </Dialog>
</template>