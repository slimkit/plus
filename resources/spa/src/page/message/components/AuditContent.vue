<template>
  <div :class="`${prefixCls}-item-bottom`">
    <span
      v-if="commentBody"
      class="content"
    >
      {{ commentBody }}
    </span>
    <section
      v-if="!commentableDel"
      @click.capture.stop="goToDetail()"
    >
      <div
        v-if="!image && !video"
        :class="`${prefixCls}-item-bottom-noImg`"
        class="content"
      >
        {{ content }}
      </div>
      <div
        v-else
        :class="`${prefixCls}-item-bottom-img`"
      >
        <div class="img">
          <AsyncFile
            v-if="image && type !== 'group'"
            :file="image.file"
          >
            <img
              slot-scope="props"
              :src="props.src"
            >
          </AsyncFile>
          <img
            v-if="type === 'group'"
            :src="image"
          >
          <img
            v-if="video"
            :src="video"
          >
        </div>
        <div class="content">{{ content }}</div>
      </div>
    </section>
    <section v-if="commentableDel">
      <div
        :class="`${prefixCls}-item-bottom-noImg`"
        class="content"
      >
        {{ $t('article.deleted') }}
      </div>
    </section>
  </div>
</template>

<script>
const prefixCls = 'msgList'
const detailUrl = {
  feed: '/feeds/',
  group: '/groups/',
  news: '/news/',
}
export default {
  name: 'AuditContent',
  props: {
    audit: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    prefixCls,
  }),
  computed: {
    image () {
      const avatar = this.audit.image || {}
      return avatar.url || null
    },
    commentBody () {
      return this.audit.commentBody || ''
    },
    content () {
      return this.audit.content || ''
    },
    commentDel () {
      return this.audit.commentDel
    },
    commentableDel () {
      return this.audit.commentableDel
    },
    video () {
      return this.audit.video
        ? `${this.$http.defaults.baseURL}/files/${this.audit.video}`
        : false
    },
    contentId () {
      return this.audit.contentId
    },
    extraId () {
      return this.audit.extraId || 0
    },
    type () {
      return this.audit.type
    },
    url () {
      const { type } = this.audit
      // 特殊url， 双参数
      if (type === 'group-post') {
        return `/groups/${this.extraId}/posts/${this.contentId}`
      }

      return detailUrl[type] + this.contentId
    },
  },
  methods: {
    goToDetail () {
      this.$router.push(this.url)
    },
  },
}
</script>
