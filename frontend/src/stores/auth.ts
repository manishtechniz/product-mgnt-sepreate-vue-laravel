import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAuthenticated: localStorage.getItem('token') ? true : false,
    isKeepLogin: false,
  }),

  actions: {
    login(user) {
      this.isAuthenticated = true

      if (! this.isKeepLogin) {
        sessionStorage.setItem('user', JSON.stringify(user));

        return;
      }

      localStorage.setItem('user', JSON.stringify(user))
    },

    logout() {
      this.isAuthenticated = false

      if (! this.isKeepLogin) {
        sessionStorage.removeItem('user');

        return;
      }

      localStorage.removeItem('user')
    },

    user() {
      if (! this.isKeepLogin) {
        return JSON.parse(sessionStorage.getItem('user'));
      }

      return JSON.parse(localStorage.getItem('user'));
    }
  }
})
