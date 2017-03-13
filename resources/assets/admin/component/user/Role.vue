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
          <th>节点名称</th>
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
            <button type="button" class="btn btn-primary btn-sm">管理</button>

            <!-- delete role. -->
            <button v-if="deleteIds.hasOwnProperty(role.id)" type="button" class="btn btn-danger btn-sm" disabled="disabled">
              <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </button>
            <button v-else type="button" class="btn btn-danger btn-sm" @click.prevent="deleteRole(role.id)">删除</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const RoleComponent = {
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
    deleteIds: {}
  }),
  /**
   * methods.
   *
   * @type {Object}
   */
  methods: {
    // updateRole (role) {
    //   let roles = [];
    //   this.roles.forEach(_role => {
    //     if (_role.id === role.id) {
    //       return {
    //         ..._role,
    //         ...role
    //       };
    //     }

    //     return _role;
    //   });

    //   this.roles = roles;
    // }
    /**
     * delete this.deleteIds item.
     *
     * @param {Number} id
     * @author Seven Du <shiweidu@outlook.com>
     */
    deleteIdsItem (id) {
      let ids = {};
      for (let _id in this.deleteIds) {
        if (_id !== id) {
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
      }).catch(() => {
        this.deleteIdsItem(id);
        window.alert('删除失败');
      });
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
    }).catch(() => {
      this.loadding = false;
    });
  }
};

export default RoleComponent;
</script>
