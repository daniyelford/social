<template>
  <div>
    <h3 class="h3">تغییر رمز عبور</h3>
    <p v-if="error" class="error-message">{{ error }}</p>
    <form @submit.prevent="resetPassword">
      <label for="password">رمز عبور جدید:</label>
      <input
        class="input"
        id="password"
        v-model="password"
        type="password"
        placeholder="رمز عبور جدید"
        required
      />
      <label for="confirmPassword">تکرار رمز عبور:</label>
      <input
        class="input"
        id="confirmPassword"
        v-model="confirmPassword"
        type="password"
        placeholder="تکرار رمز عبور"
        required
      />
      <button type="submit" :disabled="loading || !password || !confirmPassword || password !== confirmPassword">{{ loading ? 'در حال ذخیره...' : 'ذخیره رمز عبور' }}</button>
    </form>
    <button class="back" @click="$emit('back')">بازگشت به ورود</button>
  </div>
</template>
<script setup>
  import { ref } from 'vue'
  import { sendApi } from '@/utils/api'
  const props = defineProps({
    phone: String
  })
  const emit = defineEmits(['reset-complete', 'back'])
  const password = ref('')
  const confirmPassword = ref('')
  const error = ref('')
  const loading = ref(false)
  const token = localStorage.getItem('reset_token')
  const resetPassword = async () => {
    error.value = ''
    if (password.value !== confirmPassword.value) {
      error.value = 'رمز عبور و تکرار آن مطابقت ندارند'
      return
    }
    loading.value = true
    try {
      const response = await sendApi({
        action: 'users_action/login_handler',
        handler: 'resetPassword',
        data: {
          phone: props.phone,
          password: password.value,
          password_confirmation: confirmPassword.value,
          token:token
        }
      })
      if (response.status === 'success') {
        localStorage.removeItem('forgot_step')
        localStorage.removeItem('forgot_phone')
        localStorage.removeItem('activeForm')
        localStorage.removeItem('reset_token')
        emit('reset-complete')
      } else {
        error.value = response.message || 'خطا در ذخیره رمز عبور'
      }
    } catch {
      error.value = 'خطا در ارتباط با سرور'
    } finally {
      loading.value = false
    }
  }
</script>
<style scoped>
  .back{
    color: rgb(44, 41, 41);
    background: #e8e8e8;
  }
  .h3{
    text-align: center;
    padding-bottom: 10px;
    font-weight: bold;
  }
  .input{
        outline: none;
        background: #d3d3d385;
        width: 100%;
        padding: 8px;
        margin: 5px 0 15px 0;
        box-sizing: border-box;
        border: none;
        border-radius: 5px;
        height: 40px;
    }
  .error-message {
        padding: 5px;
        color: white;
        background: red;
        border-radius: 10px;
        margin-bottom: 5px;
        text-align: center;
    }
    button {
        width: 100%;
        padding: 10px;
        margin: 2px 0;
        background-color: #1452a1;
        color: white;
        font-weight: bolder;
        font-size: 18px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }
    button:disabled {
        background-color: #8ba9dc;
        cursor: not-allowed;
    }
</style>