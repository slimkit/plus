<template>
  <div class="p-post-text m-box-model">
    <CommonHeader :pinned="true">
      {{ $t('release.feed') }}
      <template slot="left">
        <a href="javascript:;" @click="beforeGoBack">
          {{ $t('cancel') }}
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
          {{ $t('release.name') }}
        </a>
      </template>
    </CommonHeader>

    <main>
      <div class="content-wrap">
        <TextareaInput
          v-model="contentText"
          :maxlength="255"
          :warnlength="200"
          :rows="11"
        />
      </div>
      <div class="options">
        <TopicSelector v-model="topics" />

        <FormSwitchItem
          v-if="paycontrol"
          v-model="pinned"
          :label="$t('release.need_pay')"
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
      fromTopic: false,

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
        this.fromTopic = true
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
          text: this.$t('confirm'),
          method: () => void this.goBack(),
        },
      ]
      this.$bus.$emit('actionSheet', actions, this.$t('cancel'), this.$t('release.confirm_cancel'))
    },
    chooseDefaultAmount (amount) {
      this.customAmount = null
      this.amount = amount
    },
    beforePost () {
      if (this.pinned) {
        this.amount === 0
          ? this.$Message.error(this.$t('release.set_amount'))
          : this.contentText.length <= this.limit
            ? this.$Message.error(this.$t('release.text_limit', [this.limit]))
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
          this.$Message.success(this.$t('release.success'))
          if (this.fromTopic) return this.goBack()
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

  main {
    flex: auto;
    padding-top: 90px;

    .options {
      border-top: 1px solid @border-color;
    }

    .content-wrap {
      padding: 20px;
    }
  }
}
</style>
