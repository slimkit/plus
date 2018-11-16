import http from '@/api/api.js'

const getOauth = url => {
  return http.post('socialite/wxconfig', { url }, { validateStatus: s => s === 200 })
}

export default {
  getOauth,
}
