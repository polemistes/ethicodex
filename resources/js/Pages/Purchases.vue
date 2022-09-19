<template>
  <div class="newtransaction">
    <Link 
      href="/purchase_new" 
      method="post"
      as="button"
      style="padding:0.3em 0.5em;"
    >
      Add Transaction
    </Link>
  </div>

  <div class="transactioncontainer" style="background-color: #ccc;">
    <div class="pur_first"></div>
    <div class="pur_second"><b>Year</b></div>
    <div class="pur_third"><b>Transaction</b></div>
    <div class="pur_fourth"><b>Description</b></div>
    <div class="pur_fifth"><b>Codices</b></div>
    <div class="pur_sixth"><b>Parties</b></div>
  </div>
  <div v-for="purchase in purchases" :key="purchase.id" class="transactioncontainer">
    <div class="pur_first">
      <Link :href="'/purchase_edit/' + purchase.id" as="button" style="margin-right:1em; padding:0.3em 0.5em;">Edit </Link>
      <Link :href="'/purchase_delete/' + purchase.id" as="button" style="padding:0.3em 0.5em;" onclick="return confirm('Are you sure you want to delete this Transaction?')" method="post">Delete</Link>
    </div>
    <div class="pur_second">{{ purchase.year }}</div>
    <div class="pur_third">{{ purchase.name }}</div>
    <div class="pur_fourth">{{ purchase.description }}</div>
    <div class="pur_fifth">
        <span v-for="document in purchase.documents" :key="document.id">
          {{ document.standard_name }}<br>
        </span>
      </div>
      <div class="pur_sixth">
        <span v-for="purchase_party in purchase.purchase_parties" :key="purchase_party.id">
          {{ purchase_party.name }}<br>
        </span>
      </div>
  </div>
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
.newtransaction {
  padding: 1em;
}
.transactioncontainer {
  display: flex;
  border-style: none;
  padding: 1em;
  margin: 0.5em 0em;
  gap: 1em;
  background-color: #eee;
  width: 100%;
}

.pur_first {
  flex: 0 0 8%;
  align-self: center;
  justify-self: end;
}

.pur_second {
  flex: 0 0 5%;
  align-self: center;
  justify-self: end;
}
.pur_third {
  flex: 0 0 20%;
  align-self: center;
  justify-self: end;
}
.pur_fourth {
  flex: 0 0 30%;
  align-self: center;
  justify-self: end;
}
.pur_fifth {
  flex: 0 0 14%;
  align-self: center;
  justify-self: end;
}
.pur_sixth {
  flex: 0 0 15%;
  align-self: center;
  justify-self: end;
}

</style>
