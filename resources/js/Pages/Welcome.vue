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
import Galleria from 'primevue/galleria';

const props = defineProps({
    kategori: {
        default: [
            1,2,3,4,5
        ]
    }
})

const images = ref([
    'Get start <br /> your favorite shopping 1',
    'Get start <br /> your favorite shopping 2',
])


</script>

<template>
    <Head title="Home"/>
    <div class="h-[85vh] relative bg-gray-400">
        <div class="absolute inset-0  z-10">
            <img src="/images/bg.jpg" class="object-cover w-full h-full" alt="">
        </div>
        <div class="absolute z-20 top-0 inset-x-6 lg:inset-x-48 ">
            <div class=" bg-prime-300 p-6">
                <nav>
                    <ul class="flex flex-row gap-8 items-center justify-center text-white">
                        <li class="capitalize text-lg">About</li>
                        <li class="capitalize text-lg">Catalog</li>
                        <li class="capitalize text-lg">Customer Service</li>
                    </ul>
                </nav>
            </div>
            <div class="mt-16">
                <div class="flex flex-row gap-12">
                    <Dropdown id="paging" :options="kategori" @change="changePage(1)" size="small"/>
                    <div class="p-inputgroup">
                        <InputText id="search" type="text" placeholder="Cari" v-model="searchQuery" />
                        <Button type="submit" icon="fas fa-magnifying-glass" severity="danger"/>
                    </div>
                    <div class="flex flex-row gap-4 items-center">
                        <div class="flex text-lg items-center">
                            <i class="fas fa-shopping-cart"></i>&nbsp;CART
                        </div>
                        <div class="flex text-lg items-center">
                            <i class="fas fa-user"></i>&nbsp;USER
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute inset-0 flex justify-center items-end z-30">
            <Galleria :value="images" :circular="true" class="w-full bg-white duration-150" :showItemNavigators="true" :autoPlay="true" :transitionInterval="2000" :showItemNavigatorsOnHover="true" :showThumbnails="false">
                <template #item="slotProps">
                    <div class="flex flex-col gap-24 items-center justify-center px-24 py-24">
                        <h3 class="text-6xl uppercase text-bold text-white text-center" v-html="slotProps.item" />
    
                        <div class="mt-24">
                            <div class="bg-prime-400 px-12 py-3 text-lg rounded-md text-white">BUY NOW</div>
                        </div>
                    </div>
                    <!-- <img :src="slotProps.item" :alt="slotProps.item.alt" style="width: 100%; display: block" /> -->
                </template>
            </Galleria>
        </div>
    </div>
        <main class="container py-16">
            <h2 class="text-5xl font-bold text-center">Meidy Busana Muslim</h2>
        </main>
    <footer class="text-white">
        <div class="flex flex-col justify-center items-center w-full bg-prime-500">
            <div class="py-24 w-fit">
                <img src="/images/logo.png" alt="" class="w-[72px]">
            </div>
            <span>FOLLOW US</span>
            <div class="w-fit flex flex-row gap-3">
                <i class="far bg-white fa-facebook"></i>
                <i class="far bg-white fa-instagram"></i>
                <i class="far bg-white fa-whatsapp"></i>
            </div>
        </div>
        <main class="container">

        </main>
        <div class="bg-gray-800 py-8 text-center">
            © 2023 Meidy Busana Muslim
        </div>
    </footer>
</template>