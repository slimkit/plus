import request, { createRequestURI } from '../util/request'

class VendorRequestGenerator {

  uri

  constructor (uri) {
    this.uri = createRequestURI(uri)
  }

  get () {
    return request.get(this.uri, {
      validateStatus: status => status === 200
    })
  }

  update (settings = {}) {
    return request.put(this.uri, settings, {
      validateStatus: status => status === 204
    })
  }
}

export const easemob = new VendorRequestGenerator('setting/vendor/easemob')
export const qq = new VendorRequestGenerator('setting/vendor/qq')
export const wechat = new VendorRequestGenerator('setting/vendor/wechat')
export const weibo = new VendorRequestGenerator('setting/vendor/weibo')
export const wechatMp = new VendorRequestGenerator('setting/vendor/wechat-mp')

