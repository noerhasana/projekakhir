<script setup>
import HomeLayout from '@/Layouts/HomeLayout.vue';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import Rating from 'primevue/rating';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Galleria from 'primevue/galleria';
import Menu from 'primevue/menu'

const page = usePage();
const selectedKategori = ref();
const searchQuery = ref();
const selectedPage = ref();

const props = defineProps({
    kategori: Object,
    data: Object,
})

const user = computed (() => page.props.auth.user);

const usermenu = ref()
const userItems = ref([
    {
        label: 'Profile', 
        icon: 'fa fa-user',
        command: () => {
            router.get(route('profile.edit'));
            
        }
    },
    {
        label: 'Logout', 
        icon: 'fa fa-sign-out text-red-500',
        command: () => {
            router.post(route('logout'));
        }
    },
]);
const images = ref([
    'Get start <br /> your favorite shopping 1',
    'Get start <br /> your favorite shopping 2',
])

const toggle = (event) => {
    usermenu.value.toggle(event);
};

function fetchAgain()  {
    const params = {};

    if (selectedKategori.value) {
        params.kategori = selectedKategori.value.id;
    }

    if (searchQuery.value) {
        params.search = searchQuery.value;
    }

    if (selectedPage.value) {
        params.page = selectedPage.value
    }

    router.get(route('home', params));
}

