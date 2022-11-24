<template>
    <form @submit.prevent="submit" class="mainstyle">
        <div class="maincontainer">
            <fieldset class="party_partygrid">
                <legend class="sectionheading">Edit Transaction Party</legend>

                <div class="party_transparty">
                    <EthInput
                        input_type="text"
                        input_id="name"
                        v-model="form.name"
                        >Transaction Party</EthInput
                    >
                </div>
                <div class="party_partydesc">
                    <EthInput
                        input_type="textarea"
                        input_id="description"
                        v-model="form.description"
                        >Description of Transaction Party</EthInput
                    >
                </div>

                <div class="party_institution">
                    <p>
                        <input
                            id="institution"
                            type="checkbox"
                            :value="form.institution"
                            v-model="form.institution"
                        />
                        <label for="institution">Institution</label>
                    </p>
                </div>

                <div class="party_transactions">
                    <label :for="input_id">Transactions</label>
                    <button
                        @click.prevent="dropdown = !dropdown"
                        class="dropdownbutton"
                    >
                        <span>Select</span>
                        <span>⌄</span>
                    </button>
                    <p class="topborder">
                        <span
                            v-for="purchase in form.purchases"
                            :key="purchase.id"
                            class="choiceelement"
                        >
                            <button
                                @click.prevent="removepurchase(purchase)"
                                class="removebutton"
                            >
                                ✕
                            </button>
                            {{ purchase.name }}
                        </span>
                    </p>
                    <div v-if="dropdown" class="dropdown-content">
                        <div v-if="all_purchases.length > 12">
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
                                    v-model="form.purchases"
                                />
                                <label>{{ choice.name }}</label>

                                <select
                                    v-if="
                                        form.purchases.some(
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

                <!--
      <EthInput input_type="multi_choice" 
                  input_id="purchases" 
                  :choices="purchases_all"
                  v-model="form.purchases">
                  Transactions
      </EthInput>
-->
            </fieldset>
            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
        </div>
    </form>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, computed } from "vue";
import EthInput from "../Components/EthInput.vue";
import { useForm } from "@inertiajs/inertia-vue3";

let party_roles = ["Seller", "Buyer", "Broker"];
let dropdown = ref(false);
let submitted = false;

const props = defineProps({
    purchase_party: Object,
    purchases: Array,
    purchases_all: Array,
    auth: Object,
});

const purch = [];

for (let p of props.purchases) {
    purch.push({
        id: p.id,
        year: p.year,
        name: p.name,
        description: p.description,
        party_role: p.pivot.party_role,
    });
}

const form = useForm({
    name: props.purchase_party.name,
    description: props.purchase_party.description,
    institution: props.purchase_party.institution == 1 ? true : false,
    purchases: purch,
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

const all_purchases = reactive([]);

for (let p of props.purchases_all) {
    all_purchases.push({
        id: p.id,
        year: p.year,
        name: p.name,
        description: p.description,
        party_role: form.purchases.some((e) => e.id === p.id)
            ? form.purchases.find((item) => item.id === p.id).party_role
            : null,
    });
}

let search = ref("");

const search_choices = computed(() => {
    return search.value != ""
        ? all_purchases.filter(function (el) {
              return el.name != null ? el.name.includes(search.value) : null;
          })
        : all_purchases;
});

function changerole(id, value) {
    const index1 = form.purchases.findIndex((obj) => {
        return obj.id === id;
    });
    const index2 = all_purchases.findIndex((obj) => {
        return obj.id === id;
    });
    form.purchases[index1].party_role = value;
    all_purchases[index2].party_role = value;
}

function removepurchase(choice) {
    form.purchases = form.purchases.filter((obj) => {
        return obj.id !== choice.id;
    });
}

function submit() {
    submitted = true;
    form.post(`/purchase_party_update/${props.purchase_party.id}`);
}
</script>

<style></style>
