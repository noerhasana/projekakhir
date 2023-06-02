<script setup>
import { Head, router, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, defineProps, onMounted } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Carousel from 'primevue/carousel';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const toast = useToast();
const page = usePage()
const props = defineProps({ 
    data: Object
});

const cart = ref(props.data);

const selectedProduk = ref();

const totalTagihan = computed(() => 
    (selectedProduk.value)
        ? selectedProduk.value
            .map(val => (val.total_pesanan * val.produk.harga))
            .reduce((a, b) => (a + b), 0)
        : 0);

function onCellEditComplete(event) {
    const cartSelected = cart.value.filter(val => (val.id == event.data.id))[0];

    if (cartSelected.total_pesanan != event.newValue) {
        cartSelected.total_pesanan = event.newValue;

        router.put(route('cart.update', {id: event.data.id}), {
            total_pesanan: event.newValue,
        }, {
            onFinish: () => {
                toast.add({ 
                    severity: page.props.flash.message.type, 
                    summary: page.props.flash.message.title, 
                    detail: page.props.flash.message.detail, 
                    life: 3000 
                });
            }
        });
    }

}

function destroy(id) {
    router.delete(route('cart.destroy', {id: id}), {
        onFinish: () => {
            router.get(route('cart.index'));
        }
    })
}

onMounted(() => {
})

</script>

<template>
    <Head title="Keranjang" />

    <main class="container py-16 mx-auto">
        <div class="mb-8">
            <Link :href="route('home')">
                <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
            </Link>
        </div>
        <div class="mb-12">
            <h2 class="text-5xl font-bold text-center">Keranjang</h2>
        </div>
        <div class="card">
            <DataTable v-model:selection="selectedProduk" :value="cart" dataKey="id" editMode="cell" @cell-edit-complete="onCellEditComplete">
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="id" header="">
                    <template #body="{data}">
                        <img v-if="data.produk.gambar[0]" :src="data.produk.gambar[0].url" alt="" class="h-[56px] object-contain rounded-lg">
                        <p v-else class="p-4 w-fit rounded bg-gray-200 text-gray-600">Gambar tidak tersedia</p>
                    </template>
                </Column>
                <Column field="id" header="Nama">
                    <template #body="{data}">
                        <h2 class="capitalize">{{ data.produk.nama }}</h2>
                        <h2 class="text-sm font-semibold text-red-400">Stok tersedia: {{ data.produk.jumlah_stok }}</h2>
                        <h2 class="text-sm text-gray-500">Harga: Rp {{ data.produk.harga.toLocaleString('id-ID') }}</h2>
                    </template>
                </Column>
                <Column field="ukuran" header="Ukuran"></Column>
                <Column field="total_pesanan" header="Jumlah Pemesanan">
                    <template #body="{ data, field }">
                        <div class="flex flex-row gap-4 items-center justify-center">
                            {{ data.total_pesanan }}
                        </div>
                        <p class="text-sm text-gray-500 mt-3 text-center">Klik untuk mengubah jumlah</p>
                    </template>
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data.total_pesanan" :min="1" :max="data.produk.jumlah_stok" autofocus />
                    </template>
                </Column>
                <Column field="total_pesanan" header="Total harga">
                    <template #body="{data}">
                        <p>Rp {{ (data.produk.harga * data.total_pesanan).toLocaleString('id-ID') }}</p>
                    </template>
                </Column>
                <Column field="id" header="">
                    <template #body="{data}">
                        <Button @click="destroy(data.id)" icon="fa fa-trash" severity="danger" />
                    </template>
                </Column>
            </DataTable>
            <div class="flex flex-row gap-6 items-end justify-end pt-4">
                <div class="">
                    <p class="text-md font-semibold  text-gray-600">Total harga barang</p>
                    <p class="text-xl font-bold">Rp {{ totalTagihan.toLocaleString('id-ID') }}</p>
                </div>
                <div class="">
                    <Button @click="router.post(route('order.store'), {list_produk: selectedProduk})" :disabled="!selectedProduk || selectedProduk.length == 0" label="Checkout" icon="fa fa-wallet"/>
                </div>
            </div>
        </div>
    </main>
    <Toast />
</template>
