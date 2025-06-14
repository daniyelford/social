<template>
    <div class="loading" v-if="!isLoaded">
        در حال بارگذاری...
    </div>
    <div class="cart-inner" v-else-if="filteredCards.length>0">
        <div v-for="card in filteredCards" :key="card.id" class="cart">
            <div class="info">
                <div class="img">
                    <img v-if="card.photo_path" :src="card.photo_path|| './images/banner.png'" alt="social image">
                </div>
                <div class="name">
                    <p>{{ card.username }}</p>
                    <p>{{ card.title }}</p>
                </div>
            </div>
            <div class="follwer">
                <div class="count">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><rect fill="none" height="24" width="24"/><g><path d="M12,12.75c1.63,0,3.07,0.39,4.24,0.9c1.08,0.48,1.76,1.56,1.76,2.73L18,18H6l0-1.61c0-1.18,0.68-2.26,1.76-2.73 C8.93,13.14,10.37,12.75,12,12.75z M4,13c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2s-2,0.9-2,2C2,12.1,2.9,13,4,13z M5.13,14.1 C4.76,14.04,4.39,14,4,14c-0.99,0-1.93,0.21-2.78,0.58C0.48,14.9,0,15.62,0,16.43V18l4.5,0v-1.61C4.5,15.56,4.73,14.78,5.13,14.1z M20,13c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2s-2,0.9-2,2C18,12.1,18.9,13,20,13z M24,16.43c0-0.81-0.48-1.53-1.22-1.85 C21.93,14.21,20.99,14,20,14c-0.39,0-0.76,0.04-1.13,0.1c0.4,0.68,0.63,1.46,0.63,2.29V18l4.5,0V16.43z M12,6c1.66,0,3,1.34,3,3 c0,1.66-1.34,3-3,3s-3-1.34-3-3C9,7.34,10.34,6,12,6z"/></g></svg>
                    <p>
                        {{ card.followers }}
                    </p>
                </div>
                <div   class="inflowence">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M21,5.47L12,12L7.62,7.62L3,11V8.52L7.83,5l4.38,4.38L21,3L21,5.47z M21,15h-4.7l-4.17,3.34L6,12.41l-3,2.13L3,17l2.8-2 l6.2,6l5-4h4V15z"/></g></svg>
                    <p>
                        {{ card.influencerScore }}%
                    </p>
                </div>
            </div>
            <div class="category">
                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><rect fill="none" height="24" width="24"/><path d="M7.02,13c-2.21,0-4,1.79-4,4s1.79,4,4,4s4-1.79,4-4S9.23,13,7.02,13z M13,13v8h8v-8H13z M7,2l-5,9h10L7,2z M19.25,2.5 c-1.06,0-1.81,0.56-2.25,1.17c-0.44-0.61-1.19-1.17-2.25-1.17C13.19,2.5,12,3.78,12,5.25c0,2,2.42,3.42,5,5.75 c2.58-2.33,5-3.75,5-5.75C22,3.78,20.81,2.5,19.25,2.5z"/></svg>
                <span class="order-name">
                    {{ card.categoryName }}
                </span>
            </div>
            <div class="admin-info" v-if="card.admin">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 4c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1.4c0-2 4-3.1 6-3.1s6 1.1 6 3.1V19z"/></svg>
                <span class="order-name">
                {{ card.admin }}
                </span>
            </div>
            <div class="price">
                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M20,4H4C2.89,4,2.01,4.89,2.01,6L2,18c0,1.11,0.89,2,2,2h16c1.11,0,2-0.89,2-2V6C22,4.89,21.11,4,20,4z M12,10H8v1h3 c0.55,0,1,0.45,1,1v3c0,0.55-0.45,1-1,1h-1v1H8v-1H6v-2h4v-1H7c-0.55,0-1-0.45-1-1V9c0-0.55,0.45-1,1-1h1V7h2v1h2V10z M16,16.25 l-2-2h4L16,16.25z M14,10l2-2l2,2H14z"/></g></svg>
                <span class="order-name">
                    {{ formatPrice(card.price) }}
                </span>
            </div>
            <div class="time">
                <span>
                    زمان بروزرسانی تعرفه:
                </span>
                <span>
                    {{ moment(card.updated_at).format('jYYYY/jMM/jDD') }}
                </span>
            </div>
            <div class="choose">
                <a v-if="!card.selected" @click="select(card.id)">
                    انتخاب
                </a>
                <span class="choosen" v-else>
                    انتخاب شده
                </span>
            </div>
        </div>
    </div>
    <div class="none-cart-error" v-else>
        مدیا پلنی وجود ندارد
    </div>
