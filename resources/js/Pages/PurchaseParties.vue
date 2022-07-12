<template>
  <h1>Purchase Parties</h1>
  <Link href="/purchase_party_new" method="post">Add Purchase Party</Link>
  <table>
    <tr>
      <th></th>
      <th>Purchase Party</th>
      <th>Description</th>
      <th>Institution</th>
      <th>Purchases</th>
    </tr>
    <tr v-for="purchase_party in purchase_parties" :key="purchase_party.id">
      <td>
        <Link :href="'/purchase_party_edit/' + purchase_party.id" class="text-blue-800 hover:underline">Edit </Link>
        <Link :href="'/purchase_party_delete/' + purchase_party.id" onclick="return confirm('Are you sure?')" method="post" class="text-blue-800 hover:underline">Delete</Link>
      </td>
      <td>{{ purchase_party.name }}</td>
      <td>{{ purchase_party.description }}</td>
      <td>{{ purchase_party.institution ? "Institution" : "Person" }}</td>
      <td>
        <span v-for="purchase in purchase_party.purchases" :key="purchase.id">
          {{ purchase.name }}<br>
        </span>
      </td>
    </tr>
  </table>
</template>

<script setup>

import { ref, watch } from "vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({ 
  purchase_parties: Array,
  auth: Object,
});

let edit = ref((props.auth == null) ? 0 : (props.auth.user.role.id >=2) ? 1 : 0);

</script>

<style>
td {
  padding: 5px;
}
tr:nth-child(even) {background: #EEE}
tr:nth-child(odd) {background: #AAA}

table {
  margin-top: 10px;
  overflow: hidden;
  border-radius: 10px;
  border-spacing: 0;
}
</style>
