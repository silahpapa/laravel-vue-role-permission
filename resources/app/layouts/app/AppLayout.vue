<script setup>
import {onBeforeMount} from 'vue'
import Header from './sections/Header.vue'
import Sidebar from './sections/Sidebar.vue'
import Footer from './sections/Footer.vue'

import {useRoute,useRouter} from 'vue-router'

const route=useRoute()
const router = useRouter()

onBeforeMount(()=> {
})

</script>
<template>
    <div class="grid-container">
        <Header />
        <Sidebar />
        <main class="main-container">
            <div class="main-title">
                <p class="font-weight-bold">{{ router.currentRoute.value.name }}</p>
            </div>
            <div class="main-content">
                <div class="container">
                    <router-view  />
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>
