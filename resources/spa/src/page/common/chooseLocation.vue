<template>
  <div class="page-location">
    <HeadTop
      :append="true"
      :title="$t('location.choose')"
    >
      <div
        slot="append"
        class="head-top-cancel"
        @click="$router.go(-1)"
      >
        {{ $t('cancel') }}
      </div>
      <div
        slot="title"
        class="head-top-search"
      >
        <svg class="m-style-svg m-svg-def head-top-search-icon">
          <use xlink:href="#icon-search" />
        </svg>
        <input
          v-model="keyword"
          class="head-top-search-input"
          type="text"
          :placeholder="$t('search')"
          @input="search"
        >
      </div>
    </HeadTop>
    <!-- 保留此空 div -->
    <div />
    <template v-if="isShowHot">
      <div class="location-current">
        <span>{{ $t('location.current') }}</span>
        <span
          :class="{c999: !cur_txt }"
          class="location-current-txt"
        >
          {{ cur_txt || $t('location.empty') }}
        </span>
        <svg
          class="m-style-svg m-svg-def location-current-append"
          @click.native.stop="updateLocation"
        >
          <use xlink:href="#cur-icon" />
        </svg>
      </div>
      <div class="location-hot">
        <label>{{ $t('location.choose') }}</label>
        <div class="location-hot-list">
          <div
            v-for="hotCity in hot_citys"
            v-if="hotCity.length > 0"
            :key="hotCity"
            class="location-hot-item"
            @click="chooseHotCity(hotCity)"
          >
            {{ hotCity.slice(hotCity.lastIndexOf(' ')) }}
          </div>
        </div>
      </div>
    </template>
    <template v-if="dataList.length > 0">
      <ul class="location-search-list">
        <li
          v-for="item in dataList"
          v-if="item"
          :key="item"
          class="location-search-list-item"
          @click="chooseHotCity(item)"
        >
          {{ item }}
        </li>
      </ul>
    </template>
  </div>
</template>

<script>
import _ from 'lodash'
import HeadTop from '@/components/HeadTop'

let sources = []

export default {
  name: 'ChooseLocation',
  components: {
    HeadTop,
  },
  data () {
    return {
      keyword: '',
      showHot: true,
      loading: false,

      dataList: [],
      redirect: '', // 选择地址后 跳转的路径
    }
  },
  computed: {
    location () {
      const location = this.$store.state.LOCATION || {}
      if (JSON.stringify(location) === '{}') {
        this.$store.dispatch('GET_LOCATION')
      } else {
        /* eslint-disable */
        this.loading = false;
      }
      return location;
    },
    cur_txt() {
      return this.location.label || "";
    },
    hot_citys() {
      return this.$store.state.HOTCTIYS;
    },
    is_loading() {
      return this.loading || !(this.cur_txt.length > 0);
    },
    isShowHot() {
      return !(this.keyword.length > 0) && !(this.dataList.length > 0);
    }
  },
  methods: {
    updateLocation() {
      this.loading = true;
      this.$store.dispatch("UPDATE_LOCATION");
    },
    chooseHotCity(cityTxt) {
      this.$http
        .get(`around-amap/geo?address=${cityTxt.replace(/[\s\uFEFF\xA0]+/g, "")}`)
        .then(res => {
          const {
            data: { geocodes: [{ city, district, province, location }] } = {}
          } = res;

          const [lng, lat] = location.split(",");
          const label =
            district.length > 0 ? district : city.length > 0 ? city : province;

          if (this.redirect) {
            if (this.redirect.indexOf("/find/") > -1) {
              this.$store.commit("SAVE_LOCATION", {
                label,
                lng,
                lat
              });
              this.$router.push(`/find/nearby?lng=${lng}&lat=${lat}`);
            } else if (this.redirect.indexOf("/add_group") > -1) {
              this.$store.commit("SAVE_GROUP_LOCATION", {
                label,
                lng,
                lat
              });
              this.$router.go(-1);
            }
          } else {
            this.$router.go(-1);
          }
        })
    },

    // 使用_.debounce控制搜索的触发频率
    // 准备搜索
    search: _.debounce(
      function() {
        let that = this;
        // 删除已经结束的请求
        _.remove(sources, n => n.source === null);

        // 取消还未结束的请求
        sources.forEach(function(item) {
          if (item !== null && item.source !== null && item.status === 1) {
            item.status = 0;
            item.source.cancel();
          }
        });

        // 创建新的请求cancelToken,并设置状态请求中
        let sc = {
          source: that.$http.CancelToken.source(),
          status: 1 // 状态1：请求中，0:取消中
        };
        sources.push(sc);

        // 开始搜索数据
        if (that.keyword) {
          that.$http
            .get(`/locations/search?name=${that.keyword}`, {
              cancelToken: sc.source.token
            })
            .then(({ data = [] }) => {
              // 置空请求canceltoken
              sc.source = null;
              that.dataList = data.map(item => {
                let name = "";
                item = item.tree;
                while (item) {
                  name = item.name + "," + name;
                  item = item.parent;
                }
                return name.substr(0, name.length - 1);
              });
            })
        }
      },
      500 // 空闲时间间隔设置500ms
    )
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.redirect = from.fullPath;
    });
  },
  created() {
    this.$store.dispatch("GET_HOT_CITYS");
  }
};
</script>

<style lang='less' scoped>
@location-prefix: location;
.page-location {
  .head-top-title {
    padding: 0 120px 0 30px;
    width: 100%;
  }
}

.@{location-prefix} {
  &-search-list {
    background-color: #fff;
    padding: 0 30px;
    &-item {
      border-bottom: 1px solid #ededed; /*no*/
      width: 100%;
      height: 100px;
      line-height: 98px;
      font-size: 30px;
      color: #333;
    }
  }

  &-current {
    display: flex;
    margin-top: 20px;
    padding: 0 20px;
    height: 90px;
    align-items: center;
    justify-content: space-between;
    font-size: 28px;
    color: #333;
    background-color: #fff;
    &-txt {
      font-size: 28px;
      margin: -2px 35px;
      flex: 1 1 auto;
      cursor: default;
      &.c999 {
        color: #999;
      }
    }
    &-append {
      width: 30px;
      height: 30px;
      margin-right: 10px;
    }
  }

  &-hot {
    label {
      display: block;
      padding: 0 20px;
      height: 60px;
      line-height: 60px;
      font-size: 26px;
      color: #999;
    }

    &-list {
      display: flex;
      flex-wrap: wrap;
      width: 100%;
      padding: 40px 20px 10px;
      background-color: #fff;
    }
    &-item {
      padding: 0 20px;
      width: 150px;
      height: 60px;
      line-height: 58px;
      font-size: 28px;
      color: #333;
      margin-right: 30px;
      margin-bottom: 30px;
      background-color: #f4f5f5;
      border-radius: 8px;
      text-align: center;
    }
  }
}
</style>
