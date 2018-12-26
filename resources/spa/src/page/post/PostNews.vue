<template>
  <div class="m-box-model p-post-news">
    <CommonHeader>
      {{ title }}
      <template slot="left">
        <a v-if="step === 1" @click.prevent="cancel">{{ $t('cancel') }}</a>
        <svg
          v-else
          class="m-style-svg m-svg-def"
          @click="preStep"
        >
          <use xlink:href="#icon-back" />
        </svg>
      </template>
      <template slot="right">
        <a
          v-if="step != 3"
          :class="{ disabled }"
          class="m-send-btn"
          @click.prevent="nextStep"
        >
          {{ $t('next_step') }}
        </a>
      </template>
    </CommonHeader>

    <TransitionGroup
      :enter-active-class="animated.enterClass"
      :leave-active-class="animated.leaveClass"
      tag="main"
      class="m-box-model m-flex-grow1 m-flex-shrink1 p-post-news-main"
    >
      <template v-if="step === 1">
        <div
          key="step1"
          class="m-pos-f m-box-model m-flex-grow1 m-flex-shrink1 m-justify-center m-aln-center m-main"
          style="padding-left: 0.3rem; padding-right: 0.3rem"
        >
          <div class="m-box m-flex-grow0 m-shrink0 m-bb1 m-lim-width m-post-news-title">
            <input
              v-model.trim="news.title"
              class="m-lim-width"
              maxlength="20"
              type="text"
              :placeholder="$t('news.post.placeholder.title', [20])"
            >
          </div>
          <div class="m-box-model m-flex-grow1 m-flex-shrink1 m-lim-width m-post-news-content">
            <textarea
              ref="textarea"
              v-model.trim="contentText"
              :placeholder="$t('news.post.placeholder.body')"
            />
          </div>
        </div>
      </template>
      <template v-if="step === 2">
        <div key="step2" class="m-pos-f m-box-model m-flex-grow1 m-flex-shrink1">
          <div class="m-box m-aln-center m-lim-width m-post-news-row m-main m-bb1" @click="switchCate">
            <span class="m-post-news-row-label">{{ $t('news.post.label.category') }}</span>
            <div :class="{placeholder: !(category.id > 0)}" class="m-box m-flex-grow1 m-flex-shrink1 m-aln-center m-justify-end">
              <span>{{ category.name || $t('news.post.placeholder.category') }}</span>
              <svg class="m-style-svg m-svg-def m-entry-append">
                <use xlink:href="#icon-arrow-right" />
              </svg>
            </div>
          </div>
          <div class="m-box m-aln-center m-lim-width m-post-news-row m-main m-bb1" @click="switchTags">
            <span class="m-post-news-row-label">{{ $t('news.post.label.tags') }}</span>
            <div class="m-flex-grow1 m-flex-shrink1 m-text-r">
              <div v-if="tags.length > 0" class="m-tags">
                <span
                  v-for="tag in tags"
                  :key="tag.id"
                  class="m-tag"
                  v-text="tag.name"
                />
              </div>
              <div v-else class="m-box m-justify-end placeholder">
                <span>{{ $t('news.post.placeholder.tags', [5]) }}</span>
              </div>
            </div>
            <svg class="m-flex-grow0 m-flex-shrink0 m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right" />
            </svg>
          </div>
          <div class="m-box m-aln-center m-lim-width m-post-news-row m-main m-bb1">
            <span class="m-post-news-row-label">{{ $t('news.post.label.from') }}</span>
            <div class="m-box m-flex-grow1 m-flex-shrink1 m-aln-center m-justify-end">
              <input
                v-model.trim="news.from"
                type="text"
                dir="rtl"
                :placeholder="$t('news.post.placeholder.from')"
              >
            </div>
          </div>
          <div class="m-box m-aln-center m-lim-width m-post-news-row m-main m-bb1">
            <span class="m-post-news-row-label">{{ $t('news.post.label.author') }}</span>
            <div class="m-box m-flex-grow1 m-flex-shrink1 m-aln-center m-justify-end">
              <input
                v-model.trim="news.author"
                type="text"
                dir="rtl"
                :placeholder="$t('news.post.placeholder.author')"
              >
            </div>
          </div>
          <div class="m-box m-aln-center m-lim-width m-post-news-row m-main">
            <span class="m-post-news-row-label">{{ $t('news.post.label.subject') }}</span>
            <div class="m-box m-flex-grow1 m-flex-shrink1 m-aln-center m-justify-end">
              <TextareaInput
                v-model="news.subject"
                maxlength="200"
                warnlength="150"
                :placeholder="$t('news.post.placeholder.subject', [200])"
              />
            </div>
          </div>
        </div>
      </template>
      <template v-if="step === 3">
        <div key="step3" class="m-box-model m-flex-grow1 m-flex-shrink1 m-aln-center step3 m-main">
          <div
            :class="{ loading: poster.loading, error: poster.error }"
            class="m-box m-aln-center m-justify-center m-poster-box"
            @click="addPoster"
          >
            <img
              v-if="poster.src"
              :src="poster.src"
              class="m-poster"
              @load.stop="loadedPoster(poster)"
              @error="posterError"
            >
            <div v-else class="m-box-model m-aln-center m-justify-center m-lim-width m-poster-placeholder">
              <svg class="m-style-svg m-svg-big">
                <use xlink:href="#icon-camera" />
              </svg>
              <span>{{ $t('news.cover.tap_to_upload') }}</span>
            </div>
            <div v-if="!poster.error && poster.loading" class="fixed-loading">
              <div class="u-loading" style="height: 58px;width: 58px">
                <svg
                  class="loading"
                  width="100%"
                  height="100%"
                  viewBox="0 0 29 29"
                >
                  <circle
                    class="c1"
                    cx="14.5"
                    cy="14.5"
                    r="12.5"
                    fill="none"
                    stroke-width="4"
                    stroke="#b1b1b1"
                  />
                  <circle
                    class="c2"
                    cx="14.5"
                    cy="14.5"
                    r="12.5"
                    fill="none"
                    stroke-width="4"
                    stroke="#c7c7c7"
                  />
                </svg>
              </div>
            </div>

            <input
              id="selectimage"
              ref="imagefile"
              type="file"
              class="m-rfile"
              @change="selectPhoto"
            >
          </div>
          <p>{{ $t('news.cover.placeholder') }}</p>
          <button class="m-long-btn m-signin-btn" @click="handleOk">
            {{ newsPay ? 'news.post.submit_with_payment' : 'news.post.submit' | t }}
          </button>

          <PasswordConfirm ref="password" @submit="handlePostNews" />
        </div>
      </template>
    </TransitionGroup>
    <ChooseCate />
  </div>
