/**
 *  图片懒加载, 支持背景图片
 */

import _ from "lodash";
import api from "@/api/api.js";

/**
 * 绑定事件
 * @author jsonleex <jsonlseex@163.com>
 * @param  {Object -> DOM}        el
 * @param  {String}               type
 * @param  {Function}             func
 * @param  {Boolean}              capture
 */
function onEvent(el, type, func, capture = false) {
  el.addEventListener(type, func, {
    capture: capture
  });
}

export default Vue => {
  // 图片监听列表
  const listenList = new Map();

  // 图片缓存列表
  const imageCatche = new Map();

  const wW = window.innerWidth;
  const wH = window.innerHeight;

  /**
   * 判断节点是否在视口内
   * @author jsonleex <jsonlseex@163.com>
   * @param  {DOM} el
   * @return {Boolean}
   */
  const checkInView = el => {
    const rect = el.getBoundingClientRect();

    /**
     * 以三分之一屏高为偏移量
     */
    return (
      rect.top < wH * 1.3 &&
      rect.bottom > 1.3 &&
      (rect.left < wW * 1.3 && rect.right > 0)
    );
  };

  const isCanShow = item => {
    const { el, file, q = 40, w, h } = item;
    const isIMG = el.nodeName === "IMG";
    if (checkInView(el)) {
      let image = new Image();
      el.classList.add("loading");
      /**
       * 获取图片真实链接
       */
      api
        .get(`/files/${file}`, {
          // 验证 status ; 屏蔽 404 抛错
          vaildateStatus: s => s === 200 || s === 201 || s === 204 || s === 400,
          params: { json: 1, q: w > 4096 || h > 4096 ? undefined : q }
        })
        .then(({ data: { url } }) => {
          if (url) {
            image.src = url;

            image.onload = () => {
              isIMG
                ? (el.src = url)
                : (el.style.backgroundImage = `url(${url})`);

              listenList.delete(file);
              imageCatche.set(file, url);

              el.classList.remove("loading");

              image = null;
            };

            image.onerror = () => {
              listenList.delete(file);
              el.classList.remove("loading");
              el.classList.add("error");
            };
          }
        });
      return true;
    } else {
      return false;
    }
  };
  const addListener = (el, binding) => {
    const file = binding.value;
    const isIMG = el.nodeName === "IMG";

    // 检查图片是否已加载过
    if (imageCatche.has(file.file))
      return isIMG
        ? (el.src = imageCatche.get(file.file))
        : (el.style.backgroundImage = `url(${imageCatche.get(file.file)})`);

    const item = {
      el,
      nodeName: el.nodeName,
      error: false,
      loading: true,
      ...file
    };

    // 检查图片是否刚好在视口内
    if (isCanShow(item)) return;

    listenList.set(file.file, item);
  };

  // 注册 vue 自定义指令
  Vue.directive("async-image", {
    inserted: addListener,
    updated: addListener
  });

  /**
   * 绑定滚动监听, 兼容 ios 和 pc
   */
  [
    "scroll",
    "wheel",
    "mousewheel",
    "resize",
    "animationend",
    "transitionend",
    "touchmove"
  ].forEach(evt =>
    onEvent(
      window,
      evt,
      _.debounce(() => {
        listenList.forEach(img => {
          isCanShow(img);
        });
      }, 1e3)
    )
  );
};
