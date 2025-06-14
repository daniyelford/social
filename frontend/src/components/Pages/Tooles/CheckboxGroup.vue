<template>
  <label
    v-for="option in options"
    :key="option.id"
    class="checkBox-inner">
    <input
      type="checkbox"
      :value="option.id"
      :checked="modelValue.includes(option.id)"
      @change="toggle(option.id)"
    />
    <span>{{ option.label }}</span>
  </label>
</template>
<script setup>
  const props = defineProps({
    options: {
      type: Array,
      required: true
    },
    modelValue: {
      type: Array,
      required: true
    }
  })
  const emit = defineEmits(['update:modelValue'])
  function toggle(id) {
    const newValue = props.modelValue.includes(id)? props.modelValue.filter((v) => v !== id): [...props.modelValue, id]
    emit('update:modelValue', newValue)
  }
</script>
<style scoped>
  .checkBox-inner {
    height: 15px;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-start;
    align-items: stretch;
  }
  .checkBox-inner input {
    margin: 0;
    height: 10px;
  }
  .checkBox-inner span {
    font-size: 9px;
    padding-right: 2px;
  }
</style>