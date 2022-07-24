<template>
  <div class="container">
    <h1>Information about Ethicodex comes here.</h1>
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


</style>