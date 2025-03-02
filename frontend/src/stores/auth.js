import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isKeepLogin: false,
  }),

  actions: {
    login(user) {
      if (! this.isKeepLogin) {
        sessionStorage.setItem('user', JSON.stringify(user));

        return;
      }
      console.log("Local Storage");
      localStorage.setItem('user', JSON.stringify(user))
    },

    logout() {
      sessionStorage.removeItem('user');

      localStorage.removeItem('user');
    },

    user() {
        return JSON.parse(sessionStorage.getItem('user')) || JSON.parse(localStorage.getItem('user'))
    },

    isUserLoggedIn() {
      return !! this.user();
    }
  }
})
