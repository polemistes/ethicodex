<template>

  <h1 class="edittransactionheader">Edit transaction: {{ form.name }}</h1>

   <form @submit.prevent="submit">
    <fieldset class="edittransactioncontainer">
      <input type="hidden" input_id="id" v-model="form.id" />
      <EthInput input_type="year" input_id="date" v-model="form.date">Year</EthInput>
      <EthInput input_type="text" input_id="name" v-model="form.name">Transaction</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>

  
        <EthInput input_type="document_choice" 
                  input_id="documents" 
                  :choices="documents_all"
                  v-model="form.documents">
                  Documents in Transaction
        </EthInput>
 
        <label :for="input_id">Parties to the Transaction</label>
        <p style="border-style: solid;">
          <b>Selected:</b> 
          <span v-for="party in form.purchase_parties" :key="party.id">
            {{ party.name }}, 
          </span>
        </p>

        <div v-if="all_purchase_parties.length > 12">
          <label :for="search">Search:</label>
          <input type="text" v-model="search">
        </div>      
      
        <div class="scrollwindow">
          <div v-for="choice in search_choices" :key="choice.id">
           <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="form.purchase_parties"
            >
            <label>{{ choice.name }}</label>

            <select v-if="form.purchase_parties.some(e => e.id === choice.id)" v-model="choice.party_role" @change="changerole(choice.id, $event.target.value)">
              <option></option>
              <option v-for="party_role in party_roles" :key="party_role">{{ party_role }}</option>
            </select>
          </div>
        </div>    
<!--
        <EthInput input_type="party_choice" 
                  input_id="purchase_parties" 
                  :choices="purchase_parties_all"
                  :roles="party_roles"
                  v-model="form.purchase_parties">
                  Parties to the Transaction
        </EthInput>
-->

      <button @click.prevent="submit">Store All Changes</button>
    </fieldset>
  
  </form>


</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import { reactive, ref, computed, onMounted } from 'vue'
import EthInput from '../Components/EthInput.vue'

let party_roles = ["Seller", "Buyer", "Broker"];

const props = defineProps({ 
  purchase: Object,
  documents: Array,
  documents_all: Array,
  purchase_parties: Array,
  purchase_parties_all: Array,
  auth: Object,
  });


const form = reactive({
    date: props.purchase.date,
    name: props.purchase.name,
    description: props.purchase.description,
    documents: props.documents,
    purchase_parties: [],
})

for (let p of props.purchase_parties) {
    form.purchase_parties.push({
    id: p.id, 
    name: p.name, 
    description: p.description, 
    institution: p.institution, 
    party_role: p.pivot.party_role
    })
}

const all_purchase_parties = reactive([])

for (let p of props.purchase_parties_all) {
  all_purchase_parties.push({
    id: p.id, 
    name: p.name, 
    description: p.description, 
    institution: p.institution, 
    party_role: form.purchase_parties.some(e => e.id === p.id) ? 
                form.purchase_parties.find(item => item.id === p.id).party_role : null
  })
}

let search = ref("")

const search_choices = computed(() => {
  return search.value != "" ? all_purchase_parties.filter(function (el) {
      return (el.name != null) ? el.name.includes(search.value) : null }) : all_purchase_parties
})

function changerole(id, value) {
  const index1 = form.purchase_parties.findIndex(obj => { return obj.id === id})
  const index2 = props.purchase_parties_all.findIndex(obj => { return obj.id === id})
  form.purchase_parties[index1].party_role = value
  props.purchase_parties_all[index2].party_role = value
}

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