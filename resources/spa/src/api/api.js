import axios from "axios";
import { baseURL } from "./index";
import router from "@/routers";
import Message from "@/plugins/message-box";
import lstore from "@/plugins/lstore/lstore.js";

let cancel;
let pending = {};

const instance = axios.create({
  baseURL,
  headers: {
    "X-Requested-With": "XMLHttpRequest"
  }
});

//请求拦截器
instance.interceptors.request.use(
  config => {
    //发起请求时，取消掉当前正在进行的相同请求
    if (pending[config.url]) {
      pending[config.url]("操作取消");
      pending[config.url] = cancel;
    } else {
      pending[config.url] = cancel;
    }
    const TOKEN = lstore.getData("H5_ACCESS_TOKEN");
    if (TOKEN) {
      config.headers.Authorization = TOKEN;
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

//响应拦截器即异常处理
instance.interceptors.response.use(
  res => res,
  err => {
    const requireAuth = () => {
      setTimeout(() => {
        router.push({
          path: "/signin",
          query: { redirect: router.currentRoute.fullPath }
        });
      }, 500);
    };
    if (!axios.isCancel(err)) {
      if (err && err.response) {
        switch (err.response.status) {
          case 400:
            err.tips = "错误请求";
            break;
          case 401:
            err.tips = lstore.hasData("H5_CUR_USER")
              ? "登陆失效，请重新登录"
              : "请登录";
            lstore.clearData();
            requireAuth();
            break;
          case 403:
            err.tips = "拒绝访问";
            break;
          case 404:
            err.tips = "请求错误,未找到该资源";
            break;
          case 405:
            err.tips = "请求方法未允许";
            break;
          case 408:
            err.tips = "请求超时";
            break;
          case 422: {
            const { data } = err.response;
            err.tips = data || { message: "错误请求" };
            break;
          }
          case 500:
            err.tips = "服务器端出错";
            break;
          case 501:
            err.tips = "网络未实现";
            break;
          case 502:
            err.tips = "网络错误";
            break;
          case 503:
            err.tips = "服务不可用";
            break;
          case 504:
            err.tips = "网络超时";
            break;
          case 505:
            err.tips = "http版本不支持该请求";
            break;
          default:
            err.tips = `连接错误${err.response.status}`;
        }
      } else {
        err.tips = "网络不可用，请检查！";
      }
      Message.error(err.tips);
    }
    return Promise.reject(err);
  }
);

export default instance;