</template>

<script>
import { mapState } from 'vuex'
import ChooseCate from '@/page/ChooseCate.vue'
import PasswordConfirm from '@/components/common/PasswordConfirm.vue'
import TextareaInput from '@/components/common/TextareaInput.vue'
import sendImage from '@/util/SendImage.js'
import * as api from '@/api/news.js'

export default {
  name: 'PostNews',
  components: {
    ChooseCate,
    PasswordConfirm,
    TextareaInput,
  },
  data () {
    return {
      scrollHeight: 0,
      step: 1,
      news: {
        title: '',
        subject: '',
        content: '',
        image: '',
        from: '',
        author: '',
        text_content: '',
      },
      tags: [],
      category: {},
      poster: {},
      animated: {
        enterClass: 'animated slideInRight',
        leaveClass: 'animated slideOutLeft',
      },
    }
  },
  computed: {
    ...mapState({
      newsConfig: state => state.CONFIG.news || {},
      newsPay: state => state.CONFIG.news.contribute.pay,
      newCurrency: state => state.CONFIG.news.pay_contribute,
      verified: state => state.CURRENTUSER.verified,
    }),
    currentCurrency () {
      const user = this.$store.state.CURRENTUSER
      return user.currency.sum || 0
    },
    canPostNews () {
      const { verified } = this.newsConfig.contribute || {}
      return !verified || (verified && this.verified)
    },
    contentText: {
      get () {
        return this.news.content
      },
      set (val) {
        val !== this.news.content && (this.news.content = val)
      },
    },
    shadowText () {
      return 'blank' + this.contentText
    },
    title () {
      const title = this.$t('news.post.title') // ['编辑文章', '完善文章信息', '上传封面']
      if (!this.step) return title[0]
      return title[this.step - 1]
    },
    disabled () {
      let result
      switch (this.step) {
        case 1:
          result = !(this.news.title && this.news.content)
          break
        case 2:
          result = !(this.category.id > 0 && this.tags.length > 0)
          break
        case 3:
          result = !(this.news.image > 0)
          break
      }
      return result
    },
  },
  created () {
    if (!this.canPostNews) {
      this.$Message.error(this.$t('certificate.need'))
      this.$router.go(-1)
    }
  },
  methods: {
    focusArea () {},
    moveCurPos () {},
    deleteHandler () {},
    switchCate () {
      this.$bus.$emit('choose-cate', cate => {
        this.category = cate
      })
    },
    switchTags () {
      const chooseTags = this.tags.map(t => t.id)
      this.$bus.$emit('choose-tags', {
        nextStep: tags => void (this.tags = tags),
        chooseTags,
      })
    },
    addPoster () {
      if (this.poster.loading) return
      this.$refs.imagefile.click()
    },
    selectPhoto () {
      const files = this.$refs.imagefile.files
      if (files && files.length > 0) {
        const posterObj = {
          loading: true,
          file: files[0],
          type: files[0].mimeType,
          src: window.URL.createObjectURL(files[0]),
        }
        this.poster = Object.assign(
          {
            id: '',
            src: '',
            type: '',
            file: null,
            error: false,
            loading: false,
          },
          posterObj
        )
      }
    },
    loadedPoster (poster) {
      const file = poster.file
      file &&
        sendImage(file)
          .then(id => {
            Object.assign(poster, {
              id,
              file: null,
              loading: !1,
              error: !1,
            })
          })
          .catch(() => {
            poster.error = !0
            poster.loading = false
          })
    },
    posterError () {
      this.$Message.error(this.$t('news.cover.upload_failed'))
    },
    handlePostNews (password) {
      const { title, content } = this.news
      const param = {
        title,
        content,
        tags: this.tags.map(t => t.id).join(','),
        password,
      }
      this.news.form && (param.form = this.news.form)
      this.poster.id > 0 && (param.image = this.poster.id)
      this.news.author && (param.author = this.news.author)
      this.news.subject && (param.subject = this.news.subject)

      api
        .postNews(this.category.id, param)
        .then(({ data }) => {
          this.$Message.success(data)
          this.goBack()
        })
        .catch(err => {
          this.$Message.error(err.response.data)
        })
    },
    showPasswordConfirm () {
      if (this.currentCurrency < this.amount) {
        this.$Message.error(this.$t('currency.insufficient'))
        this.cancel()
        return this.$router.push({ name: 'currencyRecharge' })
      }
      this.$refs.password.show()
    },
    handleOk () {
      const { title, content } = this.news
      if (!(title && content)) return (this.step = 1) && this.$Message.error(this.$t('news.post.need.title&content'))
      if (!this.category.id) return (this.step = 2) && this.$Message.error(this.$t('news.post.need.category'))
      if (this.tags.length === 0) return (this.step = 2) && this.$Message.error(this.$t('news.post.need.tags'))

      this.newsPay
        ? this.$bus.$emit('payfor', {
          title: this.$t('news.post.pay.title'),
          amount: this.newCurrency,
          content: this.$t('news.post.pay.title', { count: this.newCurrency, currencyUnit: this.currencyUnit }),
          confirmText: this.$t('news.post.pay.confirm'),
          cancelText: this.$t('news.post.pay.cancel'),
          onOk: () => {
            this.showPasswordConfirm()
          },
        })
        : this.handlePostNews()
    },
    preStep () {
      if (this.step <= 1) {
        this.animated = {
          enterClass: 'animated slideInLeft',
          leaveClass: 'animated slideOutRight',
        }
      }
      this.step -= 1
    },
    nextStep () {
      if (this.disabled) return
      if (this.step < 3) {
        this.animated = {
          enterClass: 'animated slideInRight',
          leaveClass: 'animated slideOutLeft',
        }
        this.step += 1
      }
    },
    cancel () {
      const actions = [
        { text: this.$t('confirm'), method: this.goBack },
      ]
      this.$bus.$emit(
        'actionSheet',
        actions,
        this.$t('cancel'),
        this.$t('news.post.draft_confirm')
      )
    },
  },
}
</script>

