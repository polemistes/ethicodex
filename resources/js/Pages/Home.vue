<template>
  <div class="container">
    <h1>Welcome to the Ethicodex Database. The site is still under development.</h1>
  </div>
</template>

<script setup>

defineProps({
  test: {type: Boolean,
         default: false},
})

function toggle() {
  test = true;
  alert("TEst!")
}
</script>

<style>
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