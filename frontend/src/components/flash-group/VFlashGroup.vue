<template>
    <transition-group
        tag="div"
        name="flash-group"
        enter-from-class="ltr:translate-x-full rtl:-translate-x-full"
        enter-active-class="transform transition ease-in-out duration-200"
        enter-to-class="ltr:translate-x-0 rtl:-translate-x-0"
        leave-from-class="ltr:translate-x-0 rtl:-translate-x-0"
        leave-active-class="transform transition ease-in-out duration-200"
        leave-to-class="ltr:translate-x-full rtl:-translate-x-full"
        class="grid gap-[10px] fixed top-[20px] ltr:right-[20px] rtl:left-[20px] z-[100022] justify-items-end"
    >
        <VFlashGroupItem v-for="flash in flashes" :key="flash.uid" :flash="flash" @remove="remove(flash)" />
    </transition-group>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import VFlashGroupItem from './VFlashGroupItem.vue'
import emitter from '@/plugins/emitter'

const flashes = ref([]);
const uid = ref(0);

onMounted(() => {
    const sessionFlashes = [];

    sessionFlashes.forEach(flash => {
        flashes.value.push({ ...flash, uid: uid.value++ });
    });

    emitter.on('add-flash', add);
});

const add = (flash) => {
    flash.uid = uid.value++;
    
    flashes.value.push(flash);
};

const remove = (flash) => {
    flashes.value = flashes.value.filter(f => f.uid !== flash.uid);
};
</script>
