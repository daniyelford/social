<script setup>
  import { sendApi } from '@/utils/api'
  import { ref,onMounted,watch } from 'vue'
  import RadioGroup from '@/components/Pages/Tooles/RadioGroup.vue'
  const emit = defineEmits(['update:filters'])
  const username = ref('')
  const price = ref('')
  const result = ref('')
  const categories = ref([])
  const categoriesName = ref('category')
  const selectedCategories = ref([])
  const PaymentTypesName = ref('payment-type')
  const PaymentTypes = [
    { id: 12, label: '12 ساعته' },
    { id: 24, label: '24 ساعته' },
    { id: 48, label: '48 ساعته' },
  ]
  const selectedPaymentTypes = ref(PaymentTypes['0'].id)
  const runPython = async () => {
    if (!username.value.trim()) {
      result.value = 'لطفاً یوزرنیم تلگرام را وارد کنید.'
      return
    }
    result.value = 'در حال دریافت اطلاعات...'
    try {
      const res = await sendApi({
        action: 'run_python/telegram_bot',
        id : username.value,
        price:price.value,
        category:selectedCategories.value,
        type:selectedPaymentTypes.value
      })
      if (res.status==='success') {
        result.value = 'done';
      }else{
            console.warn('دریافت دسته‌بندی‌ها ناموفق بود:', carts)
      }
    } catch (e) {
      result.value = 'خطا در اجرای پایتون';
    }
  }
  onMounted(async () => {
    const categoriesFinder = await sendApi({action: "page_handler/category_handler", handler:'all_categories'});
    if(categoriesFinder.status==="success") {
      categories.value = categoriesFinder.data.map(cat => ({
        id: cat.id,
        label: cat.name
      }))      
      selectedCategories.value=categories.value[0].id
    } else {
      console.warn('دریافت دسته‌بندی‌ها ناموفق بود:', categoriesFinder)
    }
  })
  watch(
    [
        selectedCategories,
        selectedPaymentTypes,
    ],
    () => {
        emit('update:filters', {
            categories: selectedCategories.value,
            paymentTypes: selectedPaymentTypes.value,
        })
    },
    { deep: true }
  ) 
</script>
<template>
  <div class="p-4 max-w-xl mx-auto">
    <h1 class="text-xl font-bold mb-4">بررسی اطلاعات telegram</h1>
    <input
      v-model="username"
      type="text"
      placeholder="id telegram را وارد کنید"
      class="border px-3 py-2 rounded w-full mb-2"
    />
    <input
      v-model="price"
      type="number"
      placeholder="قیمت را وارد کنید"
      class="border px-3 py-2 rounded w-full mb-2"
    />
    categorys
    <div class="category-inner">
      <RadioGroup v-model="selectedCategories" :name="categoriesName" :options="categories" />
    </div>
    payment type
    <div class="payment-inner">
      <RadioGroup v-model="selectedPaymentTypes" :name="PaymentTypesName" :options="PaymentTypes" />
    </div>
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
