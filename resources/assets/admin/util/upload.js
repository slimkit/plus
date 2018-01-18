import request, { createRequestURI, createAPI } from '../util/request';
import { plusMessageFirst } from '../filters';

/**
 * 文件上传.
 * @param  {Object}   file
 * @param  {Function} callback
 * @return {void}
 */
export function uploadFile(file, callback) {
    let param = new FormData();
    param.append('file', file);
    let reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = function(e) {
     request.post(
     	createAPI('files'), 
     	param, 
     	{ validateStatus: status => status === 201 })
      .then((response) => {
          callback(response.data.id);
      }).catch((error) => {
          window.alert(plusMessageFirst(error));
      });
    }
};