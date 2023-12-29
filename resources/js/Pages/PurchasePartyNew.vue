<template>
    <form @submit.prevent="submit" class="mainstyle">
        <div class="maincontainer">
            <fieldset class="party_partygrid">
                <legend class="sectionheading">Add Transaction Party</legend>

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
                    <label
                        for="institution"
                        style="font-size: 18px; font-weight: 'bold'"
                        >Institution</label
                    >
                    <input
                        id="institution"
                        type="checkbox"
                        style="
                            margin-right: auto;
                            margin-left: 35px;
                            margin-top: 10px;
                            transform: scale(1.5);
                        "
                        :value="form.institution"
                        v-model="form.institution"
                    />
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
                                    style="margin-right: 5px"
                                />
                                <label>{{ choice.name }}</label>

                                <select
                                    v-if="
                                        form.purchases.some(
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
                    <div class="choicelist-stack">
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
                    </div>
                </div>
            </fieldset>
            <button
                :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                @click.prevent="submit"
            >
                Store All Changes
            </button>
        </div>
    </form>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { reactive, ref, computed } from "vue";
import EthInput from "../Components/EthInput.vue";
import { useForm } from '@inertiajs/vue3'

let party_roles = ["Seller", "Buyer", "Broker", "Donor", "Recipient"];
let dropdown = ref(false);
let submitted = false;

const props = defineProps({
    purchases_all: Array,
    auth: Object,
});

const form = useForm("PurchasePartyNew", {
    name: "",
    description: "",
    institution: false,
    purchases: [],
});

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
    form.post(`/purchase_party_store`);
}
</script>

<style></style>
