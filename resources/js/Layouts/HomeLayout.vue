<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();
const props = defineProps({
    kategori: Object,
})

</script>

<template>
    <slot name="header" />
    <main class="container py-16 mx-auto">
        <!-- <div class="mb-12">
            <h2 class="text-5xl font-bold text-center">Meidy Busana Muslim</h2>
        </div> -->
        <div class="">
            <slot />
        </div>
    </main>
    <Footer />
</template>