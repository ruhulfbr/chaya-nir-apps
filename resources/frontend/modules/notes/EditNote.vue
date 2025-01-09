<script setup>
import {ref, computed, onMounted} from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import {useNoteStore} from "./noteStore.js";

const props = defineProps(["note_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const noteStore = useNoteStore();
const note_data = computed(
    () => noteStore.current_note_item
);

async function submitData() {
    noteStore
        .editNote(
            JSON.parse(
                JSON.stringify(noteStore.current_note_item)
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
    await noteStore.fetchNote(id);
    loading.value = false;
}

async function closeEditNoteModal() {
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
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditNoteModal"/>
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading"/>
                    <div class="form-items" v-if="loading === false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Title <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="note_data.title"
                                    :class="{
                                    'border-danger': noteStore.edit_note_errors.title
                                }"
                                    placeholder="Enter title"
                                />
                                <span
                                    class="text-danger"
                                    v-if="
                                    noteStore.edit_note_errors.title
                                "
                                >
                                {{
                                        noteStore.edit_note_errors.title
                                    }}
                            </span>
                            </div>

                            <div class="form-item">
                                <label class="my-2">Description <span class="text-danger">*</span></label>
                                <textarea
                                    v-model="note_data.description"
                                    placeholder="Enter description"
                                    class="form-control"
                                    rows="5"
                                    :class="{
                                    'border-danger': noteStore.edit_note_errors.description
                                }"
                                ></textarea>

                                <span
                                    class="text-danger"
                                    v-if="noteStore.edit_note_errors.description"
                                >
                                {{ noteStore.edit_note_errors.description }}
                            </span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditNoteModal"
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
