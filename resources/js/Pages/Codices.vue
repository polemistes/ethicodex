<template>
  <div id="topline">
    <Link class="pages" href="/codex_new">Add Codex</Link>  
    <Link class="pages" v-for="link in documents.links" 
      :key="link.label" 
      :href="link.url" 
      v-html="link.label" 
    />
  </div>
  <div class="flex-container">
      <div class="first">Nothing</div>
      <div class="second">Standard Name</div>
      <div class="third">Shelfmark</div>
      <div class="fourth">Publication</div>
      <div class="fifth">Trismegistos ID</div>
      <div class="sixth">Date</div>
  </div>
  <div class="flex-container">
      <div class="first">Search:</div>
      <div class="second"><input class="searchfield" v-model="search_standard" type="text" /></div>
      <div class="third"><input class="searchfield" v-model="search_shelf" type="text" /></div>
      <div class="fourth"><input class="searchfield" v-model="search_pub" type="text" /></div>
      <div class="fifth"><input class="searchfield" v-model="search_tri" type="text" /></div>
      <div class="sixth">
        <span class="searchfield">
          <input size="3" v-model="search_from" type="text" /> –
          <input size="3" v-model="search_to" type="text" />
        </span>
      </div>
  </div>

  <div class="flex-container" v-for="document in documents.data" :key="document.id">
    <div class="first">
      <Link
          :href="'/codex_show/' + document.id"
          class="text-blue-800 hover:underline"
          >Show 
      </Link>
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
    </div>

    <div class="second" v-text="document.standard_name"></div>
    <div class="third" v-text="document.current_shelfmarks"></div>
    <div class="fourth" v-text="document.publication"></div>
    <div class="fifth" v-text="document.trismegistos_id"></div>
    <div class="sixth" v-text="document.start_year + '–' + document.end_year"></div>
  </div>
  
  <Link class="pages" v-for="link in documents.links" 
    :key="link.label" 
    :href="link.url" 
    v-html="link.label" 
  />

</template>

<script setup>
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  documents: Object,
  languages: Array,
  auth: Object,
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

let search_standard = ref("");
let search_shelf = ref("");
let search_pub = ref("");
let search_tri = ref("");
let search_from = ref("");
let search_to = ref("");

watch(search_standard, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_tri: search_tri.value,
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
      search_tri: search_tri.value,
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
      search_tri: search_tri.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});

watch(search_tri, (value) => {
  Inertia.get(
    "/codices",
    {
      search_standard: search_standard.value,
      search_shelf: search_shelf.value,
      search_pub: search_pub.value,
      search_tri: search_tri.value,
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
      search_tri: search_tri.value,
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
      search_tri: search_tri.value,
      search_from: search_from.value,
      search_to: search_to.value,
    },
    { preserveState: true }
  );
});
</script>

<style>
#topline {
  display: flex;
  flex-direction: row;
}

#topline div {
  align-self: center;
  justify-self: center;
}
.pages {
  padding: 1em; 
}

.flex-container {
  display: flex;
  border-style: none;
  border-radius: 0.5em;
  padding: 1em;
  margin: 0.5em;
  gap: 1em;
  background-color: lightgray;
}

.first {
  flex: 0 0 10em;
  align-self: center;
  justify-self: end;
}

.second {
  flex: 0 0 10%;
  align-self: center;
  justify-self: end;
}
.third {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}
.fourth {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}
.fifth {
  flex: 0 0 8%;
  align-self: center;
  justify-self: end;
}
.sixth {
  flex: 0 0 8%;
  align-self: center;
  justify-self: end;
}

.searchfield {
  width: 100%;
}
</style>
