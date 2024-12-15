<script setup>
import {ref, computed, onMounted} from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import {useMemberStore} from "./memberStore.js";

const props = defineProps(["member_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const memberStore = useMemberStore();
const member_data = computed(
    () => memberStore.current_member_item
);

async function submitData() {
    memberStore
        .editMember(
            JSON.parse(
                JSON.stringify(memberStore.current_member_item)
            )
        )
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
    await memberStore.fetchMember(id);
    loading.value = false;
}

async function closeEditMemberModal() {
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
                    <h5 class="modal-title">Edit Member</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditMemberModal"/>
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading"/>
                    <div class="form-items" v-if="loading === false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Name <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="member_data.name"
                                    :class="{
                                        'border-danger': memberStore.edit_member_errors.name
                                    }"
                                    placeholder="Enter name"
                                />
                                <span
                                    class="text-danger"
                                    v-if="memberStore.edit_member_errors.name"
                                >
                                    {{
                                        memberStore
                                            .edit_member_errors.name
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
                                        'border-danger': memberStore.edit_member_errors.phone
                                    }"
                                    placeholder="Enter phone number"
                                />
                                <span
                                    class="text-danger"
                                    v-if="memberStore.edit_member_errors.phone"
                                >
                                    {{
                                        memberStore
                                            .edit_member_errors.phone
                                    }}
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditMemberModal"
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
