<script setup>
import {onBeforeMount,ref} from 'vue'
import { useRoute} from 'vue-router'
const route = useRoute()
let layout = ref(null)
onBeforeMount(()=>{
    setTimeout(()=>{
        layout.value = route.meta.layout == 'app' ? 'AppLayout' : 'AuthLayout';
    },200)
})
</script>
<template>
    <component :is="layout">
    </component>
</template>
