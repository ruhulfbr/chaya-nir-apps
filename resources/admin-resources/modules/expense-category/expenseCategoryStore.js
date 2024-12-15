import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";
import {handleErrors, handleSuccess} from "../../utils/handle-notification.js";

export const useExpenseCategoryStore = defineStore("expense_category", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        limit: 20,
        q_name: "",
        expense_categories: [],
        edit_expense_category_id: null,
        view_expense_category_id: null,
        add_expense_category_errors: {},
        edit_expense_category_errors: {},
        current_expense_category_item: {
            id: "",
            name: "",
        },
    }),

    getters: {},

    actions: {
        resetCurrentExpenseCatData() {
            this.current_expense_category_item = {
                id: "",
                name: "",
            };
            this.add_expense_category_errors = [];
            this.edit_expense_category_errors = [];
        },

        fetchCatList() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/category?limit=100`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        fetchExpenseCats(page, limit, q_name = "") {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/category?page=${page}&limit=${limit}&name=${q_name}`
                    )
                    .then((response) => {
                        this.expense_categories = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.limit = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.expense_categories);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        async fetchExpenseCat(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/category/${id}`)
                    .then((response) => {
                        this.current_expense_category_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        async addExpenseCat(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/category`, data)
                    .then((response) => {
                        this.resetCurrentExpenseCatData();
                        handleSuccess("Expense Category Added Successfully")
                        resolve();
                    })
                    .catch((errors) => {
                        handleErrors(errors, this.add_expense_category_errors)
                        reject(errors);
                    });
            });
        },

        async editExpenseCat(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(
                        `/api/category/${this.edit_expense_category_id}`,
                        data
                    )
                    .then((response) => {
                        this.resetCurrentExpenseCatData();
                        handleSuccess("Expense Category Updated Successfully")
                        resolve(response);
                    })
                    .catch((errors) => {
                        handleErrors(errors, this.edit_expense_category_errors)
                        reject(errors);
                    });
            });
        },

        async deleteExpenseCat(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/category/${id}`)
                    .then((response) => {
                        if (
                            this.expense_categories.length === 1 ||
                            (Array.isArray(id) &&
                                id.length === this.expense_categories.length)
                        ) {
                            this.current_page === 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentExpenseCatData();
                        handleSuccess("Expense Category Deleted Successfully")
                        resolve(response);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },
    },
});
