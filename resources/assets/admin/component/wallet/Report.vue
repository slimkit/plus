<template>
    <div class="container-fluid" style="margin-top:10px;">
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
          <div class="panel-body">
          	<table class="table table-striped">
          	  <thead>
          	  	<tr>
          	  	  <th>类型</th>
          	  	  <th>总数量</th>
          	  	  <th>中金额（单位:分）</th>
          	  	</tr>
          	  </thead>
          	  <tbody>
                <!-- 加载 -->
                <table-loading :loadding="loadding" :colspan-num="7"></table-loading>
          	  	<tr  v-for="(item, key) in items" v-show="!loadding">
          	  	  <td>{{ key | type }}</td>
          	  	  <td>{{ item.count }}</td>
          	  	  <td>{{ item.sum }}</td>
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
    filters: {
      type(val) {
        if (val == 'expenditure') {
          return '支出';
        } else if(val == 'income') {
          return '收入';
        } else {
          return '未知';
        }
      }
    },
    methods: {
      getWalletStatistics() {
      	this.loadding = true;
        request.get(
          createRequestURI(`new-wallet/statistics`),
          { validateStatus: status => status === 200 }
        ).then(({ data = {}}) => {
          this.loadding = false;
          this.items = data;
        }).catch(({ response: { data: { errors = '加载数据失败，请重试' } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = errors;
        });
      },
    },
    created () {
   	  this.getWalletStatistics();
    },
};
export default ReportComponent;
</script>
