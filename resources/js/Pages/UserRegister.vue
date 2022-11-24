<template>
    <div class="registercontainer">
        <h1>Register User</h1>
        <form @submit.prevent="submit">
            <EthInput input_type="text" input_id="name" v-model="form.name"
                >Name</EthInput
            >
            <EthInput input_type="text" input_id="email" v-model="form.email"
                >Email Address</EthInput
            >
            <EthInput
                input_type="password"
                input_id="password"
                v-model="form.password"
                >Password</EthInput
            >
            <button type="submit">Submit</button>
        </form>
    </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import EthInput from "../Components/EthInput.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
});

let submit = () => {
    form.post("/user_store");
};
</script>

<style></style>
