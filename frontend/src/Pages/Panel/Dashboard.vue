<template>
  <div class="nav">
    <NavMenu />
  </div>
  <div class="container">
    <div class="w-100">
      <div class="sidebar">
          <Sidebar />
      </div>
      <Content />
    </div>
  </div>
</template>
<script setup>
  import Sidebar from '@/components/Pages/Tooles/Sidebar.vue';
  import NavMenu from '@/components/Pages/Tooles/NavMenu.vue';
  import Content from '@/components/Pages/Content.vue';
</script>
<style scoped>
  .sidebar {
    position: fixed;
    top: 60px;
    right: 0;
    width: 60px;
    bottom: 0;
    box-shadow: 0 3px 5px grey;
  }
  .nav{
    position: fixed;
    top: 0%;
    left: 0%;
    right: 0;
    height: 60px;
    box-shadow: 0 0 15px gray;
    padding: 0 5px;
  }
  .container{
    position: fixed;
    top: 60px;
    left: 0;
    right: 0;
    bottom: 0;
  }
</style>