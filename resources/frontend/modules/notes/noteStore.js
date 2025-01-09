import axios from "axios";
import { defineStore } from "pinia";
import {handleErrors, handleSuccess} from "../../utils/handle-notification.js";

export const useNoteStore = defineStore("notes", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        limit: 20,
        q_search: "",
        notes: [],

        edit_note_id: null,
        view_note_id: null,

        add_note_errors: {},
        edit_note_errors: {},
        current_note_item: {
            id: "",
            title: "",
            description: ""
        },
    }),

    getters: {},

    actions: {
        resetCurrentNoteData() {
            this.current_note_item = {
                id: "",
                title: "",
                description: ""
            };
            this.add_note_errors = [];
            this.edit_note_errors = [];
        },

        fetchNoteList() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/notes?limit=100`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        fetchNotes(page, limit, q_search = "") {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/notes?page=${page}&limit=${limit}&search=${q_search}`
                    )
                    .then((response) => {
                        this.notes = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.limit = response.data.meta.per_page;
                            this.q_search = q_search;
                        }
                        resolve(this.notes);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        async fetchNote(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/notes/${id}`)
                    .then((response) => {
                        this.current_note_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        handleErrors(errors)
                        reject(errors);
                    });
            });
        },

        async addNote(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/notes`, data)
                    .then((response) => {
                        this.resetCurrentNoteData();
                        handleSuccess("Note Added Successfully");
                        resolve();
                    })
                    .catch((error) => {
                        handleErrors(error, this.add_note_errors)
                        reject(error);
                    });
            });
        },

        async editNote(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(
                        `/api/notes/${this.edit_note_id}`,
                        data
                    )
                    .then((response) => {
                        this.resetCurrentNoteData();
                        handleSuccess("Note updated successfully");
                        resolve(response);
                    })
                    .catch((error) => {
                        handleErrors(error, this.edit_note_errors)
                        reject(error);
                    });
            });
        },

        async deleteNote(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/notes/${id}`)
                    .then((response) => {
                        if (
                            this.notes.length === 1 ||
                            (Array.isArray(id) &&
                                id.length === this.notes.length)
                        ) {
                            this.current_page === 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentNoteData();
                        handleSuccess("Note Data Deleted Successfully");
                        resolve(response);
                    })
                    .catch((error) => {
                        handleErrors(error)
                        reject(error);
                    });
            });
        },
    },
});

