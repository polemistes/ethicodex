<template>
  <div class="newparty">
    <Link 
      href="/purchase_party_new" 
      method="post"
      as="button"
      style="padding:0.3em 0.5em;"
    >
      Add Transaction Party
    </Link>
  </div>

  <div class="partycontainer" style="background-color: #ccc;">
    <div class="par_first"></div>
    <div class="par_second"><b>Transaction Party</b></div>
    <div class="par_third"><b>Description</b></div>
    <div class="par_fourth"><b>Institution</b></div>
    <div class="par_fifth"><b>Transactions</b></div>
  </div>
  <div v-for="purchase_party in purchase_parties" :key="purchase_party.id" class="partycontainer">
      <div class="par_first">
        <Link :href="'/purchase_party_edit/' + purchase_party.id" as="button" style="margin-right:1em; padding:0.3em 0.5em;">Edit </Link>
        <Link :href="'/purchase_party_delete/' + purchase_party.id" as="button" style="padding:0.3em 0.5em;" onclick="return confirm('Are you sure you want to delete this Transaction Party?')" method="post">Delete</Link>
      </div>
      <div class="par_second">{{ purchase_party.name }}</div>
      <div class="par_third">{{ purchase_party.description }}</div>
      <div class="par_fourth">{{ purchase_party.institution ? "Institution" : "Person" }}</div>
      <div class="par_fifth">
        <span v-for="purchase in purchase_party.purchases" :key="purchase.id">
          {{ purchase.name }}<br>
        </span>
      </div>
  </div>
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
.newparty {
  padding: 1em;
}
.partycontainer {
  display: flex;
  border-style: none;
  padding: 1em;
  margin: 0.5em 0em;
  gap: 1em;
  background-color: #eee;
  width: 100%;
}

.par_first {
  flex: 0 0 8%;
  align-self: center;
  justify-self: end;
}

.par_second {
  flex: 0 0 15%;
  align-self: center;
  justify-self: end;
}
.par_third {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}
.par_fourth {
  flex: 0 0 15%;
  align-self: center;
  justify-self: end;
}
.par_fifth {
  flex: 0 0 25%;
  align-self: center;
  justify-self: end;
}

</style>
