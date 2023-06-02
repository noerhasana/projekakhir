<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { ref, computed } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';
const toast = useToast();

const user = usePage().props.auth.user;

const fileAvatar = ref();

const urlFileAvatar = computed(() => (fileAvatar.value) ? URL.createObjectURL(fileAvatar.value) : '');

function selectAvatar(event) {
    fileAvatar.value = event.target.files[0];
}

function cancel() {
    fileAvatar.value = null;
}

function upload() {
    const formData = new FormData();
    formData.append('avatar', fileAvatar.value);

    axios.post(route('avatar.store'), formData)
        .then(() => {
            toast.add({ 
                severity: 'info', 
                summary: 'Berhasil', 
                detail: 'Foto profile berhasil diunggah', 
                life: 3000 
            });
        })
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Foto Profile</h2>
        </header>

        <div class="mt-4">
            <img v-if="! fileAvatar" :src="user.avatar_url" alt="" class="w-[150px]">
            <img v-else :src="urlFileAvatar" alt="" class="w-[150px]">
        </div>

        <div class="mt-4">
            <input @change="selectAvatar" id="avatar-input" class="hidden" type="file" accept="*.jpg,*.png">
            <label v-if="! fileAvatar" for="avatar-input">
                <div class=" px-6 py-3 bg-prime-300 hover:bg-prime-400 w-fit rounded text-white text-lg cursor-pointer"><i class="fa fa-image mr-2"></i> Upload profile </div>
            </label>
            <div v-else class=" flex gap-4">
                <Button @click="cancel" label="Batal" icon="fa fa-times" severity="danger"></Button>
                <Button @click="upload" label="Simpan" icon="fa fa-check" severity="success"></Button>
            </div>
        </div>
    </section>
    <Toast />
</template>