<template>
  <div @touchmove.prevent>
    <Transition name="toast">
      <div
        v-if="isShow"
        class="m-pop-box"
        @click="cancel"
      />
    </Transition>
    <Transition name="fade">
      <div
        v-if="isShow"
        class="m-box-model m-image-paid-option-box"
      >
        <h2 class="m-image-paid-option-head">
          <slot name="title">
            <span>{{ title }}</span>
          </slot>
        </h2>
        <div class="m-image-paid-option-row m-bt1">
          <p class="m-image-paid-option-label">选择图片收费金额</p>
          <div class="m-box m-aln-center m-justify-bet m-image-paid-option-btns">
            <button
              v-for="item in items"
              :key="item"
              :style="{ width: `${1 / items.length * 100}%` }"
              :class="{ active: ~~amount === ~~item }"
              class="m-box m-aln-center m-justify-center m-flex-grow1 m-flex-shrink1 m-pinned-amount-btn"
              @click="chooseDefaultAmount(item)"
            >
              {{ (~~item) }}
            </button>
          </div>
        </div>
        <div
          class="m-image-paid-option-row m-bt1 m-box"
          style="overflow: hidden;"
        >
          <p
            class="m-image-paid-option-label m-flex-grow0 m-flex-shrink0"
            style="margin-bottom: 0"
          >
            自定义金额
          </p>
          <div class="m-box m-aln-center m-flex-grow1 m-flex-shrink1 m-justify-end m-lim-width m-pinned-amount-customize">
            <input
              v-model="customAmount"
              type="number"
              pattern="[0-9]*"
              placeholder="输入自定义金额"
              oninput="value=value.slice(0, 8)"
              class="m-text-r m-flex-grow1 m-flex-shrink1"
            >
            <span class="m-flex-grow0 m-flex-shrink0">{{ currencyUnit }}</span>
          </div>
        </div>
        <div class="m-image-paid-option-row m-bt1">
          <button
            class="m-long-btn"
            @click="handleOk"
          >
            确定
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>
<script>
export default {
  name: 'ImagePaidOption',
  data () {
    return {
      isShow: false,
      title: '图片收费选项',
      // type: "",
      amount: null,
      customAmount: null,
      curIndex: -1,
    }
  },
  computed: {
    items () {
      return this.$store.state.CONFIG.feed.items || []
    },
  },
  watch: {
    isShow (val) {
      if (val) {
        this.scrollTop = document.scrollingElement.scrollTop
        document.body.classList.add('m-pop-open')
        document.body.style.top = -this.scrollTop + 'px'
      } else {
        document.body.style.top = ''
        document.body.classList.remove('m-pop-open')
        document.scrollingElement.scrollTop = this.scrollTop
      }
    },
    customAmount (val) {
      this.amount = ~~val
    },
  },
  methods: {
    chooseDefaultAmount (amount) {
      this.customAmount && (this.customAmount = null)
      this.amount = amount
    },
    handleOk () {
      const { curIndex, /* type, */ amount } = this.$data
      if (curIndex > -1) {
        if (amount > 0) {
          this.$parent.$set(
            this.$parent.pics,
            curIndex,
            Object.assign(this.$parent.pics[curIndex], {
              amount,
              amountType: 'read',
            })
          )
          this.cancel()
        } else this.$Message.error('请输入或选择 收费金额')
      } else this.cancel()
    },
    show (image, index) {
      const { amount } = image
      this.amount = amount
      this.curIndex = index
      this.isShow = true
    },
    cancel () {
      this.isShow = false
      this.amount = null
      this.curIndex = -1
      this.customAmount = null
    },
  },
}
</script>

<style lang="less">
.m-image-paid-option-box {
  position: fixed;
  top: 50%;
  left: 50%;
  z-index: 120;
  transform: translate(-50%, -50%);
  padding: 0 50px;
  width: 650px;
  border-radius: 10px;
  background-color: #fff;
}
.m-image-paid-option-head {
  padding: 40px 0;
  color: #333;
  text-align: center;
  font-size: 32px;
  border-bottom: 1px solid @border-color; /*no*/
}
.m-image-paid-option-row {
  .m-image-paid-option-radio,
  .m-pinned-amount-btn {
    padding: 0 20px;
    height: 70px;
    border-radius: 6px;
    border: 1px solid #dedede; /*no*/
    margin: 0 20px;
    &.active {
      color: @primary;
      border-color: @primary;
    }
  }
  font-size: 32px;
  padding: 40px 0;
}
.m-image-paid-option-label {
  font-size: 26px;
  color: @text-color3;
  margin-bottom: 30px;
}
</style>
