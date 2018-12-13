<template>
  <div class="c-article-reward-badge">
    <p class="m-art-rew-label">
      <a href="javascript:;">{{ total | formatNum }}</a> 人打赏，
      共 <a href="javascript:;">{{ ~~amount }}</a> {{ currencyUnit }}
    </p>
    <RouterLink
      tag="ul"
      to="rewarders"
      append
      class="m-box m-aln-center m-art-rew-list"
    >
      <li
        v-for="{id, user} in rewarders"
        :key="id"
        :class="`m-avatar-box-${user.sex}`"
        class="m-flex-grow0 m-flex-shrink0 m-art-rew m-avatar-box tiny"
        :style="{backgroundImage: user.avatar && `url(${getAvatar(user.avatar)})`}"
      />
      <li v-if="rewarders.length > 0" class="m-box m-aln-center">
        <svg class="m-style-svg m-svg-def" style="color: #bfbfbf;">
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </li>
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
