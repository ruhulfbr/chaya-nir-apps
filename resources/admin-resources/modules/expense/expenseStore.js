import axios from "axios";
import {defineStore} from "pinia";
import {handleErrors, handleSuccess} from "../../utils/handle-notification.js";

export const useExpenseStore = defineStore("expense", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        limit: 20,

        q_title: "",
        q_category: "",
        q_start_amount: "",
        q_end_amount: "",
        q_start_date: "",
        q_end_date: "",
        q_sort_column: "id",
        q_sort_order: "desc",

        expenses: [],

        edit_expense_id: null,
        view_expense_id: null,

        add_expense_errors: {},

        edit_expense_errors: {},

        current_expense_item: {
            id: "",
            category_id: "",
            title: "",
            amount: "",
            description: "",
            receipt: "",
            spent_at: "",
            spent_by: "",
        },
    }),

    getters: {},

    actions: {
        resetCurrentExpenseData() {
            this.current_expense_item = {
                id: "",
                category_id: "",
                title: "",
                amount: "",
                description: "",
                receipt: "",
                spent_at: "",
                spent_by: "",
            };
            this.add_expense_errors = [];
            this.edit_expense_errors = [];
        },

        fetchExpenses(page, limit, q_title = "") {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/expenses?page=${page}&limit=${limit}&title=${q_title}&category=${this.q_category}&start_amount=${this.q_start_amount}&end_amount=${this.q_end_amount}&start_date=${this.q_start_date}&end_date=${this.q_end_date}&sort_column=${this.q_sort_column}&sort_order=${this.q_sort_order}`
                    )
                    .then((response) => {
                        this.expenses = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.limit = response.data.meta.per_page;
                            this.q_title = q_title;
                        }
                        resolve(this.expenses);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        async fetchExpense(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/expenses/${id}`)
                    .then((response) => {
                        this.current_expense_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        async addExpense(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/expenses`, data)
                    .then((response) => {
                        this.resetCurrentExpenseData();
                        handleSuccess("Expense Added Successfully")
                        resolve();
                    })
                    .catch((errors) => {
                        handleErrors(errors, this.add_expense_errors)
                        reject(errors);
                    });
            });
        },

        async editExpense(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/expenses/${this.edit_expense_id}`, data)
                    .then((response) => {
                        this.resetCurrentExpenseData();
                        handleSuccess("Expense record updated successfully")
                        resolve(response);
                    })
                    .catch((errors) => {
                        handleErrors(errors, this.edit_expense_errors)
                        reject(errors);
                    });
            });
        },

        async deleteExpense(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/expenses/${id}`)
                    .then((response) => {
                        if (this.expenses.length === 1 || (Array.isArray(id) && id.length === this.expenses.length)) {
                            this.current_page === 1 ? (this.current_page = 1) : (this.current_page -= 1);
                        }

                        this.resetCurrentExpenseData();
                        handleSuccess("Expense record deleted successfully")
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
