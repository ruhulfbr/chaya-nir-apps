<script setup>
import {computed, onMounted} from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import {useDepositStore} from "./depositStore.js";
import Multiselect from "@vueform/multiselect";

const emit = defineEmits(["close", "refreshData"]);
const props = defineProps(["members"]);

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
                        <CrossSvgIcon @click="closeAddDepositModal"/>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2 mb-1">Member <span class="text-danger">*</span></label>
                            <select
                                class="form-select"
                                v-model="deposit_data.member_id"
                                :class="{
                                    'border-danger': depositStore.add_deposit_errors.member_id
                                }"
                            >
                                <option value="">Select member</option>
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
                                v-if="depositStore.add_deposit_errors.member_id"
                            >
                                {{ depositStore.add_deposit_errors.member_id }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Amount <span class="text-danger">*</span></label>

                            <input
                                type="number"
                                placeholder="Enter amount"
                                class="form-control"
                                v-model="deposit_data.amount"
                                :class="{
                                    'border-danger': depositStore.add_deposit_errors.amount
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.amount"
                            >
                                {{ depositStore.add_deposit_errors.amount }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Deposit Date <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                placeholder="Enter date"
                                class="form-control"
                                v-model="deposit_data.deposit_at"
                                :class="{
                                    'border-danger': depositStore.add_deposit_errors.deposit_at
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.deposit_at"
                            >
                                {{ depositStore.add_deposit_errors.deposit_at }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Comment <span class="text-danger">*</span></label>
                            <textarea
                                v-model="deposit_data.comment"
                                placeholder="Enter coment"
                                class="form-control"
                                rows="5"
                                :class="{
                                    'border-danger': depositStore.add_deposit_errors.comment
                                }"
                            ></textarea>
                            <span
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.comment"
                            >
                                {{ depositStore.add_deposit_errors.comment }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Slip URL</label>
                            <input
                                type="url"
                                placeholder="Enter receipt url"
                                class="form-control"
                                v-model="deposit_data.slip"
                                :class="{
                                    'border-danger': depositStore.add_deposit_errors.slip
                                }"
                            />
                            <span
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.slip"
                            >
                                {{ depositStore.add_deposit_errors.slip }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Received By</label>
                            <select
                                class="form-select"
                                v-model="deposit_data.received_by"
                            >
                                <option value="">Select who receive amount</option>
                                <option
                                    :key="member.id"
                                    :value="member.id"
                                    v-for="member in members"
                                    :class="{
                                        'border-danger': depositStore.add_deposit_errors.received_by
                                    }"
                                >
                                    {{ member.name }}
                                </option>
                            </select>
                            <span
                                class="text-danger"
                                v-if="depositStore.add_deposit_errors.received_by"
                            >
                                {{ depositStore.add_deposit_errors.received_by }}
                            </span>
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
