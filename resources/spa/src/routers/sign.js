import i18n from '@/i18n'

import Forgot from '@/page/sign/Forgot.vue'
import ChangePassword from '@/page/sign/ChangePassword.vue'
import Signup from '@/page/sign/Signup.vue'
import Signin from '@/page/sign/Signin.vue'
import SigninDynamic from '@/page/sign/SigninDynamic.vue'
import RegisterProtocol from '@/page/sign/RegisterProtocol.vue'

export default [
  {
    path: '/signin',
    component: Signin,
    meta: {
      title: i18n.t('auth.login'),
      forGuest: true,
    },
  },
  {
    path: '/signin/dynamic',
    component: SigninDynamic,
    meta: {
      title: i18n.t('auth.one_key'),
      forGuest: true,
    },
  },
  {
    path: '/signup',
    component: Signup,
    meta: {
      title: i18n.t('auth.register.name'),
      forGuest: true,
    },
  },
  {
    path: '/signup/protocol',
    component: RegisterProtocol,
    meta: {
      title: i18n.t('auth.register.protocol'),
    },
  },
  {
    path: '/forgot',
    component: Forgot,
    meta: {
      title: i18n.t('auth.forgot.name'),
    },
  },
  {
    path: '/changePassword',
    component: ChangePassword,
    meta: {
      title: i18n.t('auth.change_password.name'),
      requiresAuth: true,
    },
  },
]
