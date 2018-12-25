<template>
  <Transition name="pop">
    <div
      v-if="show"
      class="c-report"
      @touchmove.prevent
    >
      <CommonHeader :back="cancel"> 举报 </CommonHeader>

      <main class="m-box-model m-aln-center m-justify-center">
        <div class="m-box-model m-lim-width m-main">
          <p class="m-pinned-amount-label">举报 <span class="primary">{{ username }}</span> {{ typeText ? `的${typeText}` : '' }}</p>
          <div class="reference">
            {{ reference }}
          </div>
          <TextareaInput
            v-model="reason"
            class="reason"
            maxlength="255"
            warnlength="200"
            placeholder="填写举报原因"
          />
        </div>
      </main>
      <div class="report-btn">
        <button
          :disabled="disabled || loading"
          class="m-long-btn"
          @click="report"
        >
          <CircleLoading v-if="loading" />
          <span v-else>举报</span>
        </button>
      </div>
    </div>
  </Transition>
</template>

<script>
import { noop } from '@/util'
import { reportFeed } from '@/api/feeds'
import { reportNews } from '@/api/news'
import { reportUser } from '@/api/user'
import { reportTopic } from '@/api/topic'
import { reportComment } from '@/api'
import TextareaInput from '@/components/common/TextareaInput'

const apiMap = {
  feed: reportFeed,
  news: reportNews,
  comment: reportComment,
  user: reportUser,
  topic: reportTopic,
}

export default {
  name: 'Report',
  components: { TextareaInput },
  data () {
    return {
      show: false,
      loading: false,
      reason: '',
      type: '',
      api: noop,
      callback: noop,
      payload: {},
      username: '',
      reference: '',
    }
  },
  computed: {
    disabled () {
      return !this.reason
    },
    typeText () {
      switch (this.type) {
        case 'feed':
          return this.$t('feed.name')
        case 'news':
          return this.$t('news.name')
        case 'comment':
        case 'postComment':
          return this.$t('comment.name')
        case 'topic':
          return this.$t('feed.topic.name')
        default:
          return ''
      }
    },
  },
  watch: {
    $route (to, from) {
      if (to !== from) this.cancel()
    },
  },
  created () {
    /**
     * 弹出举报窗口 (hooks -> report)
     * @author mutoe <mutoe@foxmail.com>
     * @param {Object} options
     * @param {string} options.type 举报的类型
     * @param {AxiosPromise} options.api 举报的 api，接受 axios promise 对象
     * @param {string|Object} options.payload api 的第一个参数，取决于 api
     * @param {string} options.username 被举报的用户名
     * @param {string} options.reference 被举报的内容
     * @param {requestCallback} [options.callback] 举报成功后的回调方法
     */
    this.$bus.$on('report', options => {
      this.type = options.type
      this.reason = options.reason
      this.callback = options.callback || noop
      this.payload = options.payload
      this.username = options.username
      this.reference = options.reference
      this.open()
    })
  },
  methods: {
    report () {
      if (this.loding || !this.type) return
      this.loading = true
      apiMap[this.type](this.payload, this.reason)
        .then(() => {
          this.$Message.success('举报成功')
          this.callback()
          this.$nextTick(this.cancel)
        })
        .catch(({ response: { data: message } }) => {
          message && this.$Message.error(message)
        })
        .finally(() => {
          this.loading = false
        })
    },
    resetProps () {
      this.type = ''
      this.reason = ''
      this.callback = noop
      this.payload = {}
      this.username = ''
      this.reference = ''
    },
    open () {
      this.show = true
      this.scrollable = false
    },
    cancel () {
      this.show = false
      this.scrollable = true
      this.resetProps()
    },
  },
}
</script>

<style lang="less" scoped>
.c-report {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f4f5f6;
  z-index: 200;

  main {
    padding: 20px;
    background-color: #fff;

    .reference {
      margin-bottom: 20px;
      padding: 20px;
      background-color: #f4f5f6;
      font-size: 26px;
    }

    .reason {
      padding: 20px;
      border: 1px solid @border-color; /* no */
      background-color: #f4f5f6;
    }
  }

  .report-btn {
    margin-top: 40px;
    padding: 0 20px;
  }
}
.m-pinned-row {
  font-size: 0.3rem;
  height: 1rem;
}
.plr20 {
  padding-left: 20px;
  padding-right: 20px;
}
</style>
