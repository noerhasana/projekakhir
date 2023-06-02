<script setup>
import { ref, onMounted, computed, toRef } from "vue";

const props = defineProps({
    meta: Object,
});


const maxButton = ref(5);
const pages = computed(() => {
    var items = []
    // var from = Math.min(props.meta.current_page, (props.meta.last_page - maxButton.value + 1));
    var from = props.meta.current_page;
    var to = Math.min(props.meta.last_page, (props.meta.current_page + maxButton.value - 1));
    for (var i = from; i <= to; i++) {
        items.push(i);
    }

    return items;
})

function changePage(page) {
    console.log(page);
    // currentPage.value = page;
}

</script>

<template>
    <div class="flex justify-center">
        <button
            :disabled="meta.current_page == 1"
            @click="changePage(1), $emit('changePage', 1)"
            class="bg-white text-prime-400 rounded-md min-w-fit py-2 px-3 mx-1 border border-prime-100 disabled:bg-gray-300 disabled:text-gray-300 disabled:border-gray-300 disabled:hover:!bg-white hover:!bg-prime-50"><i
                class="fa fa-angles-left"></i></button>
        <button
            :disabled="meta.current_page == 1"
            @click="changePage(Math.max(1, meta.current_page - 1)), $emit('changePage', Math.max(1, meta.current_page - 1))"
            class="bg-white text-prime-400 rounded-md min-w-fit py-2 px-3 mx-1 border border-prime-100 disabled:bg-gray-300 disabled:text-gray-300 disabled:border-gray-300 disabled:hover:!bg-white hover:!bg-prime-50"><i
                class="fa fa-angle-left"></i></button>
        <button
            @click="changePage(page), $emit('changePage', page)"
            :class="(page == meta.current_page ? 'bg-prime-500 text-white font-bold  hover:!bg-prime-600' : 'bg-white text-prime-400 hover:!bg-prime-50') + ' rounded-md min-w-fit py-2 px-3 mx-1 border border-prime-100 disabled:bg-gray-300 disabled:text-gray-300 disabled:border-gray-300 disabled:hover:!bg-white'"
            v-for="(page, index) in pages" :key="index">
            {{ page }}
        </button>

        <button
            :disabled="meta.current_page == meta.last_page"
            @click="changePage(Math.min(meta.last_page, meta.current_page + 1)), $emit('changePage', Math.min(meta.last_page, meta.current_page + 1))"
            class="bg-white text-prime-400 rounded-md min-w-fit py-2 px-3 mx-1 border border-prime-100 disabled:bg-gray-300 disabled:text-gray-300 disabled:border-gray-300 disabled:hover:!bg-white hover:!bg-prime-50"><i
                class="fa fa-angle-right"></i></button>
        <button
            :disabled="meta.current_page == meta.last_page"
            @click="changePage(meta.last_page), $emit('changePage', meta.last_page)"
            class="bg-white text-prime-400 rounded-md min-w-fit py-2 px-3 mx-1 border border-prime-100 disabled:bg-gray-300 disabled:text-gray-300 disabled:border-gray-300 disabled:hover:!bg-white hover:!bg-prime-50"><i
                class="fa fa-angles-right"></i></button>
    </div>
</template>

