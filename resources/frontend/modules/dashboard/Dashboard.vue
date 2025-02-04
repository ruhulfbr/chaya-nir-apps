<script setup>
import { ref, onMounted } from "vue";
import Loader from "../../components/shared/loader/Loader.vue";
import axios from "axios";
import WalletSvgIcon from "../../assets/icons/wallet-svg-icon.vue";
import CoinSvgIcon from "../../assets/icons/coin-1-svg-icon.vue";
import twentyfourSVGICon from "../../assets/icons/twentyfour-svg-icon.vue";
import HandLoveSVGICon from "../../assets/icons/hand-love-svg-icon.vue";

const loading = ref(false);
const report_data = ref({});
const member_deposits = ref([]);
const category_expenses = ref([]);
const floors = ref([7, 8, 9, 10]);
const selectedFloor = ref("All");

async function fetchData(stairNo=false) {
    loading.value = true;
    let url = `/api/dashboard-reports`
    if (stairNo){
        url +=`?stair_no=${stairNo}`
    }
    await axios
        .get(url)
        .then((response) => {
            report_data.value = response.data;
            member_deposits.value = response.data.member_deposits
            category_expenses.value = response.data.category_expenses
        })
        .catch((errors) => {
            console.log(errors);
        });
    loading.value = false;
}

onMounted(async () => {
    await fetchData();
});
</script>

<template>
    <div class="dashboard-page">
        <Loader v-if="loading" />
        <div class="dashboard-page-contents mx-2" v-if="loading === false">

            <div class="dashboard-top-stats row flex-wrap">
                <div class="row col-md-6 my-1 p-1">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <!-- 'All' Button -->
                        <input
                            type="radio"
                            class="btn-check"
                            name="stair_no"
                            id="stair_noAll"
                            value="All"
                            v-model="selectedFloor"
                            checked
                            @change="fetchData()"
                        >
                        <label class="btn btn-outline-primary" for="stair_noAll">All</label>

                        <!-- Loop through floors -->
                        <template v-for="floor in floors" :key="floor">
                            <input
                                type="radio"
                                class="btn-check"
                                name="stair_no"
                                :id="'stair_no' + floor"
                                :value="floor"
                                v-model="selectedFloor"
                                @change="fetchData(floor)"
                            >
                            <label class="btn btn-outline-primary" :for="'stair_no' + floor">
                                {{ floor }}th Floor
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <div class="dashboard-top-stats row flex-wrap">

                <div class="row">

                </div>

                <!-- Total Income -->
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <WalletSvgIcon
                                color="white"
                                width="28"
                                height="28"
                            />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                report_data.total_deposits
                            }}</span>
                            <br />
                            <span>Total Deposits</span>
                        </div>
                    </div>
                </div>
                <!-- Total Expenses -->
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <CoinSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                report_data.total_expenses
                            }}</span>
                            <br />
                            <span>Total Expenses</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-charts my-3 row">
                <div class="col-md-6 p-1">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Category wise Expenses
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <!-- Check if the items array is empty -->
                                <li v-if="category_expenses.length === 0" class="list-group-item text-center">
                                    Data not found
                                </li>

                                <!-- Render the list items if data exists -->
                                <li
                                    v-for="(item, index) in category_expenses"
                                    :key="index"
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                >
                                    {{ item.category_name }}
                                    <span class="badge bg-primary rounded-pill">{{ item.total_amount }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 p-1">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Deposits From Members
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <!-- Check if the items array is empty -->
                                <li v-if="member_deposits.length === 0" class="list-group-item text-center">
                                    Data not found
                                </li>

                                <!-- Render the list items if data exists -->
                                <li
                                    v-for="(item, index) in member_deposits"
                                    :key="index"
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                >
                                    {{ item.member_name }}
                                    <span class="badge bg-primary rounded-pill">{{ item.total_amount }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
