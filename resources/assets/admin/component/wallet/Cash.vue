<template>
  <div class="component-container container-fluid">
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
            <input type="number" class="form-control" placeholder="User ID" min="1">
          </div>

          <!-- 状态 -->
          <div class="form-group">
            <label>状态</label>
            <select class="form-control">
              <option>全部</option>
              <option>待审批</option>
              <option>已审批</option>
              <option>被拒绝</option>
            </select>
          </div>

          <!-- 排序 -->
          <div class="form-group">
            <label>排序</label>
            <select class="form-control">
              <option>最新申请</option>
              <option>时间排序</option>
            </select>
          </div>

          <!-- 搜索按钮 -->
          <button type="submit" class="btn btn-default">搜索</button>

        </div>
      </div>

      <!-- 列表 -->
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>用户</th>
            <th>金额</th>
            <th>提现账户</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Seven(id:1)</td>
            <td>1500</td>
            <td>支付宝：lovevipdsw@outlook.com</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 分页 -->
    <div class="text-center">
      <ul class="pagination">
        <li>
          <a href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li>
          <a href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </div>

  </div>
</template>