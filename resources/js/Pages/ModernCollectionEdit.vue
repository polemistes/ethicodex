<template>

  <h1 class="editcollectionheader">Edit Modern Collection: {{ form.name }}</h1>

   <form @submit.prevent="submit">
    <fieldset class="editcollectioncontainer">
      <input type="hidden" input_id="id" v-model="form.id" />
      <EthInput input_type="text" input_id="name" v-model="form.name">Modern Collection</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>

      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;">
        <b>Selected:</b> 
        <span v-for="document in form.documents" :key="document.id">
          {{ document.standard_name }},
        </span>
      </p>
      
      <label :for="search">Search:</label>
      <input id="search" type="text" v-model="search">

      <div class="scrollwindow">
      <div v-for="document in documents_all.data" :key="document.id">
        <input type="checkbox"
              :id="document.id"  
              :value="form.documents"
              v-model="form.documents">
        <label> 
          {{ document.standard_name }} (Tr.ID: {{ document.trismegistos_id }})
          <span v-if="document.modern_collection_id && document.modern_collection_id != modern_collection.id">
            <b> Assigned to {{ modern_collections_all.find(x => x.id === document.modern_collection_id).name }}</b>
          </span>
        </label>
      </div>
      </div>    

      <EthInput input_type="document_choice_taken" 
                  input_id="documents" 
                  :choices="documents_all"
                  :collection_id="modern_collection.id"
                  :collections="modern_collections_all"
                  v-model="form.documents">
                  Codices in Collection
      </EthInput>

      <button @click.prevent="submit">Store All Changes</button>
    </fieldset>
  
  </form>


</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import { reactive, ref, watch } from 'vue'
import EthInput from '../Components/EthInput.vue'

const props = defineProps({ 
  modern_collection: Object,
  modern_collections_all: Array,
  documents: Array,
  documents_all: Object,
  auth: Object,
  });

const form = reactive({
    name: props.modern_collection.name,
    description: props.modern_collection.description,
    documents: props.documents,
})

let search = ref("");

watch(search, (value) => {
  Inertia.get(
    "/modern_collection_edit",
    {
      search_string: search,
    },
    { preserveState: true,
      preserveScroll: true, }
  );
});

function submit() {
      Inertia.post(`/modern_collection_update/${props.modern_collection.id}`, form)
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