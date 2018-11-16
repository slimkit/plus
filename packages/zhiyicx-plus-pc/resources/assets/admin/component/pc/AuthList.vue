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
    <!-- 认证列表面板 -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-3">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="用户名" v-model='search.key'>
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" @click='getAuthList(1)'>搜索</button>
              </span>
            </div>
          </div>
          <div class="col-xs-3">
            <div class="form-group rs">
              <label class="radio-inline" v-for='state in state'>
                <input type="radio" name="optionsRadios" v-model='search.state' @click='getAuthList(1)' :value="state.value">{{state.label}}
              </label>
            </div>
          </div>
          <div class="col-xs-4 text-right">
            <Page :current='current_page' :last='last_page' @pageGo='pageGo'></Page>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <!-- Table -->
        <table class="table table-hove text-center table-striped table-bordered">
          <thead>
            <tr>
              <th>用户ID</th>
              <th>用户名</th>
              <th>真实姓名</th>
              <th>身份证号</th>
              <th>联系方式</th>
              <th>认证补充</th>
              <th>认证资料</th>
              <th>认证状态</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="auth in auth" :key="auth.id" :class="verified2stye(auth.verified)">
              <td>{{ auth.user_id }}</td>
              <td>{{ auth.user.name || '' }}</td>
              <td>{{ auth.realname }}</td>
              <td>{{ auth.idcard }}</td>
              <td>{{ auth.phone }}</td>
              <td>{{ auth.info }}</td>
              <td>
                <a v-if="auth.storage" :href="`/api/v1/storages/${auth.storage}`" target="_blank" title="">查看详情</a>
                <span v-else>暂无认证资料</span>
              </td>
              <td>{{ verified2label(auth.verified) }}</td>
              <td>
                <!-- 审核 -->
                <button type="button" class="btn btn-primary btn-sm" :disabled='auth.verified === 1' @click='manageAuth(auth)'>通过</button>
                <!-- 驳回 -->
                <button type="button" class="btn btn-danger btn-sm" :disabled='auth.verified === 2' @click='backAuth(auth)'>驳回</button>
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
  name: 'authlist',
  components: {
    Page
  },
  data: () => ({
    loading: false,
    tips: {
      error: null,
      success: null
    },
    auditID: '',
    state: [{
      value: '',
      label: '全部'
    }, {
      value: -1,
      label: '未认证'
    }, {
      value: 1,
      label: '成功'
    }, {
      value: 2,
      label: '失败'
    }],
    search: {
      state: '',
      key: ''
    },
    current_page: 1,
    last_page: 0,
    auth: []
  }),
  created() {
    this.getAuthList()
  },
  methods: {
    dismisError() {
      this.tips.error = null;
      this.tips.success = null;
    },
    getAuthList(page) {
      this.dismisError();
      this.loadding = true;
      request.get('/pc/admin/auth', {
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
          this.auth = data;
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
    backAuth(auth) {
      request.post('/pc/admin/auth/audit/' +auth.id, {
          state: 2
        })
        .then(response => {
          this.auditID = null;
          if (!response.data.status) return this.tips.error = '操作失败！';
          this.getAuthList();
        }).catch(({
          response: {
            data: {
              errors = ['操作失败！']
            } = {}
          } = {}
        }) => {
          this.deleteID = null;
        });
    },
    manageAuth(auth) {
      // 认证状态，0：未认证；1：成功 2 :  失败
      request.post('/pc/admin/auth/audit/' + auth.id, {
          state:1
        })
        .then(response => {
          this.auditID = null;
          if (!response.data.status) return this.tips.error = '操作失败！';
          this.getAuthList();
        }).catch(({
          response: {
            data: {
              errors = ['操作失败！']
            } = {}
          } = {}
        }) => {
          this.deleteID = null;
        });
    },
    verified2label(v) {
      // 认证状态，0：未认证；1：成功 2 :  失败
      switch (v) {
        case 0:
          return '未认证';
        case 1:
          return '成功';
        case 2:
          return '失败';
        default:
          return '未知状态';
      }
    },
    verified2stye(v) {
      // 认证状态，0：未认证；1：成功 2 :  失败
      switch (v) {
        case 0:
          return 'info';
        case 1:
          return '';
        case 2:
          return 'danger';
        default:
          return 'info';
      }
    },
    pageGo(page) {
      this.current_page = page;
      this.getAuthList();
    }
  }
}
</script>
<style>
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
