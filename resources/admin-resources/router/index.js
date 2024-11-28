import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    mode: "hash",
    history: createWebHistory(),

    routes: [
        {
            path: "/admin",
            name: "admin",
            component: () => import("../views/Admin.vue"),
            children: [
                // dashboard route
                {
                    name: "dashboard",
                    path: "",
                    component: () =>
                        import("../modules/dashboard/Dashboard.vue"),
                },

                // Member
                {
                    name: "members",
                    path: "members",
                    component: () =>
                        import(
                            "../modules/members/Members.vue"
                            ),
                },

                {
                    name: "deposits",
                    path: "deposits",
                    component: () => import("../modules/deposit/Deposits.vue"),
                },

                // Expenses Route
                {
                    name: "expenses",
                    path: "expenses",
                    component: () => import("../modules/expense/Expenses.vue"),
                }
            ],
        },
    ],
});

export default router;
