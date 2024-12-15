<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useExpenseStore } from "./expenseStore";

const props = defineProps(["expense_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const expenseStore = useExpenseStore();
const expense_data = computed(() => expenseStore.current_expense_item);

async function fetchData(id) {
    loading.value = true;
    await expenseStore.fetchExpense(id);
    loading.value = false;
}

async function closeViewExpenseModal() {
    expenseStore.resetCurrentExpenseData();
    emit("close");
}

onMounted(async () => {
    await fetchData(props.expense_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Expense Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewExpenseModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading === false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Title: </label>

                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="expense_data.title"
                                />
                            </div>
                            <div class="form-item" v-if="expense_data.category">
                                <label class="my-2">Category: </label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="expense_data.category.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Date: </label>

                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="expense_data.spent_at"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Amount: </label>
                                <input
                                    disabled
                                    type="number"
                                    class="form-control"
                                    v-model="expense_data.amount"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Description: </label>
                                <textarea
                                    disabled
                                    v-model="expense_data.description"
                                    class="form-control"
                                    rows="5"
                                ></textarea>
                            </div>
                            <div class="form-item" v-if="expense_data.spent_by_member">
                                <label class="my-2">Spent By: </label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="expense_data.spent_by_member.name"
                                />
                            </div>

                            <div class="form-item mt-3">
                                <label class="my-2">Receipt: </label>

                                <template v-if="expense_data.receipt">
                                    <a :href="expense_data.receipt" target="_blank" class="ms-2 link-primary">View Receipt</a>
                                </template>
                                <template v-else>
                                    N/A
                                </template>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeViewExpenseModal"
                    >
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>
