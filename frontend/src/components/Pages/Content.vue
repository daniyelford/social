<template>
    <div class="main">
        <div class="top-tooles">
            <TopTooles @update:search="handleSearchUpdate" @update:sort="sortKey = $event" @exportExcel="handleExportExcel" />
        </div>
        <div class="content">
            <div class="right-tooles">
                <RightTooles @update:filters="(data) => filters = data" :ranges="ranges"/>
            </div>
            <div class="carts">
                <Telegram :filters="filters" :searchTerm="search" :sortKey="sortKey" @initFilterRanges="r => ranges = r" ref="telegramRef" />
            </div>
        </div>
      </div>
</template>
<script setup>
    import { ref } from 'vue'
    import TopTooles from '@/components/Pages/Contents/Tooles/TopTooles.vue';
    import RightTooles from '@/components/Pages/Contents/Tooles/RightTooles.vue';
    import Telegram from '@/components/Pages/Contents/Carts/Telegram.vue';
    const search = ref('')
    const sortKey = ref('')
    const telegramRef = ref(null)
    const filters = ref({
        onlySelected: false,
        onlySuitable: false,
        withInsight: false,
        categories: [],
        socialTypes: [],
        paymentTypes: [],
        priceRange: { from: 0, to: 1000 },
        followerRange: { from: 0, to: 100000 }
    })
    const ranges = ref({
        priceRange: { min: 0, max: 0 },
        followerRange: { min: 0, max: 0 }
    })
    function handleSearchUpdate(value) {
        search.value = value
    }
    function handleExportExcel() {
        telegramRef.value?.exportToExcel()
    }
</script>
<style scoped>
    .main {
        position: fixed;
        top: 60px;
        bottom: 0;
        right: 60px;
        left: 0;
    }
    .top-tooles {
        width: 95%;
        margin: 10px auto;
        box-shadow: 0 0 5px grey;
        height: 50px;
        padding: 0 10px;
        text-align: center;
        border-radius: 10px;
    }
    .content {
        width: 95%;
        margin: 0 auto;
        padding: 5px 0;
        height: calc(100% - 100px);
        overflow-y: auto;
        overflow-x: hidden;
    }
    .right-tooles {
        width: 25%;
        float: right;
        border-radius: 10px;
        box-shadow: 0 0 5px grey;
        padding: 5px;
        position: sticky;
        top: -80px;
    }
    .carts {
        width: 75%;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
</style>