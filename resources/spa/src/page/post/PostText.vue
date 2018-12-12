<template>
  <div class="p-post-text m-box-model">
    <CommonHeader :pinned="true">
      发布动态
      <template slot="left">
        <a href="javascript:;" @click="beforeGoBack">
          取消
        </a>
      </template>
      <template slot="right">
        <CircleLoading v-if="loading" />
        <a
          v-else
          :class="{ disabled }"
          class="m-send-btn"
          @click.prevent.stop="beforePost"
        >
          发布
        </a>
      </template>
    </CommonHeader>

    <main>
      <div class="text-content">
        <TextareaInput
          v-model="contentText"
          :maxlength="255"
          :warnlength="200"
          :rows="11"
          class="textarea-input"
        />
      </div>
      <div class="options">
        <TopicSelector v-model="topics" />

        <FormSwitchItem
          v-if="paycontrol"
          v-model="pinned"
          label="是否收费"
          @click.capture.stop.prevent="popupBuyTS"
        />
      </div>
    </main>
  </div>
</template>

<script>
import TopicSelector from './components/TopicSelector'
import TextareaInput from '@/components/common/TextareaInput.vue'

export default {
  name: 'PostText',
  components: {
    TextareaInput,
    TopicSelector,
  },
  data () {
    return {
      loading: false,
      contentText: '',
      curpos: 0,
      scrollHeight: 0,
      pinned: false,
      topics: [],

      amount: 0,
      customAmount: null,

      appBackgroundColor: null,
    }
  },
  computed: {
    paycontrol () {
      return this.$store.state.CONFIG.feed.paycontrol
    },
    disabled () {
      return !this.contentText.length
    },
    items () {
      return this.$store.state.CONFIG.feed.items || []
    },
    limit () {
      return this.$store.state.CONFIG.feed.limit || 50
    },
  },
  watch: {
    customAmount (val) {
      if (val) this.amount = ~~val
    },
  },
  created () {
    this.queryTopic()
  },
  mounted () {
    this.contentText = ''
  },
  methods: {
    queryTopic () {
      const { topicId, topicName } = this.$route.query
      if (topicId) {
        this.topics.push({
          id: topicId,
          name: topicName,
          readonly: true,
        })
      }
    },
    beforeGoBack () {
      if (this.contentText.length === 0) return this.goBack()
      const actions = [
        {
          text: '确定',
          method: () => void this.goBack(),
        },
      ]
      this.$bus.$emit('actionSheet', actions, '取消', '你还有没有发布的内容,是否放弃发布?')
    },
    chooseDefaultAmount (amount) {
      this.customAmount = null
      this.amount = amount
    },
    beforePost () {
      if (this.pinned) {
        this.amount === 0
          ? this.$Message.error('请设置收费金额')
          : this.contentText.length <= this.limit
            ? this.$Message.error(`正文内容不足${this.limit}字, 无法设置收费`)
            : this.postText()
      } else {
        this.amount = 0
        this.postText()
      }
    },
    postText () {
      if (this.loading) return
      this.loading = true

      const mark = new Date().valueOf() + '' + this.$store.state.CURRENTUSER.id
      this.$http
        .post(
          'feeds',
          {
            feed_content: this.contentText,
            feed_from: 2,
            feed_mark: mark,
            amount: this.amount,
            topics: this.topics.map(item => item.id),
          },
          { validateStatus: s => s === 201 }
        )
        .then(() => {
          this.$router.replace('/feeds?type=new&refresh=1')
        })
        .finally(() => {
          this.loading = false
        })
    },

  },
}
</script>

<style lang="less" scoped>
.p-post-text {
  background-color: #fff;
  height: 100%;

  main {
    flex: auto;
    padding-top: 90px;

    .options {
      border-top: 1px solid @border-color;
    }

    .textarea-input {
      padding-top: 20px;
      padding-left: 20px;
    }

  }
}
</style>
