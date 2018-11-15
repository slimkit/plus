import http from "@/api/api.js";
export const signinByWechat = () => {
  const redirectUrl = window.location.origin + process.env.BASE_URL + "wechat/";
  http
    .post("socialite/getOriginUrl", {
      redirectUrl,
      validateStatus: s => s === 200
    })
    .then(({ data: { url = "" } = {} }) => {
      window.location.href = url;
    });
};
