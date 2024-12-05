<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useExpenseStore } from "./expenseStore";
import Multiselect from "@vueform/multiselect";

const emit = defineEmits(["close", "refreshData"]);
const props = defineProps(["categories", "members"]);

const expenseStore = useExpenseStore();
const expense_data = computed(() => expenseStore.current_expense_item);

async function submitData() {
    expenseStore
        .addExpense(
            JSON.parse(JSON.stringify(expenseStore.current_expense_item))
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddExpenseModal() {
    expenseStore.resetCurrentExpenseData();
    emit("close");
}

onMounted(() => {
    expenseStore.resetCurrentExpenseData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Expense</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddExpenseModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Title <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                placeholder="Enter title"
                                class="form-control"
                                v-model="expense_data.title"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.title
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="expenseStore.add_expense_errors.title"
                            >
                                {{ expenseStore.add_expense_errors.title }}
                            </span>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Category <span class="text-danger">*</span></label>

                            <select
                                class="form-select"
                                v-model="expense_data.category_id"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.category_id
                                }"
                            >
                                <option value="">Select category</option>
                                <option
                                    :key="category.id"
                                    :value="category.id"
                                    v-for="category in categories"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <span
                                class="text-danger"
                                v-if="
                                    expenseStore.add_expense_errors.categories
                                "
                            >
                                {{ expenseStore.add_expense_errors.categories }}
                            </span>

                        </div>
                        <div class="form-item">
                            <label class="my-2">Date <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                placeholder="Enter date"
                                class="form-control"
                                v-model="expense_data.spent_at"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.spent_at
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="expenseStore.add_expense_errors.spent_at"
                            >
                                {{ expenseStore.add_expense_errors.spent_at }}
                            </span>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Amount <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                placeholder="Enter amount"
                                class="form-control"
                                v-model="expense_data.amount"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.amount
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="expenseStore.add_expense_errors.amount"
                            >
                                {{ expenseStore.add_expense_errors.amount }}
                            </span>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Description <span class="text-danger">*</span></label>
                            <textarea
                                v-model="expense_data.description"
                                placeholder="Enter description"
                                class="form-control"
                                rows="5"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.description
                                }"
                            ></textarea>

                            <span
                                class="text-danger"
                                v-if="expenseStore.add_expense_errors.description"
                            >
                                {{ expenseStore.add_expense_errors.amount }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Spent By <span class="text-danger">*</span></label>
                            <select
                                class="form-select"
                                v-model="expense_data.spent_by"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.spent_by
                                }"
                            >
                                <option value="">Select who spent</option>
                                <option
                                    :key="member.id"
                                    :value="member.id"
                                    v-for="member in members"
                                >
                                    {{ member.name }}
                                </option>
                            </select>
                            <span
                                class="text-danger"
                                v-if="expenseStore.add_expense_errors.spent_by"
                            >
                                {{ expenseStore.add_expense_errors.spent_by }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Receipt</label>
                            <input
                                type="url"
                                placeholder="Enter receipt URL"
                                class="form-control"
                                v-model="expense_data.receipt"
                                :class="{
                                    'border-danger': expenseStore.add_expense_errors.receipt
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="expenseStore.add_expense_errors.receipt"
                            >
                                {{ expenseStore.add_expense_errors.receipt }}
                            </span>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddExpenseModal"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary ml-1 btn-sm"
                        @click="submitData"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
