<template>
    <h1 class="newtransactionheader">Add Transaction</h1>

    <form
        @submit.prevent="submit"
        style="min-height: 95%; background-color: #eee"
    >
        <fieldset class="newtransactioncontainer">
            <div class="newtransactiongrid">
                <input type="hidden" input_id="id" v-model="form.id" />
                <div class="newtrans_year">
                    <EthInput
                        input_type="number"
                        input_id="year"
                        v-model="form.year"
                        >Year</EthInput
                    >
                </div>
                <div class="newtrans_month">
                    <EthInput
                        input_type="number"
                        input_id="month"
                        minimum="1"
                        maximum="12"
                        v-model="form.month"
                        >Month</EthInput
                    >
                </div>
                <div class="newtrans_day">
                    <EthInput
                        input_type="number"
                        input_id="day"
                        minumum="1"
                        maximum="12"
                        v-model="form.day"
                        >Day</EthInput
                    >
                </div>

                <div class="newtrans_transaction">
                    <EthInput
                        input_type="text"
                        input_id="name"
                        v-model="form.name"
                        >Transaction</EthInput
                    >
                </div>
                <div class="newtrans_transdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="description"
                        v-model="form.description"
                        >Description</EthInput
                    >
                </div>

                <div class="newtrans_docs">
                    <EthInput
                        input_type="document_choice"
                        input_id="documents"
                        :choices="documents_all"
                        v-model="form.documents"
                    >
                        Documents in Transaction
                    </EthInput>
                </div>

                <div class="newtrans_parties input_oneline">
                    <label :for="input_id">Parties to the Transaction</label>
                    <button
                        @click.prevent="dropdown = !dropdown"
                        class="dropdownbutton"
                    >
                        <span>Select</span>
                        <span>⌄</span>
                    </button>
                    <div class="topborder">
                        <span
                            v-for="party in form.purchase_parties"
                            :key="party.id"
                            class="choiceelement"
                        >
                            <button
                                @click.prevent="removechoice(value)"
                                class="removebutton"
                            >
                                ✕
                            </button>
                            {{ party.name }}
                        </span>
                    </div>
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

const props = defineProps({
    documents_all: Array,
    purchase_parties_all: Array,
    auth: Object,
});

const form = useForm({
    year: "",
    month: "",
    day: "",
    name: "",
    description: "",
    documents: [],
    purchase_parties: [],
});

const all_purchase_parties = reactive([]);

for (let p of props.purchase_parties_all) {
    all_purchase_parties.push({
        id: p.id,
        name: p.name,
        description: p.description,
        institution: p.institution,
        party_role: null,
    });
}

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

let search = ref("");
let submitted = false;

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
    form.post(`/purchase_store`);
}
</script>

<style>
.newtransactionheader {
    font-size: larger;
    font-weight: bold;
    padding: 1em 1em 0em 1em;
}

.newtransactioncontainer {
    display: flex;
    background-color: #eee;
    border-style: none;
    margin-top: 4px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    flex-direction: column;
    width: 100%;
}

.newtrans_year {
    grid-area: year;
}
.newtrans_month {
    grid-area: month;
}
.newtrans_day {
    grid-area: day;
}
.newtrans_transaction {
    grid-area: transaction;
}
.newtrans_transdesc {
    grid-area: transdesc;
}
.newtrans_parties {
    grid-area: parties;
}
.newtrans_docs {
    grid-area: docs;
}

.newtransactiongrid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 50%;
    grid-template-rows: auto;
    grid-template-areas:
        "transaction transaction transaction transaction transaction transaction transdesc"
        "year year month month day day transdesc"
        "parties parties parties docs docs docs transdesc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.dropdownbutton {
    display: flex;
    justify-content: space-between;
    background-color: #eee;
    width: 10em;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 5px 10px;
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

.input_oneline {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}
.removebutton {
    all: unset;
    cursor: pointer;
}
</style>
