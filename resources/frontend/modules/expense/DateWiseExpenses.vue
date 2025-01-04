<script setup>
import { ref, onMounted } from "vue";
import Loader from "../../components/shared/loader/Loader.vue";
import axios from "axios";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import ViewExpense from "./ViewExpense.vue";
import { useExpenseStore } from "./expenseStore";

const loading = ref(false);
const expense_data = ref([]);
const showViewExpense = ref(false);

const expenseStore = useExpenseStore();

async function fetchData() {
    loading.value = true;
    await axios
        .get(`/api/expenses-by-date`)
        .then((response) => {
          expense_data.value = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    loading.value = false;
}

function openViewExpenseModal(id) {
    expenseStore.view_expense_id = id;
    showViewExpense.value = true;
}

onMounted(async () => {
    const script = document.createElement('script');
    script.src =
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js';
    script.async = true;
    document.body.appendChild(script);

    await fetchData();
});
</script>

<template>
    <div class="dashboard-page">
        <Loader v-if="loading" />
        <div class="dashboard-page-contents mx-2" v-if="loading === false">
            <div class="row flex-wrap">
                <div
                    class="col-md-4 col-sm-6 my-1 p-1 min150"
                    v-for="(group, index) in expense_data"
                   :key="index"
                >
                    <div class="accordion-item">
                        <h2 class="accordion-header" :id="`panelsStayOpen-heading${index}`">
                            <button class="accordion-button collapsed d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" :data-bs-target="`#panelsStayOpen-collapse${index}`" aria-expanded="false" :aria-controls="`panelsStayOpen-collapse${index}`">
                                <div class="d-flex justify-content-between w-100">
                                    <span class="fw-bold">{{ group.date }}</span>
                                    <span class="fw-bold me-1">{{ group.total_amount }}</span>
                                </div>
                            </button>
                        </h2>
                        <div :id="`panelsStayOpen-collapse${index}`" class="accordion-collapse collapse" :aria-labelledby="`panelsStayOpen-heading${index}`">
                            <div class="accordion-body p-2">
                                <table class="table table-striped table-bordered mb-0">
                                    <tbody>
                                        <tr v-for="(row, rowIndex) in group.rows" :key="rowIndex">
                                            <td>{{ row.title }}</td>
                                            <td>{{ row.amount }}</td>
                                            <td class="text-center">
                                              <ViewSvgIcon
                                                  color="#00CFDD"
                                                  @click="openViewExpenseModal(row.id)"
                                              />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="modals-container">
        <ViewExpense
            v-if="showViewExpense"
            :expense_id="expenseStore.view_expense_id"
            @close="showViewExpense = false"
        />
      </div>
    </div>
</template>