onMounted(() => {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());

    if (params.search) {
        searchQuery.value = params.search
    }

    if (params.kategori) {
        selectedKategori.value = props.kategori.filter(val => (val.id == params.kategori))[0];
    }
    
})
</script>
<template>
    <Head title="Home" />
    <HomeLayout :kategori="kategori">
        <template #header>
            <div class="min-h-[600px] h-[85vh] relative bg-gray-400">
                <div class="absolute inset-0  z-10">
                    <img src="/images/bg.jpg" class="object-cover w-full h-full" alt="">
                </div>
                <div class="absolute z-30 top-0 inset-x-6 lg:inset-x-48 ">
                    <div class=" bg-prime-300 p-6">
                        <nav>
                            <ul class="flex flex-row gap-8 items-center justify-center text-white">
                                <li class="capitalize text-lg">
                                    <Link :href="route('about')">
                                        About
                                    </Link>
                                </li>
                                <template v-if="page.props.auth.user" class="">
                                    <li v-if="page.props.auth.user.role.includes('admin')"  class="capitalize ">
                                        <Link :href="route('dashboard')" class="flex text-lg items-center">
                                            <i class="fas fa-home mr-2"></i>&nbsp;dashboard
                                        </Link>
                                    </li>
                                    <template v-else class="flex flex-row gap-4 w-fit">
                                        <li class="capitalize ">
                                            <Link :href="route('order.index')"  class="flex text-lg items-center">
                                                <i class="fas fa-box mr-2"></i>&nbsp;pemesanan
                                            </Link>
                                        </li>
                                        <li class="capitalize ">
                                            <Link :href="route('cart.index')"  class="flex text-lg items-center">
                                                <i class="fas fa-shopping-cart mr-2"></i>&nbsp;cart
                                            </Link>
                                        </li>
                                        <li class="capitalize ">
                                            <div @click="toggle" class="flex flex-row text-lg items-center gap-3 cursor-pointer">
                                                <!-- <i class="fa fa-user" aria-haspopup="true" aria-controls="overlay_menu"></i> -->
                                                <img class="h-[32px]" :src="user.avatar_url" alt="">
                                                <span class=" ">{{ page.props.auth.user.name }}</span>
                                            </div>
                                            <Menu ref="usermenu" id="overlay_menu" :model="userItems" :popup="true" />
                                        </li>

                                        <!-- <Link :href="route('logout')" method="post" as="button" class="flex flex-row text-lg items-center">
                                            <i class="fa fa-sign-out text-red-500" /> -->
                                            <!-- <span class="ml-2">LogOut</span> -->
                                        <!-- </Link> -->
                                    </template>
                                </template>
                                <template v-else >
                                    <li  class="capitalize">
                                        <Link :href="route('login')" class="flex text-lg items-center">
                                            <i class="fas fa-user mr-2"></i>&nbsp;login
                                        </Link>
                                    </li>
                                </template>
                            </ul>
                        </nav>
                    </div>
                    <div class="mt-16">
                        <div class="flex flex-col xl:flex-row jus  gap-6 xl:gap-12">
                            <Dropdown @change="fetchAgain" v-model="selectedKategori" :options="kategori" optionLabel="nama" placeholder="Kategori Produk" size="small"/>
                            <form @submit.prevent="fetchAgain" class="w-full">
                                <div class="p-inputgroup">
                                    <InputText id="search" type="text" placeholder="Cari" v-model="searchQuery" />
                                    <Button type="submit" icon="fas fa-magnifying-glass" severity="danger"/>
                                </div>
                            </form>
                            <div class="flex flex-row gap-4 items-center justify-center xl:justify-start">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 flex justify-center items-end z-20">
                    <div class="card  mb-16 !bg-prime-500/50 !border-0 backdrop-blur-sm">
                        <h3 class="text-4xl lg:text-4xl xl:text-6xl uppercase text-bold  text-center text-white py-24 px-12" >
                            Meidy Busana Muslim
                        </h3>
                    </div>
                    <!-- <Galleria :value="images" :circular="true" class="w-full bg-white duration-300" :showItemNavigators="true" :autoPlay="true" :transitionInterval="2000" :showItemNavigatorsOnHover="true" :showThumbnails="false">
                        <template #item="slotProps">
                            <div class="flex flex-col gap-10 xl:gap-24 items-center justify-center px-24 py-[4vh]">
                                <h3 class="text-4xl lg:text-4xl xl:text-6xl uppercase text-bold text-white text-center lg:mb-10 xl:mb-[24vh] 2xl:mb-[3vh]" v-html="slotProps.item" /> -->
            
                                <!-- <div class="lg:mt-0 xl:mt-[3vh] 2xl:mt-[3vh]">
                                    <div class="bg-prime-400 px-12 py-3 text-lg rounded-md text-white">BUY NOW</div>
                                </div> -->
                            </div>
                            <!-- <img :src="slotProps.item" :alt="slotProps.item.alt" style="width: 100%; display: block" /> -->
                        <!-- </template>
                    </Galleria>
                </div> --->
            </div>
        </template>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div @click="router.get(route('detail-produk', {id: produk.id}))" v-for="produk in data.data" class="card grid gap-8 justify-items-stretch p-12 cursor-pointer hover:bg-gray-100">
                <div class="">
                    <h3 class="uppercase text-xl font-bold text-center">{{ produk.nama }}</h3>
                    <h5 class="text-lg font-semibold text-center">Harga Rp {{ produk.harga.toLocaleString('id-ID') }}</h5>

                </div>
                <div class="border rounded p-1 h-full">
                    <img v-if="produk.gambar[0]" :src="produk.gambar[0].url" alt="" class="object-contain h-[350px] rounded mx-auto">
                    <div v-else class="h-[350px] flex items-center justify-center ">
                        <p class="text-center text-gray-400">
                            Gambar tidak tersedia
                        </p>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="w-fit">
                        <Rating v-if="produk.ringkasanRating.avg > 0" v-model="produk.ringkasanRating.avg" readonly :cancel="false" />
                    </div>
                    <Link :href="route('detail-produk', {id: produk.id})">
                        <span class="text-blue-500 hover:text-blue-700">Lihat Detail <i class="fa fa-arrow-right"></i></span>
                    </Link>
                </div>
            </div>
        </div>
        <div class="my-8 flex justify-center gap-8">
            <Link :href="data.prev_page_url">
                <Button :disabled="(data.prev_page_url == null)" icon="fas fa-chevron-left" class="!bg-gray-500 hover:!bg-gray-700"/>        
            </Link>
            <Link :href="data.next_page_url">
                <Button :disabled="(data.next_page_url == null)" icon="fas fa-chevron-right" class="!bg-gray-500 hover:!bg-gray-700"/>        
            </Link>
        </div>
    </HomeLayout>
        
</template>