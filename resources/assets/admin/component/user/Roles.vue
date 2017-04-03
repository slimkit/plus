<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">

    <!-- 提示 -->
    <div class="alert alert-success" role="alert">
      尽量不要删除用户组～删除用户组会造成用户组混乱！请谨慎编辑。
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>角色名称</th>
          <th>显示名称</th>
          <th>描述</th>
          <th>更新时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles">
          <td>{{ role.name }}</td>
          <td>{{ role.display_name }}</td>
          <td>{{ role.description }}</td>
          <td>{{ role.updated_at }}</td>
          <td>
            <!-- 管理分类 -->
            <router-link :to="`${role.id}`" tag="button" append exact type="button" class="btn btn-primary btn-sm">管理</router-link>

            <!-- delete role. -->
            <button v-if="deleteIds.hasOwnProperty(role.id)" type="button" class="btn btn-danger btn-sm" disabled="disabled">
              <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </button>
            <button v-else type="button" class="btn btn-danger btn-sm" @click.prevent="deleteRole(role.id)">删除</button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="input-group">
              <input v-model="add.name" type="text" class="form-control" placeholder="输入角色名称">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input v-model="add.display_name" type="text" class="form-control" placeholder="输入显示名称">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input v-model="add.description" type="text" class="form-control" placeholder="输入节点描述">
            </div>
          </td>
          <td></td>
          <td>
            <button v-if="add.adding" class="btn btn-primary btn-sm" disabled="disabled">
              <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </button>
            <button v-else type="button" class="btn btn-primary btn-sm" @click.pervent="postRole">添加</button>
          </td>
        </tr>

      </tbody>
    </table>

    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>

    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const RolesComponent = {
  /**
   * The component state tree.
   *
   * @return {Object} state tree
   * @author Seven Du <shiweidu@outlook.com>
   */
  data: () => ({
    /**
     * roles
     *
     * @type {Array}
     */
    roles: [],
    /**
     * is loadding.
     *
     * @type {Boolean}
     */
    loadding: true,
    /**
     * delete role ids.
     *
     * @type {Array}
     */
    deleteIds: {},
    add: {
      name: '',
      display_name: '',
      description: '',
      adding: false
    },
    error: null
  }),
  /**
   * methods.
   *
   * @type {Object}
   */
  methods: {
    /**
     * 创建角色
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    postRole () {
      this.add.adding = true;
      const { name, display_name, description } = this.add;
      request.post(
        createRequestURI('roles'),
        { name, display_name, description },
        { validateStatus: status => status === 201 }
      ).then(({ data }) => {
        this.roles = [
          ...this.roles,
          data
        ];
        this.add = {
          name: '',
          display_name: '',
          description: '',
          adding: false
        };
      }).catch(({ response: { data = {} } = {} }) => {
        const { errors = ['添加失败'] } = data;
        const errorMessage = lodash.values(errors).pop();
        this.add.adding = false;
        this.error = errorMessage;
      });
    },
    /**
     * delete this.deleteIds item.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     */
    deleteIdsItem (id) {
      let ids = {};
      for (let _id in this.deleteIds) {
        if (parseInt(_id) !== parseInt(id)) {
          ids[_id] = id;
        }
      }

      this.deleteIds = ids;
    },
    /**
     * delete role.
     *
     * @param {Number} id
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    deleteRole (id) {
      if (window.confirm('是否确认删除？')) {
        this.deleteIds = {
          ...this.deleteIds,
          [id]: id
        };
      }

      request.delete(
        createRequestURI(`roles/${id}`),
        { validateStatus: status => status === 204 }
      ).then(() => {
        this.deleteIdsItem(id);
        let roles = [];
        this.roles.forEach(role => {
          if (role.id !== id) {
            roles.push(role);
          }
        });
        this.roles = roles;
      }).catch(({ response: { data: { errors = ['删除失败'] } = {} } = {} }) => {
        this.deleteIdsItem(id);
        this.error = lodash.values(errors).pop();
      });
    },
    dismisError () {
      this.error = null;
    }
  },
  /**
   * The component created run.
   *
   * @author Seven Du <shiweidu@outlook.com>
   */
  created () {
    this.loadding = true;
    request.get(
      createRequestURI('roles'),
      { validateStatus: status => status === 200 }
    ).then(({ data }) => {
      this.loadding = false;
      this.roles = data;
    }).catch(({ response: { data: { errors = ['加载失败,请刷新重试！'] } = {} } = {} }) => {
      this.loadding = false;
      this.error = lodash.values(errors).pop();
    });
  }
};

export default RolesComponent;
</script>
