<template>
  <h3 class="h3">ورود به ادوایس</h3>
  <div v-if="errorMessage" class="error-message">
    {{ errorMessage }}
  </div>
  <form @submit.prevent="submitLogin">
    <div>
      <label for="phone">شماره موبایل:</label>
      <input
        class="input"
        id="phone"
        v-model="phone"
        type="tel"
        maxlength="11"
        pattern="[0-9]{11}"
        placeholder="مثلاً 09123456789"
        required
        inputmode="numeric"
      />
    </div>
    <div>
      <label for="password">رمز عبور:</label>
      <input
        class="input"
        id="password"
        v-model="password"
        type="password"
        placeholder="رمز عبور"
        required
      />
    </div>
    <button type="submit" :disabled="!canSubmit">
      {{ loading ? 'در حال ورود...' : 'ورود' }}
    </button>
  </form>
  <div class="forgot-password">
    <a href="#" @click.prevent="$emit('switch-to-forgot')">بازیابی رمز عبور</a>
  </div>
</template>
<script setup>
    import { ref, watch, onMounted ,computed } from 'vue'
    import { sendApi } from '@/utils/api'
    import router from '@/router'
    const phone = ref('')
    const password = ref('')
    const loading = ref(false)
    const errorMessage = ref('')
    const isPhoneValid = computed(() => {
    const regex = /^[0-9]{11}$/
      return regex.test(phone.value)
    })
    const isPasswordValid = computed(() => {
      return password.value.length > 0
    })
    const canSubmit = computed(() => {
      return isPhoneValid.value && isPasswordValid.value && !loading.value
    })
    onMounted(() => {
        const savedPhone = localStorage.getItem('login_phone')
        const savedPassword = localStorage.getItem('login_password')
        if (savedPhone) phone.value = savedPhone
        if (savedPassword) password.value = savedPassword
    })
    watch(phone, val => {
      const onlyNumbers = val.replace(/\D/g, '')
      if (val !== onlyNumbers) phone.value = onlyNumbers
      localStorage.setItem('login_phone', onlyNumbers)
    })
    watch(password, val => localStorage.setItem('login_password', val))
    const submitLogin = async () => {
        errorMessage.value = ''
        loading.value = true
        try {
            const response = await sendApi({
                action: 'users_action/login_handler',
                handler: 'login',
                data: {
                    phone: phone.value,
                    password: password.value,
                },
            })
            if (response.status === 'success') {
                localStorage.removeItem('login_phone')
                localStorage.removeItem('login_password')
                localStorage.removeItem('activeForm')
                localStorage.setItem('isLogin',true)
                window.dispatchEvent(new Event("storage"))
                router.push('/dashboard')
              } else {
                errorMessage.value = response.message || 'خطا در ورود'
            }
        } catch (err) {
            errorMessage.value = 'خطا در اتصال به سرور'
        } finally {
            loading.value = false
        }
    }
</script>
<style scoped>
    .input{
        background: #d3d3d385;
        width: 100%;
        padding: 8px;
        outline: none;
        margin: 5px 0;
        box-sizing: border-box;
        border: none;
        border-radius: 5px;
        height: 40px;
    }
    .h3{
        text-align: center;
        padding-bottom: 10px;
        font-weight: bold;
    }
    .forgot-password a {
        color: rgb(44, 41, 41);
        background: #e8e8e8;
        font-weight: bold;
        text-decoration: none;
        font-size: 16px;
        width: 100%;
        display: inline-block;
        padding: 8px;
        margin-top: 5px;
        border-radius: 10px;
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
    .error-message {
        padding: 5px;
        color: white;
        background: red;
        border-radius: 10px;
        margin-bottom: 5px;
        text-align: center;
    }
</style>