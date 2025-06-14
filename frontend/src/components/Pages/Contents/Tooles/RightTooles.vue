<template>
    <div class="tooles">
        <div class="toole" v-if="activeFilters && activeFilters.length">
            <div class="fillter-header">
                <p class="fillter-header-title">
                    فیلتر های انتخابی
                </p>
                <a class="fillter-header-delete" @click="clearAllFilters">
                    <span>
                        حذف
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="10px" viewBox="0 -960 960 960" width="10px" fill="red"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                </a>
            </div>
            <div class="fillters">
                <div v-for="filter in activeFilters" :key="filter.id" class="fillter">
                    <a @click="removeFilter(filter)">x</a>
                    {{ filter.label }}
                </div>
            </div>
        </div>
        <div class="toole">
            <span class="toggle">
                <ToggleSwitch v-model="isChecked" />
            </span>
            <span class="label-text">فقط انتخاب شده ها</span>
        </div>
        <div class="toole">
            <span class="toggle">
                <ToggleSwitch v-model="isChecked1" />
            </span>
            <span class="label-text">
                فقط مناسب شما
            </span>
        </div>
        <div class="toole">
            <span class="toggle">
                <ToggleSwitch v-model="isChecked2" />
            </span>
            <span class="label-text">
                دارای اینسایت
            </span>
        </div>
        <div class="toole">
            <p class="category">
                دسته بندی ها
            </p>
            <input
                type="search"
                v-model="searchCategory"
                id="search-category"
                name="search"
                placeholder="جستجو"
                
            />
            <div class="category-inner">
                <CheckboxGroup v-model="selectedCategories" :options="filteredCategories" />
            </div>
        </div>
        <div class="toole">
            <p class="soical-type">
                نوع پیج
            </p>
            <div class="soical-inner">
                <CheckboxGroup v-model="selectedSoicalTypes" :options="SoicalTypes" />
            </div>
        </div>
        <div class="toole">
            <p class="type">
                نوع تعرفه
            </p>
            <div class="payment-inner">
                <CheckboxGroup v-model="selectedPaymentTypes" :options="PaymentTypes" />
            </div>
        </div>
        <div class="toole">
            <p class="range-title">
                بازه قیمت(تومان)
            </p>
            <DoubleRange v-model="price" :min="props.ranges.priceRange.min" :max="props.ranges.priceRange.max"/>
        </div>
        <div class="toole">
            <p class="range-title">
                بازه فالوئر(نفر)
            </p>
            <DoubleRange v-model="follower" :min="props.ranges.followerRange.min" :max="props.ranges.followerRange.max"/>
        </div>
    </div>
