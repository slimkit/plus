<template>
  <div class="msgList-status">
    <section v-if="audit.comment != null">
      <section v-if="audit.expires_at != null" class="gray">
        <span class="amount-show">{{ $t('message.audit.top.amount', audit) }}</span>
        {{ $t('message.audit.reviewed') }}
      </section>
      <section
        v-else
        class="green"
        @click="showOperations(audit)"
      >
        <span class="audit-show">{{ $t('message.audit.top.amount', audit) }}</span>
        <span class="audit-operation">{{ $t('message.audit.review') }}</span>
      </section>
    </section>
    <section v-if="!audit.comment" class="red"> {{ $t('article.deleted') }} </section>
  </div>
</template>

<script>
/**
 * 提取动态评论置顶申请的状态控制组件
 */
import AuditStatusBase from './AuditStatusBase.vue'

export default {
  name: 'AuditStatusFeedComment',
  extends: AuditStatusBase,
  methods: {
    accept () {
      const {
        raw: feedId = 0,
        target: commentId = 0,
        id: pinnedId = 0,
      } = this.audit
      this.$http
        .patch(
          `/feeds/${feedId}/comments/${commentId}/currency-pinneds/${pinnedId}`,
          {
            validateStatus: s => s === 201,
          }
        )
        .then(({ data }) => {
          this.audit.expires_at = 1
          this.$Message.success(data)
        })
    },
    reject () {
      const { id: pinnedId = 0 } = this.audit
      this.$http
        .delete(`/user/feed-comment-currency-pinneds/${pinnedId}`, {
          validateStatus: s => s === 204,
        })
        .then(() => {
          this.audit.expires_at = 1
          this.$Message.success(this.$t('message.audit.rejected'))
        })
    },
  },
}
</script>

<style lang="less">
@import url("../style.less");
</style>
