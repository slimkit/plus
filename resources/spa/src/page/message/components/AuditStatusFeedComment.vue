<template>
  <div class="msgList-status">
    <section v-if="audit.comment != null">
      <section v-if="audit.expires_at != null" class="gray">
        <span class="amount-show">{{ audit.amount }}{{ currencyUnit }} / {{ audit.day }}天</span>已审核
      </section>
      <section
        v-else
        class="green"
        @click="showOperations(audit)">
        <span class="audit-show">{{ audit.amount }}{{ currencyUnit }} / {{ audit.day }}天</span>
        <span class="audit-operation">审核</span>
      </section>
    </section>
    <section
      v-if="audit.comment == null"
      class="red">
      该评论已被删除
    </section>
  </div>
</template>

<script>
/**
 * 提取动态评论置顶申请的状态控制组件
 */
import AuditStatusBase from "./AuditStatusBase.vue";

export default {
  name: "AuditStatusFeedComment",
  extends: AuditStatusBase,
  methods: {
    accept() {
      const {
        raw: feedId = 0,
        target: commentId = 0,
        id: pinnedId = 0
      } = this.audit;
      this.$http
        .patch(
          `/feeds/${feedId}/comments/${commentId}/currency-pinneds/${pinnedId}`,
          {
            validateStatus: s => s === 201
          }
        )
        .then(({ data }) => {
          this.audit.expires_at = 1;
          this.$Message.success(data);
        });
    },
    reject() {
      const { id: pinnedId = 0 } = this.audit;
      this.$http
        .delete(`/user/feed-comment-currency-pinneds/${pinnedId}`, {
          validateStatus: s => s === 204
        })
        .then(() => {
          this.audit.expires_at = 1;
          this.$Message.success("已驳回");
        });
    }
  }
};
</script>

<style lang="less">
@import url("../style.less");
</style>
