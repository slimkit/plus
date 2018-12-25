import axios from 'axios'
import api from './api'
import { hashFile } from '@/util/SendImage.js'
import i18n from '@/i18n'

// 新 axios 实例用于第三方请求
const localUploadInstance = axios.create()

/**
 * 创建上传任务
 * @param {File} file
 * @export
 */
export async function createTask (file) {
  return api.post('/storage', {
    filename: file.name,
    size: file.size,
    mime_type: file.type,
    storage: {
      channel: 'public',
    },
    hash: await hashFile(file),
  }, {
    validateStatus: s => s === 201,
  })
}

async function uploadByPut (task, file) {
  return localUploadInstance.put(task.uri, file, {
    headers: task.headers,
  })
}

async function uploadByPost (task, file) {
  return localUploadInstance.post(task.uri, file, {
    headers: task.headers,
  })
}

/**
 * 启动上传
 * @export
 * @param {File} file
 * @returns
 */
export default async function (file) {
  const { data: task } = await createTask(file)

  switch (task.method) {
    case 'PUT':
      await uploadByPut(task, file)
      break
    case 'POST':
      await uploadByPost(task, file)
      break
    default:
      throw new Error(i18n.t('upload.unsupported'))
  }

  return task.node
}
