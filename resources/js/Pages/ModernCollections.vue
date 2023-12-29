<template>
  <div class="newcollection">
    <Link 
      href="/modern_collection_new" 
      method="post"
      as="button"
      style="padding:0.3em 0.5em;"
    >
      Add Modern Collection
    </Link>
  </div>

  <div class="collectioncontainer" style="background-color: #ccc;">
    <div class="coll_first"></div>
    <div class="coll_second"><b>Modern Collection</b></div>
    <div class="coll_third"><b>Description</b></div>
    <div class="coll_fourth"><b>Codices</b></div>
  </div>

  <div v-for="modern_collection in modern_collections" :key="modern_collection.id" class="collectioncontainer">
    <div class="coll_first">
      <Link :href="'/modern_collection_edit/' + modern_collection.id" as="button" style="margin-right:1em; padding:0.3em 0.5em;">Edit </Link>
      <Link :href="'/modern_collection_delete/' + modern_collection.id" as="button" style="padding:0.3em 0.5em;" onclick="return confirm('Are you sure you want to delete this Modern Collection?')" method="post" class="text-blue-800 hover:underline">Delete</Link>
    </div>
    <div class="coll_second">{{ modern_collection.name }}</div>
    <div class="coll_third">{{ modern_collection.description }}</div>
    <div class="coll_fourth">
      <span v-for="document in modern_collection.documents" :key="document.id">
        {{ document.standard_name }} ({{ document.trismegistos_id }})<br>
      </span>
    </div>
  </div>
</template>

<script setup>

import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3'

const props = defineProps({ 
  modern_collections: Object,
  auth: Object,
});

let edit = ref((props.auth == null) ? 0 : (props.auth.user.role.id >=2) ? 1 : 0);

</script>

<style>
.newcollection {
  padding: 1em;
}

.collectioncontainer {
  display: flex;
  border-style: none;
  padding: 1em;
  margin: 0.5em 0em;
  gap: 1em;
  background-color: #eee;
  width: 100%;
}

.coll_first {
  flex: 0 0 8%;
  align-self: center;
  justify-self: end;
}

.coll_second {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}
.coll_third {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}
.coll_fourth {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}

</style>
