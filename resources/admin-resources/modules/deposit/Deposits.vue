<script setup>
import { computed, onMounted, ref } from "vue";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useDepositStore } from "./depositStore.js";
import { useMemberStore } from "../members/memberStore.js";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import addDeposit from "./AddDeposit.vue";
import editDeposit from "./EditDeposit.vue";
import ViewDeposit from "./ViewDeposit.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddDeposit = ref(false);
const showEditDeposit = ref(false);
const showViewDeposit = ref(false);

const depositStore = useDepositStore();
const confirmStore = useConfirmStore();
const memberStore = useMemberStore()
const deposits = computed(() => depositStore.deposits);
const members = ref([]);
const q_search = ref("");
const selected_deposits = ref([]);
const all_selected = ref(false);

function select_all() {
    if (all_selected.value === false) {
        selected_deposits.value = [];
        depositStore.deposits.forEach((element) => {
            selected_deposits.value.push(element.id);
        });
        all_selected.value = true;
    } else {
        all_selected.value = false;
        selected_deposits.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected deposit?" })
        .then(async () => {
            if (confirmStore.do_action === true) {
                depositStore.deleteDeposit(id).then(() => {
                    depositStore.fetchDeposits(
                        depositStore.current_page,
                        depositStore.limit,
                        depositStore.q_title
                    );

                    if (Array.isArray(id)) {
                        all_selected.value = false;
                        selected_deposits.value = [];
                    }
                });
            }
        });
}

function openEditDepositModal(id) {
    depositStore.edit_deposit_id = id;
    showEditDeposit.value = true;
}

function openViewDepositModal(id) {
    depositStore.view_deposit_id = id;
    showViewDeposit.value = true;
}

async function fetchData(
    page = depositStore.current_page,
    limit = depositStore.limit,
    q_search = depositStore.q_search
) {
    loading.value = true;

    all_selected.value = false;
    selected_deposits.value = [];

    try {
        depositStore.fetchDeposits(page, limit, q_search).then((response) => {
            loading.value = false;
        });
    } catch (error) {
        // console.log(error);
        loading.value = false;
    }
}

onMounted(async () => {
    await fetchData(1);
    memberStore.fetchMemberList().then((response) => {
        members.value = response;
    });
});
</script>

<template>
    <div>
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Deposit List</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="selected_deposits.length > 0"
                    @click="deleteData(selected_deposits)"
                />
                <AddNewButton @click="showAddDeposit = true" />
                <FilterButton @click="filterTab = !filterTab" />
            </div>
        </div>
        <div class="p-1 my-2" v-if="filterTab">
            <div class="row">
                <div class="col-md-3 col-sm-6 my-1">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="type name.."
                        v-model="q_search"
                        @keyup="fetchData(1, depositStore.limit, q_search)"
                    />
                </div>
                <div class="col-md-3 col-sm-6 my-1">
                    <select
                        class="form-select"
                        v-model="depositStore.q_member"
                        @change="fetchData(1)"
                    >
                        <option value="">select member</option>
                        <option
                            :key="member.id"
                            :value="member.id"
                            v-for="member in members"
                        >
                            {{ member.name }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 my-1">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">From</span>
                        <input
                            type="date"
                            class="form-control"
                            @change="fetchData(1)"
                            v-model="depositStore.q_start_date"
                        />
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">To</span>
                        <input
                            type="date"
                            class="form-control"
                            @change="fetchData(1)"
                            v-model="depositStore.q_end_date"
                        />
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">Amount</span>
                        <input
                            type="number"
                            class="form-control"
                            @input="fetchData(1)"
                            v-model="depositStore.q_amount"
                        />
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 my-1">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">Sort By</span>
                        <select
                            class="form-select"
                            v-model="depositStore.q_sort_column"
                            @change="fetchData(1)"
                        >
                            <option value="id">Default</option>
                            <option value="deposit_at">Deposit Date</option>
                            <option value="amount">Amount</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">order</span>
                        <select
                            class="form-select"
                            v-model="depositStore.q_sort_order"
                            @change="fetchData(1)"
                        >
                            <option value="desc">desc</option>
                            <option value="asc">asc</option>
                        </select>
                    </div>
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
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="deposit in deposits" :key="deposit.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_deposits"
                                :value="deposit.id"
                            />
                        </td>
                        <td class="min150 max150">{{ deposit.member.name }}</td>
                        <td class="min100 max100">{{ deposit.amount }}</td>

                        <td class="min100 max100">{{ deposit.deposit_at }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewDepositModal(deposit.id)"
                            />
                            <EditSvgIcon
                                color="#739EF1"
                                @click="openEditDepositModal(deposit.id)"
                            />
                            <BinSvgIcon
                                color="#FF7474"
                                @click="deleteData(deposit.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading === false && deposits.length > 0"
            :total_pages="depositStore.total_pages"
            :current_page="depositStore.current_page"
            :per_page="depositStore.limit"
            @pageChange="
                (currentPage) => fetchData(currentPage, depositStore.limit)
            "
            @perPageChange="(perPage) => fetchData(1, perPage)"
        />
        <div class="modals-container">
            <addDeposit
                v-if="showAddDeposit"
                :members="members"
                @close="showAddDeposit = false"
                @refreshData="fetchData(1)"
            />
            <editDeposit
                v-if="showEditDeposit"
                :deposit_id="depositStore.edit_deposit_id"
                :members="members"
                @close="showEditDeposit = false"
                @refreshData="fetchData(depositStore.current_page)"
            />
            <ViewDeposit
                v-if="showViewDeposit"
                :deposit_id="depositStore.view_deposit_id"
                @close="showViewDeposit = false"
            />
        </div>
    </div>
</template>
