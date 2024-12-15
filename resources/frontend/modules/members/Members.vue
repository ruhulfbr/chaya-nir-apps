<script setup>
import { computed, onMounted, ref } from "vue";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useMemberStore } from "./memberStore.js";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddIncomeCat from "./AddMember.vue";
import EditIncomeCat from "./EditMember.vue";
import ViewIncomeCat from "./ViewMember.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddMember = ref(false);
const showEditMember = ref(false);
const showViewMember = ref(false);

const confirmStore = useConfirmStore();
const memberStore = useMemberStore();
const members = computed(() => memberStore.members);
const q_name = ref("");
const q_phone = ref("");
const selected_members = ref([]);
const all_selected = ref(false);
const props = defineProps(["isAuthenticated"]);

function select_all() {
    if (all_selected.value === false) {
        selected_members.value = [];
        memberStore.members.forEach((element) => {
            selected_members.value.push(element.id);
        });
        all_selected.value = true;
    } else {
        all_selected.value = false;
        selected_members.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({
            message: "Do you want to delete selected member data?",
        })
        .then(async () => {
            if (confirmStore.do_action === true) {
                memberStore.deleteMember(id).then(() => {
                    memberStore.fetchMembers(
                        memberStore.current_page,
                        memberStore.limit,
                        memberStore.q_name,
                        memberStore.q_phone
                    );

                    if (Array.isArray(id)) {
                        all_selected.value = false;
                        selected_members.value = [];
                    }
                });
            }
        });
}

function openEditMemberModal(id) {
    memberStore.edit_member_id = id;
    showEditMember.value = true;
}

function openViewMemberModal(id) {
    memberStore.view_member_id = id;
    showViewMember.value = true;
}

async function fetchData(
    page = memberStore.current_page,
    limit = memberStore.limit,
    q_name = memberStore.q_name,
    q_phone = memberStore.q_phone,
) {
    loading.value = true;

    all_selected.value = false;
    selected_members.value = [];

    try {
        memberStore
            .fetchMembers(page, limit, q_name, q_phone)
            .then((response) => {
                loading.value = false;
            });
    } catch (error) {
        loading.value = false;
    }
}

onMounted(async () => {
    await fetchData(1);
});
</script>

<template>
    <div>
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Members</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="props.isAuthenticated && selected_members.length > 0"
                    @click="deleteData(selected_members)"
                />
                <AddNewButton v-if="props.isAuthenticated" @click="showAddMember = true" />
                <FilterButton @click="filterTab = !filterTab" />
            </div>
        </div>
        <div class="p-1 my-2" v-if="filterTab">
            <div class="row">
                <div class="col-md-3 col-sm-6 my-1">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter name"
                        v-model="q_name"
                        @keyup="fetchData(1, memberStore.limit, q_name, q_phone)"
                    />
                </div>

                <div class="col-md-3 col-sm-6 my-1">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter phone"
                        v-model="q_phone"
                        @keyup="fetchData(1, memberStore.limit, q_name, q_phone)"
                    />
                </div>

            </div>
        </div>

        <Loader v-if="loading" />
        <div
            class="table-responsive bg-white shadow-sm"
            v-if="loading === false"
        >
            <table class="table mb-0 table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                @click="select_all"
                                v-model="all_selected"
                            />
                        </th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Total Deposit</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-if="members.length === 0">
                        <td colspan="6" class="text-center">No data found</td>
                    </tr>

                    <tr
                        v-for="member in members"
                        :key="member.id"
                    >
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_members"
                                :value="member.id"
                            />
                        </td>
                        <td class="min150 max150">{{ member.name }}</td>
                        <td class="min150 max150">{{ member.phone }}</td>
                        <td class="min150 max150">{{ member.total_deposit_formatted }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewMemberModal(member.id)"
                            />
                            <EditSvgIcon v-if="props.isAuthenticated"
                                color="#739EF1"
                                @click="openEditMemberModal(member.id)"
                            />
                            <BinSvgIcon v-if="props.isAuthenticated"
                                color="#FF7474"
                                @click="deleteData(member.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading === false && members.length > 0"
            :total_pages="memberStore.total_pages"
            :current_page="memberStore.current_page"
            :per_page="memberStore.limit"
            @pageChange="
                (currentPage) =>
                    fetchData(currentPage, memberStore.limit)
            "
            @perPageChange="(perPage) => fetchData(1, perPage)"
        />
        <div class="modals-container">
            <AddIncomeCat
                v-if="showAddMember"
                @close="showAddMember = false"
                @refreshData="fetchData(1)"
            />
            <EditIncomeCat
                v-if="showEditMember"
                :member_id="
                    memberStore.edit_member_id
                "
                @close="showEditMember = false"
                @refreshData="fetchData(memberStore.current_page)"
            />
            <ViewIncomeCat
                v-if="showViewMember"
                :member_id="
                    memberStore.view_member_id
                "
                @close="showViewMember = false"
            />
        </div>
    </div>
</template>
