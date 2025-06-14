import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
// import VueRangeDual from 'vue-range-dual'
const app = createApp(App)
app.use(router)
// app.component('VueRangeDual', VueRangeDual)
app.mount('#app')