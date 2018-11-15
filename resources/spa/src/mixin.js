import reload from "@/util/wechatShareForIOS.js";

export default {
  data() {
    return {
      scrollable: true,
      windowScrollTop: 0,
      isCurrentView: false
    };
  },
  computed: {
    currencyUnit() {
      return this.$store.state.currency.unit;
    },
    isIosWechat() {
      const ua = navigator.userAgent;
      const wechatUA = ua.toLowerCase().match(/MicroMessenger/i);
      const isIos = ua.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
      const isWechat =
        wechatUA !== null && wechatUA.toString() === "micromessenger";
      return isIos && isWechat && this.$route.query.jxytime === undefined;
    }
  },
  watch: {
    scrollable(val) {
      const el = document.scrollingElement;
      if (val) {
        document.body.style.top = "";
        document.body.classList.remove("m-pop-open");
        el && (el.scrollTop = this.windowScrollTop);
      } else {
        document.body.classList.add("m-pop-open");
        this.windowScrollTop = el ? el.scrollTop : 0;
        document.body.style.top = -this.windowScrollTop + "px";
      }
    }
  },
  methods: {
    goBack(num = -1) {
      const fallIndex = this.isIosWechat ? 2 : 1;
      window.history.length <= fallIndex
        ? this.$router.replace("/")
        : this.$router.back(num);
    },
    reload: reload,
    popupBuyTS() {
      this.$bus.$emit("popupDialog", {
        title: "温馨提示",
        content:
          "开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。"
      });
    }
  }
};
