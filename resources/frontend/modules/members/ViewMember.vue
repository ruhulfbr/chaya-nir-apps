<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useMemberStore } from "./memberStore.js";

const props = defineProps(["member_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const memberStore = useMemberStore();
const member_data = computed(
    () => memberStore.current_member_item
);

async function fetchData(id) {
    loading.value = true;
    await memberStore.fetchMember(id);
    loading.value = false;
}

async function closeViewMemberModal() {
    memberStore.resetCurrentMemberData();
    emit("close");
}

onMounted(async () => {
    await fetchData(props.member_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Member Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewMemberModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items mb-3" v-if="loading === false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Name</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="member_data.name"
                                />
                            </div>

                            <div class="form-item">
                                <label class="my-2">Phone Number</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="member_data.phone"
                                />
                            </div>

                            <div class="form-item">
                                <label class="my-2">Total Deposit</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="member_data.total_deposit_formatted"
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeViewMemberModal"
                    >
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>
