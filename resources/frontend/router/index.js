import {createRouter, createWebHistory} from "vue-router";

const router = createRouter({
    mode: "hash",
    history: createWebHistory(),

    routes: [
        {
            path: "/",
            name: "home",
            component: () => import("../views/Admin.vue"),
            children: [
                {
                    name: "dashboard",
                    path: "",
                    component: () => import("../modules/dashboard/Dashboard.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
                {
                    name: "members",
                    path: "members",
                    component: () => import("../modules/members/Members.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },

                {
                    name: "deposits",
                    path: "deposits",
                    component: () => import("../modules/deposit/Deposits.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
                {
                    name: "expenses",
                    path: "expenses",
                    component: () => import("../modules/expense/Expenses.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
                {
                    name: "expense_categories",
                    path: "expense-categories",
                    component: () => import("../modules/expense-category/ExpenseCategories.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
                {
                    name: "expenses_by_date",
                    path: "expenses-by-date",
                    component: () => import("../modules/expense/DateWiseExpenses.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
                {
                    name: "notes",
                    path: "notes",
                    component: () => import("../modules/notes/Notes.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
                {
                    name: "404",
                    path: "/:pathMatch(.*)*",
                    component: () => import("../modules/errors/404.vue"),
                    props: (route) => ({isAuthenticated: route.meta.isAuthenticated}),
                },
            ],
        }
    ],
});

export default router;
