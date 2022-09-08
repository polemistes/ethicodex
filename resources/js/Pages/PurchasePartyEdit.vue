<template>

  <h1 class="edittransactionpartyheader">Edit Transaction Party: {{ form.name }}</h1>

   <form @submit.prevent="submit" >
    <fieldset class="edittransactionpartycontainer">
      <EthInput input_type="text" input_id="name" v-model="form.name">Transaction Party</EthInput>
      <EthInput input_type="textarea" input_id="description" v-model="form.description">Description</EthInput>

      <p><input id="institution" type="checkbox" :value="form.institution" v-model="form.institution">
      <label for="institution">Institution</label></p>

      <label :for="input_id">Transactions</label>
       <p style="border-style: solid;">
         <b>Selected:</b> 
         <span v-for="purchase in form.purchases" :key="purchase.id">
           {{ purchase.name }}, 
         </span>
       </p>

        <div v-if="all_purchases.length > 12">
          <label :for="search">Search:</label>
          <input type="text" v-model="search">
        </div>      
      
        <div class="scrollwindow">
          <div v-for="choice in search_choices" :key="choice.id">
           <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="form.purchases"
            >
            <label>{{ choice.name }}</label>

            <select v-if="form.purchases.some(e => e.id === choice.id)" v-model="choice.party_role" @change="changerole(choice.id, $event.target.value)">
              <option></option>
              <option v-for="party_role in party_roles" :key="party_role">{{ party_role }}</option>
            </select>
          </div>
        </div>    

<!--
      <EthInput input_type="multi_choice" 
                  input_id="purchases" 
                  :choices="purchases_all"
                  v-model="form.purchases">
                  Transactions
      </EthInput>
-->
      <button @click.prevent="submit">Store All Changes</button>
    </fieldset>
  
  </form>


</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import { reactive, ref, computed } from 'vue'
import EthInput from '../Components/EthInput.vue'

let party_roles = ["Seller", "Buyer", "Broker"];

const props = defineProps({
  purchase_party: Object, 
  purchases: Array,
  purchases_all: Array,
  auth: Object,
  });

const form = reactive({
    name: props.purchase_party.name,
    description: props.purchase_party.description,
    institution: (props.purchase_party.institution == 1) ? true : false,
    purchases: [],   
})

for (let p of props.purchases) {
    form.purchases.push({
    id: p.id,
    year: p.year,
    name: p.name, 
    description: p.description,
    party_role: p.pivot.party_role
    })
}

const all_purchases = reactive([])

for (let p of props.purchases_all) {
  all_purchases.push({
    id: p.id, 
    year: p.year,
    name: p.name, 
    description: p.description, 
    party_role: form.purchases.some(e => e.id === p.id) ? 
                form.purchases.find(item => item.id === p.id).party_role : null
  })
}

let search = ref("")

const search_choices = computed(() => {
  return search.value != "" ? all_purchases.filter(function (el) {
      return (el.name != null) ? el.name.includes(search.value) : null }) : all_purchases
})

function changerole(id, value) {
  const index1 = form.purchases.findIndex(obj => { return obj.id === id})
  const index2 = all_purchases.findIndex(obj => { return obj.id === id})
  form.purchases[index1].party_role = value
  all_purchases[index2].party_role = value
}

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