</template>
<script setup>
    import { computed, onMounted ,ref } from 'vue'
    import moment from 'moment-jalaali'
    import * as XLSX from 'xlsx'
    import { sendApi } from '@/utils/api'
    const emit = defineEmits(['initFilterRanges'])
    const props = defineProps({
        filters: Object,
        searchTerm: String,
        sortKey: String
    })
    const isLoaded = ref(false)
    const cards = ref([])
    onMounted(async () => {
        const carts = await sendApi({action: "page_handler/telegram_cards", handler:'all_telegram_cards'});
        if(carts.status==="success") {
            console.log(carts);
            cards.value = carts.data.map(cat => ({
                id: cat.id,
                username: cat.username,
                title: cat.title,
                photo_path: cat.photo_path,
                category: cat.category,
                categoryName: cat.category_name ?? '',
                type: cat.type,
                paymentType: cat.payment_type,
                price: cat.price,
                followers: cat.followers, 
                insight: cat.insight,
                selected: cat.selected,
                suitable: cat.suitable,
                influencerScore: cat.influencer_score ?? 0,
                admin: cat.admin,
                updated_at: cat.updated_at
            }))
            isLoaded.value=true
            const minPrice = Math.min(...cards.value.map(c => c.price))
            const maxPrice = Math.max(...cards.value.map(c => c.price))
            const minFollower = Math.min(...cards.value.map(c => c.followers))
            const maxFollower = Math.max(...cards.value.map(c => c.followers))
            emit('initFilterRanges', {
                priceRange: { min: minPrice, max: maxPrice },
                followerRange: { min: minFollower, max: maxFollower }
            })
        } else {
            console.warn('دریافت دسته‌بندی‌ها ناموفق بود:', carts)
        }
    })
    const filteredCards = computed(() => {
        let result = (cards.value || []).filter(card => {
            const f = props.filters
            if (f) {
                if (f.onlySelected && !card.selected) return false
                if (f.onlySuitable && !card.suitable) return false
                if (f.withInsight && !card.insight) return false
                if (Array.isArray(f.categories) && f.categories.length > 0 && !f.categories.includes(card.category)) {
                    return false
                }
                if (Array.isArray(f.socialTypes) && f.socialTypes.length > 0 && !f.socialTypes.some(type => (card.type || '').includes(type))) {
                    return false
                }
                if (Array.isArray(f.paymentTypes) && f.paymentTypes.length > 0 && !f.paymentTypes.includes(card.paymentType)) {
                    return false
                }
                if (f.priceRange) {
                    if (card.price < f.priceRange.from || card.price > f.priceRange.to) return false
                }
                if (f.followerRange) {
                    if (card.followers < f.followerRange.from || card.followers > f.followerRange.to) return false
                }
            }
            if (props.searchTerm) {
                const term = props.searchTerm.toLowerCase()
                const username = (card.username || '').toLowerCase()
                const title = (card.title || '').toLowerCase()
                if (!username.includes(term) && !title.includes(term)) return false
            }
            return true
        })
        if (props.sortKey) {
            result = [...result].sort((a, b) => (b[props.sortKey] ?? 0) - (a[props.sortKey] ?? 0))
        }
        return result
    })
    async function select(id){
        const response = await sendApi({action: "page_handler/telegram_cards", handler:'select_card', data: id});
        if(response.status ==='success'){
            const selectedCard = cards.value.find(card => card.id === id)
            if (selectedCard) {
                selectedCard.selected = true
            }
        }else{
            alert('ارتباط با اینترنت قطع است');            
        }
    }
    function formatPrice(price) {
        if (!price && price !== 0) return '-'
        return new Intl.NumberFormat('fa-IR').format(price) + ' تومان'
    }
    function exportToExcel() {
        const exportData = filteredCards.value.map(card => ({
            یوزرنیم: card.username,
            عنوان: card.title,
            دسته‌بندی: card.categoryName,
            فالورها: card.followers,
            قیمت: card.price,
            ادمین: card.admin,
            امتیاز: card.influencerScore,
            تاریخ: card.updated_at
        }))
        const worksheet = XLSX.utils.json_to_sheet(exportData)
        const workbook = XLSX.utils.book_new()
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Telegram Cards')
        XLSX.writeFile(workbook, 'telegram_cards.xlsx')
    }
    defineExpose({
        exportToExcel
    })
