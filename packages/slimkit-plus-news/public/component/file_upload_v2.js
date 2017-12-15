import MD5 from 'js-md5';
import request, { createAPI } from '../util/request.js';

// 用户TOKEN 后台分发
const TOKEN = window.NEWS.token;

// 配置请求头 用户认证 "Bearer" + TOKEN
// request.defaults.headers.common['Authorization'] = 'Bearer' + ' ' + TOKEN;
request.defaults.headers.common['Authorization'] = TOKEN;

function readAsArrayBuffer(file) {
  return new Promise(function(resolve) {
    let reader = new FileReader();
    reader.onload = function(e) {
      resolve(e.target.result);
    };
    reader.readAsArrayBuffer(file); // 读取文件流
  });
}

// 监听文件上传事件
function fileUpload(file, callback) {
  readAsArrayBuffer(file)
    .then(buffer => {
      // 计算MD5
      return MD5(buffer);
    }).then((hash) => {
      // 检测文件是否已存在
      isUploaded(hash, file, callback);
    });

  // 返回DataURL 用于页面显示图片
  return new Promise((resolve) => {
    let reader = new FileReader();
    reader.onload = function(e) {
      resolve(e.target.result);
    };
    reader.readAsDataURL(file);
  })
};

// 检测文件是否存在
function isUploaded(hash, f, callback) {
  request.get(`/api/v2/files/uploaded/${hash}`)
    .then((res) => {
      if (res.status === 200) return callback(res.data.id);
    })
    .catch(function(error) {
      if (error.response) {
        console.log(error.response);
        error.response.status === 404 ? uploadFile(f) : alert("程序出错，前往控制台查看相关错误信息！");
        return;
      } else if (error.request) {
        console.log(error.request);
      } else {
        console.log('Error', error.message);
      }
    });
};

// 上传图片
function uploadFile(file) {
  let param = new FormData();
  param.append('file', file);
  //  设置请求头
  let config = {
    headers: { 'Content-Type': 'multipart/form-data' }
  };
  request.post('/api/v2/files', param, config)
    .then((res) => {
      if (res.status === 201) return callback(res.data.id);
    })
    .catch(function(error) {
      if (error.response) {
        console.log(error.response.status);
      } else if (error.request) {
        console.log(error.request);
      } else {
        console.log('Error', error.message);
      }
    });
}
export default fileUpload;
