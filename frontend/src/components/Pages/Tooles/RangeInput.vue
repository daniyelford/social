<template>
    <label class="range">{{ label }}</label>
    <input
      type="range"
      :min="min"
      :max="max"
      :step="step"
      v-model="modelValueLocal"
      class="range-input"
    />
</template>
<script setup>
    import { ref, watch } from 'vue'
    const props = defineProps({
        label: String,
        min: {
            type: Number,
            default: 0
        },
        max: {
            type: Number,
            default: 100
        },
        step: {
            type: Number,
            default: 1
        },
        modelValue: Number
    })
    const emit = defineEmits(['update:modelValue'])
    const modelValueLocal = ref(props.modelValue ?? props.min)
    watch(modelValueLocal, (val) => {
        emit('update:modelValue', val)
    })
    watch(() => props.modelValue, (val) => {
        modelValueLocal.value = val
    })
</script>
<style scoped>
    .range{
        font-size: 9px;
        display: block;
        padding: 3px 0 2px;
    }
    .range-input{
        display: block;
        width: 100%;
        height: 5px;
    }
</style>