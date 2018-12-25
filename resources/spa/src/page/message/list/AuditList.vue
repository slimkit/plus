<template>
  <div class="p-audit-list">
    <CommonHeader>
      <DiySelect
        v-model="currentType"
        :readonly="true"
        :options="options"
        style="margin-top: -1px"
        @click="popupBuyTS"
      />
    </CommonHeader>

    <div class="container" @click.capture.stop.prevent="popupBuyTS">
      <RouterView />
    </div>
  </div>
</template>

<script>
import i18n from '@/i18n'

const prefixCls = 'auditList'
const options = [
  { value: 'feedcomments', label: i18n.t('message.audit.types.feed_comment_top') },
  { value: 'newscomments', label: i18n.t('message.audit.types.news_comment_top') },
  { value: 'groupposts', label: i18n.t('message.audit.types.post_top') },
  { value: 'groupcomments', label: i18n.t('message.audit.types.post_comment_top') },
  { value: 'groupjoins', label: i18n.t('message.audit.types.group_join') },
]

export default {
  name: 'AuditList',
  data: () => ({
    prefixCls,
    refreshData: [],
    options,
    currentType: 'feedcomments',
  }),
  watch: {
    currentType (newVal, oldVal) {
      this.currentType = oldVal
      this.popupBuyTS()
    },
  },
  created () {
    const regex = new RegExp('/message/audits/')
    this.currentType = this.$route.path.replace(regex, '')
  },
}
</script>

<style lang="less" scoped>
@import url("../style.less");

.p-audit-list {
  .c-common-header {
    position: fixed;
  }

  .diy-select {
    width: 100%;
  }

  .container {
    padding-top: 90px;
  }
}
</style>
