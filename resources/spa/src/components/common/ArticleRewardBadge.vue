<template>
  <div class="c-article-reward-badge">
    <p class="m-art-rew-label">
      <a href="javascript:;">{{ total | formatNum }}</a> 人打赏，
      共 <a href="javascript:;">{{ ~~amount }}</a> {{ currencyUnit }}
    </p>
    <RouterLink
      v-if="rewarders.length > 0"
      tag="div"
      to="rewarders"
      append
      class="m-box m-aln-center m-art-rew-list"
    >
      <div class="avatar-list">
        <Avatar
          v-for="{id, user} in rewarders"
          :key="id"
          :user="user"
          size="small"
        />
      </div>
      <svg class="m-box m-aln-center m-style-svg m-svg-def" style="color: #bfbfbf;">
        <use xlink:href="#icon-arrow-right" />
      </svg>
    </RouterLink>
  </div>
</template>

<script>
export default {
  name: 'ArticleRewardBadge',
  props: {
    total: { type: [Number, String], default: 0 },
    amount: { type: [Number, String], default: 0 },
    rewarders: { type: Array, default: () => [] },
  },
  methods: {
    getAvatar (avatar) {
      if (!avatar) return undefined
      return avatar.url || null
    },
  },
}
</script>

<style lang="less" scoped>
.c-article-reward-badge {
  .avatar-list {
    position: relative;

    &::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      display: block;
      background: transparent;
      z-index: 7;
    }
  }
}
</style>
