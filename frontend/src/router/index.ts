import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('@/views/Pages/BlankPage.vue'),
      meta: {
        title: 'Dashboard',
      },
    },
    {
      path: '/profile',
      name: 'Profile',
      component: () => import('@/views/User/UserProfile.vue'),
      meta: {
        title: 'Profile',
      },
    },
    {
      path: '/category',
      name: 'Category',
      component: () => import('@/views/Category/Categories.vue'),
      meta: {
        title: 'Category',
        requiresAuth: true,
      },
    },
    {
      path: '/product',
      name: 'Products',
      component: () => import('@/views/Product/Product.vue'),
      meta: {
        title: 'Product',
        requiresAuth: true,
      },
    },
    {
      path: '/error-404',
      name: '404 Error',
      component: () => import('@/views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
        requiresAuth: false,
      },
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/Auth/Login.vue'),
      meta: {
        title: 'Login',
        requiresAuth: false,
      },
    },
    {
      path: '/register',
      name: 'Register',
      component: () => import('@/views/Auth/Register.vue'),
      meta: {
        title: 'Resgister',
        requiresAuth: false,
      },
    },

    {
      path: '/test',
      name: 'Test',
      component: () => import('@/components/common/Test.vue'),
      meta: {
        title: 'Test',
        requiresAuth: false,
      },
    },
  ],
})

export default router

router.beforeEach((to, from, next) => {
  /**
   * Redirect to login page if user is not logged in and tries to access a page that requires authentication
   */
  if ((to.meta.requiresAuth !== false)
      && ! useAuthStore().isUserLoggedIn
    ) {
      next('/login')
  }

  /**
   * Redirect to dashboard if user is logged in and tries to access login page
   */
  if (to.path === '/login' 
      && useAuthStore().isUserLoggedIn
    ) {
    next('/')
  }

  next()
})
