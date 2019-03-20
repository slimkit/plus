import Vue from 'vue'

export default new Vue({
  data () {
    return {
      gif: {
        feedId: null,
        elList: [],
        index: null,
        timer: null,
      },
    }
  },

  watch: {
    'gif.feedId' (val, oldId) {
      document.querySelectorAll('.playing > div').forEach(el => el.stop())
      clearTimeout(this.gif.timer)
      this.gif.index = null
      this.gif.timer = null
      this.$nextTick(() => {
        this.gif.index = 0
      })
    },
    'gif.index' (index) {
      if (index === null) return
      if (index >= this.gif.elList.length) {
        this.gif.index = 0
        return
      }
      this.gif.elList[index].play()
    },
  },
})
