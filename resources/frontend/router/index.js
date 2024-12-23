import {createRouter, createWebHistory} from "vue-router";

function PathNotFound() {
    window.location.href = "/404";
}

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
                    props: (route) => ({ isAuthenticated: route.meta.isAuthenticated }),
                },

                // Member
                {
                    name: "members",
                    path: "members",
                    component: () => import("../modules/members/Members.vue"),
                    props: (route) => ({ isAuthenticated: route.meta.isAuthenticated }),
                },

                {
                    name: "deposits",
                    path: "deposits",
                    component: () => import("../modules/deposit/Deposits.vue"),
                    props: (route) => ({ isAuthenticated: route.meta.isAuthenticated }),
                },

                // Expenses Route
                {
                    name: "expenses",
                    path: "expenses",
                    component: () => import("../modules/expense/Expenses.vue"),
                    props: (route) => ({ isAuthenticated: route.meta.isAuthenticated }),
                },
                {
                    name: "expense_categories",
                    path: "expense-categories",
                    component: () => import("../modules/expense-category/ExpenseCategories.vue"),
                    props: (route) => ({ isAuthenticated: route.meta.isAuthenticated }),
                },
            ],
        },
        // 404 fallback to Laravel
        { path: '/:pathMatch(.*)*', component: PathNotFound },
    ],
});

export default router;
