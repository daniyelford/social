<template>
  <div>
    <h3 class="h3">اعتبار سنجی شماره موبایل</h3>
    <p v-if="error" class="error-message">{{ error }}</p>
    <form @submit.prevent="verifyCode">
      <label for="code">کد تایید:</label>
      <input
        id="code"
        v-model="code"
        type="text"
        maxlength="6"
        placeholder="کد ۶ حرفی"
        required
        class="input"
      />
      <button type="submit" :disabled="loading || code.length !== 6">{{ loading ? 'در حال بررسی...' : 'تایید کد' }}</button>
    </form>
    <button class="edit" @click="editPhone">ویرایش شماره</button>
    <button class="back" @click="$emit('back')">بازگشت به ورود</button>
  </div>
</template>
<script setup>
  import { ref } from 'vue'
  import { sendApi } from '@/utils/api'
  const props = defineProps({
    phone: String
  })
  const emit = defineEmits(['success', 'back','edit-phone'])
  const code = ref('')
  const error = ref('')
  const loading = ref(false)
  const editPhone = () => {
    localStorage.setItem('forgot_step', '1')
    emit('edit-phone') 
  }
  const verifyCode = async () => {
    error.value = ''
    loading.value = true
    try {
      const response = await sendApi({
        action: 'users_action/login_handler',
        handler: 'verifyResetCode',
        data: {
          phone: props.phone,
          token: code.value,
        }
      })
      if (response.status === 'success') {
        localStorage.setItem('forgot_step', '3')
        localStorage.setItem('reset_token', code.value)
        emit('success')
      } else {
        error.value = response.message || 'کد تایید نادرست است'
      }
    } catch {
      error.value = 'خطا در ارتباط با سرور'
    } finally {
      loading.value = false
    }
  }
</script>
<style scoped>
  .edit{
    background-color: #ff8837;
  }
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
        margin: 5px 0 20px 0;
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
