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
      权限节点，用于各个位置标示用户权限的配置～配置需要配合程序。尽量不要删除权限节点～以为节点name是在程序中赢编码的～
      这里提供管理，只是方便技术人员对节点进行管理。
    </div>

    <!-- 表格列表 -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>节点名称</th>
          <th>显示名称</th>
          <th>描述</th>
          <th>更新时间</th>
        </tr>
      </thead>
    </table>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const PermissionComponent = {
  data: () => ({
    perms: []
  }),
  created () {
    request.get(
      createRequestURI('perms'),
      { validateStatus: status => status === 200 }
    ).then(({ data }) => {
      this.perms = data;
    }).catch();
  }
};

export default PermissionComponent;
</script>
