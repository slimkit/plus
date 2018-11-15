import http from "@/api/api.js";
// import wx from "weixin-js-sdk";

const getOauth = url => {
  return new Promise((resolove, reject) => {
    http
      .post(
        "socialite/wxconfig",
        {
          url
        },
        {
          validateStatus: s => s === 200
        }
      )
      .then(({ data }) => {
        resolove(data);
      })
      .catch(() => {
        reject(new Error());
      });
  });
};

export default {
  getOauth
};
