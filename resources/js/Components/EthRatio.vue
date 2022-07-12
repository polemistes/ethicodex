<template>
  <label><slot /></label>
      <span v-if="(dividend > 0) && (divisor > 0)">
      {{ parseFloat(dividend / divisor).toFixed(2) }}
      </span>
      <span v-else>N/A</span>
</template>

<script setup>

defineProps({
  dividend: Number,
  divisor: Number,
})

</script>

<style>

label {
  padding-top: 15px;
  padding-right: 15px;
}

</style>