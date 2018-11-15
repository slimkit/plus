// import autosize from 'autosize'
import { getStyle, getScrollTop, getScrollEventTarget } from "@/util/";
const autosize = el => {
  const originalHeight = el.style.height;
  el.style.height = "";
  let endHeight = el.scrollHeight;
  if (el.scrollHeight === 0) {
    el.style.height = originalHeight;
    return;
  }
  el.style.height = endHeight + "px";
};
export default {
  txtautosize: {
    inserted(el) {
      autosize(el);
    },
    update(el) {
      autosize(el);
    },
    unbind() {}
  },
  "load-more": {
    bind: (el, binding) => {
      let windowHeight = window.screen.height;
      let height;
      let setTop;
      let paddingBottom;
      let marginBottom;
      let requestFram;
      let oldScrollTop;
      let scrollEl;
      let heightEl;
      let scrollType = el.attributes.type && el.attributes.type.value;
      let scrollReduce = 2;
      if (scrollType === 2) {
        scrollEl = el;
        heightEl = el.children[0];
      } else {
        scrollEl = getScrollEventTarget(el);
        heightEl = el;
      }
      el.addEventListener(
        "touchstart",
        () => {
          height = heightEl.clientHeight;
          if (scrollType === 2) {
            // height = height
          }
          setTop = el.offsetTop;
          paddingBottom = getStyle(el, "paddingBottom");
          marginBottom = getStyle(el, "marginBottom");
        },
        false
      );

      el.addEventListener(
        "touchmove",
        () => {
          loadMore();
        },
        false
      );

      el.addEventListener(
        "touchend",
        () => {
          oldScrollTop = getScrollTop(scrollEl);
          moveEnd();
        },
        false
      );

      const moveEnd = () => {
        requestFram = requestAnimationFrame(() => {
          if (getScrollTop(scrollEl) !== oldScrollTop) {
            oldScrollTop = getScrollTop(scrollEl);
            moveEnd();
          } else {
            cancelAnimationFrame(requestFram);
            height = heightEl.clientHeight;
            loadMore();
          }
        });
      };

      const loadMore = () => {
        if (
          getScrollTop(scrollEl) + windowHeight >=
          height + setTop + paddingBottom + marginBottom - scrollReduce
        ) {
          binding.value();
        }
      };
    }
  }
};
