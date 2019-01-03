<template>
  <Transition name="toast">
    <div
      v-if="show"
      class="m-box-model m-pos-f"
      style="background-color: #f4f5f6; z-index: 101"
      @touchmove.prevent
    >
      <CommonHeader :back="cancel">{{ $t('top.apply') }}</CommonHeader>

      <main class="m-box-model m-aln-center m-justify-center">
        <div class="m-box-model m-lim-width">
          <div class="m-pinned-amount-btns m-main">
            <p class="m-pinned-amount-label">{{ $t('top.select_day') }}</p>
            <div class="m-box m-aln-center ">
              <button
                v-for="item in items"
                :key="item"
                :class="{ active: ~~day === ~~item }"
                :style="{ width: `${1 / items.length * 100}%` }"
                class="m-pinned-amount-btn"
                @click="chooseDefaultDay(item)"
              >
                {{ ((~~item)) }} {{ $t('date.day') }}
              </button>
            </div>
          </div>

          <template v-if="!isManager">
            <div
              class="m-box m-aln-center m-justify-bet m-bb1 m-pinned-row plr20 m-pinned-amount-customize m-main"
              style="margin-top: .2rem"
            >
              <span>{{ $t('top.amount.name') }}</span>
              <div class="m-box m-aln-center">
                <input
                  v-model="customAmount"
                  type="number"
                  pattern="[0-9]*"
                  :placeholder="$t('top.amount.input')"
                  oninput="value=value.slice(0,8)"
                  class="m-flex-grow1 m-flex-shrink1 m-text-r"
                >
                <span>{{ $t('currency.unit') }}</span>
              </div>
            </div>

            <div class="m-box m-aln-center m-justify-bet m-pinned-row plr20 m-pinned-amount-customize m-main">
              <span>{{ $t('top.amount.total') }}</span>
              <div class="m-box m-aln-center">
                <input
                  v-model="amount"
                  class="m-flex-grow1 m-flex-shrink1 m-text-r"
                  type="number"
                  pattern="[0-9]*"
                  disabled="true"
                  readonly="true"
                  :placeholder="$t('top.amount.total')"
                  style="background-color: transparent"
                >
                <span>{{ $t('currency.unit') }}</span>
              </div>
            </div>
            <p class="placeholder m-flex-grow1 m-flex-shrink1">
              {{ $t('currency.current') }} {{ currencySum }}
            </p>
          </template>
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
            <span v-else>{{ isOwner || isManager ? $t('top.confirm') : $t('top.apply') }}</span>
          </button>
        </div>
      </main>

      <PasswordConfirm ref="password" @submit="applyTop" />
    </div>
  </Transition>
</template>

<script>
import { noop } from '@/util'
import PasswordConfirm from '@/components/common/PasswordConfirm.vue'

export default {
  name: 'ApplyTop',
  components: { PasswordConfirm },
  data () {
    return {
      day: 0,
      show: false,
      currencySum: 0,
      loading: false,
      customAmount: null,
      isOwner: false,
      applyType: '', // 申请置顶的类型
      applyApi: noop, // 申请置顶的api 类型是一个 Promise 对象
      applyPayload: {}, // 申请置顶的负载数据，如feedId等
      applyCallback: noop,
    }
  },
  computed: {
    amount () {
      return this.day * this.customAmount
    },
    items () {
      return [1, 5, 10]
    },
    disabled () {
      return this.amount < 0
    },
    currency () {
      const currency = this.$store.state.CURRENTUSER.currency || {}
      return currency.sum || 0
    },
    isManager () {
      return this.applyType === 'post-manager'
    },
  },
  watch: {
    $route (to, from) {
      if (to !== from) this.cancel()
    },
  },
  created () {
    /**
     * 弹出申请置顶窗口 (hooks -> applyTop)
     * @author mutoe <mutoe@foxmail.com>
     * @param {Object} options
     * @param {string} options.type 申请置顶类型
     * @param {AxiosPromise} options.api 申请置顶的 api，需要返回 axios promise 对象
     * @param {string|Object} options.payload 申请置顶 api 的第一个参数，取决于 api 中的设定
     * @param {boolean} [options.isOwner = false] 是否是文章的所有者
     * @param {requestCallback} [options.callback = noop] 申请置顶成功后执行的回调方法
     */
    this.$bus.$on('applyTop', options => {
      const { type, api, payload, isOwner = false, callback = noop } = options
      this.applyType = type
      this.applyApi = api
      this.applyPayload = payload
      this.isOwner = isOwner
      this.applyCallback = callback || noop
      this.open()
    })
  },
  methods: {
    showPasswordConfirm () {
      if (this.currency < this.amount) {
        this.$Message.error(this.$t('currency.insufficient'))
        this.cancel()
        return this.$router.push({ name: 'currencyRecharge' })
      }
      if (this.isManager) this.applyTop()
      else this.$refs.password.show()
    },
    applyTop (password) {
      if (this.loading || !this.applyType) return
      this.loading = true
      const params = {
        amount: ~~this.amount,
        day: this.day,
        password,
      }

      this.applyApi(this.applyPayload, params)
        .then(({ data = {} }) => {
          this.loading = false
          this.$Message.success(data)
          this.applyCallback()
          this.$nextTick(this.cancel)
        })
        .catch(err => {
          this.loading = false
          this.$Message.error(err.response.data)
        })
    },
    chooseDefaultDay (day) {
      this.day = day
    },
    resetProps () {
      this.day = this.items[0]
    },
    async open () {
      this.show = true
      this.scrollable = false
      const { currency } = await this.$store.dispatch('fetchUserInfo')
      this.currencySum = currency.sum || 0
      this.day = this.items[0]
    },
    cancel () {
      this.show = false
      this.day = null
      this.customAmount = null
      this.scrollable = true
      this.isOwner = false
      this.applyType = ''
      this.applyApi = noop
      this.applyPayload = {}
      this.applyCallback = noop
    },
  },
}
</script>

<style lang="less" scoped>
.m-pinned-row {
  font-size: 0.3rem;
  height: 1rem;
}
.plr20 {
  padding-left: 20px;
  padding-right: 20px;
}
.placeholder {
  padding: 0.3rem 0.2rem 0;
  font-size: 0.24rem;
}
</style>
