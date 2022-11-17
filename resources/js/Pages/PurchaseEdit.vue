<template>
    <h1 class="edittransactionheader">Edit transaction: {{ form.name }}</h1>

    <form @submit.prevent="submit">
        <fieldset class="edittransactioncontainer">
            <div class="edittransaction">
                <input type="hidden" input_id="id" v-model="form.id" />
                <div class="edittrans_year">
                    <EthInput
                        input_type="number"
                        input_id="year"
                        v-model="form.year"
                        >Year</EthInput
                    >
                </div>
                <div class="edittrans_transaction">
                    <EthInput
                        input_type="text"
                        input_id="name"
                        v-model="form.name"
                        >Transaction</EthInput
                    >
                </div>
                <div class="edittrans_transdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="description"
                        v-model="form.description"
                        >Description</EthInput
                    >
                </div>

                <div class="edittrans_docs">
                    <EthInput
                        input_type="document_choice"
                        input_id="documents"
                        :choices="documents_all"
                        v-model="form.documents"
                    >
                        Documents in Transaction
                    </EthInput>
                </div>

                <div class="edittrans_parties">
                    <label :for="input_id">Parties to the Transaction</label>
                    <button
                        @click.prevent="dropdown = !dropdown"
                        class="dropdownbutton"
                    >
                        Select
                    </button>
                    <p class="topborder">
                        <span
                            v-for="party in form.purchase_parties"
                            :key="party.id"
                            class="choiceelement"
                        >
                            {{ party.name }}
                        </span>
                    </p>
                    <div v-if="dropdown" class="dropdown-content">
                        <div v-if="all_purchase_parties.length > 12">
                            <label :for="search">Search:</label>
                            <input type="text" v-model="search" />
                        </div>

                        <div class="scrollwindow">
                            <div
                                v-for="choice in search_choices"
                                :key="choice.id"
                            >
                                <input
                                    type="checkbox"
                                    :id="choice.id"
                                    :value="choice"
                                    v-model="form.purchase_parties"
                                />
                                <label>{{ choice.name }}</label>

                                <select
                                    v-if="
                                        form.purchase_parties.some(
                                            (e) => e.id === choice.id
                                        )
                                    "
                                    v-model="choice.party_role"
                                    @change="
                                        changerole(
                                            choice.id,
                                            $event.target.value
                                        )
                                    "
                                >
                                    <option></option>
                                    <option
                                        v-for="party_role in party_roles"
                                        :key="party_role"
                                    >
                                        {{ party_role }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <button
                            @click.prevent="dropdown = false"
                            class="dropdownbutton"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
            <!--
        <EthInput input_type="party_choice" 
                  input_id="purchase_parties" 
                  :choices="purchase_parties_all"
                  :roles="party_roles"
                  v-model="form.purchase_parties">
                  Parties to the Transaction
        </EthInput>
-->

            <button @click.prevent="submit" class="submitbutton">
                Store All Changes
            </button>
        </fieldset>
    </form>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, computed, onMounted } from "vue";
import EthInput from "../Components/EthInput.vue";
import { useForm } from "@inertiajs/inertia-vue3";

let party_roles = ["Seller", "Buyer", "Broker"];
let dropdown = ref(false);
let submitted = false;

const props = defineProps({
    purchase: Object,
    documents: Array,
    documents_all: Array,
    purchase_parties: Array,
    purchase_parties_all: Array,
    auth: Object,
});

const pparties = [];

for (let p of props.purchase_parties) {
    pparties.push({
        id: p.id,
        name: p.name,
        description: p.description,
        institution: p.institution,
        party_role: p.pivot.party_role,
    });
}

const form = useForm({
    year: props.purchase.year,
    name: props.purchase.name,
    description: props.purchase.description,
    documents: props.documents,
    purchase_parties: pparties,
});

let removeStartEventListener = Inertia.on("before", (event) => {
    if (form.isDirty && !submitted) {
        if (
            confirm(
                "You have unsaved data in your form. Are you sure you want to leave the page?"
            )
        ) {
            removeStartEventListener();
            window.onbeforeunload = null;
        } else if (submitted) {
            removeStartEventListener();
            window.onbeforeunload = null;
        } else {
            return false;
        }
    }
});

window.onbeforeunload = (s) => (form.isDirty ? "" : null);

const all_purchase_parties = reactive([]);

for (let p of props.purchase_parties_all) {
    all_purchase_parties.push({
        id: p.id,
        name: p.name,
        description: p.description,
        institution: p.institution,
        party_role: form.purchase_parties.some((e) => e.id === p.id)
            ? form.purchase_parties.find((item) => item.id === p.id).party_role
            : null,
    });
}

let search = ref("");

const search_choices = computed(() => {
    return search.value != ""
        ? all_purchase_parties.filter(function (el) {
              return el.name != null ? el.name.includes(search.value) : null;
          })
        : all_purchase_parties;
});

function changerole(id, value) {
    const index1 = form.purchase_parties.findIndex((obj) => {
        return obj.id === id;
    });
    const index2 = all_purchase_parties.findIndex((obj) => {
        return obj.id === id;
    });
    form.purchase_parties[index1].party_role = value;
    all_purchase_parties[index2].party_role = value;
}

function submit() {
    submitted = true;
    form.post(`/purchase_update/${props.purchase.id}`);
}
</script>

<style>
.edittransactionheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;
}

.edittransactioncontainer {
    display: flex;
    background-color: #eee;
    margin-top: 4px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    flex-direction: column;
    width: 100%;
}

.edittrans_year {
    grid-area: year;
}
.edittrans_transaction {
    grid-area: transaction;
}
.edittrans_transdesc {
    grid-area: transdesc;
}
.edittrans_parties {
    grid-area: parties;
}
.edittrans_docs {
    grid-area: docs;
}

.edittransaction {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "transaction year transdesc"
        "parties docs transdesc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.dropdownbutton {
    background-color: #6f726f;
    color: white;
    width: fit-content;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown-content {
    display: block;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    padding: 20px;
    border-style: solid;
    border-color: #999;
    border: 4px;
    border-radius: 10px;
}

.choiceelement {
    background-color: gray; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: auto; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 5px 10px 5px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 16px; /* Changing font size */
}

.topborder {
    margin-top: 10px;
}

.submitbutton {
    font-size: 20px;
    width: auto;
    height: auto;
    padding: 20px;
    border-radius: 15px;
    align-self: center;
    margin: 20px;
}
</style>
