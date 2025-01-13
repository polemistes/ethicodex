<template>
    <div class="pageline">
        <Link
            class="addbutton"
            href="/transaction_new"
            method="post"
            as="button"
        >
            Add Transaction
        </Link>
        <div class="pageline_search_field">
            <EthInput input_type="text" input_id="search" v-model="search">
                Search
            </EthInput>
        </div>

        <div class="pageline_total_found">
            <div style="padding-bottom: 8px; font-weight: bold">Found</div>
            <div>{{ search_results.length }}</div>
        </div>
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
        v-for="transaction in search_results"
        :key="transaction.id"
        class="transactioncontainer"
    >
        <div class="pur_first cod_buttons">
            <Link
                :href="'/transaction_edit/' + transaction.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                >Edit
            </Link>
            <Link
                :href="'/transaction_delete/' + transaction.id"
                as="button"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                onclick="return confirm('Are you sure you want to delete this Transaction?')"
                method="post"
                >Delete</Link
            >
        </div>
        <div class="pur_second">
            {{ transaction.year }}
            {{ transaction.month > 0 ? "/" : "" }} {{ transaction.month }}
            {{ transaction.day > 0 ? "/" : "" }} {{ transaction.day }}
        </div>
        <div class="pur_third">{{ transaction.name }}</div>
        <div class="pur_fourth" v-html="transaction.description" />
        <div class="pur_fifth">
            <ul>
                <span
                    v-for="document in transaction.documents"
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
        <div class="pur_sixth">
            <ul>
                <span
                    v-for="transaction_party in transaction.transaction_parties"
                    :key="transaction_party.id"
                >
                    <li>
                        {{ transaction_party.name }} ({{
                            transaction_party.pivot.party_role
                        }})
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

let search = ref("");

const props = defineProps({
    transactions: Array,
    auth: Object,
});

const search_results = computed(() => {
    return search.value != ""
        ? props.transactions.filter(function (el) {
              return (
                  (el.name != null ? el.name.toLowerCase().includes(search.value.toLowerCase()) : null) ||
                  (el.description != null
                      ? el.description.toLowerCase().includes(search.value.toLowerCase())
                      : null)
              );
          })
        : props.transactions;
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);
</script>

<style></style>
