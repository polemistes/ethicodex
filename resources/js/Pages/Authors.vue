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

    <div class="author_container" style="background-color: #ccc">
        <div class="author_first"></div>
        <div class="author_second"><b>Name</b></div>
        <div class="author_third"><b>Alternative Names</b></div>
        <div class="author_fourth"><b>Description</b></div>
    </div>
    <div class="author_container">
        <div class="author_first">
            <button class="addbutton" @click="author_submit">Add author</button>
        </div>
        <div class="author_second">
            <EthInput
                input_type="text"
                input_id="name"
                v-model="form.name"
            ></EthInput>
        </div>
        <div class="author_third">
            <EthInput
                input_type="text"
                input_id="altnames"
                v-model="form.altnames"
            ></EthInput>
        </div>
         <div class="author_fourth">
            <EthInput
                input_type="textarea"
                input_id="description"
                v-model="form.description"
            ></EthInput>
        </div>
    </div>

    <div v-for="author in search_results" :key="author.id">
        <div class="author_container">
            <div class="author_first author_buttons">
                <button
                    v-if="author.id != edit_id"
                    type="button"
                    @click="
                        edit_id = author.id;
                        editauthor(author.id);
                    "
                >
                    Edit
                </button>

                <button
                    v-if="author.id == edit_id"
                    type="button"
                    @click="
                        edit_id = null;
                    "
                >
                    Cancel
                </button>

                <button
                    v-if="author.id != edit_id"
                    type="button"
                    @click="deleteauthor(author.id)"
                >
                    Delete
                </button>

                <button
                    v-if="author.id == edit_id"
                    type="button"
                    @click="edit_id = null; updateauthor(author.id)"
                >
                    Update
                </button>
            </div>
            <template v-if="author.id != edit_id">
                <div class="author_second">
                    {{ author.name }}
                </div>
                <div class="author_third">
                    {{ author.altnames }}
                </div>
                  <div class="author_fourth">
                    {{ author.description }}
                </div>
            </template>

            <template v-if="author.id == edit_id">
                <div class="author_second">
                    <EthInput
                        input_type="text"
                        input_id="name"
                        v-model="editform.name"
                    ></EthInput>
                </div>
                <div class="author_third">
                    <EthInput
                        input_type="text"
                        input_id="altnames"
                        v-model="editform.altnames"
                    ></EthInput>
                </div>
                <div class="author_fourth">
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
    authors: Array,
    auth: Object,
});

const form = useForm("NewAuthor", {
    name: "",
    altnames: "",
    description: "",
});

const editform = useForm("EditAuthor", {
    name: "",
    altnames: "",
    description: "",
});

const search_results = computed(() => {
    return search.value != ""
        ? props.authors.filter(function (el) {
              return (
                  (el.name != null ? el.name.includes(search.value) : null) ||
                  (el.altnames != null ? el.altnames.includes(search.value) : null) ||
                  (el.description != null
                      ? el.description.includes(search.value)
                      : null)
              );
          })
        : props.authors;
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

function author_submit() {
    form.post("/authors", {
        onSuccess: () => {
            form.reset();
        },
    });
}

function editauthor(id) {
    let author = props.authors.find((x) => x.id === id);
    editform.name = author.name;
    editform.altnames = author.altnames;
    editform.description = author.description;
}

function updateauthor(id) {
    editform.put("/authors/" + id);
}

function deleteauthor(id) {
    if (confirm("Are you sure you want to delete this author?")) {
        form.delete("/authors/" + id);
    }
}
</script>
