<script setup>
import {computed, onMounted} from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import {useMemberStore} from "./memberStore.js";

const emit = defineEmits(["close", "refreshData"]);

const memberStore = useMemberStore();
const member_data = computed(
    () => memberStore.current_member_item
);

async function submitData() {
    memberStore
        .addMember(
            JSON.parse(
                JSON.stringify(memberStore.current_member_item)
            )
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddMemberModal() {
    memberStore.resetCurrentMemberData();
    emit("close");
}

onMounted(() => {
    memberStore.resetCurrentMemberData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Member</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddMemberModal"/>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="member_data.name"
                                :class="{
                                    'border-danger': memberStore.add_member_errors.name
                                }"
                                placeholder="Enter name"
                            />
                            <span
                                class="text-danger"
                                v-if="
                                    memberStore.add_member_errors.name
                                "
                            >
                                {{
                                    memberStore.add_member_errors.name
                                }}
                            </span>
                        </div>

                        <div class="form-item">
                            <label class="my-2">Phone Number <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="member_data.phone"
                                :class="{
                                    'border-danger': memberStore.add_member_errors.phone
                                }"
                                placeholder="Enter phone number"
                            />
                            <span
                                class="text-danger"
                                v-if="
                                    memberStore.add_member_errors.phone
                                "
                            >
                                {{
                                    memberStore.add_member_errors.phone
                                }}
                            </span>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddMemberModal"
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
