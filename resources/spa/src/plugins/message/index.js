import Message from './messageInstance'

const prefixCls = 'v-msg'
const prefixKey = 'v_message_key_'

const defaults = {
  duration: 3,
}

let messageInstance
let name = 1

const iconTypes = {
  info: 'info',
  success: 'success',
  warning: 'warning',
  error: 'error',
  loading: 'loading',
}

function getMessageInstance () {
  messageInstance = messageInstance || Message.newInstance()
  return messageInstance
}

function notice (
  type,
  content = '',
  closable = false,
  onClose = function () {},
  duration = defaults.duration
) {
  const iconType = iconTypes[type]

  let instance = getMessageInstance()

  instance.notice({
    content,
    styles: {},
    icon: iconType,
    type: 'message',
    onClose: onClose,
    closable: closable,
    duration: duration,
    transitionName: 'move-up',
    name: `${prefixKey}${name}`,
  })

  return (function () {
    let target = name++

    return function () {
      instance.remove(`${prefixKey}${target}`)
    }
  })()
}

export default {
  name: 'Message',

  info (options) {
    return this.message('info', options)
  },
  success (options) {
    return this.message('success', options)
  },
  warning (options) {
    return this.message('warning', options)
  },
  error (options) {
    return this.message('error', options)
  },
  loading (options) {
    return this.message('loading', options)
  },
  message (type, options) {
    if (typeof options === 'string') {
      options = {
        content: options,
      }
    }
    return notice(
      type,
      options,
      options.closable,
      options.onClose,
      options.duration
    )
  },
  config (options) {
    if (options.duration || options.duration === 0) {
      defaults.duration = options.duration
    }
  },
  destroy () {
    let instance = getMessageInstance()
    messageInstance = null
    instance.destroy(prefixCls)
  },
}
