<template>
  <h1>Modern Collections</h1>
  <Link href="/modern_collection_new" method="post">Add Modern Collection</Link>
  <table>
    <tr>
      <th></th>
      <th>Modern Collection</th>
      <th>Description</th>
      <th>Codices</th>
    </tr>
    <tr v-for="modern_collection in modern_collections" :key="modern_collection.id">
      <td>
        <Link :href="'/modern_collection_edit/' + modern_collection.id" class="text-blue-800 hover:underline">Edit </Link>
        <Link :href="'/modern_collection_delete/' + modern_collection.id" onclick="return confirm('Are you sure?')" method="post" class="text-blue-800 hover:underline">Delete</Link>
      </td>
      <td>{{ modern_collection.name }}</td>
      <td>{{ modern_collection.description }}</td>
      <td>
        <span v-for="document in modern_collection.documents" :key="document.id">
          {{ document.standard_name }} ({{ document.trismegistos_id }})<br>
        </span>
      </td>
    </tr>
  </table>
</template>

<script setup>

import { ref, watch } from "vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({ 
  modern_collections: Object,
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
