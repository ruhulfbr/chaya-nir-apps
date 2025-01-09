<script setup>
import {computed, onMounted} from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import {useNoteStore} from "./noteStore.js";

const emit = defineEmits(["close", "refreshData"]);

const noteStore = useNoteStore();
const note_data = computed(
    () => noteStore.current_note_item
);

async function submitData() {
    noteStore
        .addNote(
            JSON.parse(
                JSON.stringify(noteStore.current_note_item)
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

async function closeAddNoteModal() {
    noteStore.resetCurrentNoteData();
    emit("close");
}

onMounted(() => {
    noteStore.resetCurrentNoteData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Note</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddNoteModal"/>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Title <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="note_data.title"
                                :class="{
                                    'border-danger': noteStore.add_note_errors.title
                                }"
                                placeholder="Enter title"
                            />
                            <span
                                class="text-danger"
                                v-if="
                                    noteStore.add_note_errors.title
                                "
                            >
                                {{
                                    noteStore.add_note_errors.title
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
                                    'border-danger': noteStore.add_note_errors.description
                                }"
                            ></textarea>

                            <span
                                class="text-danger"
                                v-if="noteStore.add_note_errors.description"
                            >
                                {{ noteStore.add_note_errors.description }}
                            </span>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddNoteModal"
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
