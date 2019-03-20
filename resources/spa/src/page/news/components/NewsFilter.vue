<template>
  <div :class="[prefixCls, {showAll}]">
    <div :class="`${prefixCls}--head`">
      <span>{{ 'tap_to_edit' | t }}</span>
      <div>
        <button @click="onOk">{{ editing ? 'complete' : 'edit' | t }}</button>
        <button @click="showEditor">{{ 'collapse' | t }}</button>
      </div>
    </div>
    <div :class="`${prefixCls}--list__wrap`">
      <div
        v-show="!showAll"
        :class="`${prefixCls}--switch`"
        @click="showEditor"
      >
        <svg>
          <use xlink:href="#icon-arrow-right" />
        </svg>
      </div>
      <span :class="`${prefixCls}--list__label`">{{ 'news.my_subscription' | t }}</span>
      <div :class="[`${prefixCls}--list`, { editing }]">
        <div
          :class="[`${prefixCls}--list__item`, { active: ~~(currentCate.id) === 0 }]"
          @click="chooseCate($event, {id: 0, name: $t('recommend')})"
        >
          {{ 'recommend' | t }}
        </div>
        <div
          v-for="myCate in myCates"
          :key="`myCate-${myCate.id}`"
          :class="[`${prefixCls}--list__item`, { active: myCate.id === currentCate.id }]"
          @click="chooseCate($event, myCate)"
        >
          {{ myCate.name }}
        </div>
      </div>
    </div>
    <div v-show="showAll" :class="`${prefixCls}--list__wrap`">
      <span :class="`${prefixCls}--list__label`">{{ 'news.more_subscription' | t }}</span>
      <div :class="`${prefixCls}--list`">
        <div
          v-for="cate in moreCates"
          :key="`moreCate-${cate.id}`"
          :class="[`${prefixCls}--list__item`]"
          @click="chooseCate($event, cate)"
        >
          {{ cate.name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const prefixCls = 'news__filter'
export default {
  name: 'NewsFilter',
  data () {
    return {
      prefixCls,
      editing: false,
      showAll: false,

      myCates: [],
      moreCates: [],

      currentCate: {
        id: 0,
        name: this.$t('recommend'),
      },
    }
  },

  watch: {
    editing () {},
    showAll (val) {
      !val && (this.editing = !1)
    },
  },

  mounted () {
    this.fetchCates()
  },
  methods: {
    showEditor () {
      this.showAll = !this.showAll
    },
    onOk () {
      if (this.editing) {
        this.editing = false
        const follows = this.myCates.map(c => c.id).join(',')
        this.$http.patch('/news/categories/follows', {
          follows,
        })
        this.showAll = false
        this.$emit('onOk', this.myCates)
      } else {
        this.editing = true
      }
    },
    chooseCate (e, cate) {
      if (this.showAll) {
        if (this.editing) {
          const index = this.myCates.findIndex(c => c.id === cate.id)
          if (index > -1) {
            this.moreCates.push(cate)
            this.myCates.splice(index, 1)
          } else {
            const index2 = this.moreCates.findIndex(c => c.id === cate.id)
            this.myCates.push(cate)
            this.moreCates.splice(index2, 1)
          }
        } else {
          this.editing = true
        }
      } else {
        if (cate.id !== this.currentCate.id) {
          const { target: el } = e
          this.currentCate = { ...cate, el }
          this.$emit('change', this.currentCate)
        }
      }
    },
    async fetchCates () {
      const {
        data: { my_cates: myCates, more_cates: moreCates } = {},
      } = await this.$http.get('/news/cates')
      this.myCates = myCates ? [...myCates] : []
      this.moreCates = moreCates ? [...moreCates] : []
    },
  },
}
</script>

<style lang="less" scoped>
.news__filter {
  position: fixed;
  left: 0;
  right: 0;
  top: 90px;
  z-index: 9;
  background-color: #fff;
  padding-top: 0 !important;
  min-height: 80px;
  max-height: 80px;
  max-width: 768px;
  margin: 0 auto;
  font-size: 28px;
  overflow: hidden;
  transform: max-height 0.3s ease;
  border-bottom: 1px solid #ededed; /* no */
  transform: translate3d(0, 0, 0);
  &--head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    height: 80px;
    background-color: #f4f5f5;
    display: none;
    button {
      padding: 0;
      display: inline;
      outline: 0;
      border: 0;
      background-color: inherit;
      color: @primary;
      margin-left: 60px;
    }
  }

  &--switch {
    width: 80px;
    height: 78px;
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    background: linear-gradient(
      to right,
      rgba(255, 255, 255, 0.1) 0,
      white 40%,
      white
    );
    svg {
      width: 24px;
      height: 24px;
      color: #999;
      margin-left: (80-24) * 2/3px;
      vertical-align: middle;
      transform: rotate(90deg);
    }
  }

  &--list {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    overflow-x: auto;
    margin-left: -50/2px;
    margin-right: -50/2px;
    -webkit-overflow-scrolling: touch;
    &::-webkit-scrollbar-thumb,
    &::-webkit-scrollbar {
      width: 0;
    }
    &__wrap {
      padding: 0 25px;
      height: 80px;
      line-height: 80px;
    }
    &__label {
      display: none;
      color: #999;
    }

    &__item {
      cursor: pointer;
      flex: 0 0 auto;
      margin-left: 50/2px;
      margin-right: 50/2px;
      color: #999;
      position: relative;
      &:not(:first-child):after {
        content: "×";
        font-size: 16px; /*no*/
        color: #999;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: -15px;
        margin-top: -15px;
        width: 30px;
        height: 30px;
        border-radius: 100%;
        background-color: rgba(127, 127, 127, 0.1);
        position: absolute;
        left: 0;
        top: 0;
        transform: scale(0);
        transition: transform 0.2s ease;
      }
      &.active {
        color: #333;
        font-size: 30px;
      }

      &:last-child {
        padding-right: 80px;
      }
    }
  }

  &.showAll {
    max-height: 100%;
    bottom: 0;
  }

  .showAll & {
    &--head {
      display: flex;
    }
    &--list {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      margin-top: 30px;
      margin-left: -15px;
      margin-right: -15px;
      &__label {
        display: block;
      }
      &__item {
        margin: 30/2px;
        height: 60px;
        line-height: 60px;
        padding: 0 1em;
        text-align: center;
        border-radius: 8px;
        background-color: #f4f5f5;
      }
      &__wrap {
        margin-top: 60px;
        padding: 0 30px;
        width: 100%;
        height: auto;
        line-height: 1;
      }
    }
  }

  .editing &--list__item {
    &:after {
      transform: scale(1);
    }
  }
}
</style>
