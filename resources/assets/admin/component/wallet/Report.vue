<style lang="css" module>
    .container {
        padding: 15px;
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
    <div :class="$style.container">
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
          	  	  <th>总金额</th>
          	  	</tr>
          	  </thead>
          	  <tbody>
	            <tr v-show="loadding">
	                <!-- 加载动画 -->
	                <td :class="$style.loadding" colspan="7">
	                    <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
	                </td>
	            </tr>
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
import plusMessageBundle from 'plus-message-bundle';
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
        }).catch(({ response: { data: { message: [ anyMessage = '加载失败，请刷新重试' ] = [] } = {} } = {} } = {}) => {
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
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
