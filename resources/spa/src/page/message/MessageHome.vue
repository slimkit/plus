<template>
  <div class="p-message-home">
    <JoLoadMore
      ref="loadmore"
      :show-bottom="false"
      @onRefresh="fetchMessage"
    >
      <ul class="message-list m-box-model">
        <RouterLink
          v-for="item in system"
          :key="item.url"
          :to="item.url"
          tag="li"
          class="m-entry"
        >
          <svg class="m-style-svg m-svg-big m-entry-prepend m-flex-grow0 m-flex-shrink0">
            <use :xlink:href="`#icon-message-${item.icon}`" />
          </svg>
          <div class="m-box-model m-justify-bet m-flex-grow1 m-flex-shrink1 m-flex-base0 m-entry-main">
            <h2 class="m-text-cut">{{ item.title }}</h2>
            <p class="m-text-cut">{{ computedGetter(item.placeholder) }}</p>
          </div>
          <div class="m-box-model m-flex-grow0 m-flex-shrink0 m-entry-end m-justify-bet">
            <h5 v-if="computedGetter(item.time) !== '' && item.time">
              {{ +new Date((computedGetter(item.time))) + 10 || '' | time2tips }}
            </h5>
            <h5 v-else />
            <div class="m-box m-aln-center m-justify-end">
              <span v-if="computedGetter(item.count) !== 0" :class="`${prefixCls}-time-count`">
                {{ computedGetter(item.count) }}
              </span>
            </div>
          </div>
        </RouterLink>
      </ul>
    </JoLoadMore>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

const prefixCls = 'msg'

export default {
  name: 'MessageHome',
  data () {
    return {
      prefixCls,
      system: {
        system: {
          title: '系统消息',
          placeholder: 'sPlaceholder',
          icon: 'notice',
          hanBadge: 0,
          url: '/message/system',
          count: 'sCount',
          time: 'sTime',
        },
        comments: {
          title: '收到的评论',
          placeholder: 'cPlaceholder',
          icon: 'comment',
          hanBadge: 0,
          url: '/message/comments',
          count: 'cCount',
          time: 'cTime',
        },
        diggs: {
          title: '收到的赞',
          placeholder: 'dPlaceholder',
          icon: 'like',
          hanBadge: 0,
          url: '/message/likes',
          count: 'dCount',
          time: 'dTime',
        },
        audits: {
          title: '审核通知',
          placeholder: 'aPlaceholder',
          icon: 'audit',
          hanBadge: 0,
          url: '/message/audits/feedcomments',
          count: 'aCount',
        },
      },
    }
  },
  computed: {
    ...mapState({
      msg: state => state.MESSAGE.UNREAD_COUNT.msg,
      newMsg: state => state.MESSAGE.NEW_UNREAD_COUNT,
      sCount: state => state.MESSAGE.NEW_UNREAD_COUNT.system || 0,
    }),

    cPlaceholder () {
      return this.msg.comments.placeholder
    },
    dPlaceholder () {
      return this.msg.diggs.placeholder
    },
    aPlaceholder () {
      return this.aCount ? '你有未审核的信息请及时处理' : '暂无未审核的申请'
    },
    sPlaceholder () {
      return this.msg.system.placeholder
    },
    cTime () {
      return this.msg.comments.time
    },
    dTime () {
      return this.msg.diggs.time
    },
    sTime () {
      return this.msg.system.time
    },
    cCount () {
      return this.newMsg.commented || 0
    },
    dCount () {
      return this.newMsg.liked || 0
    },
    aCount () {
      return 0
    },
  },
  methods: {
    ...mapActions(['GET_NEW_UNREAD_COUNT']),
    async fetchMessage () {
      await this.GET_NEW_UNREAD_COUNT()
      this.$refs.loadmore.afterRefresh()
    },
    computedGetter (key) {
      return this[key]
    },
  },
}
</script>

<style lang="less" scoped>
.p-message-home {
  .message-list {
    background-color: #fff;
  }

  .m-entry {
    align-items: stretch;
    height: initial;
    padding: 30px 20px;
  }

  .m-entry-prepend {
    margin: 0;
    width: 76px;
    height: 76px;
  }

  .m-entry-main {
    margin-left: 30px;
    margin-right: 30px;
    h2 {
      font-weight: 400;
      font-size: 32px;
    }
    p {
      font-size: 24px;
      color: @text-color3;
    }
  }

  .m-entry-end {
    color: #ccc;
    font-size: 24px;
  }

  .msg-time-count {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 32px;
    height: 32px;
    border-radius: 32px;
    background: @error;
    color: #fff;
    font-size: 24px;
  }
}

</style>
