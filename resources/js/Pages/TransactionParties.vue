<template>
    <div class="pageline">
        <Link
            class="addbutton"
            href="/transaction_party_new"
            method="post"
            as="button"
        >
            Add Transaction Party
        </Link>
        <div class="pageline_search_field">
            <EthInput input_type="text" input_id="search" v-model="search">
                Full-Text Search
            </EthInput>
        </div>

        <div class="pageline_total_found">
            <div style="padding-bottom: 8px; font-weight: bold">Found</div>
            <div>{{ search_results.length }}</div>
        </div>
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
        v-for="transaction_party in search_results"
        :key="transaction_party.id"
        class="partycontainer"
    >
        <div class="par_first cod_buttons" v-if="edit">
            <Link
                :href="'/transaction_party_edit/' + transaction_party.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                >Edit
            </Link>
            <Link
                :href="'/transaction_party_delete/' + transaction_party.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                onclick="return confirm('Are you sure you want to delete this Transaction Party?')"
                method="post"
                >Delete</Link
            >
        </div>
        <div class="par_second">{{ transaction_party.name }}</div>
        <div class="par_third" v-html="transaction_party.description" />
        <div class="par_fourth">
            {{ transaction_party.institution ? "Institution" : "Person" }}
        </div>
        <div class="par_fifth">
            <ul>
                <span
                    v-for="transaction in transaction_party.transactions"
                    :key="transaction.id"
                >
                    <li>
                        {{ transaction.name }} ({{
                            transaction.pivot.party_role
                        }})
                    </li>
                </span>
            </ul>
        </div>
        <div class="par_sixth">
            <ul>
                <span
                    v-for="document in documents[transaction_party.id]"
                    :key="document.id"
                >
                    <li>
                        <Link :href="'/codex_show/' + document.id">
                            {{ document.standard_name }}
                        </Link>
                    </li>
                </span>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import EthInput from "../Components/EthInput.vue";

const props = defineProps({
    transaction_parties: Array,
    auth: Object,
});

let search = ref("");
let documents = [];

for (const transaction_party of props.transaction_parties) {
    documents[transaction_party.id] = [];
    for (const transaction of transaction_party.transactions) {
        for (const document of transaction.documents) {
            if (
                documents[transaction_party.id].find(
                    (x) => x.id === document.id
                ) === undefined
            ) {
                documents[transaction_party.id].push(document);
            }
        }
    }
}

const search_results = computed(() => {
    return search.value != ""
        ? props.transaction_parties.filter(function (el) {
              return (
                  (el.name != null ? el.name.includes(search.value) : null) ||
                  (el.description != null
                      ? el.description.includes(search.value)
                      : null)
              );
          })
        : props.transaction_parties;
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);
</script>

<style></style>
