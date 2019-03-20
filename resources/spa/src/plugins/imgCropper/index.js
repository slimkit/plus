import ImgCropper from './imgCropperInstance'

let ImgCropperInstance

function getImgCropperInstance () {
  ImgCropperInstance = ImgCropperInstance || ImgCropper.newInstance({})

  return ImgCropperInstance
}

ImgCropper.show = options => {
  let instance = getImgCropperInstance()

  options.onRemove = function () {
    ImgCropperInstance = null
  }

  instance.show(options)
}

ImgCropper.remove = () => {
  if (!ImgCropperInstance) {
    // at loading status, remove after Cancel
    return false
  }
  const instance = getImgCropperInstance()
  instance.remove()
}

export default {
  install (vue) {
    if (this.installed) return
    vue.prototype.$ImgCropper = ImgCropper
  },
}
