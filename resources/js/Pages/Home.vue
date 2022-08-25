<template>
  <div class="homecontainer">
    <h1>Ethicodex Home</h1>
    <p>Welcome to the EthiCodex database of early codices. This database 
      attempts to provide both physical descriptive details and provenance
      histories for Greek and Latin codices of the first five centuries of the common era.</p>
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


.homecontainer {
  width: 60%;
  margin: auto;
}

.homecontainer h1 {
  margin-top: 1em;
  font-size: larger;
}

</style>