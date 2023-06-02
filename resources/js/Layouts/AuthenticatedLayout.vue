<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { routes } from './Route';
import Toast from 'primevue/toast';
import Menu from 'primevue/menu';
import { useToast } from 'primevue/usetoast';

const page = usePage()
const toast = useToast();
const showingNavigationDropdown = ref(false);
const profileMenu = ref();

const open = ref(true);

function toggleSideBar() {
    open.value = !open.value;
}

const toggle = (event) => {
    profileMenu.value.toggle(event);
};


onMounted(() => {
    if (window.innerWidth < 1024) {
        open.value = false;
    }

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
    <div class="flex w-screen h-screen">
        <div
            :class="(open ? 'left-[0px] lg:w-[300px]' : 'lg:w-[90px]') + 'w-[300px] z-50 absolute -left-[90px] lg:left-[0px] lg:relative duration-300 bg-prime-200 h-screen p-4 shadow-lg shadow-gray-700 overflow-auto'">
            <div class="text-white">
                <div class="px-2 pt-2 pb-5 flex flex-row gap-3 items-center justify-center text-center  border-b border-gray-600">
                    <img src="/images/logo.png" alt="" class="w-[32px] h-[32px]">
                    <span :class="((open) ? 'block' : 'hidden')">Meidy Busana Muslim</span>
                </div>
                <nav v-for="route in routes" class="mt-6 px-3 flex flex-col gap-6 justify-center ">
                    <Link :href="route.to" :class="(open ? '' : 'justify-center') + ' flex flex-row gap-3 items-center cursor-pointer'">
                        <i :class="(open ? '' : 'text-xl ') + route.icon"></i>
                        <span :class="((open) ? 'block' : 'hidden')" class="capitalize">{{ route.name }}</span>
                    </Link>
                </nav>
            </div>
        </div>
        <div class="bg-gray-50 inset-y-0 w-full overflow-scroll">
            <div class="p-5 bg-prime-200 text-white flex justify-between items-center">
                <div class="flex flex-row gap-6 items-center">
                    <button @click="toggleSideBar">
                        <i class="fa fa-bars"></i>
                    </button>
                    <span>
                        <slot name="name" />
                    </span>
                </div>
                <div class="">
                    <button @click="toggle">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <Menu ref="profileMenu" :popup="true" class="w-fit" >
                        <template #start>
                            <div class="flex flex-col p-2 pl-4">
                                <span class="font-bold">{{ page.props.auth.user.name }}</span>
                                <span class="text-sm">{{ page.props.auth.user.email }}</span>
                            </div>
                        </template>
                        <template #end>
                            <Link :href="route('logout')" method="post" as="button" class="cursor-pointer w-full flex gap-4 items-center p-2 pl-4 text-color hover:surface-200">
                                <i class="fa fa-sign-out text-red-500" />
                                <span class="ml-2">Log Out</span>
                            </Link>
                        </template>
                    </Menu>
                </div>
            </div>
            <div class="p-8">
                <h2 class="text-3xl">
                    <slot name="name" />
                </h2>
                <div class="mt-6">
                    <slot />
                    <div v-if="open" @click="toggleSideBar" class="absolute lg:hidden bg-gray-700/50 inset-0 cursor-pointer"></div>
                </div>
            </div>
        </div>
    </div>
    <Toast />
</template>
