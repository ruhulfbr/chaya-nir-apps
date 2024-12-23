import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        fetched: false,
        user: {},
        authenticated: false,
    }),
    getters: {},
    actions: {
        async getAuthUser() {
            await axios
                .get(`/api/users/authenticated-user`)
                .then((response) => {
                    this.user = response.data.user;
                    this.authenticated = response.data.authenticated;
                })
                .catch((errors) => {});
        }
    },
});


