import api from '@/api/api'

export const signinByWechat = () => {
  let redirectUrl = window.location.origin + process.env.BASE_URL
  if (process.env.VUE_APP_ROUTER_MODE === 'hash') redirectUrl += '#/'
  redirectUrl += 'wechat/'
  api
    .post(
      'socialite/getOriginUrl',
      { redirectUrl },
      { validateStatus: s => s === 200 }
    )
    .then(({ data: { url = '' } = {} }) => {
      window.location.href = url
    })
}
