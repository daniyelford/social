import { createRouter, createWebHistory } from 'vue-router'
import { sendApi } from '@/utils/api'
import Login from '@/Pages/Users/Login.vue'
import Dashboard from '@/Pages/Panel/Dashboard.vue'
import Telagram from '@/Pages/Tooles/Telagram.vue'
import Instagram from '@/Pages/Tooles/Instagram.vue'
const routes = [
  { path: '/', name: 'login', component: Login ,meta:{onlyAuth:true}},
  { path: '/dashboard', name: 'dashboard', component: Dashboard ,meta:{mustLogin:true}},
  {path: '/telegram',name: 'telegram',component: Telagram},
  {path: '/instagram',name: 'instagram',component: Instagram},
]
const router = createRouter({
  history: createWebHistory(),
  routes,
})
router.beforeEach(async (to, from, next) => {
  try {
    const meta = to.meta;
    if (meta.mustLogin || meta.onlyAuth){
      const res = await sendApi({action: "users_action/login_handler", handler:'check_auth'});
      if (meta.mustLogin && res.status === 'error'){
        return next('/');
      }
      if (meta.onlyAuth && res.status === 'success'){
        return next('/dashboard');
      }
    }
    next();
  } catch (e) {
    console.error('Router Guard Error:', e);
    next('/');
  }
});
export default router