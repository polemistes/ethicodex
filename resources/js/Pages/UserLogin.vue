<template>
    <div class="container">
    <h1>Login</h1>
    <form @submit.prevent="submit">
        <EthInput input_type="text" input_id="email" v-model="form.email">Email Address</EthInput>
        <EthInput input_type="password" input_id="password" v-model="form.password">Password</EthInput>
        <label for="remember">Stay logged in </label><input id="remember" type="checkbox" v-model="form.remember" />
        <button type=submit>Login</button>
    </form>
    </div>
</template>

<script setup>

import EthInput from "../Components/EthInput.vue";
import { useForm } from "@inertiajs/inertia-vue3";

let form = useForm({
    email: '',
    password: '',
    remember: false
})

let submit = () => {
    form.post('/login');
}
</script>

<style>
td {
  padding: 5px;
}
tr:nth-child(even) {background: #EEE}
tr:nth-child(odd) {background: #AAA}

table {
  margin-top: 10px;
  overflow: hidden;
  border-radius: 10px;
  border-spacing: 0;
}

.container {
  display: flex;
  background-color: #bbb;
  margin: 10px;
  padding: 20px;
  font-family: sans-serif;
  font-size: 14px;
  flex-direction: column;
  border-radius: 10px;
}

</style>
