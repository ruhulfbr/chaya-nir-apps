<script setup>
import Sidebar from "../components/Sidebar.vue";
import Navbar from "../components/Navbar.vue";
import Loader from "../components/shared/loader/Loader.vue";
import NotificationsContainer from "../components/shared/notification/notifications-container.vue";
import ConfirmBox from "../components/shared/confirm-alert/confirm-box.vue";
import {useConfirmStore} from "../components/shared/confirm-alert/confirmStore";
import {onMounted, ref} from "vue";
import {useAuthStore} from "../stores/authStore";

const adminReady = ref(false);
const isAuthenticated = ref(false);
const authUser = ref({});
const authStore = useAuthStore();
const confirmStore = useConfirmStore();

onMounted(async () => {
    await authStore.getAuthUser()

    adminReady.value = true;
    isAuthenticated.value = authStore.authenticated
    authUser.value = authStore.user
});
</script>
<template>
    <div>
        <div v-if="adminReady === false" class="pt-5">
            <Loader/>
        </div>
        <div id="app" v-if="adminReady === true">
            <Sidebar/>
            <div id="main">
                <Navbar :isAuthenticated="isAuthenticated" :authUser="authUser"/>
                <div class="main-content container-fluid">
                    <router-view :isAuthenticated="isAuthenticated" />
                </div>
            </div>
        </div>
        <div class="admin-area-modals-container">
            <ConfirmBox v-if="confirmStore.show_confirm_box"/>
            <NotificationsContainer/>
        </div>
    </div>
</template>
