<template>
    <div class="pageline">
        <div class="pageline_search_field">
            <EthInput input_type="text" input_id="search" v-model="search">
                Search
            </EthInput>
        </div>

        <div class="pageline_total_found">
            <div style="padding-bottom: 8px; font-weight: bold">Found</div>
            <div>{{ search_results.length }}</div>
        </div>
    </div>

    <div class="work_container" style="background-color: #ccc">
        <div class="work_first"></div>
        <div class="work_second"><b>Title</b></div>
        <div class="work_third"><b>Alternative Titles</b></div>
        <div class="work_fourth"><b>Author</b></div>
        <div class="work_fifth"><b>Description</b></div>
    </div>
    <div class="work_container">
        <div class="work_first">
            <button class="addbutton" @click="work_submit">Add Work</button>
        </div>
        <div class="work_second">
            <EthInput
                input_type="text"
                input_id="name"
                v-model="form.name"
            ></EthInput>
        </div>
        <div class="work_third">
            <EthInput
                input_type="text"
                input_id="altnames"
                v-model="form.altnames"
            ></EthInput>
        </div>
        <div class="work_fourth">
            <EthInput
                input_type="single_choice_authors"
                input_id="author_id"
                :choices="authors"
                v-model="form.author_id"
            ></EthInput>
        </div>
        <div class="work_fifth">
            <EthInput
                input_type="textarea"
                input_id="description"
                v-model="form.description"
            ></EthInput>
        </div>
    </div>

    <div v-for="work in search_results" :key="work.id">
        <div class="work_container">
            <div class="work_first work_buttons">
                <button
                    v-if="work.id != edit_id"
                    type="button"
                    @click="
                        edit_id = work.id;
                        editwork(work.id);
                    "
                >
                    Edit
                </button>

                <button
                    v-if="work.id == edit_id"
                    type="button"
                    @click="edit_id = null"
                >
                    Cancel
                </button>

                <button
                    v-if="work.id != edit_id"
                    type="button"
                    @click="deletework(work.id)"
                >
                    Delete
                </button>

                <button
                    v-if="work.id == edit_id"
                    type="button"
                    @click="
                        edit_id = null;
                        updatework(work.id);
                    "
                >
                    Update
                </button>
            </div>
            <template v-if="work.id != edit_id">
                <div class="work_second">
                    {{ work.name }}
                </div>
                <div class="work_third">
                    {{ work.altnames }}
                </div>
                <div
                    :title="work.author.altnames ? work.author.altnames : ''"
                    class="work_fourth"
                    v-html="work.author == null ? '' : work.author.name"
                />
                <div class="work_fifth">
                    {{ work.description }}
                </div>
            </template>

            <template v-if="work.id == edit_id">
                <div class="work_second">
                    <EthInput
                        input_type="text"
                        input_id="name"
                        v-model="editform.name"
                    ></EthInput>
                </div>
                <div class="work_third">
                    <EthInput
                        input_type="text"
                        input_id="altnames"
                        v-model="editform.altnames"
                    ></EthInput>
                </div>
                <div class="work_fourth">
                    <EthInput
                        input_type="single_choice_authors"
                        input_id="author_id"
                        :choices="authors"
                        v-model="editform.author_id"
                    ></EthInput>
                </div>
                <div class="work_fifth">
                    <EthInput
                        input_type="textarea"
                        input_id="description"
                        v-model="editform.description"
                    ></EthInput>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import EthInput from "../Components/EthInput.vue";

let search = ref("");
let edit_id = ref(null);

const props = defineProps({
    works: Array,
    authors: Array,
    auth: Object,
});

const form = useForm("NewWork", {
    name: "",
    altnames: "",
    author_id: null,
    description: "",
});

const editform = useForm("EditAuthor", {
    name: "",
    altnames: "",
    author_id: null,
    description: "",
});

const search_results = computed(() => {
    return search.value != ""
        ? props.works.filter(function (el) {
              return (
                  (el.name != null ? el.name.toLowerCase().includes(search.value.toLowerCase()) : null) ||
                  (el.altnames != null
                      ? el.altnames.toLowerCase().includes(search.value.toLowerCase())
                      : null) ||
                  (el.description != null
                      ? el.description.toLowerCase().includes(search.value.toLowerCase())
                      : null) ||
                  (el.author != null
                      ? el.author.name.toLowerCase().includes(search.value.toLowerCase())
                      : null) ||
                  (el.author.altnames != null
                      ? el.author.altnames.toLowerCase().includes(search.value.toLowerCase())
                      : null) 
              );
          })
        : props.works;
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

function work_submit() {
    if(!form.author_id) {
        alert("The Work must have an Author.");
        return;
    }
    form.post("/works", {
        preserveState: false,
        onSuccess: () => {
            form.reset();
        },
    });
}

function editwork(id) {
    let work = props.works.find((x) => x.id === id);
    editform.name = work.name;
    editform.altnames = work.altnames;
    editform.author_id = work.author_id;
    editform.description = work.description;
}

function updatework(id) {
    editform.put("/works/" + id);
}

function deletework(id) {
    if (confirm("Are you sure you want to delete this Work?")) {
        form.delete("/works/" + id);
    }
}
</script>
