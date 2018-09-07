export default function queryURL(url){
  var arr1 = url.split('?');
  var params = arr1[1].split('&');
  var obj = {};//声明对象
  for(var i=0;i<params.length;i++){
    var param = params[i].split('=');
    obj[param[0]] = param[1];//为对象赋值
  }

  return obj;
}