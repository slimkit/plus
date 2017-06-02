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
      <table v-show="cashes.length" class="table table-striped table-hover">
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
        <tbody>
          <tr
            v-for="cash in cashes"
            :key="cash.id" :class="cash.status === 2 ? 'danger' : cash.status === 1 ? 'success' : ''"
          >
            <!-- 用户信息 -->
            <td>{{ cash.user.name }} ({{ cash.user.id }})</td>

            <!-- 金额 -->
            <td>{{ cash.value / 100 * ratio / 100 }} ({{ cash.value / 100 }})</td>

            <!-- 账户 -->
            <td v-if="cash.type === 'alipay'">支付宝：{{ cash.account }}</td>
            <td v-else-if="cash.type === 'wechat'">微信：{{ cash.account }}</td>
            <td v-else>未知：{{ cash.account }}</td>

            <!-- 状态 -->
            <td v-if="cash.status === 1">已审批</td>
            <td v-else-if="cash.status === 2">被拒绝</td>
            <td v-else>待审批</td>

            <!-- 备注 -->
            <td v-if="actions[cash.id]">{{ remarks[cash.id] }}</td>
            <td v-else-if="cash.status === 0">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="备注" v-model="remarks[cash.id]">
              </div>
            </td>
            <td v-else>{{ cash.remark }}</td>

            <!-- 操作 -->
            <td v-if="cash.status === 0">
              <!-- 通过 -->
              <button v-if="actions[cash.id] === 1" type="button" class="btn btn-primary btn-sm" disabled="disabled">
                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              </button>
              <button v-else-if="actions[cash.id] === 2" type="button" class="btn btn-primary btn-sm" disabled="disabled">通过</button>
              <button v-else type="button" class="btn btn-primary btn-sm" @click="requestCashPassed(cash.id)">通过</button>

              <!-- 拒绝 -->
              <button v-if="actions[cash.id] === 2" type="button" class="btn btn-danger btn-sm" disabled="disabled">拒绝</button>
              <button v-else-if="actions[cash.id] === 1" type="button" class="btn btn-danger btn-sm" disabled="disabled">拒绝</button>
              <button v-else type="button" class="btn btn-danger btn-sm">拒绝</button>
            </td>
            <td v-else></td>
          </tr>
        </tbody>
      </table>

      <!-- Loading -->
      <div v-if="loading" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <div v-else-if="alert.status" class="panel-body">
        <div :class="['alert', `alert-${alert.type}`, $style.alert]" role="alert">
          {{ alert.message }}
        </div>
        <br>
        <button type="button" class="btn btn-primary" @click="requestCashes">重试</button>
      </div>

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

    <!-- 提示框 -->
    <div :class="$style.modal" v-show="modal.status">
      <div :class="$style.modalContent">
        <div :class="$style.modalIcon">
          <span v-if="modal.type"class="glyphicon glyphicon-ok-sign" style="color: #449d44;"></span>
          <span v-else class="glyphicon glyphicon-warning-sign"></span>
        </div>
        {{ modal.message }}
      </div>
    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';
export default {
  data: () => ({
    cashes: [],
    query: {},
    page: {
      last: 0,
      current: 1,
      first: 1,
    },
    alert: {
      status: false,
      message: '',
      type: 'info',
      interval: null,
    },
    loading: true,
    ratio: 100,
    actions: {},
    remarks: {},
    modal: {
      status: false,
      interval: null,
      type: false,
      message: '',
    }
  }),
  methods: {
    /**
     * 请求审批通过
     *
     * @param {Number} id
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashPassed(id) {
      // 备注
      const { [id]: remark } = this.remarks;
      if (! remark) {
        this.sendModal('请输入备注内容', false);

        return;
      }

      // 添加到正在被执行当中
      this.actions = {
        ...this.actions,
        [id]: 1
      }

      // 请求通过
      request.patch(
        createRequestURI(`wallet/cashes/${id}`),
        { remark },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.actions = lodash.reduce(this.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        this.cashes = lodash.reduce(this.cashes, function (cashes, cash) {
          if (id !== cash.id) {
            cashes.push(cash);
          }

          return cashes;
        }, []);
        this.sendModal('审核成功！');
      }).catch(({ response: { data: { remark = [], message = [] } = {} } = {} } = {}) => {
        const [ currentMessage = '提交失败，请刷新网页重试！' ] = [ ...remark, ...message ];
        this.actions = lodash.reduce(this.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            console.log(id, key);
            actions[key] = item;
          }

          return actions;
        }, {});
        this.sendModal(currentMessage, false);
      });
    },

    /**
     * 请求数据.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashes() {
      this.loading = true
      const query = { ...this.query, page: this.page.current };
      request.get(
        createRequestURI('wallet/cashes'),
        {
          query,
          validateStatus: status => status === 200
        }
      ).then(({ data = {} }) => {
        const {
          cashes = [],
          current_page: current = this.page.current,
          first_page: first = this.page.first,
          last_page: last = thus.page.last,
        } = data;
        this.loading = false;
        this.cashes = cashes;
        this.page = { last, current, first };
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} } = {}) => {
        this.loading = false;
        this.sendAlert('danger', message, false);
      });
    },

    /**
     * 发送模糊框提示
     *
     * @param {[type]} message [description]
     * @param {Boolean} success [description]
     * @return {[type]} [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendModal(message, success = true, time = 1500) {
      window.clearInterval(this.modal.interval);
      this.modal = {
        type: !!success,
        message,
        status: true,
        interval: window.setInterval(() => {
          this.modal.status = false;
          window.clearInterval(this.modal.interval);
        }, time)
      };
    },

    /**
     * 发送 alert 提示.
     *
     * @param {string} type
     * @param {string} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert(type, message, hide = true) {
      window.clearInterval(this.alert.interval);
      this.loading = false;
      this.alert = {
        ...this.alert,
        type,
        message,
        status: true,
        interval: !hide ? null : window.setInterval(() => {
          window.clearInterval(this.alert.interval);
          this.alert.status = false;
        }, 2000)
      };
    }
  },
  created() {
    this.requestCashes();
  },
};
</script>
