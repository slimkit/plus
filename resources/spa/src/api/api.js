import axios from 'axios'
import { baseURL } from './index'
import router from '@/routers'
import Message from '@/plugins/message-box'
import lstore from '@/plugins/lstore/lstore.js'
import i18n from '@/i18n'
import { plusMessageFirst } from '@/filters'

let cancel
let pending = {}

const instance = axios.create({
  baseURL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
})

// 请求拦截器
instance.interceptors.request.use(
  config => {
    // 开发环境静态资源重定向
    if (process.env.NODE_ENV === 'development') {
      config.url = config.url.replace(/^http:\/\/test-plus\.zhibocloud\.cn\/storage/, 'http://localhost:8080/storage')
    }
    // 发起请求时，取消掉当前正在进行的相同请求
    if (pending[config.url]) {
      pending[config.url](i18n.t('network.cancel'))
      pending[config.url] = cancel
    } else {
      pending[config.url] = cancel
    }
    const TOKEN = lstore.getData('H5_ACCESS_TOKEN')
    if (TOKEN) {
      config.headers.Authorization = TOKEN
    }
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// 响应拦截器即异常处理
instance.interceptors.response.use(
  res => res,
  err => {
    const requireAuth = () => {
      setTimeout(() => {
        router.push({
          path: '/signin',
          query: { redirect: router.currentRoute.fullPath },
        })
      }, 500)
    }
    if (!axios.isCancel(err)) {
      if (err && err.response) {
        switch (err.response.status) {
          case 400:
            err.tips = i18n.t('network.error.e400')
            break
          case 401:
            err.tips = lstore.hasData('H5_CUR_USER')
              ? i18n.t('network.error.e401_expire')
              : i18n.t('network.error.e401')
            lstore.clearData()
            requireAuth()
            break
          case 403:
            err.tips = i18n.t('network.error.e403')
            break
          case 404:
            err.tips = i18n.t('network.error.e404')
            break
          case 405:
            err.tips = i18n.t('network.error.e405')
            break
          case 408:
            err.tips = i18n.t('network.error.e408')
            break
          case 422: {
            const { data } = err.response
            err.tips = plusMessageFirst(data, i18n.t('network.error.e422'))
            break
          }
          case 500:
            err.tips = i18n.t('network.error.e500')
            break
          case 501:
            err.tips = i18n.t('network.error.e501')
            break
          case 502:
            err.tips = i18n.t('network.error.e502')
            break
          case 503:
            err.tips = i18n.t('network.error.e503')
            break
          case 504:
            err.tips = i18n.t('network.error.e504')
            break
          case 505:
            err.tips = i18n.t('network.error.e505')
            break
          default:
            err.tips = i18n.t('network.error.default', [err.response.status])
        }
      } else {
        err.tips = i18n.t('network.error.disconnect')
      }
      Message.error(err.tips)
    }
    return Promise.reject(err)
  }
)

export default instance
