<template>
  <div class="c-article-reward">
    <button class="reward-btn" @click="reward">{{ $t('reward.name') }}</button>
    <!-- eslint-disable-next-line vue/component-name-in-template-casing -->
    <i18n
      :slot-scope="{currencyUnit}"
      path="article.reward_count"
      class="reward-info"
      tag="p"
    >
      <span slot="count">{{ count | formatNum }}</span>
      <span slot="amount">{{ ~~amount }}</span>
    </i18n>
    <RouterLink
      v-if="list.length > 0"
      tag="div"
      to="rewarders"
      append
      class="reward-list"
    >
      <div class="avatar-list">
        <Avatar
          v-for="{id, user} in list"
          :key="id"
          :user="user"
          :readonly="true"
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
  name: 'ArticleReward',
  props: {
    type: { type: String, required: true },
    article: { type: Number, required: true },
    count: { type: Number, default: 0 },
    amount: { type: Number, default: 0 },
    list: { type: Array, default: () => [] },
    isMine: { type: Boolean, default: false },
  },
  methods: {
    getAvatar (avatar) {
      if (!avatar) return undefined
      return avatar.url || null
    },
    reward () {
      this.popupBuyTS()
    },
  },
}
</script>

<style lang="less" scoped>
.c-article-reward {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 20px;
  padding-bottom: 40px;

  .reward-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 160px;
    height: 60px;
    border-radius: 6px;
    background-color: #f76c69;
    color: #fff;
    font-size: 28px;
  }

  .reward-info {
    font-size: 24px;
    color: @text-color1;
    margin: 15px 0;

    > span {
      color: @error;
    }
  }

  .reward-list {
    display: flex;
    align-items: center;
    justify-content: center;
  }

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
