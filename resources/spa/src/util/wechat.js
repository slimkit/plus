import api from '@/api/api'

export const signinByWechat = () => {
  const redirectUrl = window.location.origin + process.env.BASE_URL + 'wechat/'
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
