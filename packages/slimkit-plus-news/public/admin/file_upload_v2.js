import MD5 from 'js-md5';
import { api, admin } from "./axios/";

// 监听文件上传事件
const fileUpload = (file, callback) => {
    if(typeof callback !== 'function') {
        throw new Error('callback is required');
    }
    let reader = new FileReader();
    reader.onload = function(e) {
        // MD5
        const hash = MD5(e.target.result);
        // 检测是否上传
        isUploaded(hash, file, callback);
    };
    reader.readAsArrayBuffer(file); // 读取文件流
};

// 检测文件是否存在
const isUploaded = (hash, file, callback) => {
    api.get(`/files/uploaded/${hash}`, {
        validateStatus: status => status === 404 || status === 200,
    }).then(({ status, data }) => {
        status === 200 ? callback(data.id) : uploadFile(file, callback);
    }).catch((error) => {
        if(error.response) {
            console.log(error.response);
        } else if(error.request) {
            console.log(error.request);
        } else {
            console.log('Error', error.message);
        }
    });
};

// 上传图片
const uploadFile = (file, callback) => {
    let param = new FormData();
    param.append('file', file);
    //  设置请求头
    let config = {
        headers: { 'Content-Type': 'multipart/form-data' }
    };
    api.post('/files', param, config).then(({ data: { id } }) => {
        id ? callback(id) : alert("图片上传失败！");
    }).catch((error) => {
        if(error.response) {
            console.log(error.response.data.message);
        } else if(error.request) {
            console.log(error.request);
        } else {
            console.log('Error', error.message);
        }
    });
};

export default fileUpload;