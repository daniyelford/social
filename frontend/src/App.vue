<template>
  <router-view />
  <p class="powerBy">Powered By Advice Digital Agency</p>
</template>
<script>
  export default {
    name: "App"
  }
</script>
<style>
  .powerBy{
    text-align: center;
    color: #959492;
    font-size: 15px;
    position: fixed;
    bottom: 5px;
    left: 0;
    right: 0;
    background: transparent;
  }
</style>