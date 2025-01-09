<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useNoteStore } from "./noteStore.js";

const props = defineProps(["note_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const noteStore = useNoteStore();
const note_data = computed(
    () => noteStore.current_note_item
);

async function fetchData(id) {
    loading.value = true;
    await noteStore.fetchNote(id);
    loading.value = false;
}

async function closeViewNoteModal() {
    noteStore.resetCurrentNoteData();
    emit("close");
}

onMounted(async () => {
    await fetchData(props.note_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Note Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewNoteModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items mb-3" v-if="loading === false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Title</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="note_data.title"
                                />
                            </div>

                            <div class="form-item">
                                <label class="my-2">Description: </label>
                                <textarea
                                    disabled
                                    v-model="note_data.description"
                                    class="form-control"
                                    rows="8"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeViewNoteModal"
                    >
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>
