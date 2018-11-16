// const REG_PSW = /^[0-9]{4,6}$/
// const REG_EMAIL = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
// const REG_PHONE = /^(((13[0-9]{1})|14[0-9]{1}|(15[0-9]{1})|17[0-9]{1}|(18[0-9]{1}))+\d{8})$/
// const REG_USER = /^[a-zA-Z_\u4E00-\u9FA5\uF900-\uFA2D][a-zA-Z0-9_\u4E00-\u9FA5\uF900-\uFA2D]*$/

const types = ['password', 'account']
const TEST = {
  password (psw) {
    const res = {}
    if (psw) {
      if (psw.length < 5) {
        res.r = false
        res.tips = '密码不少于6位'
      } else if (psw.length > 15) {
        res.r = false
        res.tips = '密码不多余15位'
      } else {
        res.r = true
        res.tips = '密码验证通过'
      }
    } else {
      res.r = false
      res.tips = '密码必能为空'
    }

    return res
  },
  // todo 用户账号 判断
  account (val) {
    const res = {}
    if (val) {
      res.r = true
      res.tips = '账号验证通过'
    }
    return res
  },
}

export function addValidate ({ type, rule }) {
  if (type && typeof type === 'string') {
    if (rule) {
      types.push(type)

      if (typeof rule === 'function') {
        TEST[type] = rule
      }

      if (rule instanceof RegExp) {
        TEST[type] = val => rule.test(val)
      }
    } else {
      throw new Error(
        '`rule` is required. Expected `[String, RegExp, Function]`'
      )
    }
  } else {
    throw new Error('`type` is required. Expected `String`')
  }
}

export default ({ val = '', type = '' }) => {
  if (types.indexOf(type) === -1) {
    return { r: false, tips: '`type` 参数错误' }
  } else {
    return TEST[type](val)
  }
}