</template>
<script setup>
    import { ref,watch,onMounted,computed } from 'vue'
    import { sendApi } from '@/utils/api'
    import ToggleSwitch from '@/components/Pages/Tooles/ToggleSwitch.vue'
    import CheckboxGroup from '@/components/Pages/Tooles/CheckboxGroup.vue'
    import DoubleRange from '@/components/Pages/Tooles/DoubleRange.vue'
    const props = defineProps({
        ranges: {
            type: Object,
            default: () => ({
                priceRange: { min: 0, max: 1000 },
                followerRange: { min: 0, max: 100000 }
            })
        }
    })
    const isChecked = ref(false)
    const isChecked1 = ref(false)
    const isChecked2 = ref(false)
    const searchCategory = ref('')
    const selectedCategories = ref([])
    const categories = ref([])
    const selectedSoicalTypes = ref([])
    const SoicalTypes = [
        { id: 'very', label: 'پر مخاطب' },
        { id: 'good', label: 'اینفلونسر' },
    ]
    const selectedPaymentTypes = ref([])
    const PaymentTypes = [
        { id: 12, label: '12 ساعته' },
        { id: 24, label: '24 ساعته' },
        { id: 48, label: '48 ساعته' },
    ]
    const price = ref({ from: 10, to: 90})
    const follower = ref({ from: 10, to: 90})
    const emit = defineEmits(['update:filters'])
    const filteredCategories = computed(() => {
        if (!searchCategory.value) return categories.value
        return categories.value.filter(cat => 
            cat.label.includes(searchCategory.value.trim())
        )
    })
    onMounted(async () => {
        const categoriesFinder = await sendApi({action: "page_handler/category_handler", handler:'all_categories'});
        if(categoriesFinder.status==="success") {
            categories.value = categoriesFinder.data.map(cat => ({
                id: cat.id,
                label: cat.name
            }))            
        } else {
            console.warn('دریافت دسته‌بندی‌ها ناموفق بود:', categoriesFinder)
        }
    })
    const activeFilters = computed(() => {
        const filters = []
        selectedSoicalTypes.value.forEach(type =>filters.push({ id: type, label: getLabel(type, SoicalTypes), type: 'social' }))
        selectedCategories.value.forEach(cat =>filters.push({ id: cat, label: getLabel(cat, categories.value), type: 'category' }))
        selectedPaymentTypes.value.forEach(pay =>filters.push({ id: pay, label: getLabel(pay, PaymentTypes), type: 'payment' }))
        if (isChecked.value) filters.push({ id: 'onlySelected', label: 'فقط انتخاب شده‌ها', type: 'toggle' })
        if (isChecked1.value) filters.push({ id: 'onlySuitable', label: 'فقط مناسب شما', type: 'toggle' })
        if (isChecked2.value) filters.push({ id: 'withInsight', label: 'دارای اینسایت', type: 'toggle' })
        return filters
    })
    function removeFilter(filter) {
        if (filter.type === 'social') {
            selectedSoicalTypes.value = selectedSoicalTypes.value.filter(t => t !== filter.id)
        } else if (filter.type === 'category') {
            selectedCategories.value = selectedCategories.value.filter(c => c !== filter.id)
        } else if (filter.type === 'payment') {
            selectedPaymentTypes.value = selectedPaymentTypes.value.filter(p => p !== filter.id)
        } else if (filter.id === 'onlySelected') {
            isChecked.value = false
        } else if (filter.id === 'onlySuitable') {
            isChecked1.value = false
        } else if (filter.id === 'withInsight') {
            isChecked2.value = false
        }
    }
    function getLabel(id, list) {
        const item = list.find(i => i.id === id)
        return item ? item.label : id
    }
    function clearAllFilters() {
        selectedSoicalTypes.value = []
        selectedCategories.value = []
        selectedPaymentTypes.value = []
        isChecked.value = false
        isChecked1.value = false
        isChecked2.value = false
    }
    watch(
        () => props.ranges,
        (ranges) => {
            price.value = {
                from: ranges.priceRange.min,
                to: ranges.priceRange.max,
            }
            follower.value = {
                from: ranges.followerRange.min,
                to: ranges.followerRange.max,
            }
        },
        { immediate: true, deep: true }
    )
    watch(
        [
            isChecked,
            isChecked1,
            isChecked2,
            selectedCategories,
            selectedSoicalTypes,
            selectedPaymentTypes,
            price,
            follower,
        ],
        () => {
            emit('update:filters', {
                onlySelected: isChecked.value,
                onlySuitable: isChecked1.value,
                withInsight: isChecked2.value,
                categories: selectedCategories.value,
                socialTypes: selectedSoicalTypes.value,
                paymentTypes: selectedPaymentTypes.value,
                priceRange: price.value,
                followerRange: follower.value,
            })
        },
        { deep: true }
    )
</script>
<style scoped>
    .toole{
        border-radius:10px;
        background: lightgray;
        width: 100%;
        margin: 2px 0;
        padding: 0 10px;
        text-align: end;
    }
    .fillter-header{
        width: 100%;
        height: 20px;
        padding-top: 4px;
    }
    .fillter-header-title{
        font-size: 10px;
        width: calc(100% - 30px);
        float: right;
    }
    .fillter-header-delete{
        color: red;
        float: left;
        font-size: 7px;
        width: 30px;
        padding-bottom: 0;
        padding-top: 2px;
    }
    .fillter-header-delete span{
        position: relative;
        bottom: 3px;
    }
    .fillter {
        border: 1px solid gray;
        padding: 0 5px;
        height: 15px;
        border-radius: 5px;
        font-size: 9px;
        margin-left: 2px;
        display: inline-block;
    }
    .fillter a{
        color: red;
    }
    .toggle{
        float:right;
    }
    .label-text {
        font-weight: bold;
        font-size: 9px;
        margin-right: 3px;
    }
    .category,.soical-type,.type,.range-title{
        font-size: 10px;
        padding: 2px 0 1px 0;
    }
    #search-category {
        display: block;
        direction: rtl;
        font-size: 10px;
        height: 20px;
        border-radius: 10px;
        outline: none;
        border: none;
        padding: 1px 5px 0 5px;
        width: 98%;
        margin: 2px auto;
    }
    .category-inner,.soical-inner,.payment-inner{
        padding-top: 3px;
        width: 100%;
        gap: 2px;
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        direction: rtl;
        align-items: flex-start;
        justify-content: flex-start;
    }
</style>