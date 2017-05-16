<template>
  <div class="component-container container-fluid">
    <!-- error -->
    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>
    <div class="panel panel-default">
      <!-- 短信记录面板 -->
      <div class="panel-heading">
        <div class="input-group" style="
          max-width: 356px;
        ">
          <div class="input-group-btn">
            <!-- 状态 -->
            <button type="button" class="btn btn-default dropdown-toggle" id="state" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">状态 <span class="caret"></span></button>
            <ul class="dropdown-menu" aria-labelledby="state">
              <li>
                <a href="#" @click.prevent="changeState(-1)">
                  <span v-if="search.state === -1" class="glyphicon glyphicon-ok-circle"></span>
                  <span v-else class="glyphicon glyphicon-record"></span>
                  全部状态
                </a>
              </li>
              <li>
                <a href="#" @click.prevent="changeState(0)">
                  <span v-if="search.state === 0" class="glyphicon glyphicon-ok-circle"></span>
                  <span v-else class="glyphicon glyphicon-record"></span>
                  未发送
                </a>
              </li>
              <li>
                <a href="#" @click.prevent="changeState(1)">
                  <span v-if="search.state === 1" class="glyphicon glyphicon-ok-circle"></span>
                  <span v-else class="glyphicon glyphicon-record"></span>
                  发送成功
                </a>
              </li>
              <li>
                <a href="#" @click.prevent="changeState(2)">
                  <span v-if="search.state === 2" class="glyphicon glyphicon-ok-circle"></span>
                  <span v-else class="glyphicon glyphicon-record"></span>
                  发送失败
                </a>
              </li>
            </ul>
          </div>
          <input type="text" class="form-control" aria-label="input-group-btn" placeholder="输入要搜索的手机号码" v-model="search.keyword">
          <div class="input-group-btn">
            <button v-if="loading === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              搜索...
            </button>
            <button v-else class="btn btn-primary" type="submit" @click.stop.prevent="requestLogs">搜索</button>
          </div>
        </div>
      </div>
      <!-- Table -->
      <table class="table table-hove">
        <thead>
          <tr>
            <th>手机号码</th>
            <th>验证码</th>
            <th>状态</th>
            <th>时间</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs" :key="log.id">
            <td>{{ log.account }}</td>
            <td>{{ log.code }}</td>
            <td v-if="log.state === 0" style="color: #5bc0de;">未发送</td>
            <td v-else-if="log.state === 1" style="color: #449d44;">发送成功</td>
            <td v-else-if="log.state === 2" style="color: #d9534f;">发送失败</td>
            <td v-else>未知状态</td>
            <td>{{ log.created_at }}</td>
          </tr>
        </tbody>
      </table>
      <div class="panel-footer">
        <nav aria-label="...">
          <ul class="pager">
            <li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> Older</a></li>
            <li class="next"><a href="#">Newer <span aria-hidden="true">&rarr;</span></a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const SmsMainComponent = {
  data: () => ({
    search: {
      state: -1,
      keyword: '',
      after: [],
    },
    loading: false,
    logs: [],
    error: null,
  }),
  methods: {
    dismisError() {
      this.error = null;
    },
    changeState(state) {
      this.search.after = [];
      this.search.state = state;
    },
    requestLogs() {
      const [last = null] = this.search.after;
      let params = {
        after: last,
      };

      if (this.search.state >= 0) {
        params['state'] = this.search.state;
      }

      if (this.search.keyword.length) {
        params['phone'] = this.search.keyword;
      }

      this.loading = true;

      request.get(
        createRequestURI('sms'),
        { params, validateStatus: status => status === 200 }
      ).then(({ data = [] }) => {
        this.loading = false;
        if (!data.length) {
          this.error = '没有更多消息了';
          return;
        }

        const last = data.pop();
        this.logs = [...data, last];
        this.search.after = [last.id, this.search.after];
      }).catch();
    }
  },
  created() {
    this.requestLogs();
  }
};

export default SmsMainComponent;
</script>
