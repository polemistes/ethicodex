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

<style>
label {
    font-weight: bold;
}

.ratio {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.ratio_string {
    background-color: #ddd;
    padding: 5px;
    border-style: none;
    border-radius: 5px;
    min-height: 2em;
    width: 10em;
}
</style>
