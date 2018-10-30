<style lang="scss" module>
.alert {
  margin: 22px 0 0;
}
.modal {
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.65);
  z-index: 9999;
  .modalContent {
    min-width: 260px;
    max-width: 400px;
    height: auto;
    margin: 10% auto;
    background: #fff;
    border-radius: 6px;
    padding: 12px;
    text-align: center;
    .modalIcon {
      font-size: 4em;
      color: #c9302c;
    }
  }
}
</style>

<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">
        提现审批
        <router-link to="setting" class="pull-right" append replace>
          <span class="glyphicon glyphicon-cog"></span>
          提现设置
        </router-link>
      </div>

      <!-- Body -->
      <div class="panel-body">
        <!-- 搜索 -->
        <div class="form-inline">
          <!-- 提现用户 -->
          <div class="form-group">
            <label>用户：</label>
            <input type="number" class="form-control" placeholder="User ID" min="1" >
          </div>

          <!-- 状态 -->
          <div class="form-group">
            <label>状态</label>
            <select class="form-control" >
              <option value="all">全部</option>
              <option value="0">待审批</option>
              <option value="1">已审批</option>
              <option value="2">被拒绝</option>
            </select>
          </div>

          <!-- 排序 -->
          <div class="form-group">
            <label>排序</label>
            <select class="form-control">
              <option value="desc">最新申请</option>
              <option value="asc">时间排序</option>
            </select>
          </div>

          <!-- 搜索 -->
          <a href="javascript:;" class="btn btn-default" tag="button">
            搜索
          </a>

        </div>
      </div>

      <!-- 列表 -->
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>用户(用户ID)</th>
            <th>金额(真实金额)</th>
            <th>提现账户</th>
            <th>状态</th>
            <th>备注</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody> </tbody>
      </table>

      <p class="panel-body">开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>

    </div>
  </div>
</template>
