<template>
  <div>
    <ForgotStep1_SendCode
      v-if="step === 1"
      :phone="phone"
      @success="goToStep2"
      @back="backToLogin"
    />
    <ForgotStep2_VerifyCode
      v-if="step === 2"
      :phone="phone"
      @success="goToStep3"
      @back="backToLogin"
      @edit-phone="step = 1"
    />
    <ForgotStep3_ResetPassword
      v-if="step === 3"
      :phone="phone"
      @reset-complete="resetFlow"
      @back="backToLogin"
    />
  </div>
</template>
<script setup>
  import { ref, watch, onMounted } from 'vue'
  import ForgotStep1_SendCode from './Forgot/Step1.vue'
  import ForgotStep2_VerifyCode from './Forgot/Step2.vue'
  import ForgotStep3_ResetPassword from './Forgot/Step3.vue'
  const step = ref(1)
  const phone = ref('')
  const emit = defineEmits(['switch-to-login'])
  onMounted(() => {
    const savedStep = localStorage.getItem('forgot_step')
    const savedPhone = localStorage.getItem('forgot_phone')
    if (savedStep) step.value = parseInt(savedStep)
    if (savedPhone) phone.value = savedPhone
  })
  watch(step, val => localStorage.setItem('forgot_step', val))
  watch(phone, val => localStorage.setItem('forgot_phone', val))
  const goToStep2 = (value) => {
    phone.value = value
    step.value = 2
  }
  const goToStep3 = () => {
    step.value = 3
  }
  const resetFlow = () => {
    phone.value = ''
    step.value = 1
    localStorage.removeItem('forgot_step')
    localStorage.removeItem('forgot_phone')
    localStorage.removeItem('activeForm')
    localStorage.removeItem('reset_token')
    emit('switch-to-login')
  }
  const backToLogin = () => {
    resetFlow()
  }
</script>