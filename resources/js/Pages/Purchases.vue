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
        <div class="pur_fourth" v-html="purchase.description" />
        <div class="pur_fifth">
            <ul>
                <span v-for="document in purchase.documents" :key="document.id">
                    <li>
                        <Link :href="'/codex_show/' + document.id">
                            {{ document.standard_name }}
                        </Link>
                    </li>
                </span>
            </ul>
        </div>
        <div class="pur_sixth">
            <ul>
                <span
                    v-for="purchase_party in purchase.purchase_parties"
                    :key="purchase_party.id"
                >
                    <li>
                        {{ purchase_party.name }} ({{
                            purchase_party.pivot.party_role
                        }})
                    </li>
                </span>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3'

const props = defineProps({
    purchases: Array,
    auth: Object,
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);
</script>

<style></style>
