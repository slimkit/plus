<template>
<div class="container-fluid" style="margin-top:10px;">
	<div class="panel panel-default">
		<div class="panel-heading">
			检索条件
		</div>
		<div class="panel-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#ID</th>
						<th>举报人</th>
						<th>被举报人</th>
						<th>举报资源</th>
						<th>举报资源类型</th>
						<th>状态</th>
						<th>举报理由</th>
						<th>处理备注</th>
						<th>举报时间</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
                  <!-- loading -->
                  <table-loading :loadding="loading" :colspan-num="10"></table-loading>
                  <tr v-for="item in items">
                  	<td>{{ item.id }}</td>
                  	<td>{{ item.user.name }}</td>
                  	<td>{{ item.target.name }}</td>
                  	<td>{{ item | reportable }}</td>
                  	<td>{{ item.reportable_type | moduleName }}</td>
                  	<td>{{ item.status | status }}</td>
                  	<td>{{ item.reason ? item.reason : '无' }}</td>
                  	<td>
                  		<input type="text" class="form-control" v-if="!item.status" class="审核需要填写">
                  		<span v-else>{{ item.status | status }}</span>
                  	</td>
                  	<td>{{ item.created_at | localDate }}</td>
                  	<td>
                  		<button class="btn btn-primary btn-sm">通过</button>
                  		<button class="btn btn-primary btn-sm">驳回</button>
                  	</td>
                  </tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';

const reportComponent = {
	data:()=>({
		items: [],
		loading: true,
		total: 0,
	}),

	filters: {
		status(val) {
			let title = '待审核';
			if (val == 1) {
				title = '已处理';
			} else if (val == 2) {
				title = '已驳回';
			}
			return title;
		},
		moduleName(val) {
			switch (val) {
				case 'users':
					return '用户';
				case 'groups':
					return '圈子';
				case 'comments':
					return '评论';
				case 'group-posts':
					return '圈子帖子';
				default:
					return '未知';
			}
		},
		reportable(val) {
			switch (val.reportable_type) {
				case 'users':
					return val.reportable.name;
				default:
					return '未知';
			}
		}
	},

	methods: {
      getReports (query = {}) {
        this.items = [];
        this.loadding = true;
        request.get(
          createRequestURI('reports'),
          { 
            validateStatus: status => status === 200,
            params: { ...query, limit: 15 },
          }
        ).then(({ data = [], headers: { 'x-ad-total': total } }) => {
          this.loading = false;
          this.total = parseInt(total);
          this.items = data;
        }).catch(({ response: { data: { errors = ['加载失败'] } = {} } = {} }) => {
          this.loading = false;
          plusMessageFirst(errors);
        });
      },
      handleAudit(id, mark) {

      }
	},

	created() {
      this.getReports(this.$route.query);
	}
};

export default reportComponent;
</script>
