import http from '../api/api.js'

export default redirect => {
  // 登录后跳转的url
  const redirectUrl =
    window.location.origin + '/wechatLogin?redirect=' + redirect
  http
    .post(
      'socialite/getOriginUrl',
      {
        redirectUrl,
      },
      {
        validateStatus: s => s === 200,
      }
    )
    .then(({ data: { url = '' } = {} }) => {
      window.location.href = url
    })
}
