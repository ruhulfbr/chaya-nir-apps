<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useDepositStore } from "./depositStore.js";
import Multiselect from "@vueform/multiselect";

const props = defineProps(["income_id", "categories"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const depositStore = useDepositStore();
const deposit_data = computed(() => depositStore.current_deposit_item);

async function submitData() {
    depositStore
        .editDeposit(JSON.parse(JSON.stringify(depositStore.current_deposit_item)))
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred" + error);
        });
}

async function fetchData(id) {
    loading.value = true;
    await depositStore.fetchDeposit(id);
    loading.value = false;
}

async function closeeditDepositModal() {
    depositStore.resetCurrentDepositData();
    emit("close");
}

onMounted(async () => {
    fetchData(props.income_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Income</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeeditDepositModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Income Short Title</label>
                                <p
                                    class="text-danger"
                                    v-if="depositStore.edit_deposit_errors.title"
                                >
                                    {{ depositStore.edit_deposit_errors.title }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="deposit_data.title"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Income Category</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        depositStore.edit_deposit_errors
                                            .categories
                                    "
                                >
                                    {{
                                        depositStore.edit_deposit_errors
                                            .categories
                                    }}
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
                                    v-if="depositStore.edit_deposit_errors.date"
                                >
                                    {{ depositStore.edit_deposit_errors.date }}
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
                                    v-if="depositStore.edit_deposit_errors.amount"
                                >
                                    {{ depositStore.edit_deposit_errors.amount }}
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
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeeditDepositModal"
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
