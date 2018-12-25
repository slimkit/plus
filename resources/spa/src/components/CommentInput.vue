<template>
  <div @touchmove.prevent>
    <Transition name="toast">
      <div
        v-if="show"
        class="m-pop-box"
        @click="cancel"
      />
    </Transition>
    <Transition name="pop">
      <div
        v-if="show"
        class="c-comment-input"
        @touch.prevent
      >
        <span class="textarea-wrap">
          <textarea
            ref="textarea"
            v-model.trim="contentText"
            :placeholder="placeholder"
            :style="{ height: `${textareaHeight}px` }"
            maxlength="255"
            @blur="moveCurPos"
            @keydown.enter.prevent="sendText"
            @input="moveCurPos"
          />
          <textarea
            ref="shadow"
            :value="shadowText"
            :disabled="true"
            class="textarea-shadow"
            rows="1"
            maxlength="255"
          />
        </span>
        <div class="submit-wrap">
          <span v-if="contentText.length >= 210" class="content-length">{{ contentText.length }}/255</span>
          <button
            :disabled="!contentText.length"
            class="submit-btn"
            @click="sendText"
          >
            {{ $t('send') }}
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
export default {
  name: 'CommentInput',
  data () {
    return {
      curpos: 0,
      onOk: null,
      show: false,
      loading: false,
      scrollHeight: 0,
      contentText: '',
      placeholder: this.$t('comment_placeholder'),
    }
  },
  computed: {
    shadowText () {
      return 'blank' + this.contentText
    },
    fullContentText () {
      return this.contentText
    },
    textareaHeight () {
      return this.scrollHeight > 100 ? 100 : this.scrollHeight
    },
  },
  watch: {
    show (val) {
      if (val) {
        this.scrollTop = document.scrollingElement.scrollTop
        document.body.classList.add('m-pop-open')
        document.body.style.top = -this.scrollTop + 'px'

        const txt = this.$lstore.getData('H5_COMMENT_SAVE_CONTENT')
        if (txt) {
          this.contentText = txt.trim() || ''
          this.curpos = this.contentText.length
        }

        this.$nextTick(() => {
          this.$refs.textarea && this.$refs.textarea.focus()
        })
      } else {
        document.body.style.top = ''
        document.body.classList.remove('m-pop-open')
        document.scrollingElement.scrollTop = this.scrollTop
      }
    },
    contentText (val, oval) {
      if (val !== oval) {
        this.$lstore.setData('H5_COMMENT_SAVE_CONTENT', val)
        this.$nextTick(() => {
          this.$refs.shadow &&
            (this.scrollHeight = this.$refs.shadow.scrollHeight)
        })
      }
    },
  },
  created () {
    this.$bus.$on('commentInput:close', status => {
      status && this.clean()
      this.cancel()
    })
    this.$bus.$on('commentInput', ({ placeholder, onOk }) => {
      typeof placeholder === 'string' && (this.placeholder = placeholder)
      typeof onOk === 'function' && (this.onOk = onOk)
      this.show = true
      this.$nextTick(() => {
        this.$refs.shadow &&
          (this.scrollHeight = this.$refs.shadow.scrollHeight)
      })
    })
  },
  mounted () {
    this.$nextTick(() => {
      this.$refs.shadow && (this.scrollHeight = this.$refs.shadow.scrollHeight)
    })
  },
  destroyed () {
    this.clean()
  },
  methods: {
    moveCurPos () {
      this.$refs.textarea && (this.curpos = this.$refs.textarea.selectionStart)
    },
    clean () {
      this.contentText = ''
    },
    sendText () {
      if (this.loading) return
      this.loading = true

      this.onOk &&
        typeof this.onOk === 'function' &&
        this.onOk(this.contentText)

      this.cancel()
    },
    cancel () {
      this.placeholder = '随便说说~'
      this.loading = false
      this.onOk = null
      this.show = false
    },
  },
}
</script>

<style lang="less" scoped>
.c-comment-input {
  display: flex;
  justify-content: flex-end;
  align-items: flex-end;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  min-height: 100px;
  padding: 20px;
  background-color: @body-bg;
  border-top: 1px solid @border-color;/*no*/

  .textarea-wrap {
    flex: auto;
    display: flex;
    position: relative;
    font-size: 30px;
    word-wrap: break-word;

    textarea {
      line-height: 1.5;
      background-color: transparent;
      outline: 0;
      border: 0;
      border-bottom: 1px solid @primary; /*no*/
      font-size: inherit;
      resize: none;
      width: 100%;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
    }

    .textarea-shadow {
      position: absolute;
      opacity: 0;
      z-index: -1;
    }
  }

  .submit-wrap {
    display: flex;
    flex-direction: column;
    margin-left: 15px;

    .content-length {
      font-size: 20px;
      margin-bottom: 20px;
      color: #999;
    }

    .submit-btn {
      width: 100%;
      min-width: 90px;
      height: 50px;
      border-radius: 5px;
      background-color: @primary;
      font-size: 24px;
      color: #fff;

      &[disabled] {
        background-color: #cccccc;
      }
    }
  }
}
</style>
