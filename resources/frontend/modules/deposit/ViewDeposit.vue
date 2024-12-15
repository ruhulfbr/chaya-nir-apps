<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useDepositStore } from "./depositStore.js";

const props = defineProps(["deposit_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const depositStore = useDepositStore();
const deposit_data = computed(() => depositStore.current_deposit_item);

async function fetchData(id) {
    loading.value = true;
    let data = await depositStore.fetchDeposit(id);
    loading.value = false;
}

async function closeViewDepositModal() {
    depositStore.resetCurrentDepositData();
    emit("close");
}

onMounted(async () => {
    await fetchData(props.deposit_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deposit Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewDepositModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading === false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Member: </label>
                                <template v-if="deposit_data.member">
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        v-model="deposit_data.member.name"
                                    />
                                </template>
                            </div>

                            <div class="form-item">
                                <label class="my-2">Amount: </label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    :value="deposit_data.amount_formatted"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Deposit Date: </label>

                                <input
                                    disabled
                                    type="date"
                                    class="form-control"
                                    v-model="deposit_data.deposit_at"
                                />
                            </div>

                            <div class="form-item">
                                <label class="my-2">Comment: </label>
                                <textarea
                                    disabled
                                    v-model="deposit_data.comment"
                                    class="form-control"
                                    rows="5"
                                ></textarea>
                            </div>

                            <div class="form-item mt-1">
                                <label class="my-2">Slip: </label>

                                <template v-if="deposit_data.slip">
                                    <a :href="deposit_data.slip" target="_blank" class="ms-2 link-primary">View Slip</a>
                                </template>
                                <template v-else>
                                    N/A
                                </template>

                            </div>

                            <div class="form-item">
                                <label class="my-2">Received By: </label>
                                <template v-if="deposit_data.received_by_member">
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        v-model="deposit_data.received_by_member.name"
                                    />
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
                        @click="closeViewDepositModal"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
