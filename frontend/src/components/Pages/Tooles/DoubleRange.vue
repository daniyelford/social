<template>
  <div class="custom-slider" ref="track" @mousedown="startDrag">
    <div class="slider-line"></div>
    <div
      class="slider-range"
      :style="{ left: `${startPercent}%`, width: `${endPercent - startPercent}%` }"
    ></div>
    <div
      class="thumb"
      :style="{ left: `${startPercent}%` }"
      @mousedown.prevent="dragging = 'from'"
    ></div>
    <div
      class="thumb"
      :style="{ left: `${endPercent}%` }"
      @mousedown.prevent="dragging = 'to'"
    ></div>
  </div>

  <div class="inputs">
    <label>
      از:
      <input
        type="number"
        v-model.number="internalFrom"
        :min="safeMin"
        :max="internalTo - 1"
      />
    </label>
    <label>
      تا:
      <input
        type="number"
        v-model.number="internalTo"
        :min="internalFrom + 1"
        :max="safeMax"
      />
    </label>
  </div>
</template>

<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue'

const props = defineProps({
  min: { type: Number, default: 0 },
  max: { type: Number, default: 100 },
  modelValue: {
    type: Object,
    default: () => ({ from: 20, to: 80 }),
  },
})

const emit = defineEmits(['update:modelValue'])

const dragging = ref(null)
const track = ref(null)

// ✅ امن‌سازی min و max
const safeMin = computed(() => Math.min(props.min, props.max))
const safeMax = computed(() => Math.max(props.min, props.max))
const total = computed(() => safeMax.value - safeMin.value)
const clamp = (val, min, max) => Math.min(Math.max(val, min), max)

// ✅ مقداردهی اولیه
const internalFrom = ref(clamp(props.modelValue.from, safeMin.value, safeMax.value - 1))
const internalTo = ref(clamp(props.modelValue.to, internalFrom.value + 1, safeMax.value))

// ✅ اصلاح مقادیر ورودی داخلی اگر از بازه خارج شدن
watch([internalFrom, internalTo], () => {
  let from = clamp(internalFrom.value, safeMin.value, safeMax.value)
  let to = clamp(internalTo.value, safeMin.value, safeMax.value)

  if (to <= from) {
    to = from + 1
    if (to > safeMax.value) {
      to = safeMax.value
      from = to - 1
    }
  }

  if (from !== internalFrom.value) internalFrom.value = from
  if (to !== internalTo.value) internalTo.value = to

  if (
    props.modelValue.from !== from ||
    props.modelValue.to !== to
  ) {
    emit('update:modelValue', { from, to })
  }
})

watch(
  () => props.modelValue,
  ({ from, to }) => {
    if (from !== internalFrom.value) internalFrom.value = from
    if (to !== internalTo.value) internalTo.value = to
  }
)

const startPercent = computed(() =>
  clamp(((internalFrom.value - safeMin.value) / total.value) * 100, 0, 100)
)
const endPercent = computed(() =>
  clamp(((internalTo.value - safeMin.value) / total.value) * 100, 0, 100)
)

function onDrag(e) {
  if (!dragging.value || !track.value) return

  const rect = track.value.getBoundingClientRect()
  const x = e.clientX - rect.left
  const percent = clamp((x / rect.width) * 100, 0, 100)
  const value = Math.round(safeMin.value + (percent / 100) * total.value)

  if (dragging.value === 'from') {
    internalFrom.value = Math.min(value, internalTo.value - 1)
  } else if (dragging.value === 'to') {
    internalTo.value = Math.max(value, internalFrom.value + 1)
  }
}

function startDrag() {
  document.addEventListener('mousemove', onDrag)
  document.addEventListener('mouseup', stopDrag)
}

function stopDrag() {
  dragging.value = null
  document.removeEventListener('mousemove', onDrag)
  document.removeEventListener('mouseup', stopDrag)
}

onBeforeUnmount(() => stopDrag())
</script>

<style scoped>
.custom-slider {
  position: relative;
  height: 20px;
  margin: 0;
}
.slider-line {
  position: absolute;
  top: 50%;
  height: 4px;
  width: 100%;
  background-color: #ccc;
  transform: translateY(-50%);
  border-radius: 2px;
  z-index: 1;
}
.slider-range {
  position: absolute;
  top: 50%;
  height: 4px;
  background-color: #3b82f6;
  transform: translateY(-50%);
  border-radius: 2px;
  z-index: 2;
}
.thumb {
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  height: 10px;
  width: 10px;
  background-color: #3b82f6;
  border-radius: 50%;
  cursor: grab;
  z-index: 3;
  border: 2px solid white;
  box-shadow: 0 0 2px rgba(0, 0, 0, 0.4);
}
.inputs {
  display: flex;
  direction: rtl;
  justify-content: space-between;
  font-size: 13px;
}
.inputs label {
  margin: 0 0 5px 0;
  width: 50%;
  text-align: center;
}
.inputs input {
  height: 20px;
  width: 60px;
  margin-right: 5px;
  border-radius: 5px;
  outline: none;
  border: none;
}
</style>
