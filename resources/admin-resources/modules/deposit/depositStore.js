import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import {defineStore} from "pinia";
import {useNotificationStore} from "../../components/shared/notification/notificationStore";

export const useDepositStore = defineStore("deposit", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        limit: 20,
        q_member: "",
        q_amount: "",
        q_start_date: "",
        q_end_date: "",
        q_sort_column: "id",
        q_sort_order: "desc",

        deposits: [],
        edit_deposit_id: null,
        view_deposit_id: null,

        add_deposit_errors: {},
        edit_deposit_errors: {},
        current_deposit_item: {
            id: "",
            member_id: "",
            received_by: "",
            amount: "",
            deposit_at: "",
            comment: "",
            slip: ""
        },
    }),
    getters: {},
    actions: {
        resetCurrentDepositData() {
            this.current_deposit_item = {
                id: "",
                member_id: "",
                received_by: "",
                amount: "",
                deposit_at: "",
                comment: "",
                slip: ""
            };
            this.add_deposit_errors = [];
            this.edit_deposit_errors = [];
        },
        fetchDeposits(page, limit, q_search = "") {
            const params = {
                page,
                limit,
                member_id: this.q_member,
                amount: this.q_amount,
                start_date: this.q_start_date,
                end_date: this.q_end_date,
                sort_column: this.q_sort_column,
                sort_order: this.q_sort_order,
            };

            return new Promise((resolve, reject) => {
                axios
                    .get("/api/deposits", {params})
                    .then((response) => {
                        this.deposits = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.limit = response.data.meta.per_page;
                            this.q_search = q_search;
                        }
                        resolve(this.deposits);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },
        async fetchDeposit(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/deposits/${id}`)
                    .then((response) => {
                        this.current_deposit_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addDeposit(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/deposits`, data)
                    .then((response) => {
                        this.resetCurrentDepositData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Deposit Added Successfully",
                            type: "success",
                            time: 2000,
                        });

                        resolve();
                    })
                    .catch((error) => {

                        const errorMessage =
                            error.response?.data?.message || "An error occurred while creating the deposit record.";

                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: errorMessage,
                            type: "error",
                            time: 2000,
                        });

                        if (error.response.status === 422) {
                            this.add_deposit_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editDeposit(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/deposits/${this.edit_deposit_id}`, data)
                    .then((response) => {
                        this.resetCurrentDepositData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Deposit record updated successfully",
                            type: "success",
                        });
                        resolve(response);
                    })
                    .catch((errors) => {
                        const errorMessage =
                            errors.response?.data?.message || "An error occurred while updating the deposit record.";

                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: errorMessage,
                            type: "error"
                        });

                        if (errors.response.status === 422) {
                            this.edit_deposit_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteDeposit(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/deposits/${id}`)
                    .then((response) => {
                        if (
                            this.deposits.length === 1 ||
                            (Array.isArray(id) &&
                                id.length === this.deposits.length)
                        ) {
                            this.current_page === 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentDepositData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Deposit deleted successfully",
                            type: "success",
                            time: 2000,
                        });

                        resolve(response);
                    })
                    .catch((error) => {
                        const errorMessage =
                            errors.response?.data?.message || "An error occurred while deleting the deposit record.";

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
