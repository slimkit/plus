<template>
  <div class="component-container container-fluid">
    <!-- 错误提示 -->
    <transition name="fade">
      <div v-if='!!tips.error || !!tips.success || false' class="alert alert-dismissible" :class="tips.success?'alert-success':'alert-danger'">
        <button type="button" class="close" @click.prevent="dismisError">
          <span aria-hidden="true">&times;</span>
        </button> {{ tips.error || tips.success }}
      </div>
    </transition>
    <!-- 举报管理面板 -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-3">
            <div class="input-group" :class="{'has-errors has-error':search.error}">
              <input type="text" class="form-control" placeholder="输入关键字" v-model="search.key">
              <span class="input-group-btn">
                <input class="btn btn-default" type="button" @click='getReportList(1)' value='搜索' />
              </span>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group rs">
              <label class="radio-inline" v-for='state in state'>
                <input type="radio" name="optionsRadios" v-model='search.state' @click='getReportList(1)' :value="state.value">{{state.label}}
              </label>
            </div>
          </div>
          <div class="col-xs-4 text-right">
            <Page :current='current_page' :last='last_page' @pageGo='pageGo'></Page>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-hove text-center table-striped table-bordered">
          <thead>
            <tr>
              <th>举报者</th>
              <th>资源连接</th>
              <th>举报理由</th>
              <th>举报时间</th>
              <th>处理状态</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="report in report" :key="report.id" :class="state2style(report.state)">
              <td>{{ report.user.name }}</td>
              <td><a :href="report.source_url" target="_bank" title="report.source_url">前往查看</a></td>
              <td>{{ report.reason}}</td>
              <td>{{ report.created_at }}</td>
              <td>{{ state2label(report.state) }}</td>
              <td>
                <!-- 处理 -->
                <button type="button" class="btn btn-primary btn-sm" :disabled='report.state === 1' @click='managereport(report.id)'>处理</button>
                <!-- 删除 -->
                <button type="button" class="btn btn-danger btn-sm" :disabled='report.state === 2' @click='deletereport(report.id)'>删除</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
import request, {
  createRequestURI
} from '../../util/request';
import Page from '../Page.vue';
export default {
  name: 'report',
  components: {
    Page
  },
  data: () => ({
    loading: false,
    tips: {
      error: null,
      success: null
    },
    editID: '',
    state: [{
      value: -1,
      label: '未处理'
    }, {
      value: 1,
      label: '已处理'
    }],
    search: {
      state: '',
      key: ''
    },
    current_page: 1,
    last_page: 0,
    report: []
  }),
  methods: {
    dismisError() {
      this.tips.error = null;
      this.tips.success = null;
    },
    getReportList(page) {
      this.dismisError();
      this.loadding = true;
      request.get('/pc/admin/denounce', {
          params: {
            page: page || this.current_page,
            ...this.search
          }
        })
        .then(response => {
          this.loadding = false;
          if (!response.data.status) return this.tips.error = '获取数据失败！';
          let {
            data = []
          } = response.data;
          this.report = data;
          if (!data.length) return this.tips.error = '暂无数据！';
        }).catch(({
          response: {
            data: {
              errors = ['加载数据失败']
            } = {}
          } = {}
        }) => {
          this.loadding = false;
        });
    },
    managereport(_id) {
      request.post('/pc/admin/denounce/handle/' + _id, {
          state: 1
        })
        .then(response => {
          this.editID = null;
          if (!response.data.status) return this.tips.error = '处理失败！';
          this.getReportList();
        }).catch(({
          response: {
            data: {
              errors = ['处理失败']
            } = {}
          } = {}
        }) => {
          this.editID = null;
        });
    },
    deletereport(_id) {
      request.post('/pc/admin/denounce/handle/' + _id, {
          state: -1
        })
        .then(response => {
          this.deleteID = null;
          if (!response.data.status) return this.tips.error = '删除失败！';
          this.getReportList();
        }).catch(({
          response: {
            data: {
              errors = ['删除失败']
            } = {}
          } = {}
        }) => {
          this.deleteID = null;
        });
    },
    state2label(v) {
      // 处理状态，0：未处理；1：已处理 
      switch (v) {
        case 0:
          return '未处理';
        case 1:
          return '已处理';
        default:
          return '未处理';
      }
    },
    state2style(v) {
      // 处理状态，0：未处理；1：已处理 
      switch (v) {
        case 0:
          return 'info';
        case 1:
          return '';
        default:
          return 'info';
      }
    },
    pageGo(page) {
      this.current_page = page;
      this.getReportList();
    }
  },
  created() {
    this.getReportList()
  },
}
</script>
<style scope>
.table th {
  text-align: center;
}

.mb0 {
  margin-bottom: 5px !important;
}

.rs {
  margin-top: 7px;
  margin-bottom: 0px;
  /*    margin-left: -5%;*/
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

.has-errors input {
  border-color: #a94442;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
</style>
