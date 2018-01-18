const codes = {
  1000: '手机号码格式不正确',
  1002: '用户名长度不正确，长度必须大于1个长度且小于12个长度',
  1003: '用户名格式不正确，不能特殊字符或者数字开头',
  1004: '用户名已经被使用',
  1010: '手机号码已经被使用'
};

export function code2message (code, defaultMessage) {
  const { [parseInt(code)]: message = defaultMessage } = codes;

  return message;
};

export default codes;
