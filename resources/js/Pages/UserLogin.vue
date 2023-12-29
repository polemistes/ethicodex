<template>
    <div class="logincontainer">
        <h1>Login</h1>
        <form @submit.prevent="submit">
            <EthInput input_type="text" input_id="email" v-model="form.email"
                >Email Address</EthInput
            >
            <EthInput
                input_type="password"
                input_id="password"
                v-model="form.password"
                >Password</EthInput
            >
            <div class="loginline">
                <label for="remember">Stay logged in </label
                ><input id="remember" type="checkbox" v-model="form.remember" />
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import EthInput from "../Components/EthInput.vue";
import { useForm } from '@inertiajs/vue3'

let form = useForm({
    email: "",
    password: "",
    remember: false,
});

let submit = () => {
    form.post("/login");
};
</script>

<style></style>
