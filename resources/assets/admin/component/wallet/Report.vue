<template>
    <div style="padding: 15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="form-inline">
  	          <div class="form-group">
  	          	<label>时间段：</label>
  	          	<input type="date" class="form-control" v-model="filter.start">
  	          	<label>-</label>
  	          	<input type="date" class="form-control" v-model="filter.end"> 
  	          </div>
              <div class="form-group">
                <button class="btn btn-primary" @click.prevent="getWalletStatistics">确认</button>
              </div>
            </div>
          </div>
          <div class="panel-body">
          	<table class="table table-striped">
          	  <thead>
          	  	<tr>
          	  	  <th>类型</th>
          	  	  <th>总笔数</th>
          	  	  <th>总金额（元）</th>
          	  	</tr>
          	  </thead>
          	  <tbody>
                <!-- 加载 -->
                <table-loading :loadding="loadding" :colspan-num="7"></table-loading>
          	  	<tr  v-for="item in items" v-show="!loadding">
          	  	  <td>{{ item.type }}</td>
          	  	  <td>{{ item.num }}</td>
          	  	  <td>{{ item.total_amount ? item.total_amount : 0 }}</td>
          	  	</tr>
          	  </tbody>
          	</table>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';

const ReportComponent = {
    data: () => ({
      loadding: true,
      items: [],
      filter: {
      	start: '',
      	end: '',
      	type: '',
      },
      message: {
      	success: null,
      	error: null,
      }
    }),
    methods: {
      getWalletStatistics() {
      	this.loadding = true;
      	let query = this.getQueryParams();
        request.get(
          createRequestURI(`wallet/statistics${query}`),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.items = response.data;
        }).catch(({ response: { data: { errors = '加载数据失败，请重试' } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = errors;
        });
      },
      getQueryParams() {
        let query = '?';
        query += 'start=' + this.filter.start;
        query += '&end=' + this.filter.end;
        return query;
      },
    },
    created () {
   	  this.getWalletStatistics();
    },
};
export default ReportComponent;
</script>
