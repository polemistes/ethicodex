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
      <div class="first">
        Search:
      </div>

      <div class="second">
        <input
          v-model="search_standard"
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>

      <div class="third">
        <input
          v-model="search_shelf" 
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>
      
      <div class="fourth">
        <input 
          v-model="search_pub"
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>

      <div class="fifth">
        <input
          v-model="search_tri"
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>

      <div class="sixth">
        <span class="searchfield">
          <input
            v-model="search_from"
            @keyup="sendsearch()" 
            size="3" 
            type="text" 
          /> –
          <input 
            v-model="search_to"
            @keyup="sendsearch()" 
            size="3" 
            type="text" 
          />
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
import { ref, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  documents: Object,
  search_standard: String,
  search_shelf: String,
  search_pub: String,
  search_tri: String,
  search_from: String,
  search_to: String,
  auth: Object,
});

  let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

  let search_standard = ref(props.search_standard);
  let search_shelf = ref(props.search_shelf);
  let search_pub = ref(props.search_pub);
  let search_tri = ref(props.search_tri);
  let search_from = ref(props.search_from);
  let search_to = ref(props.search_to);

function sendsearch() {
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
    { preserveState: true,
      preserveScroll: true, }
  );  
}

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
