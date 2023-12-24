<script setup >

function logout() {
    localStorage.removeItem('access_token')
    window.location.href = '/login'
}
</script>
<template>
    <aside id="sidebar">
        <div class="sidebar-title">
            <div class="sidebar-brand">
                <span class="material-icons-outlined"></span>Admin Dashboard
            </div>
            <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
        </div>

        <ul class="sidebar-list">
            <li class="sidebar-list-item">
                <router-link to="/departments" >
                    <span class="material-icons-outlined">fact_check</span>
                    Departments
                </router-link>
            </li>
            <li class="sidebar-list-item">
                <router-link to="/roles" >
                    <span class="material-icons-outlined">check_box</span>
                    Roles
                </router-link>
            </li>
            <li class="sidebar-list-item">
                <a class="text-decoration-none" @click="logout"  >
                    Logout
                </a>
            </li>
        </ul>
    </aside>
</template>
