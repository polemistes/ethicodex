<template>
    <form @submit.prevent="submit" class="mainstyle">
        <input type="hidden" input_id="id" v-model="form.id" />
        <div class="maincontainer">
            <fieldset class="transaction_grid">
                <legend class="sectionheading">Add Transaction</legend>

                <div class="trans_year">
                    <EthInput
                        input_type="number"
                        input_id="year"
                        v-model="form.year"
                        >Year</EthInput
                    >
                </div>

                <div class="trans_month">
                    <EthInput
                        input_type="number"
                        input_id="month"
                        minimum="1"
                        maximum="12"
                        v-model="form.month"
                        >Month</EthInput
                    >
                </div>

                <div class="trans_day">
                    <EthInput
                        input_type="number"
                        input_id="day"
                        minumum="1"
                        maximum="12"
                        v-model="form.day"
                        >Day</EthInput
                    >
                </div>

                <div class="trans_transaction">
                    <EthInput
                        input_type="text"
                        input_id="name"
                        v-model="form.name"
                        >Transaction</EthInput
                    >
                </div>

                <div class="trans_transdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="description"
                        v-model="form.description"
                        >Description</EthInput
                    >
                </div>

                <div class="trans_docs">
                    <EthInput
                        input_type="document_choice"
                        input_id="documents"
                        :choices="documents_all"
                        v-model="form.documents"
                    >
                        Documents in Transaction
                    </EthInput>
                </div>

                <div class="trans_parties">
                    <label :for="input_id">Parties to the Transaction</label>
                    <button
                        @click.prevent="dropdown = !dropdown"
                        class="dropdownbutton"
                    >
                        <span>Select</span>
                        <span>⌄</span>
                    </button>

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
                                    style="margin-right: 5px"
                                />
                                <label>{{ choice.name }}</label>

                                <select
                                    v-if="
                                        form.purchase_parties.some(
                                            (e) => e.id === choice.id
                                        )
                                    "
                                    style="margin-left: 10px"
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
                            <button
                                @click.prevent="dropdown = false"
                                class="dropdownbutton"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                    <div class="choicelist">
                        <span
                            v-for="party in form.purchase_parties"
                            :key="party.id"
                            class="choiceelement"
                            :title="party.description"
                        >
                            <button
                                @click.prevent="removeparty(party)"
                                class="removebutton"
                            >
                                ✕
                            </button>
                            {{ party.name }}
                        </span>
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
            </fieldset>

            <button @click.prevent="submit" class="submitbutton">
                Store All Changes
            </button>
        </div>
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

function removeparty(choice) {
    form.purchase_parties = form.purchase_parties.filter((obj) => {
        return obj.id !== choice.id;
    });
}

function submit() {
    submitted = true;
    form.post(`/purchase_store`);
}
</script>

<style></style>
