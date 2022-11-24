<template>
    <div class="ratio">
        <label><slot /></label>
        <span v-if="dividend > 0 && divisor > 0" class="ratio_string">
            {{ parseFloat(dividend / divisor).toFixed(2) }}
        </span>
        <span v-else class="ratio_string">N/A</span>
    </div>
</template>

<script setup>
defineProps({
    dividend: Number,
    divisor: Number,
});
</script>

<style></style>
