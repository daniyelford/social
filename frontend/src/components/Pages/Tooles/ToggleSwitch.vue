<template>
    <label class="switch">
        <input type="checkbox" :checked="modelValue" @change="emitChange" />
        <span class="slider"></span>
    </label>
</template>
<script setup>
    defineProps(['modelValue'])
    const emit = defineEmits(['update:modelValue'])
    function emitChange(event) {
        emit('update:modelValue', event.target.checked)
    }
</script>
<style scoped>
    .switch {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 10px;
        top: 7px;
        left: 5px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 10px;
        width: 10px;
        left: 0px;
        bottom: 0px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #4caf50;
    }
    input:checked + .slider:before {
        transform: translateX(10px);
    }
</style>