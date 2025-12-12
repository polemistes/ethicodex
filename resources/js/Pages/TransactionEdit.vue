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
                <div class="trans_transbiblio">
                    <EthInput
                        input_type="textarea"
                        input_id="bibliography"
                        v-model="form.bibliography"
                        >Bibliography</EthInput
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
                        <div v-if="all_transaction_parties.length > 12">
                            <label :for="search">Search:</label>
                            <input type="text" v-model="search" />
                            <button
                                @click.prevent="dropdown = false"
                                class="closemenubutton"
                                >
                                Close
                            </button>
                        </div>
                         
                        <div class="dropdown-scrollwindow">
                            <div
                                v-for="choice in search_choices"
                                :key="choice.id"
                            >
                                <input
                                    type="checkbox"
                                    :id="choice.id"
                                    :value="choice"
                                    v-model="form.transaction_parties"
                                    style="margin-right: 5px"
                                />
                                <label>{{ choice.name }}</label>

                                <select
                                    v-if="
                                        form.transaction_parties.some(
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
                        </div>
                    </div>
                    <div class="choicelist">
                        <span
                            v-for="party in form.transaction_parties"
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
            </fieldset>

            <button
                :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                @click.prevent="submit"
                :disabled="form.processing"
            >
                Store All Changes
            </button>
        </div>
    </form>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { reactive, ref, computed, onMounted } from "vue";
import EthInput from "../Components/EthInput.vue";
import { useForm } from '@inertiajs/vue3'

let party_roles = ["Seller", "Buyer", "Broker", "Donor", "Recipient", "Excavator"];
let dropdown = ref(false);
let submitted = false;

const props = defineProps({
    transaction: Object,
    documents: Array,
    documents_all: Array,
    transaction_parties: Array,
    transaction_parties_all: Array,
    auth: Object,
});

const pparties = [];

for (let p of props.transaction_parties) {
    pparties.push({
        id: p.id,
        name: p.name,
        description: p.description,
        institution: p.institution,
        party_role: p.pivot.party_role,
    });
}

const form = useForm("TransactionEdit", {
    year: props.transaction.year,
    month: props.transaction.month,
    day: props.transaction.day,
    name: props.transaction.name,
    description: props.transaction.description,
    bibliography: props.transaction.bibliography,
    documents: props.documents,
    transaction_parties: pparties,
});

let removeStartEventListener = router.on("before", (event) => {
    if (form.isDirty && !submitted) {
        if (
            confirm(
                "You have unsaved data in your form. Are you sure you want to leave the page?"
            )
        ) {
            removeStartEventListener();
            window.onbeforeunload = null;
            window.onpopstate = null;
        } else if (submitted) {
            removeStartEventListener();
            window.onbeforeunload = null;
            window.onpopstate = null;
        } else {
            return false;
        }
    }
});

window.onbeforeunload = (s) => (form.isDirty ? "" : null);

window.onpopstate = function (event) {
    removeStartEventListener();
    window.onbeforeunload = null;
    window.onpopstate = null;
    if (form.isDirty) {
        if (
            !confirm(
                "You have unsaved data. Do you really want to leave the page?"
            )
        ) {
            window.history.go(1);
        }
    }
};

const all_transaction_parties = reactive([]);

for (let p of props.transaction_parties_all) {
    all_transaction_parties.push({
        id: p.id,
        name: p.name,
        description: p.description,
        institution: p.institution,
        party_role: form.transaction_parties.some((e) => e.id === p.id)
            ? form.transaction_parties.find((item) => item.id === p.id).party_role
            : null,
    });
}

let search = ref("");

const search_choices = computed(() => {
    
    let result = search.value != ""
        ? all_transaction_parties.filter(function (el) {
              return el.name != null ? el.name.includes(search.value) : null;
          })
        : all_transaction_parties;

        return result.sort((a, b) => a.name.localeCompare(b.name)); 
});

function changerole(id, value) {
    const index1 = form.transaction_parties.findIndex((obj) => {
        return obj.id === id;
    });
    const index2 = all_transaction_parties.findIndex((obj) => {
        return obj.id === id;
    });
    form.transaction_parties[index1].party_role = value;
    all_transaction_parties[index2].party_role = value;
}

function removeparty(choice) {
    form.transaction_parties = form.transaction_parties.filter((obj) => {
        return obj.id !== choice.id;
    });
}

function submit() {
    submitted = true;
    form.post(`/transaction_update/${props.transaction.id}`);
}
</script>

<style></style>
