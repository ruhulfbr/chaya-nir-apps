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

async function fetchData() {
    loading.value = true;
    await axios
        .get(`/api/dashboard-reports`)
        .then((response) => {
            report_data.value = response.data;
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
                <!-- Current Month Income  -->
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <twentyfourSVGICon
                                color="white"
                                width="28"
                                height="28"
                            />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                report_data.today_deposits
                            }}</span>
                            <br />
                            <span class="text-muted fs-6">Today Deposit</span>
                        </div>
                    </div>
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
                <!-- Net Income -->
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <HandLoveSVGICon
                                color="white"
                                width="28"
                                height="28"
                            />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                report_data.balance
                            }}</span>
                            <br />
                            <span>Balance</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
