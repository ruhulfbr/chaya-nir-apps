<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useDepositStore } from "./depositStore.js";
import Multiselect from "@vueform/multiselect";

const emit = defineEmits(["close", "refreshData"]);
const props = defineProps(["categories"]);

const depositStore = useDepositStore();
const deposit_data = computed(() => depositStore.current_deposit_item);

async function submitData() {
    depositStore
        .addDeposit(JSON.parse(JSON.stringify(depositStore.current_deposit_item)))
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddDepositModal() {
    depositStore.resetCurrentDepositData();
    emit("close");
}

onMounted(() => {
    depositStore.resetCurrentDepositData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Deposit</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddDepositModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Income Short Title</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="deposit_data.title"
                            />
                            <span
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.title"
                            >
                                {{ depositStore.add_deposit_errors.title }}
                            </span>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Income Category</label>
                            <p
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.categories"
                            >
                                {{ depositStore.add_deposit_errors.categories }}
                            </p>

                            <Multiselect
                                :searchable="true"
                                mode="tags"
                                :hide-selected="false"
                                v-model="deposit_data.categories"
                                :options="categories"
                            ></Multiselect>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Income Date</label>
                            <p
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.date"
                            >
                                {{ depositStore.add_deposit_errors.date }}
                            </p>
                            <input
                                type="date"
                                class="form-control"
                                v-model="deposit_data.date"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Income Amount</label>
                            <p
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.amount"
                            >
                                {{ depositStore.add_deposit_errors.amount }}
                            </p>
                            <input
                                type="number"
                                class="form-control"
                                v-model="deposit_data.amount"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Description</label>
                            <textarea
                                v-model="deposit_data.description"
                                class="form-control"
                                rows="5"
                            ></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddDepositModal"
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
