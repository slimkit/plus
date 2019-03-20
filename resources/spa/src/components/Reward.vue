<template>
  <Transition name="pop">
    <div
      v-if="show"
      class="m-box-model m-pos-f"
      style="background-color: #f4f5f6"
      @touchmove.prevent
    >
      <CommonHeader :back="cancel">
        打赏
        <template slot="right">
          <button
            :disabled="!(amount > 0)"
            class="m-btn"
            @click.stop.prevent="resetProps"
          >
            重置
          </button>
        </template>
      </CommonHeader>

      <main class="m-box-model m-aln-center m-justify-center">
        <div class="m-box-model m-lim-width m-main">
          <div class="m-pinned-amount-btns">
            <p class="m-pinned-amount-label">选择打赏{{ currencyUnit }}</p>
            <div class="m-box m-aln-center ">
              <button
                v-for="item in items"
                :key="item"
                :style="{ width: `${1 / items.length * 100}%` }"
                :class="{ active: ~~amount === ~~item && !customAmount }"
                class="m-pinned-amount-btn"
                @click="chooseDefaultAmount(item)"
              >
                {{ ~~item }}
              </button>
            </div>
          </div>
          <div class="m-box m-aln-center m-justify-bet m-bb1 m-bt1 m-pinned-row plr20 m-pinned-amount-customize">
            <span>自定义金额</span>
            <div class="m-box m-aln-center">
              <input
                v-model="customAmount"
                :placeholder="`输入${currencyUnit}`"
                type="number"
                class="m-text-r"
                pattern="[0-9]*"
                oninput="value=value.slice(0,8)"
              >
              <span>{{ currencyUnit }}</span>
            </div>
          </div>
        </div>
        <div
          class="plr20 m-lim-width"
          style="margin-top: 0.6rem"
        >
          <button
            :disabled="disabled || loading"
            class="m-long-btn m-signin-btn"
            @click="showPasswordConfirm"
          >
            <CircleLoading v-if="loading" />
            <span v-else>确定</span>
          </button>
        </div>
      </main>

      <PasswordConfirm
        ref="password"
        @submit="reward"
      />
    </div>
  </Transition>
</template>

<script>
import { noop } from '@/util'
import PasswordConfirm from '@/components/common/PasswordConfirm.vue'
import * as feedApi from '@/api/feeds'
import * as newsApi from '@/api/news'
import * as userApi from '@/api/user'

export default {
  name: 'Reward',
  components: { PasswordConfirm },
  data () {
    return {
      show: false,
      amount: null,
      loading: false,
      customAmount: null,
      type: '',
      callback: noop,
      payload: {},
    }
  },
  computed: {
    items () {
      return this.$store.state.CONFIG.site.reward.amounts.split(',') || []
    },
    disabled () {
      return !this.amount > 0
    },
    currentCurrency () {
      const user = this.$store.state.CURRENTUSER
      return user.currency.sum || 0
    },
    api () {
      switch (this.type) {
        case 'user':
          return userApi.rewardUser
        case 'feed':
          return feedApi.rewardFeed
        case 'news':
          return newsApi.rewardNews
        default:
          return noop
      }
    },
  },
  watch: {
    customAmount (val) {
      this.amount = ~~val
    },
    $route (to, from) {
      if (to !== from) this.cancel()
    },
  },
  created () {
    /**
     * 弹出打赏窗口 (hooks -> reward)
     * @author mutoe <mutoe@foxmail.com>
     * @param {Object} options
     * @param {string} options.type 打赏的类型
     * @param {string|Object} options.payload api 的第一个参数，取决于 api
     * @param {requestCallback} [options.callback] 打赏成功后的回调方法, 接受一个参数 amount 打赏金额
     */
    this.$bus.$on('reward', options => {
      const { type, article, callback = noop } = options
      this.type = type
      this.articleId = article
      this.callback = callback
      this.open()
    })
  },
  methods: {
    showPasswordConfirm () {
      if (this.currentCurrency < this.amount) {
        this.$Message.error(`${this.currencyUnit}不足，请充值`)
        this.cancel()
        return this.$router.push({ name: 'currencyRecharge' })
      }
      this.$refs.password.show()
    },
    reward (password) {
      if (this.loding || !this.type) return
      this.loading = true
      const data = {
        amount: ~~this.amount,
        password,
      }
      this.api(this.articleId, data)
        .then(() => {
          this.loading = false
          this.$Message.success('打赏成功')
          this.callback(data.amount)
          this.$nextTick(this.cancel)
        })
        .catch(({ response: { data: message } }) => {
          message && this.$Message.error(message)
          this.loading = false
        })
    },
    chooseDefaultAmount (amount) {
      this.customAmount && (this.customAmount = null)
      this.amount = amount
    },
    resetProps () {
      this.customAmount = null
      this.amount = null
    },
    open () {
      this.show = true
      this.scrollable = false
    },
    cancel () {
      this.show = false
      this.customAmount = null
      this.type = ''
      this.callback = noop
      this.payload = {}
      this.scrollable = true
    },
  },
}
</script>

<style>
.m-pinned-row {
  font-size: 0.3rem;
  height: 1rem;
}
.plr20 {
  padding-left: 20px;
  padding-right: 20px;
}
</style>
