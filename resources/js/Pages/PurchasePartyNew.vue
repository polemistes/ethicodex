<template>

  <h1 class="addtransactionpartyheader">Add Transaction Party</h1>

   <form @submit.prevent="submit" >
    <fieldset class="addtransactionpartycontainer">
      <EthInput input_type="text" input_id="name" v-model="form.name">Transaction Party</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>
      <p><input id="institution" type="checkbox" v-model="form.institution">
      <label>Institution</label></p>

      <EthInput input_type="multi_choice" 
                  input_id="purchases" 
                  :choices="purchases_all"
                  v-model="form.purchases">
                  Transactions
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
  purchases_all: Object,
  auth: Object,
  });

const form = reactive({
    name: '',
    description: '',
    institution: false,
    purchases: [],   
})

function submit() {
      Inertia.post('/purchase_party_store', form)
    }
</script>

<style>
.addtransactionpartyheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;

}

.addtransactionpartycontainer {
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