</script>
<style scoped>
    .cart-inner{
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row-reverse;
        flex-wrap: wrap;
        align-content: flex-start;
        justify-content: flex-start;
        align-items: stretch;
    }
    .cart{
        width: 23%;
        height: 210px;
        margin: 0 1% 0 0;
        border-radius: 10px;
        box-shadow: 0 0 5px grey;
    }
    .info{
        margin-top: 7px;
    }
    .img {
        float: left;
        width: 40px;
        height: 40px;
        margin: 0 5px 0 15px;
    }
    .img img{
        width: 100%;
        height: 100%;
        border-radius: 50px;
    }
    .name{
        text-align: start;
        width: calc(100% - 60px);
        display: inline-block;
        padding: 5px;
    }
    .name p:first-child{
        font-size: 10px;
    }
    .name p:last-child{
        font-size: 12px;
    }
    .follwer{
        width: 100%;
        display: flex;
        margin-top: -3px;
        margin-bottom: 7px;
        height: 30px;
        flex-direction: row-reverse;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: stretch;
        text-align: center;
    }
    .count{
        padding-right: 10px;
    }
    .inflowence{
        padding-left: 10px;
    }
    .count,.inflowence{
        width: 50%;
        display: inline-block;
    }
    .count p,.inflowence p{
        margin-top: -12px;
        font-size: 12px;
    }
    .count svg,.inflowence svg{
        height: 20px;
        width: 20px;
        fill: grey;
    }
    .category,.admin-info,.price{
        height: 25px;
        text-align: center;
        width: 85%;
        margin: 2px auto;
        border-radius: 6px;
        background: lightgray;
    }
    .category svg,.admin-info svg,.price svg{
        width: 15px;
        float: left;
        height: 15px;
        margin: 5px;
        fill: gray;
    }
    .order-name{
        margin-top: 4px;
        font-size: 12px;
    }
    .time {
        width: 80%;
        margin: 0 auto;
    }
    .time span {
        font-size: 10px;
    }
    .time span:first-child{
        float: right;
        direction: rtl;
    }
    .time span:last-child{
        float: left;
    }
    .choose {
        width: 85%;
        margin: 0 auto;
    }
    .choosen{
        width: 100%;
        display: inline-block;
        background: grey;
        color: white;
        border-radius: 10px;
        height: 22px;
        text-align: center;
        font-size: 12px;
        padding-top: 3px;
    }
    .choose a{
        width: 100%;
        background: #00f;
        color: #fff;
        border-radius: 10px;
        display: inline-block;
        height: 22px;
        text-align: center;
        font-size: 12px;
        cursor: pointer;
    }
    .loading{
        width: 90%;
        text-align: center;
        background: rgb(166, 181, 228);
        margin: 0 auto;
        border-radius: 10px;
        color: white;
        font-size: 20px;
        height: 150px;
        padding-top: 55px;
    }
    .none-cart-error {
        width: 90%;
        text-align: center;
        background: red;
        margin: 0 auto;
        border-radius: 10px;
        color: white;
        font-size: 20px;
        height: 150px;
        padding-top: 55px;
    }
</style>