<template>
    <div class="search-tooles">
      <div class="search-plan">
        <input
          type="search"
          v-model="searchTerm"
          @input="onInput"
          id="search-plan"
          name="search"
          placeholder="جستجو در نام و یوزر ها"
        />
        <svg xmlns="http://www.w3.org/2000/svg" width="24px" fill="darkblue" height="24px" viewBox="0 -960 960 960"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
      </div>
      <a class="excel" @click="$emit('exportExcel')">
        خروجی اکسل مدیا پلن
      </a>
      <div id="sort">
        <select name="sort" class="sort" v-model="sortKey" @change="onSortChange">
          <option disabled value="">مرتب سازی بر اساس</option>
          <option value="followers">مرتب سازی بر اساس فالور</option>
          <option value="price">مرتب سازی بر اساس قیمت</option>
          <option value="influencerScore">مرتب سازی بر اساس اینفلونس</option>
        </select>
      </div>
  </div>
</template>
<script setup>
  import { ref } from 'vue'
  const searchTerm = ref('')
  const sortKey = ref('')
  const emit = defineEmits(['update:search', 'update:sort'])
  function onInput() {
    emit('update:search', searchTerm.value)
  }
  function onSortChange() {
    emit('update:sort', sortKey.value)
  }
</script>
<style scoped>
  .search-tooles{
    width: 100%;
    height: 100%;
  }
  .excel{
    cursor: pointer;
    float: left;
    margin: 10px 0;
    height: 30px;
    border: none;
    width: 20%;
    background: lightgrey;
    border-radius: 10px;
    padding: 4px;
    text-align: center;
  }
  #sort{
    float: left;
    margin: 10px 2%;
    height: 30px;
    width: 35%;
    direction: rtl;
    position: relative;
  }
  #sort::after {
    content: "▼";
    font-size: 12px;
    color: #333;
    position: absolute;
    top: 60%;
    left: 10px;
    transform: translateY(-50%);
    pointer-events: none;
  }
  .sort{
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    height: 100%;
    border: none;
    color: darkblue;
    background: lightgrey;
    border-radius: 10px;
    padding: 0 5px 0 5px;
    outline: none;

  }
  .search-plan{
    float: right;
    margin: 10px 0;
    height: 30px;
    width: 20%;
  }
  #search-plan{
    width: 100%;
    height: 100%;
    direction: rtl;
    border: none;
    background: lightgrey;
    border-radius: 10px;
    padding: 0 8px 0 0;
    outline: none;
  }
  .search-plan svg{
    position: relative;
    bottom: 27px;
    float: left;
    left: 2px;
  }

</style>