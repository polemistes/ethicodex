<template>

  <h1 class="edittransactionpartyheader">Edit Transaction Party: {{ form.name }}</h1>

   <form @submit.prevent="submit" >
    <fieldset class="edittransactionpartycontainer">
      <EthInput input_type="text" input_id="name" v-model="form.name">Transaction Party</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>
      <p><input id="institution" type="checkbox" :value="form.institution" v-model="form.institution">
      <label for="institution">Institution</label></p>

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
  purchase_party: Object, 
  purchases: Object,
  purchases_all: Object,
  auth: Object,
  });

const form = reactive({
    name: props.purchase_party.name,
    description: props.purchase_party.description,
    institution: (props.purchase_party.institution == 1) ? true : false,
    purchases: props.purchases,   
})

function submit() {
      Inertia.post(`/purchase_party_update/${props.purchase_party.id}`, form)
    }
</script>

<style>


.edittransactionpartyheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;

}

.edittransactionpartycontainer {
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