<template>
  <!-- Root element of PhotoSwipe. Must have class pswp. -->
  <div
    class="pswp"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <!-- Background of PhotoSwipe.
      It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg" />
    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">
      <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
      <!-- don't modify these 3 pswp__item elements, data is added later on. -->
      <div class="pswp__container">
        <div class="pswp__item" />
        <div class="pswp__item" />
        <div class="pswp__item" />
      </div>
      <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
      <div class="pswp__ui pswp__ui--hidden">
        <div class="pswp__top-bar">
          <!--  Controls are self-explanatory. Order can be changed. -->
          <div class="pswp__counter" />
          <button
            class="pswp__button pswp__button--close"
            title="Close (Esc)"
          />
          <!-- <button class="pswp__button pswp__button--share" title="Share"></button> -->
          <!-- <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button> -->
          <!-- <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button> -->
          <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
          <!-- element will get class pswp__preloader--active when preloader is running -->
          <div class="pswp__preloader">
            <div class="pswp__preloader__icn">
              <div class="pswp__preloader__cut">
                <div class="pswp__preloader__donut" />
              </div>
            </div>
          </div>
        </div>
        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
          <div class="pswp__share-tooltip" />
        </div>
        <button
          class="pswp__button pswp__button--arrow--left"
          title="Previous (arrow left)"
        />
        <button
          class="pswp__button pswp__button--arrow--right"
          title="Next (arrow right)"
        />
        <div class="pswp__caption">
          <div class="pswp__caption__center" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import './style/pswp/pswp.min.less'

import PhotoSwipe from 'photoswipe'
import PhotoSwipeUI from 'photoswipe/dist/photoswipe-ui-default.js'

export default {
  name: 'PhotoSwipe',
  data () {
    return {
      photoswipe: null,
      component: null,
    }
  },
  created () {
    this.$bus.$on('mvGallery', ({ component, index, images }) => {
      this.component = component
      this.openPhotoSwipe(index, images)
    })
  },
  methods: {
    payForImg (currItem) {
      const { paid_node: node, amount, index } = currItem
      this.$bus.$emit('payfor', {
        onSuccess: data => {
          this.$Message.success(data)
          if (this.component) this.component.feed.images[index].paid = true
          this.photoswipe.currItem.paid = true
          this.updateImage(index, true)
        },
        nodeType: '图片',
        node,
        amount: amount,
      })
    },
    checkImage () {
      if (!this.photoswipe) return
      const currItem = this.photoswipe.currItem
      const { paid_node: node, paid, index, type } = currItem
      node > 0 &&
        type === 'read' &&
        (paid
          ? !currItem.updated && this.updateImage(index)
          : this.payForImg(currItem))
    },
    updateImage (index) {
      if (!this.photoswipe) return
      const items = this.photoswipe.items || []
      const currItem = items[index]
      if (currItem) {
        this.$http
          .get(`/files/${currItem.file}?json=1`)
          .then(({ data: { url } }) => {
            if (!url) return
            this.photoswipe.currItem.src = url
            this.photoswipe.currItem.updated = true
            this.photoswipe.invalidateCurrItems()
            this.photoswipe.updateSize()
            currItem.el.style.backgroundImage = `url(${url})`
          })
      }
    },
    openPhotoSwipe: function (index, images) {
      const options = {
        index,
        loop: false,
        history: true,
        arrowEl: false,
        tapToClose: true, // 点击关闭

        showHideOpacity: true,
        tapToToggleControls: false,

        maxSpreadZoom: 4,
        errorMsg:
          '<div class="pswp__error-msg"><a href="%url%" target="_blank">图片加载失败</a></div>',
      }
      this.photoswipe = new PhotoSwipe(this.$el, PhotoSwipeUI, images, options)
      this.photoswipe.listen('close', () => {
        this.$bus.$off('updatePhoto')
      })
      this.photoswipe.listen('beforeChange', () => {
        this.checkImage()
      })
      this.photoswipe.init()
    },
  },
}
</script>
