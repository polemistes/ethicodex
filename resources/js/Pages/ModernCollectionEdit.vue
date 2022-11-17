<template>
    <h1 class="editcollectionheader">
        Edit Modern Collection: {{ form.name }}
    </h1>

    <form @submit.prevent="submit">
        <fieldset class="editcollectioncontainer">
            <input type="hidden" input_id="id" v-model="form.id" />
            <EthInput input_type="text" input_id="name" v-model="form.name"
                >Modern Collection</EthInput
            >
            <EthInput
                input_type="textarea"
                input_id="description"
                v-model="form.description"
                >Description</EthInput
            >

            <label :for="documents">Codices in Collection</label>
            <p style="border-style: solid">
                <b>Selected:</b>
                <span v-for="document in form.documents" :key="document.id"
                    >{{ document.standard_name }},</span
                >
            </p>

            <div v-if="documents_all.length > 20">
                <label :for="search">Search:</label>
                <input type="text" v-model="search" />
            </div>

            <div class="scrollwindow">
                <div v-for="document in search_documents" :key="document.id">
                    <input
                        type="checkbox"
                        :id="document.id"
                        :value="document"
                        v-model="form.documents"
                    />
                    <label>
                        {{ document.standard_name }} (Tr.ID:
                        {{ document.trismegistos_id }})</label
                    >
                </div>
            </div>

            <button @click.prevent="submit">Store All Changes</button>
        </fieldset>
    </form>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, watch, computed } from "vue";
import EthInput from "../Components/EthInput.vue";

const props = defineProps({
    modern_collection: Object,
    documents: Array,
    documents_all: Array,
    auth: Object,
});

const form = reactive({
    name: props.modern_collection.name,
    description: props.modern_collection.description,
    documents: props.documents,
});

let submitted = false;
let search = ref("");

/*watch(search, (value) => {
  Inertia.get(
    "/modern_collection_edit",
    {
      search_string: search,
    },
    { preserveState: true,
      preserveScroll: true, }
  );
});
*/

const search_documents = computed(() => {
    return search.value != ""
        ? props.documents_all.filter(function (el) {
              return el.standard_name != null
                  ? el.standard_name.includes(search.value)
                  : null || el.trismegistos_id != null
                  ? el.trismegistos_id.toString().includes(search.value)
                  : null;
          })
        : props.documents_all;
});

let removeStartEventListener = Inertia.on("before", (event) => {
    if (form.isDirty && !submitted) {
        if (
            confirm(
                "You have unsaved data in your form. Are you sure you want to leave the page?"
            )
        ) {
            removeStartEventListener();
            window.onbeforeunload = null;
        } else if (submitted) {
            removeStartEventListener();
            window.onbeforeunload = null;
        } else {
            return false;
        }
    }
});

window.onbeforeunload = (s) => (form.isDirty ? "" : null);

function submit() {
    submitted = true;
    Inertia.post(
        `/modern_collection_update/${props.modern_collection.id}`,
        form
    );
}
</script>

<style>
.scrollwindow {
    max-height: 180px;
    overflow: auto;
}
.editcollectionheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;
}

.editcollectioncontainer {
    display: flex;
    background-color: #eee;
    margin-top: 4px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    flex-direction: column;
    width: 100%;
}
</style>
