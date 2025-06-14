import { BASE_URL, API_SECRET_KEY } from '@/config'
import router from '@/router'
let tokenHandler = null,isLoggingOut = false;
async function getToken() {
  if (tokenHandler) return tokenHandler;
  tokenHandler = fetch(`${BASE_URL}/create_token`, {
    method: 'GET',
    credentials: 'include',
    headers: {
      'X-API-KEY': API_SECRET_KEY
    }
  }).then(res => res.json()).then(tokenJson => {
    if (!tokenJson.token || tokenJson.status === 'error') {
      tokenHandler=null;
      if (!isLoggingOut) {
        isLoggingOut = true;
        localStorage.clear();
        router.push('/');
      }
      throw new Error('Unauthorized');
    }else{
      return tokenJson.token;
    }
  }).catch(err => {
    tokenHandler = null;
    throw err;
  });
  return tokenHandler;
}
export async function sendApi(data = {}) {
  try {
    const token = await getToken();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const response = await fetch(`${BASE_URL}/check_token`, {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'X-API-KEY': API_SECRET_KEY,
        'X-XSRF-TOKEN': csrfToken,
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify(data)
    });
    const result = await response.json();
    if (result.code === 401 || result.message === 'توکن نامعتبر است') {
      tokenHandler = null;
      if (!isLoggingOut) {
        isLoggingOut = true;
        localStorage.clear();
        router.push('/');
      }
      throw new Error('Unauthorized');
    }
    tokenHandler = null;
    return result;
  } catch (err) {
    tokenHandler = null;
    console.error('API Error:', err);
    throw err;
  }
}