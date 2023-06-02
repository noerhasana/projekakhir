<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import AvatarSection from './Partials/AvatarSection.vue';
import Alamat from './Partials/Alamat.vue';
import { onMounted } from 'vue';
import { Link, useForm, usePage, Head } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const page = usePage();

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    listAlamat: Array,
    errors: Object,
});

onMounted(() => {
    if (page.props.flash.message) {
        toast.add({ 
            severity: page.props.flash.message.type, 
            summary: page.props.flash.message.title, 
            detail: page.props.flash.message.detail, 
            life: 3000 
        });
    }
});
</script>

<template>
    <Head title="Profile" />

    <main class="container py-16 mx-auto">
        <div class="mb-12">
            <h2 class="text-5xl font-bold text-center">Profile</h2>
        </div>
        <div class="">
            <div class="mb-8">
                <Link :href="route('home')">
                    <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
                </Link>
            </div>
            <div class="container mx-auto pt-12">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <AvatarSection
                                class="max-w-xl"
                            />
                        </div>
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </div>
        
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <Alamat :listAlamat="listAlamat" :errors="errors"/>
                        </div>

                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<Toast />
</template>
