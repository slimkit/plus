/**
 * 空函数
 * 用于默认函数引用判断
 * @author mutoe <mutoe@foxmail.com>
 * @returns {Function} () => {}
 */
export const noop = () => {};

/**
 * 驼峰命名
 *     @author jsonleex <jsonlseex@163.com>
 */
export const str2Hump = str => {
  return str.replace(/-(\w)/g, ($0, $1) => {
    return $1.toUpperCase();
  });
};

/**
 * 获取样式
 *     @author jsonleex <jsonlseex@163.com>
 */
export const getStyle = (element, attr, NumberMode = "int") => {
  let target;
  if (attr === "scrollTop") {
    target = getScrollTop(element);
  } else if (element.currentStyle) {
    target = element.currentStyle[attr];
  } else {
    target = document.defaultView.getComputedStyle(element, null)[attr];
  }
  // 在获取 opactiy 时需要获取小数 parseFloat
  return NumberMode === "float" ? parseFloat(target) : parseInt(target);
};

/**
 * 获取 ScrollTop
 *   @author jsonleex <jsonlseex@163.com>
 */
export const getScrollTop = element => {
  if (element === window) {
    return Math.max(
      window.pageYOffset || 0,
      document.documentElement.scrollTop
    );
  }

  return element.scrollTop;
};

/**
 * 获取 当前滚动节点
 *   @author jsonleex <jsonlseex@163.com>
 */
export const getScrollEventTarget = element => {
  let currentNode = element;
  while (
    currentNode &&
    currentNode.tagName !== "HTML" &&
    currentNode.tagName !== "BODY" &&
    currentNode.nodeType === 1
  ) {
    let overflowY = document.defaultView.getComputedStyle(currentNode)
      .overflowY;
    if (overflowY === "scroll" || overflowY === "auto") {
      return currentNode;
    }
    currentNode = currentNode.parentNode;
  }
  return window;
};

/**
 * 判断终端类型
 *     @author jsonleex <jsonlseex@163.com>
 */
export const detectOS = () => {
  const u = navigator.userAgent;

  /* eslint-disable one-var */
  const isMobile = !!u.match(/AppleWebKit.*Mobile.*/),
    /* 移动端 */
    isIPad = u.indexOf("iPad") > -1,
    isIPhone = u.indexOf("iPhone") > -1,
    isWebApp = u.indexOf("Safari") === -1,
    isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
    isAndroid = u.indexOf("Android") > -1 || u.indexOf("Linux") > -1,
    isWechat = u.toLowerCase().indexOf("micromessenger") > -1,
    /* pc 端 */
    isIE = u.indexOf("Trident") > -1,
    isOpera = u.indexOf("Presto") > -1,
    isChorme = u.indexOf("AppleWebKit") > -1,
    isFirefix = u.indexOf("Gecko") > -1 && u.indexOf("KHTML") === -1;

  return {
    isMobile,
    isWechat,
    OS: (() => {
      if (isMobile) {
        if (isIOS) return "IOS";
        if (isIPad) return "iPad";
        if (isIPhone) return "IOS";
        if (isAndroid) return "Android";
      } else {
        if (isIE) return "IE";
        if (isOpera) return "Opera";
        if (isChorme) return "Chorme";
        if (isFirefix) return "Firefix";
      }
    })(),
    versions: (() => {
      return {
        /* 是否为移动终端 */
        mobile: isMobile,

        /* 是否为iPhone或者QQHD浏览器 */
        iPhone: isIPhone,
        /* android终端或uc浏览器 */
        android: isAndroid,

        /* ios终端 */
        ios: isIOS,
        /* 是否iPad */
        iPad: isIPad,

        /* 是否 web 程序，没有头部与底部 */
        webApp: isWebApp,

        /* IE内核 */
        trident: isIE,
        /* opera内核 */
        presto: isOpera,
        /* 苹果、谷歌内核 */
        webKit: isChorme,
        /* 火狐内核 */
        gecko: isFirefix
      };
    })(),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
  };
};

/**
 * 格式化数字
 *     @author jsonleex <jsonlseex@163.com>
 */
export const formatNum = num => {
  if (typeof ~~num === "number") {
    if (num === 0) return "0";
    var k = 1000,
      sizes = ["", "K", "M", "G", "T", "P", "E", "Z", "Y"],
      i = Math.floor(Math.log(num) / Math.log(k));

    return (num / Math.pow(k, i)).toPrecision(3) + " " + sizes[i];
  }
  return "0";
};

/**
 * create object url
 *     @author jsonleex <jsonlseex@163.com>
 */
export const getFileUrl = file => {
  let url = null;
  if (window.createObjectURL !== undefined) {
    // basic
    url = window.createObjectURL(file);
  } else if (window.URL !== undefined) {
    // mozilla(firefox)
    url = window.URL.createObjectURL(file);
  } else if (window.webkitURL !== undefined) {
    // webkit or chrome
    url = window.webkitURL.createObjectURL(file);
  }
  return url;
};

/**
 * 生成一个随机字符串 [0-9a-z]
 */
export const generateString = length => {
  if (length > 8) throw RangeError("Out of range in generateString.");
  return Math.random()
    .toString(36)
    .substr(2, length);
};
