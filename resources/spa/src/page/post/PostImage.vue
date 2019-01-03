<template>
  <div class="p-post-image">
    <CommonHeader>
      {{ $t('release.feed') }}
      <template slot="left">
        <a
          class="m-send-btn"
          href="javascript:;"
          @click="beforeGoBack"
        >
          {{ $t('cancel') }}
        </a>
      </template>
      <template slot="right">
        <CircleLoading v-if="loading" />
        <a
          v-else
          :class="{ disabled }"
          class="m-send-btn"
          @click.prevent.stop="sendmessage"
        >
          {{ $t('release.name') }}
        </a>
      </template>
    </CommonHeader>

    <main>
      <div class="content-wrap">
        <TextareaInput
          v-model="contentText"
          :rows="11"
          :maxlength="255"
          :warnlength="200"
          :placeholder="$t('release.image_placeholder')"
        />
      </div>
      <ImageList :edit="pinned" style="padding: 0 .3rem .3rem" />

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
import { mapGetters } from 'vuex'
import ImageList from './components/ImageList'
import TopicSelector from './components/TopicSelector'
import TextareaInput from '@/components/common/TextareaInput.vue'

export default {
  name: 'PostImage',
  components: {
    ImageList,
    TextareaInput,
    TopicSelector,
  },
  data () {
    return {
      pinned: false,

      curpos: 0,
      loading: false,
      contentText: '',
      topics: [],
      fromTopic: false,
      scrollHeight: 0,
    }
  },
  computed: {
    ...mapGetters(['composePhoto']),
    disabled () {
      const imageAllCompleted = !this.composePhoto.some(
        img => Object.keys(img).length === 0
      )
      return !(imageAllCompleted && this.composePhoto.length > 0)
    },
    paycontrol () {
      return this.$store.state.CONFIG.feed.paycontrol
    },
  },
  created () {
    this.queryTopic()
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
      this.contentText.length > 0
        ? this.$bus.$emit(
          'actionSheet',
          [
            {
              text: this.$t('confirm'),
              method: () => {
                this.goBack()
              },
            },
          ],
          this.$t('cancel'),
          this.$t('release.confirm_cancel')
        )
        : this.goBack()
    },
    sendmessage () {
      if (!this.disabled) {
        this.loading = true
        // 检测是否存在上传失败的图片
        if (this.composePhoto.some(item => Object.keys(item).length === 0)) {
          this.$Message.error(this.$t('release.has_failed_upload'))
          this.loading = false
          return
        }
        this.$http
          .post(
            'feeds',
            {
              feed_content: this.contentText,
              images: this.composePhoto,
              feed_from: 2,
              feed_mark:
                new Date().valueOf() + '' + this.$store.state.CURRENTUSER.id,
              topics: this.topics.map(topic => topic.id),
            },
            {
              validateStatus: s => s === 201,
            }
          )
          .then(() => {
            this.$Message.success(this.$t('release.success'))
            if (this.fromTopic) return this.goBack()
            this.$router.replace('/feeds?type=new&refresh=1')
          })
          .catch(err => {
            this.$Message.error(err.response.data)
          })
          .finally(() => {
            this.loading = false
          })
      }
    },
  },
}
</script>

<style lang="less" scoped>
.p-post-image {
  background-color: #fff;

  main {
    .content-wrap {
      padding: 20px;
    }
  }

  footer {
    flex: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 10;
  }
}
</style>
