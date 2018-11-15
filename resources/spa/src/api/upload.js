import axios from "axios";
import api from "./api";

// 新 axios 实例用于第三方请求
const localUploadInstance = axios.create();

/**
 * 创建上传任务
 * @author mutoe <mutoe@foxmail.com>
 * @param {Object} payload
 * @param {string} payload.filename
 * @param {string} payload.hash
 * @param {number} payload.size
 * @param {string} payload.mime_type
 * @param {Object} payload.storage
 * @param {string} payload.storage.channel
 * @export
 */
export function createUploadTask(payload) {
  return api
    .post("/storage", payload, {
      validateStatus: s => s === 201
    })
    .then(({ data }) => {
      return data;
    });
}

/**
 * 启动上传任务
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} payload
 * @param {string} payload.method
 * @param {string} payload.url
 * @param {Object} payload.headers
 * @param {Blob} payload.blob
 * @returns
 */
export function uploadImage(payload) {
  return localUploadInstance
    .request({
      method: payload.method,
      url: payload.url,
      headers: payload.headers,
      data: payload.blob
    })
    .then(res => res.data);
}
