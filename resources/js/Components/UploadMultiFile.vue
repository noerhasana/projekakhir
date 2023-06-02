<script setup>
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import Button from 'primevue/button';
import Badge from 'primevue/badge';
import ProgressBar from 'primevue/progressbar';
import { useToast } from "primevue/usetoast";
import { ref, computed, defineProps, defineEmits, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3'


const toast = useToast();

const emit = defineEmits([
    'uploaded'
]);

const props = defineProps({
    url: String,
})

const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);
const uploadedFiles = ref([]);

const onRemoveTemplatingFile = (file, removeFileCallback, index) => {
    removeFileCallback(index);
    totalSize.value -= parseInt(formatSize(file.size));
    totalSizePercent.value = totalSize.value / 10;
};


const onClearTemplatingUpload = (clear) => {
    clear();
    totalSize.value = 0;
    totalSizePercent.value = 0;
};

const onSelectedFiles = (event) => {
    files.value = event.files;
    files.value.forEach((file) => {
        totalSize.value += parseInt(formatSize(file.size));
    });
};

const upload = () => {
    const formData = new FormData();

    files.value.forEach(file => {
        formData.append('images[]', file);
    })

    axios.post(props.url, formData, {
            onUploadProgress: (progressEvent) => {
                totalSizePercent.value = (progressEvent.loaded / progressEvent.total) * 100;
            }
        })
        .then((response) => {
            emit('uploaded', response.data)
            uploadedFiles.value = [...files.value];
            files.value = [];
        })
};

const onTemplatedUpload = ($event) => {
    toast.add({ severity: "info", summary: "Berhasil", detail: "File berhasil diunggah", life: 3000 });
    emit('uploaded');
};

const formatSize = (bytes) => {
    if (bytes === 0) return "0 B";
    const k = 1024;
    const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};
</script>

<template>
    <div class="">
        <Toast />
        <FileUpload name="images[]" customUpload @uploader="upload" :auto="true" @upload="onTemplatedUpload($event)" :multiple="true" accept="image/*" @select="onSelectedFiles">
            <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                    <div class="flex gap-2">
                        <Button @click="chooseCallback()" icon="fa fa-images" rounded outlined></Button>
                        <Button @click="upload()" icon="fa fa-cloud-upload" rounded outlined severity="success" :disabled="!files || files.length === 0"></Button>
                        <Button @click="clearCallback()" icon="fa fa-times" rounded outlined severity="danger" :disabled="!files || files.length === 0"></Button>
                    </div>
                    <ProgressBar :value="totalSizePercent" :showValue="false" :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]"
                        ><span class="white-space-nowrap">{{ totalSize }}B / 1Mb</span></ProgressBar
                    >
                </div>
            </template>
            <template #content="{ fs, ufiles, removeUploadedFileCallback, removeFileCallback }">
                <div v-if="files.length > 0">
                    <h5>Pending</h5>
                    <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                        <div v-for="(file, index) of files" :key="file.name + file.type + file.size" class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                            <div>
                                <img role="presentation" :alt="file.name" :src="file.objectURL" width="100" height="50" class="shadow-2" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <p class="font-semibold">{{ file.name.slice(0, 20) }}</p>
                                <div>{{ formatSize(file.size) }}</div>
                                <Badge value="Pending" severity="warning" />
                            </div>
                            <Button icon="fa fa-times" @click="onRemoveTemplatingFile(file, removeFileCallback, index)" outlined rounded  severity="danger" />
                        </div>
                    </div>
                </div>

                <div v-if="uploadedFiles.length > 0">
                    <h5>Completed</h5>
                    <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                        <div v-for="(file, index) of uploadedFiles" :key="file.name + file.type + file.size" class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                            <div>
                                <img role="presentation" :alt="file.name" :src="file.objectURL" width="100" height="50" class="shadow-2" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <p class="font-semibold">{{ file.name.slice(0, 20) }}</p>
                                <div>{{ formatSize(file.size) }}</div>
                                <Badge value="Completed" class="mt-3" severity="success" />
                            </div>
                            <Button icon="fa fa-times" @click="removeUploadedFileCallback(index)" outlined rounded  severity="danger" />
                        </div>
                    </div>
                </div>
            </template>
            <template #empty>
                <div class="flex items-center justify-center flex-col">
                    <i class="fa fa-cloud-upload p-5 text-8xl text-gray-300 border-2 border-dashed rounded-xl" />
                    <p class="mt-4 mb-0">Drag and drop files to here to upload.</p>
                </div>
            </template>
        </FileUpload>
    </div>
</template>