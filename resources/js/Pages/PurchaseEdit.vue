<template>

  <h1 class="edittransactionheader">Edit transaction: {{ form.name }}</h1>

   <form @submit.prevent="submit">
    <fieldset class="edittransactioncontainer">
      <input type="hidden" input_id="id" v-model="form.id" />
      <EthInput input_type="year" input_id="date" v-model="form.date">Year</EthInput>
      <EthInput input_type="text" input_id="name" v-model="form.name">Purchase</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>

  
        <EthInput input_type="document_choice" 
                  input_id="documents" 
                  :choices="documents_all"
                  v-model="form.documents">
                  Documents in Purchase
        </EthInput>
 

        <EthInput input_type="multi_choice" 
                  input_id="purchase_parties" 
                  :choices="purchase_parties_all"
                  v-model="form.purchase_parties">
                  Parties to the Purchase
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
  purchase: Object,
  documents: Object,
  documents_all: Object,
  purchase_parties: Object,
  purchase_parties_all: Object,
  auth: Object,
  });

const form = reactive({
    date: props.purchase.date,
    name: props.purchase.name,
    description: props.purchase.description,
    documents: props.documents,
    purchase_parties: props.purchase_parties,   
})

function submit() {
      Inertia.post(`/purchase_update/${props.purchase.id}`, form)
    }
</script>

<style>

.edittransactionheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;

}

.edittransactioncontainer {
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