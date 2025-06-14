<template>
  <div>
    <h3 class="h3">تغییر رمز عبور</h3>
    <p v-if="error" class="error-message">{{ error }}</p>
    <form @submit.prevent="sendResetCode">
      <label for="phone">شماره موبایل:</label>
      <input
        id="phone"
        v-model="localPhone"
        type="tel"
        maxlength="11"
        pattern="[0-9]{11}"
        inputmode="numeric"
        placeholder="مثلاً 09123456789"
        required
        class="input"
      />
      <button type="submit" :disabled="!canSubmit">
        {{
          loading
            ? 'در حال ارسال...'
            : remainingTime > 0
            ? `ارسال مجدد (${remainingTime})`
            : 'ارسال کد تایید'
        }}
      </button>
    </form>
    <button class="back" @click="$emit('back')">بازگشت به ورود</button>
  </div>
</template>
<script setup>
  import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
  import { sendApi } from '@/utils/api'
  const props = defineProps({
    phone: String
  })
  const emit = defineEmits(['success', 'back'])
  const localPhone = ref(props.phone || '')
  const error = ref('')
  const loading = ref(false)
  const remainingTime = ref(0)
  let interval = null
  watch(localPhone, val => {
    const onlyNumbers = val.replace(/\D/g, '')
    if (onlyNumbers !== val) {
      localPhone.value = onlyNumbers
    }
    localStorage.setItem('forgot_phone', onlyNumbers)
  })
  const isPhoneValid = computed(() => /^[0-9]{11}$/.test(localPhone.value))
  const canSubmit = computed(() => {
    return isPhoneValid.value && !loading.value && remainingTime.value === 0
  })
  const startTimer = () => {
    const now = Date.now()
    const expireTime = now + 30000 
    localStorage.setItem('forgot_timer', expireTime)
    updateRemainingTime()
    interval = setInterval(updateRemainingTime, 1000)
  }
  const updateRemainingTime = () => {
    const expireTime = parseInt(localStorage.getItem('forgot_timer') || 0)
    const now = Date.now()
    const diff = Math.ceil((expireTime - now) / 1000)
    remainingTime.value = diff > 0 ? diff : 0

    if (remainingTime.value <= 0 && interval) {
      clearInterval(interval)
      interval = null
    }
  }
  onMounted(() => {
    updateRemainingTime()
    interval = setInterval(updateRemainingTime, 1000)
  })
  onUnmounted(() => {
    if (interval) clearInterval(interval)
  })
  const sendResetCode = async () => {
    error.value = ''
    loading.value = true
    try {
      const response = await sendApi({
        action: 'users_action/login_handler',
        handler: 'sendResetCode',
        data: { phone: localPhone.value }
      })
      if (response.status === 'success') {
        localStorage.setItem('forgot_step', '2')
        localStorage.setItem('forgot_phone', localPhone.value)
        emit('success', localPhone.value)
        startTimer()
      } else {
        error.value = response.message || 'خطا در ارسال کد تایید'
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
        margin: 15px 0 25px 0;
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
        margin: 10px 0 3px 0;
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