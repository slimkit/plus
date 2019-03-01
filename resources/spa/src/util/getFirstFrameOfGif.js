const URL = window.URL || window.webkitURL

function dataURLtoBlob (dataURL) {
  const arr = dataURL.split(',')
  const mime = arr[0].match(/:(.*?);/)[1]
  const bstr = atob(arr[1])
  let n = bstr.length
  const u8arr = new Uint8Array(n)
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n)
  }
  return new Blob([u8arr], { type: mime })
}

export default (file, type = 'dataURL') => {
  return new Promise(resolve => {
    const image = new Image()

    image.onload = () => {
      const width = image.width
      const height = image.height

      const canvas = document.createElement('canvas')

      canvas.width = width
      canvas.height = height
      // 绘制图片帧（第一帧）
      canvas.getContext('2d').drawImage(image, 0, 0, width, height)
      const dataURL = canvas.toDataURL('image/jpeg', 0.5)
      switch (type) {
        case 'dataURL':
          return resolve(dataURL)
        case 'blob':
          return resolve(dataURLtoBlob(dataURL))
      }
    }

    image.src = URL.createObjectURL(file)
  })
}
