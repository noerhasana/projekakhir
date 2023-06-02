<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import Button from "primevue/button"

const props = defineProps({ 
    total: Object,
});

const items = ref({
    produk: {
        name: 'produk',
        total: props.total['produk'],
        icon: 'fas fa-store',
        route: '',
    },
    stok: {
        name: 'stok',
        total: props.total['stok'],
        icon: 'fa fa-shopping-bag',
        route: '',
    },
    pemesanan: {
        name: 'pemesanan',
        total: props.total['pemesanan'],
        icon: 'fas fa-shopping-cart',
        route: '',
    },
    user: {
        name: 'user',
        total: props.total['user'],
        icon: 'fa fa-user',
        route: '',
    },
});


</script>


<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #name>Dashboard</template>

        <div class="flex flex-col lg:flex-row justify-between gap-4">
            <div v-for="item in Object.values(items)" class="w-full lg:w-76 bg-prime-200 rounded-md grid justify-items-stretch text-white">
                <div class="p-6 flex flex-row justify-between h-[160px]">
                    <div class="flex flex-col gap-4">
                        <!-- <p class="text-5xl font-bold">{{ total[item.name] }}</p> -->
                        <p class="text-5xl font-bold">{{ item.total }}</p>
                        <p class="capitalize">Jumlah {{ item.name }}</p>
                    </div>
                    <div class="text-right">
                        <i :class=" item.icon + ' text-prime-500 text-8xl'"></i>
                    </div>
                </div>
                <div class="inset-x-0 text-center bg-prime-400 p-2 rounded-b-md ">
                    Info Lengkap&nbsp;<i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
