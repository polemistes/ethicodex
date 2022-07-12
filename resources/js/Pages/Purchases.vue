<template>
  <h1>Purchases</h1>
  <Link href="/purchase_new" method="post">Add Purchase</Link>
  <table>
    <tr>
      <th></th>
      <th>Date</th>
      <th>Purchase</th>
      <th>Description</th>
      <th>Codices</th>
      <th>Parties</th>
    </tr>
    <tr v-for="purchase in purchases" :key="purchase.id">
      <td>
        <Link :href="'/purchase_edit/' + purchase.id" class="text-blue-800 hover:underline">Edit </Link>
        <Link :href="'/purchase_delete/' + purchase.id" onclick="return confirm('Are you sure?')" method="post" class="text-blue-800 hover:underline">Delete</Link>
      </td>
      <td>{{ purchase.date }}</td>
      <td>{{ purchase.name }}</td>
      <td>{{ purchase.description }}</td>
      <td>
        <span v-for="document in purchase.documents" :key="document.id">
          {{ document.standard_name }}<br>
        </span>
      </td>
      <td>
        <span v-for="purchase_party in purchase.purchase_parties" :key="purchase_party.id">
          {{ purchase_party.name }}<br>
        </span>
      </td>
    </tr>
  </table>
</template>

<script setup>

import { ref, watch } from "vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({ 
  purchases: Array,
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