<style lang="less" scoped>
.p-post-news {
  min-height: 100vh;

  .m-poster-box {
    position: relative;
    background: #f4f5f5;
    width: 190 * 2px;
    height: 135 * 2px;
    border: 1px solid @border-color; /* no */
    margin-bottom: 50px;
    &.loading,
    &.error {
      img {
        opacity: 0.3;
      }
    }
    &.error:after {
      content: "上传失败, 请重试";
      color: @error;
      z-index: 9;
    }
  }
  .m-poster {
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    -o-object-fit: cover;
    object-fit: cover;
    -webkit-transition: all 0.4s ease;
    transition: all 0.4s ease;
  }
  .m-poster-placeholder {
    line-height: 1.5;
  }
  .step3 {
    padding: 0.8rem 0.3rem 0;
    color: #b3b3b3;
    p {
      margin-bottom: 80px;
    }
  }
  .p-post-news-main {
    > div {
      animation-duration: 0.3s;
    }
    .m-pos-f {
      top: 90px;
    }
  }
  .m-post-news-title {
    padding: 30px 20px;
    input {
      font-size: 30px;
      height: 100%;
      line-height: 36px;
    }
  }
  .m-post-news-content {
    overflow-y: auto;
    overflow-x: hidden;
    textarea {
      font-family: inherit;
      line-height: 36px;
      font-size: 30px;
      resize: none;
      position: absolute;
      left: 0;
      width: 100%;
      top: 0;
      bottom: 0;
      padding: 30px 20px;
    }
  }
  .m-post-news-row {
    font-size: 30px;
    padding: 40px 30px;

    .m-entry-append {
      margin-left: 10px;
    }
    &-label {
      flex: none;
      align-self: flex-start;
      width: 150px;
    }
    input {
      height: 100%;
      width: 100%;
      line-height: 30px;
      font-size: 28px;
    }

    .c-textarea-input {
      text-align: right;
    }
  }

  .placeholder {
    color: #ccc;
  }
}
</style>
