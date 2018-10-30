<template>
	<div class="container-fluid" style="margin-top:10px;">
		<div class="panel panel-default">
			<div class="panel-heading">
				钱包流水
			</div>
			<div class="panel-heading">
				<div class="form-inline">
          <div class="form-group">
              <input type="text" class="form-control" placeholder="用户ID">
          </div>
          <div class="form-group">
            <select class="form-control">
              <option value="">全部</option>
              <option value="0">等待</option>
              <option value="1">成功</option>
              <option value="-1">失败</option>
            </select>
          </div>
          <div class="form-group">
            <a href="javascript:;" class="btn btn-default">确认
            </a>
          </div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
          <thead>
            <tr>
              <th>#ID</th>
              <th>用户ID</th>
              <th>目标类型</th>
              <th>标题</th>
              <th>内容</th>
              <th>动作</th>
              <th>金额</th>
              <th>状态</th>
              <th>时间</th>
            </tr>
          </thead>
          <tbody> </tbody>
        </table>
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
			</div>
		</div>
	</div>
</template>
