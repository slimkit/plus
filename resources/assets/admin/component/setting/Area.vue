<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  padding-top: 100px;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
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
      <ol v-if="tree" class="breadcrumb">
        <li :class="$style.breadcrumbNotActvie" @click.prevent="selectCurrent(0)">全部</li>
        <li
          v-for="area in tree"
          :class="area.id === current ? 'active' : $style.breadcrumbNotActvie"
          @click.prevent="selectCurrent(area.id)"
        >
          {{ area.name }}
        </li>
      </ol>

      <!-- 位于全部提示 -->
      <div v-show="!current" class="alert alert-success" role="alert">
        <p>1. 提交：编辑地区信息的时候，直接修改输入框内容，失去焦点后程序会自动提交</p>
        <p>2. 拓展信息：拓展信息赋予单条信息而外的数据，例如国家设置，<strong>中国</strong>的拓展信息设置的<strong>3</strong>,用于在app开发中UI层展示几级选择菜单，所以，只有在业务需求下，设置拓展信息才是有用的。其他情况下留空即可。</p>
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
                  <input @change.lazy="patchArea(area.id, 'name', $event.target.value)" type="text" class="form-control" placeholder="输入名称" :value="area.name">
                </div>
              </td>
              <td>
                <div class="input-group">
                  <input @change.lazy="patchArea(area.id, 'extends', $event.target.value)" type="text" class="form-control" placeholder="输入拓展信息" :value="area.extends">
                </div>
              </td>
              <td>
                <button type="button" class="btn btn-primary btn-sm" @click.prevent="selectCurrent(area.id)">下级管理</button>
                <button v-if="deleteIds.hasOwnProperty(area.id)" type="button" class="btn btn-danger btn-sm" disabled="disabled">
                  <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                </button>
                <button v-else type="button" class="btn btn-danger btn-sm" @click.prevent="deleteArea(area.id)">删除</button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="input-group">
                  <input v-model="add.name" type="text" class="form-control" placeholder="输入名称">
                </div>
              </td>
              <td>
                <div class="input-group">
                  <input v-model="add.extends" type="text" class="form-control" placeholder="输入拓展信息">
                </div>
              </td>
              <td>
                <button v-if="!add.loadding" @click.prevent="addArea" type="button" class="btn btn-primary btn-sm">添加</button>
                <button v-else class="btn btn-primary btn-sm" disabled="disabled">
                  <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                </button>
              </td>
            </tr>
        </tbody>
      </table>

      <div v-show="add.error" class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" @click.prevent="dismisAddAreaError">
          <span aria-hidden="true">&times;</span>
        </button>
        <strong>Error:</strong>
        <p v-for="error in add.error_message">{{ error }}</p>
      </div>

    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import lodash from 'lodash';
import { SETTINGS_AREA } from '../../store/getter-types';
import { SETTINGS_AREA_CHANGE, SETTINGS_AREA_APPEND, SETTINGS_AREA_DELETE, SETTINGS_AREA_CHANGEITEM } from '../../store/types';
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
    loadding: true,
    add: {
      name: '',
      extends: '',
      loadding: false,
      error: false,
      error_message: {}
    },
    deleteIds: {}
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
    /**
     * 获取路径导航树.
     *
     * @param {Number} pid
     * @return {Array}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
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
    },
    /**
     * 设置选中id.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    selectCurrent (id) {
      if (this.add.loadding) {
        alert('正在添加新地区，请等待！！！');
        return;
      }

      this.current = id;
      this.add = {
        name: '',
        extends: '',
        loadding: false
      };
      // 用于回到顶部
      document.documentElement.scrollTop = document.body.scrollTop = 0;
    },
    /**
     * 添加新地区.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    addArea () {
      this.add.loadding = true;
      this.add.error = false;

      const data = {
        name: this.add.name,
        extends: this.add.extends,
        pid: this.current
      };

      this.$store.dispatch(SETTINGS_AREA_APPEND, cb => request.post(
        createRequestURI('site/areas'),
        data,
        { validateStatus: status => status === 201 }
      ).then(({ data }) => {
        this.add = {
          name: '',
          extends: '',
          loadding: false
        };
        cb(data);
      }).catch(({ response: { data = {} } = {} }) => {
        const { error = ['添加失败!!!'] } = data;
        this.add.loadding = false;
        this.add.error = true;
        this.add.error_message = error;
      }));
    },
    /**
     * 关闭添加错误消息.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    dismisAddAreaError () {
      this.add.error = false;
    },
    /**
     * 删除地区.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    deleteArea (id) {
      if (window.confirm('确认删除?')) {
        this.deleteIds = {
          ...this.deleteIds,
          [id]: id
        };

        const deleteId = (id) => {
          let ids = {};
          for (let _id in this.deleteIds) {
            if (parseInt(_id) !== parseInt(id)) {
              ids = {
                ...ids,
                [_id]: _id
              };
            }
          }
          this.deleteIds = ids;
        };

        this.$store.dispatch(SETTINGS_AREA_DELETE, cb => request.delete(
          createRequestURI(`site/areas/${id}`),
          { validateStatus: status => status === 204 }
        ).then(() => {
          cb(id);
          deleteId(id);
        }).catch(({ response: { data = {} } = {} }) => {
          deleteId(id);
          const { error = '删除失败' } = data;
          window.alert(error);
        }));
      }
    },
    /**
     * 更新地区数据
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    patchArea (id, key, value) {
      this.$store.dispatch(SETTINGS_AREA_CHANGEITEM, cb => request.patch(
        createRequestURI(`site/areas/${id}`),
        { key, value },
        { validateStatus: status => status === 201 }
      ).then(() => {
        cb({
          id,
          [key]: value
        });
      }).catch(({ response: { data = {} } = {} }) => {
        const { error = ['更新失败'] } = data;
        const errorMessage = lodash.values(error).pop();
        window.alert(errorMessage);
      }));
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
    }).catch(({ response: { data: { message = '获取地区失败' } = {} } = {} }) => {
      this.loadding = false;
      window.alert(message);
    }));
  }
};

export default AreaComponent;
</script>
