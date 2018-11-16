/**
 * ReadAsArrayBuffer
 * 通过文件头判断文件格式
 * @author jsonleex <jsonlseex@163.com>
 * @param  {[type]} file
 * @return {[type]}
 */
export function readAsArrayBuffer (file) {
  return new Promise(resolve => {
    const reader = new FileReader()
    reader.onloadend = event => {
      const uint8 = new Uint8Array(event.target.result).subarray(0, 4)
      let res = ''
      for (let i = 0; i < uint8.length; i++) {
        res += uint8[i].toString(16)
      }

      let mimeType = ''
      switch (res) {
        case '89504e47':
          mimeType = 'png'
          break
        case '47494638':
          mimeType = 'gif'
          break
        case '52494646':
          mimeType = 'webp'
          break
        default:
          res.indexOf('424d') === 0
            ? (mimeType = 'bmp')
            : res.indexOf('ffd8ffe') === 0 && (mimeType = 'jpeg')
      }
      file.mimeType = mimeType
      resolve(mimeType)
    }
    reader.readAsArrayBuffer(file)
  })
}
/**
 * Check image types
 * @author jsonleex <jsonlseex@163.com>
 * @param  {[type]} files
 * @return {[type]}
 */
export function checkImageType (files) {
  return new Promise((resolve, reject) => {
    const exts = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
    const blobs = []
    for (let index = 0; index < files.length; index++) {
      const fileName = files[index].name.split('.')
      if (fileName.length > 1) {
        const ext = fileName.pop().toLowerCase()
        exts.indexOf(ext) < 0
          ? reject(new Error('不支持的文件格式'))
          : (files[index].mimeType = ext)
      } else {
        blobs.push(readAsArrayBuffer(files[index]))
      }
    }
    resolve(files)
  })
}
