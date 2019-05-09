import reload from '@/util/wechatShareForIOS.js'
import directives from '@/directives'

export default {
  data () {
    return {
      scrollable: true,
      windowScrollTop: 0,
      isCurrentView: false,
    }
  },
  directives,
  computed: {
    currentUser () {
      return this.$store.state.CURRENTUSER || {}
    },
    currencyUnit () {
      return this.$store.state.currency.unit
    },
    isIosWechat () {
      const ua = navigator.userAgent
      const wechatUA = ua.toLowerCase().match(/MicroMessenger/i)
      const isIos = ua.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)
      const isWechat =
        wechatUA !== null && wechatUA.toString() === 'micromessenger'
      return isIos && isWechat && this.$route.query.jxytime === undefined
    },
  },
  watch: {
    scrollable (val) {
      const el = document.scrollingElement
      if (val) {
        document.body.style.top = ''
        document.body.classList.remove('m-pop-open')
        el && (el.scrollTop = this.windowScrollTop)
      } else {
        document.body.classList.add('m-pop-open')
        this.windowScrollTop = el ? el.scrollTop : 0
        document.body.style.top = -this.windowScrollTop + 'px'
      }
    },
  },
  methods: {
    goBack () {
      const fallIndex = this.isIosWechat ? 2 : 1
      window.history.length <= fallIndex
        ? this.$router.replace('/')
        : this.$router.back()
    },
    /**
     * 定位到锚点
     * @param {string} selector
     */
    goAnchor (selector) {
      const anchor = this.$el.querySelector(selector)
      try {
        const rect = anchor.getBoundingClientRect()
        const scrollTop = document.documentElement.scrollTop
        document.scrollingElement.scrollTop = rect.top + scrollTop
      } catch (error) {
        // eslint-disable-next-line no-console
        console.warn('锚点定位失败: ', { selector, anchor, error })
      }
    },
    reload: reload,
    popupBuyTS () {
      this.$bus.$emit('popupDialog', {
        title: this.$t('tips'),
        content: this.$t('popup_buy_ts'),
      })
    },
  },
}
