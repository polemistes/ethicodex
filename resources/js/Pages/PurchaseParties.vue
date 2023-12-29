<template>
    <div class="pageline">
        <Link
            class="addbutton"
            href="/purchase_party_new"
            method="post"
            as="button"
        >
            Add Transaction Party
        </Link>
    </div>

    <div class="partycontainer" style="background-color: #ccc">
        <div class="par_first"></div>
        <div class="par_second"><b>Transaction Party</b></div>
        <div class="par_third"><b>Description</b></div>
        <div class="par_fourth"><b>Type</b></div>
        <div class="par_fifth"><b>Transactions</b></div>
        <div class="par_sixth"><b>Codices</b></div>
    </div>
    <div
        v-for="purchase_party in purchase_parties"
        :key="purchase_party.id"
        class="partycontainer"
    >
        <div class="par_first cod_buttons" v-if="edit">
            <Link
                :href="'/purchase_party_edit/' + purchase_party.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                >Edit
            </Link>
            <Link
                :href="'/purchase_party_delete/' + purchase_party.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                onclick="return confirm('Are you sure you want to delete this Transaction Party?')"
                method="post"
                >Delete</Link
            >
        </div>
        <div class="par_second">{{ purchase_party.name }}</div>
        <div class="par_third" v-html="purchase_party.description" />
        <div class="par_fourth">
            {{ purchase_party.institution ? "Institution" : "Person" }}
        </div>
        <div class="par_fifth">
            <ul>
                <span
                    v-for="purchase in purchase_party.purchases"
                    :key="purchase.id"
                >
                    <li>
                        {{ purchase.name }} ({{ purchase.pivot.party_role }})
                    </li>
                </span>
            </ul>
        </div>
        <div class="par_sixth">
            <ul>
                <span
                    v-for="document in documents[purchase_party.id]"
                    :key="document.id"
                >
                    <li>
                        <Link :href="'/codex_show/general/' + document.id">
                            {{ document.standard_name }}
                        </Link>
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
    purchase_parties: Array,
    auth: Object,
});

let documents = [];
for (const purchase_party of props.purchase_parties) {
    documents[purchase_party.id] = [];
    for (const purchase of purchase_party.purchases) {
        for (const document of purchase.documents) {
            if (
                documents[purchase_party.id].find(
                    (x) => x.id === document.id
                ) === undefined
            ) {
                documents[purchase_party.id].push(document);
            }
        }
    }
}

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);
</script>

<style></style>
