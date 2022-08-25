<template>
  
  <h1 class="addcollectionheader">Add Modern Collection</h1>

   <form @submit.prevent="submit" >
    <fieldset class="addcollectioncontainer">
      <EthInput input_type="text" input_id="name" v-model="form.name">Modern Collection</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>

      <EthInput input_type="document_choice_taken" 
                  input_id="documents" 
                  :choices="documents_all"
                  collection_id="0"
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
import { reactive, ref } from 'vue'
import EthInput from '../Components/EthInput.vue'

const props = defineProps({
  modern_collections_all: Array,
  documents_all: Array,
  auth: Object,
  });

const form = reactive({
    name: '',
    description: '',
    documents: [],
})

function submit() {
      Inertia.post(`/modern_collection_store`, form)
    }
</script>

<style>
.scrollwindow {
  max-height: 180px;
  overflow: auto;
}

.addcollectionheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;

}

.addcollectioncontainer {
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