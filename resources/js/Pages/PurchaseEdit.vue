<template>
    <form @submit.prevent="submit" class="mainstyle">
        <input type="hidden" input_id="id" v-model="form.id" />
        <div class="maincontainer">
            <fieldset class="transaction_grid">
                <legend class="sectionheading">Edit Transaction</legend>
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
                        minimum="1"
                        maximum="31"
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

                <div class="trans_parties inputfield">
                    <label :for="input_id">Parties to the Transaction</label>
                    <button
                        @click.prevent="dropdown = !dropdown"
                        class="dropdownbutton"
                    >
                        <span>Select</span>
                        <span>⌄</span>
                    </button>
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
            </fieldset>
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
    month: props.purchase.month,
    day: props.purchase.day,
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

function removeparty(choice) {
    form.purchase_parties = form.purchase_parties.filter((obj) => {
        return obj.id !== choice.id;
    });
}

function submit() {
    submitted = true;
    form.post(`/purchase_update/${props.purchase.id}`);
}
</script>

<style></style>
