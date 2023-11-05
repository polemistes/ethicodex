<template>
    <div class="pageline">
        <Link class="addbutton" href="/purchase_new" method="post" as="button">
            Add Transaction
        </Link>
    </div>

    <div class="transactioncontainer" style="background-color: #ccc">
        <div class="pur_first"></div>
        <div class="pur_second"><b>Date</b></div>
        <div class="pur_third"><b>Transaction</b></div>
        <div class="pur_fourth"><b>Description</b></div>
        <div class="pur_fifth"><b>Codices</b></div>
        <div class="pur_sixth"><b>Parties</b></div>
    </div>
    <div
        v-for="purchase in purchases"
        :key="purchase.id"
        class="transactioncontainer"
    >
        <div class="pur_first cod_buttons">
            <Link
                :href="'/purchase_edit/' + purchase.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                >Edit
            </Link>
            <Link
                :href="'/purchase_delete/' + purchase.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                onclick="return confirm('Are you sure you want to delete this Transaction?')"
                method="post"
                >Delete</Link
            >
        </div>
        <div class="pur_second">
            {{ purchase.year }}
            {{ purchase.month > 0 ? "/" : "" }} {{ purchase.month }}
            {{ purchase.day > 0 ? "/" : "" }} {{ purchase.day }}
        </div>
        <div class="pur_third">{{ purchase.name }}</div>
        <div class="pur_fourth">{{ purchase.description }}</div>
        <div class="pur_fifth">
            <span v-for="document in purchase.documents" :key="document.id">
                <Link :href="'/codex_show/general/' + document.id"> 
                    {{ document.standard_name }}
                </Link>
                <br>
            </span>
        </div>
        <div class="pur_sixth">
            <span
                v-for="purchase_party in purchase.purchase_parties"
                :key="purchase_party.id"
            >
                {{ purchase_party.name }}<br />
            </span>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    purchases: Array,
    auth: Object,
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);
</script>

<style></style>
