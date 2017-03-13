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
          <td></td>
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
    loadding: true
  }),
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
