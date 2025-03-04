import { defineStore } from 'pinia'
import axois from '@/plugins/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isKeepLogin: false,
    user: (JSON.parse(sessionStorage.getItem('user'))
          || JSON.parse(localStorage.getItem('user')) 
          || null
        )
  }),

  getters: {
    isUserLoggedIn: (state) => !! state.user,
    token: (state) => state.user?.token,
  },

  actions: {
    save(data) {
      this.user = data;

      if (! this.isKeepLogin) {
        sessionStorage.setItem('user', JSON.stringify(data));

        return;
      }
      console.log("Local Storage");
      localStorage.setItem('user', JSON.stringify(data))
    },

    update(newData) {
      this.save({...this.user, ...newData});
    },

    setToken() {
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.user.token}`;
    },

    logout() {
      this.user = null;

      sessionStorage.removeItem('user');

      localStorage.removeItem('user');
    },
  }
})
