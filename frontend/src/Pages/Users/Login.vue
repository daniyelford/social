<template>
  <div class="login-veiw">
    <div class="image">
      <img :src="banner" alt="banner" />
    </div>
    <div class="login-container">
      <img class="logo" :src="logo" alt="logo" />
      <LoginForm v-if="activeForm === 'login'" @switch-to-forgot="handleSwitchToForgot" />
      <ForgotPasswordForm v-else @switch-to-login="handleSwitchToLogin"/>
    </div>
  </div>
</template>
<script setup>
  import { ref } from 'vue'
  import LoginForm from '@/components/Users/Login/Login.vue'
  import ForgotPasswordForm from '@/components/Users/Login/Forgot.vue'
  import banner from '@/assets/images/banner.png'
  import logo from '@/assets/images/logo.png'
  const savedForm = localStorage.getItem('activeForm')
  const activeForm = ref(savedForm || 'login')
  const handleSwitchToForgot = () => {
    activeForm.value = 'forgot'
    localStorage.setItem('activeForm', 'forgot')
  }
  const handleSwitchToLogin = () => {
    activeForm.value = 'login'
    localStorage.setItem('activeForm', 'login')
  }
</script>
<style scoped>
  .login-veiw{
    width: 100%;
    margin-top: 60px;
  }
  .logo {
    width: 51%;
    margin: 0 26% 0 0;
  }
  .image {
    display: inline-block;
    width: 70% !important;
    margin: 30px auto;
  }
  .image img {
    width: 95%;
    height: 400px;
    margin: 0 0 0 2%;
    border-radius: 10px;
    box-shadow: 0 0 10px gray;
  }
  .login-container {
    max-width: 30%;
    height: 400px;
    float: right;
    direction: rtl;
    margin: 30px auto;
    padding: 20px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 10px gray;
  }
</style>