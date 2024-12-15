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
                // dashboard route
                {
                    name: "dashboard",
                    path: "",
                    component: () => import("../modules/dashboard/Dashboard.vue"),
                    props: { authenticated: false }
                },

                // Member
                {
                    name: "members",
                    path: "members",
                    component: () => import("../modules/members/Members.vue"),
                    props: { authenticated: false }
                },

                {
                    name: "deposits",
                    path: "deposits",
                    component: () => import("../modules/deposit/Deposits.vue"),
                    props: { authenticated: false }
                },

                // Expenses Route
                {
                    name: "expenses",
                    path: "expenses",
                    component: () => import("../modules/expense/Expenses.vue"),
                    props: { authenticated: false }
                },
                {
                    name: "expense_categories",
                    path: "expense-categories",
                    component: () => import("../modules/expense-category/ExpenseCategories.vue"),
                    props: { authenticated: false }
                },
            ],
        },
    ],
});

export default router;
