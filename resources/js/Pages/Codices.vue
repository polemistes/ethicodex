<template>
  <div class="pageline">
    <Link class="pages" href="/codex_new">Add Codex</Link> 
    <div class="pagecontainer"> 
      <Link class="pages" v-for="link in documents.links" 
        :key="link.label" 
        :href="link.url"
        v-html="link.label" 
      />
    </div>
  </div>
  <div class="flex-container" style="background-color: #ccc;">
      <div class="cod_first"></div>
      <div class="cod_second"><b>Standard Name</b></div>
      <div class="cod_third"><b>Shelfmark</b></div>
      <div class="cod_fourth"><b>Publication</b></div>
      <div class="cod_fifth"><b>Trismegistos ID</b></div>
      <div class="cod_sixth"><b>Date</b></div>
  </div>
  <div class="flex-container" style="background-color: #bbb;">
      <div class="cod_first">
        Search:
      </div>

      <div class="cod_second">
        <input
          v-model="search_standard"
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>

      <div class="cod_third">
        <input
          v-model="search_shelf" 
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>
      
      <div class="cod_fourth">
        <input 
          v-model="search_pub"
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>

      <div class="cod_fifth">
        <input
          v-model="search_tri"
          @keyup="sendsearch()" 
          class="searchfield" 
          type="text" 
        />
      </div>

      <div class="cod_sixth">
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
    <div class="cod_first">
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

    <div class="cod_second" v-text="document.standard_name"></div>
    <div class="cod_third" v-text="document.current_shelfmarks"></div>
    <div class="cod_fourth" v-text="document.publication"></div>
    <div class="cod_fifth" v-text="document.trismegistos_id"></div>
    <div class="cod_sixth" v-text="document.start_year + '–' + document.end_year"></div>
  </div>
  <div class="pageline"> 
    <div class="pagecontainer">
      <Link class="pages" v-for="link in documents.links" 
       :key="link.label" 
       :href="link.url" 
       v-html="link.label" 
      />
    </div>
  </div>
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
.pageline {
  display: flex;
  flex-direction: row;
}

.pageline div {
  align-self: center;
}
.pages {
  padding: 1em;
}
.pagecontainer {
  margin-left: auto;
}
.flex-container {
  display: flex;
  border-style: none;
  padding: 1em;
  margin: 0.5em 0em;
  gap: 1em;
  background-color: #eee;
  width: 100%;
}

.cod_first {
  flex: 0 0 10%;
  align-self: center;
  justify-self: end;
}

.cod_second {
  flex: 0 0 12%;
  align-self: center;
  justify-self: end;
}
.cod_third {
  flex: 0 0 26%;
  align-self: center;
  justify-self: end;
}
.cod_fourth {
  flex: 0 0 26%;
  align-self: center;
  justify-self: end;
}
.cod_fifth {
  flex: 0 0 10%;
  align-self: center;
  justify-self: end;
}
.cod_sixth {
  flex: 0 0 10%;
  align-self: center;
  justify-self: end;
}

.searchfield {
  width: 100%;
}
</style>
