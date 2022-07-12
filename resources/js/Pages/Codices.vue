<template>
  <h1>Codices</h1>
  <Link href="/codex_new">Add Codex</Link>
  <table>
    <tr>
      <th></th>
      <th>Standard Name</th>
      <th>Shelfmark</th>
      <th>Publication</th>
      <th>Language</th>
      <th>Date</th>
    </tr>
    <tr>
      <td>Search:</td>
      <td><input v-model="search_standard" type="text" /></td>
      <td><input v-model="search_shelf" type="text" /></td>
      <td><input v-model="search_pub" type="text" /></td>
      <td>
        <select v-model="search_lang">
          <option></option>
          <option>Greek</option>
          <option>Latin</option>
        </select>
      </td>
      <td style="white-space: nowrap">
        <input size="3" v-model="search_from" type="text" /> –
        <input size="3" v-model="search_to" type="text" />
      </td>
    </tr>
    <tr>
      <td colspan="6">Search Results: {{ documents.length }}</td>
    </tr>
    <tr v-for="document in documents" :key="document.id">
      <td>
        <Link
          :href="'/codex_show/' + document.id"
          class="text-blue-800 hover:underline"
          >Show </Link
        >
        <Link
          v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
          :href="'/codex_edit/' + document.id"
          class="text-blue-800 hover:underline"
          >Edit
        </Link>
        <Link
          v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
          onclick="return confirm('Are you sure?')"
          :href="'/codex_delete/' + document.id"
          class="text-blue-800 hover:underline"
          method="post"
          >Delete
        </Link>
      </td>

      <td v-text="document.standard_name"></td>
      <td v-text="document.current_shelfmarks"></td>
      <td v-text="document.publication"></td>
      <td v-text="document.language_comment"></td>
      <td v-text="document.start_year + '–' + document.end_year"></td>
    </tr>
  </table>
</template>

<script setup>
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  documents: Array,
  languages: Array,
  auth: Object,
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

let search_standard = ref("");
let search_shelf = ref("");
let search_pub = ref("");
let search_lang = ref("");
let search_from = ref("");
let search_to = ref("");

watch(search_standard, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_lang: search_lang.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});

watch(search_shelf, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_lang: search_lang.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});

watch(search_pub, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_lang: search_lang.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});

watch(search_lang, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_lang: search_lang.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});

watch(search_from, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_lang: search_lang.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});

watch(search_to, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_lang: search_lang.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});
</script>

<style>
td {
  padding: 5px;
}
tr:nth-child(even) {
  background: #eee;
}
tr:nth-child(odd) {
  background: #aaa;
}

table {
  margin-top: 10px;
  overflow: hidden;
  border-radius: 10px;
  border-spacing: 0;
}
</style>
