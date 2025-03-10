import axios from "axios";

window.axios = axios;

axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL // Change this to your Laravel API URL

// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest' // Identifies the request as AJAX

// axios.defaults.headers.common['Accept'] = 'application/json' // Ensures Laravel returns JSON responses

const token = JSON.parse(localStorage.getItem('user'))?.token || JSON.parse(sessionStorage.getItem('user'))?.token;

if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

export default axios;
