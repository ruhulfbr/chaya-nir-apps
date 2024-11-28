import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useMemberStore = defineStore("members", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        limit: 20,
        q_name: "",
        q_phone: "",
        members: [],

        edit_member_id: null,
        view_member_id: null,

        add_member_errors: {},
        edit_member_errors: {},
        current_member_item: {
            id: "",
            name: "",
            phone: "",
            photo: "",
            status: 1
        },
    }),

    getters: {},

    actions: {
        resetCurrentMemberData() {
            this.current_member_item = {
                id: "",
                name: "",
                phone: "",
                photo: "",
                status: 1
            };
            this.add_member_errors = [];
            this.edit_member_errors = [];
        },

        fetchMemberList() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/members?limit=100`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        fetchMembers(page, limit, q_name = "", q_phone = "") {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/members?page=${page}&limit=${limit}&name=${q_name}&phone=${q_phone}`
                    )
                    .then((response) => {
                        this.members = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.limit = response.data.meta.per_page;
                            this.q_name = q_name;
                            this.q_phone = q_phone;
                        }
                        resolve(this.members);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchMember(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/members/${id}`)
                    .then((response) => {
                        this.current_member_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addMember(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/members`, data)
                    .then((response) => {
                        this.resetCurrentMemberData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Member Added Successfully",
                            type: "success",
                            time: 2000,
                        });

                        resolve();
                    })
                    .catch((error) => {
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Error Occurred",
                            type: "error",
                            time: 2000,
                        });

                        if (error.response.status === 422) {
                            this.add_member_errors =
                                formatValidationErrors(
                                    error.response.data.errors
                                );
                        }
                        reject(error);
                    });
            });
        },

        async editMember(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(
                        `/api/members/${this.edit_member_id}`,
                        data
                    )
                    .then((response) => {
                        this.resetCurrentMemberData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Member updated successfully",
                            type: "success",
                        });
                        resolve(response);
                    })
                    .catch((errors) => {
                        console.log(errors);
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Error Occurred",
                            type: "error",
                        });

                        if (errors.response.status === 422) {
                            this.edit_member_errors =
                                formatValidationErrors(
                                    errors.response.data.errors
                                );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteMember(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/members/${id}`)
                    .then((response) => {
                        if (
                            this.members.length === 1 ||
                            (Array.isArray(id) &&
                                id.length === this.members.length)
                        ) {
                            this.current_page === 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentMemberData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Member data deleted successfully",
                            type: "success",
                            time: 2000,
                        });

                        resolve(response);
                    })
                    .catch((errors) => {
                        const errorMessage =
                            errors.response?.data?.message || "An error occurred while deleting the member.";

                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: errorMessage,
                            type: "error",
                            time: 3000,
                        });

                        reject(errors);
                    });
            });
        },
    },
});
