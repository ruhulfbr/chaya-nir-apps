<script setup>
import { computed, onMounted, ref } from "vue";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useNoteStore } from "./noteStore.js";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddIncomeCat from "./AddNote.vue";
import EditIncomeCat from "./EditNote.vue";
import ViewIncomeCat from "./ViewNote.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddNote = ref(false);
const showEditNote = ref(false);
const showViewNote = ref(false);

const confirmStore = useConfirmStore();
const noteStore = useNoteStore();
const notes = computed(() => noteStore.notes);
const q_search = ref("");
const selected_notes = ref([]);
const all_selected = ref(false);
const props = defineProps(["isAuthenticated"]);

function select_all() {
    if (all_selected.value === false) {
        selected_notes.value = [];
        noteStore.notes.forEach((element) => {
            selected_notes.value.push(element.id);
        });
        all_selected.value = true;
    } else {
        all_selected.value = false;
        selected_notes.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({
            message: "Do you want to delete selected note data?",
        })
        .then(async () => {
            if (confirmStore.do_action === true) {
                noteStore.deleteNote(id).then(() => {
                    noteStore.fetchNotes(
                        noteStore.current_page,
                        noteStore.limit,
                        noteStore.q_search,
                    );

                    if (Array.isArray(id)) {
                        all_selected.value = false;
                        selected_notes.value = [];
                    }
                });
            }
        });
}

function openEditNoteModal(id) {
    noteStore.edit_note_id = id;
    showEditNote.value = true;
}

function openViewNoteModal(id) {
    noteStore.view_note_id = id;
    showViewNote.value = true;
}

async function fetchData(
    page = noteStore.current_page,
    limit = noteStore.limit,
    q_search = noteStore.q_search,
) {
    loading.value = true;

    all_selected.value = false;
    selected_notes.value = [];

    try {
        noteStore
            .fetchNotes(page, limit, q_search)
            .then((response) => {
                loading.value = false;
            });
    } catch (error) {
        loading.value = false;
    }
}

function formatDescription(content) {
    if (content.length > 100) {
        return content.substring(0, 100) + "...";
    } else {
        return content;
    }
}

onMounted(async () => {
    await fetchData(1);
});
</script>

<template>
    <div>
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Notes</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="props.isAuthenticated && selected_notes.length > 0"
                    @click="deleteData(selected_notes)"
                />
                <AddNewButton v-if="props.isAuthenticated" @click="showAddNote = true" />
                <FilterButton @click="filterTab = !filterTab" />
            </div>
        </div>
        <div class="p-1 my-2" v-if="filterTab">
            <div class="row">
                <div class="col-md-3 col-sm-6 my-1">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search"
                        v-model="q_search"
                        @keyup="fetchData(1, noteStore.limit, q_search)"
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
                        <th>Title</th>
                        <th>Description</th>
                        <th class="table-action-col" style="width: 20%">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-if="notes.length === 0">
                        <td colspan="4" class="text-center">No data found</td>
                    </tr>

                    <tr
                        v-for="note in notes"
                        :key="note.id"
                    >
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_notes"
                                :value="note.id"
                            />
                        </td>
                        <td class="min150 max150">{{ note.title }}</td>
                        <td class="min150 max150">
                            {{ formatDescription(note.description) }}
                            <ViewSvgIcon
                                v-if="note.description.length > 100"
                                color="#00CFDD"
                                @click="openViewNoteModal(note.id)"
                            />
                        </td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewNoteModal(note.id)"
                            />
                            <EditSvgIcon v-if="props.isAuthenticated"
                                color="#739EF1"
                                @click="openEditNoteModal(note.id)"
                            />
                            <BinSvgIcon v-if="props.isAuthenticated"
                                color="#FF7474"
                                @click="deleteData(note.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading === false && notes.length > 0"
            :total_pages="noteStore.total_pages"
            :current_page="noteStore.current_page"
            :per_page="noteStore.limit"
            @pageChange="
                (currentPage) =>
                    fetchData(currentPage, noteStore.limit)
            "
            @perPageChange="(perPage) => fetchData(1, perPage)"
        />
        <div class="modals-container">
            <AddIncomeCat
                v-if="showAddNote"
                @close="showAddNote = false"
                @refreshData="fetchData(1)"
            />
            <EditIncomeCat
                v-if="showEditNote"
                :note_id="
                    noteStore.edit_note_id
                "
                @close="showEditNote = false"
                @refreshData="fetchData(noteStore.current_page)"
            />
            <ViewIncomeCat
                v-if="showViewNote"
                :note_id="
                    noteStore.view_note_id
                "
                @close="showViewNote = false"
            />
        </div>
    </div>
</template>
