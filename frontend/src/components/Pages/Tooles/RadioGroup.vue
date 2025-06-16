<template>
    <label
        v-for="(option , index) in options"
        :key="option.id"
        class="checkBox-inner">
        <input
        type="radio"
        :name="name"
        :value="option.id"
        @change="$emit('update:modelValue', option.id)"
        :checked="index === 0"
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
        name:{
            type: String,
            required: true
        }
    })
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