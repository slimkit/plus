<template>
  <Transition name="pop">
    <div v-if="show" class="m-box-model m-pos-f p-choose-category m-main">
      <CommonHeader :back="cancel">{{ $t('news.post.select_category') }}</CommonHeader>

      <main>
        <ul class="m-cates">
          <li
            v-for="cate in cates"
            v-if="cate.id"
            :key="cate.id"
            class="m-cate"
            @click="selected(cate)"
          >
            <span>{{ cate.name }}</span>
          </li>
        </ul>
      </main>
    </div>
  </Transition>
</template>

<script>
export default {
  name: 'ChooseCate',
  data () {
    return {
      show: false,
      cates: [],
    }
  },
  created () {
    this.fetchCates()
    this.$bus.$on('choose-cate', callback => {
      typeof callback === 'function' && (this.callback = callback)
      this.show = true
      this.scrollable = false
    })
  },
  beforeDestroy () {
    this.$bus.$off('choose-cate')
  },
  methods: {
    callback () {},
    selected (cate) {
      typeof this.callback === 'function' && this.callback(cate)
      this.cancel()
    },
    cancel () {
      this.show = false
      this.scrollable = true
    },
    fetchCates () {
      // GET /news/cates
      this.$http
        .get(`/news/cates`)
        .then(({ data: { my_cates: myCates = [], more_cates: moreCates = [] } }) => {
          this.cates = [...myCates, ...moreCates]
        })
    },
  },
}
</script>

<style lang="less" scoped>
.p-choose-category {
  .m-cates {
    padding: 30px;
  }
}
.m-cates {
  margin-top: -30px;
  margin-left: -30px;
}

.m-cate {
  display: inline-block;
  padding: 0 10px;
  margin-top: 30px;
  margin-left: 30px;
  width: calc((1 / 4 * 100%) ~" - 30px");
  height: 60px;
  line-height: 60px;
  font-size: 28px;
  background-color: #f4f5f5;
  border-radius: 8px;
  span {
    overflow: hidden;
    width: 100%;
    display: inline-block;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-align: center;
  }
}
</style>
