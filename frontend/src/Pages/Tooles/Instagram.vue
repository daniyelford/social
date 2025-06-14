<script setup>
import { sendApi } from '@/utils/api'
import { ref } from 'vue'
const username = ref('')
const price = ref('')
const result = ref('')
const runPython = async () => {
  if (!username.value.trim()) {
    result.value = 'لطفاً یوزرنیم اینستاگرام را وارد کنید.'
    return
  }
  result.value = 'در حال دریافت اطلاعات...'
  try {
    const res = await sendApi({
      action: 'run_python/instagram_bot',
      id : username.value,
      price:price.value
    })
    if (res.status==='success') {
      result.value = 'done';
    }
  } catch (e) {
    result.value = 'خطا در اجرای پایتون';
  }
}
</script>
<template>
  <div class="p-4 max-w-xl mx-auto">
    <h1 class="text-xl font-bold mb-4">بررسی اطلاعات اینستاگرام</h1>
    <input
      v-model="username"
      type="text"
      placeholder="یوزرنیم اینستاگرام را وارد کنید"
      class="border px-3 py-2 rounded w-full mb-2"
    />
    <input
      v-model="price"
      type="number"
      placeholder="قیمت را وارد کنید"
      class="border px-3 py-2 rounded w-full mb-2"
    />
    <button
      @click="runPython"
      class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
    >
      اجرا
    </button>
    <div class="mt-4">
      <h2 class="font-semibold">نتیجه:</h2>
      <pre class="bg-gray-100 p-3 rounded whitespace-pre-wrap">{{ result }}</pre>
    </div>
  </div>
</template>
