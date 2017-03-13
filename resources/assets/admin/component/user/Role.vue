<style lang="css" module>
.container {
  padding-top: 15px;
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
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
      </tbody>
    </table>
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
