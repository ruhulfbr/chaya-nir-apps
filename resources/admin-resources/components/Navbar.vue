<script setup>
import menuSvgIcon from "../assets/icons/menu-svg-icon.vue";
import userSvgIcon from "../assets/icons/user-svg-icon.vue";
import logoutSvgIcon from "../assets/icons/logout-svg-icon.vue";
import loginSvgIcon from "../assets/icons/login-svg-icon.vue";
import {useAuthStore} from "./../stores/authStore.js";
import { useSidebar } from "../stores/sidebar";
import {onMounted, ref} from "vue";
const sidebarStore = useSidebar();
const userStore = useSidebar();
const authStore = useAuthStore();
const userDropDown = ref(false);

onMounted(async () => {
    await authStore.getAuthUser()
});

</script>

<template>
    <nav class="navbar navbar-header navbar-expand navbar-light">
        <menuSvgIcon @click="sidebarStore.toggle()" />
        <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
            <div class="top-nav-item position-relative" v-if="authStore.authenticated === true">
                <span @click="userDropDown = !userDropDown">
                   {{ authStore.user.name }} <userSvgIcon width="25px" height="25px" />
                </span>
                <div v-if="userDropDown" class="top-nav-dropdown">
                    <a class="top-nav-dropdown-item" href="/logout">
                        <logoutSvgIcon
                            width="16px"
                            height="16px"
                            color="currentColor"
                        />
                        <span class="ms-2">Logout </span>
                    </a>
                </div>
            </div>

            <div class="top-nav-item position-relative" v-else>
                <a class="top-nav-dropdown-item" href="/login">
                    <loginSvgIcon
                        width="16px"
                        height="16px"
                        color="currentColor"
                    />
                    <span class="ms-2">Login </span>
                </a>
            </div>
        </ul>
    </nav>
</template>

<style scoped>
.top-nav-dropdown {
    position: absolute;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 1px 1px 76px rgba(214, 208, 208, 0.479);
    top: 120%;
    right: 0px;
    z-index: 4;
    width: max-content;
}
.top-nav-dropdown-item {
    display: flex;
    align-items: center;
    padding: 6px 16px;
    background-color: white;
    color: #757779;
    width: 100%;
}
.top-nav-dropdown-item:hover {
    background-color: #2ba8f3;
    color: white;
}
</style>
