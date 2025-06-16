<template>
  <nav class="navbar">
    <div class="user-info">
      <a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="#701919" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
      </a>
      <span class="span">{{ userName }}</span>
      <a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="#cbcb00" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
      </a>
      <a href="#" @click.prevent="logout">
        <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
      </a>
      <router-link v-if="userRule==='admin'" to="./telegram">telegram</router-link>
    </div>
    <div class="logo"><img :src="logo" alt="logo" /></div>
  </nav>
</template>
<script>
  import { sendApi } from '@/utils/api'
  import logo from '@/assets/images/logo.png'
  export default {
    name: 'NavMenu',
    data() {
      return {
        userName: '',
        userRule: '',
        logo
      }
    },
    async mounted() {
      this.getUserInfo()
    },
    methods: {
      async getUserInfo() {
        try {
          const info = await sendApi({ action: 'users_action/login_handler', handler: 'get_info' })
          if (info.status === 'success') {
            this.userName = info.name
            this.userRule = info.rule
          }
        } catch (e) {
          console.error("خطا در گرفتن اطلاعات کاربر", e)
        }
      },
      async logout() {
        const res = await sendApi({ action: 'users_action/login_handler',handler:'logout' });
        if (res.status === 'success') {
          localStorage.removeItem("isLogin")
          window.dispatchEvent(new Event("storage"))
          this.isLoggedIn = false
          this.$router.push("/")
        } else {
          alert('خطا در خروج از حساب');
        }
      }
    }
  }
</script>
<style scoped>
  .navbar {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px;
  }
  .user-info {
    display: flex;
    gap: 1rem;
    align-items: center;
  }
  .logo img {
    height: 38px;
    margin-top: 6px;
    margin-right: 15px;
  }
  .span{
    font-size: 22px;
    margin: -7px 6px 0 -7px;
  }
  .user-info a{
    padding-top: 9px;
    margin-top: -9px;
  }
  .user-info a svg{
    margin-top: 4px;
    height:40px;
    width:40px;
  }
</style>
