<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  padding-top: 100px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  font-size: 42px;
}
.breadcrumbNotActvie {
  color: #3097D1;
  cursor: pointer;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">
    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>

    <!-- 整体盒子 -->
    <div v-show="!loadding">

      <!-- 路径导航 -->
      <ol v-if="!tree" class="breadcrumb">
        <li class="active">全部</li>
      </ol>
      <ol v-else class="breadcrumb">
        <li :class="$style.breadcrumbNotActvie">全部</li>
        <li v-for="area in tree" :class="area.id === current ? 'active' : $style.breadcrumbNotActvie" >
          {{ area.name }}
        </li>
      </ol>

      <!-- 位于全部提示 -->
      <div v-show="!current" class="alert alert-success" role="alert">
        拓展信息赋予单条信息而外的数据，例如国家设置，<strong>中国</strong>的拓展信息设置的<strong>3</strong>,用于在app开发中UI层展示几级选择菜单，所以，只有在业务需求下，设置拓展信息才是有用的。其他情况下留空即可。
      </div>

      <!-- 列表表格 -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th>名称</th>
            <th>拓展(无需设置)</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="area in list">
              <td>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="输入名称" :value="area.name">
                </div>
              </td>
              <td>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="输入拓展信息" :value="area.extends">
                </div>
              </td>
              <td></td>
            </tr>
            <tr>
              <td>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="输入名称">
                </div>
              </td>
              <td>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="输入拓展信息">
                </div>
              </td>
              <td>
                <button type="button" class="btn btn-primary btn-sm">添加</button>
              </td>
            </tr>
        </tbody>
      </table>

    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { SETTINGS_AREA } from '../../store/getter-types';
import { SETTINGS_AREA_CHANGE } from '../../store/types';
import request, { createRequestURI } from '../../util/request';

const AreaComponent = {
  /**
   * 当前状态数据.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  data: () => ({
    current: 0,
    loadding: true
  }),
  /**
   * 定义需要初始化时候计算的数据对象.
   *
   * @type {Object}
   */
  computed: {
    /**
     * 展开 maoGetters 获取的数据.
     *
     * @type {Object}
     */
    ...mapGetters({
      areas: SETTINGS_AREA
    }),
    /**
     * 计算路径导航对象.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    tree () {
      const current = this.current;
      if (current === 0) {
        return false;
      }

      return this.getTrees(current);
    },
    /**
     * 获取当前选中子列表
     *
     * @return {Object} [description]
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    list () {
      const current = this.current;
      let trees = [];
      this.areas.forEach(area => {
        if (area.pid === current) {
          trees.push(area);
        }
      });

      return trees;
    }
  },
  /**
   * 方法对象, 用于设置各个处理方法.
   *
   * @type {Object}
   */
  methods: {
    getTrees (pid) {
      let trees = [];
      this.areas.forEach(area => {
        if (area.id === pid) {
          trees = [
            ...this.getTrees(area.pid),
            area
          ];
        }
      });

      return trees;
    }
  },
  /**
   * 组件初始化完成后执行.
   *
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  created () {
    this.$store.dispatch(SETTINGS_AREA_CHANGE, cb => request.get(
      createRequestURI('site/areas'),
      // 判断状态是否是正确获取的状态, 正确进入 then.
      { validateStatus: status => status === 200 }
    ).then(({ data = [] }) => {
      cb(data);
      this.loadding = false;
    }).catch(() => {
      // todo
    }));
  }
};

export default AreaComponent;
</script>
