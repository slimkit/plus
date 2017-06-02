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
            <input type="number" class="form-control" placeholder="User ID" min="1" v-model.lazy="queryTemp.user">
          </div>

          <!-- 状态 -->
          <div class="form-group">
            <label>状态</label>
            <select class="form-control" v-model="queryTemp.status">
              <option value="all">全部</option>
              <option value="0">待审批</option>
              <option value="1">已审批</option>
              <option value="2">被拒绝</option>
            </select>
          </div>

          <!-- 排序 -->
          <div class="form-group">
            <label>排序</label>
            <select class="form-control" v-model="queryTemp.order">
              <option value="desc">最新申请</option>
              <option value="asc">时间排序</option>
            </select>
          </div>

          <!-- 搜索 -->
          <router-link class="btn btn-default" tag="button" :to="{ path: '/wallet/cash', query: searchQuery }">
            搜索
          </router-link>

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
              <button v-else type="button" class="btn btn-danger btn-sm" @click="requestCashRefuse(cash.id)">拒绝</button>
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
    <div class="text-center" v-show="pagination.show">
      <ul class="pagination">
        <!-- 上一页按钮 -->
        <router-link
          v-show="!!pagination.isPrevPage"
          tag="li"
          :to="{ path: '/wallet/cash', query: resolveStatus2Query({ page: pagination.isPrevPage }) }"
        >
          <a aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </router-link>

        <!-- 前页码 -->
        <router-link
          v-for="item in pagination.prevPages"
          tag="li"
          :key="item.page"
          :to="{ path: '/wallet/cash', query: resolveStatus2Query({ page: item.page }) }"
        >
          <a>{{ item.name }}</a>
        </router-link>

        <!-- 当前页码 -->
        <router-link class="active" tag="li" :to="{ path: '/wallet/cash', query: resolveStatus2Query({ page: pagination.current }) }">
          <a>
            {{ pagination.current }}
          </a>
        </router-link>

        <!-- 后页码 -->
        <router-link
          v-for="item in pagination.nextPages"
          tag="li"
          :key="item.page"
          :to="{ path: '/wallet/cash', query: resolveStatus2Query({ page: item.page }) }"
        >
          <a>{{ item.name }}</a>
        </router-link>

        <!-- 下一页按钮 -->
        <router-link
          v-show="!!pagination.isNextPage"
          tag="li"
          :to="{ path: '/wallet/cash', query: resolveStatus2Query({ page: pagination.isNextPage }) }"
        >
          <a aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </router-link>
        
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
    queryTemp: {
      user: null,
      status: 'all',
      order: 'desc',
    },
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
  computed: {
    pagination() {
      // 当前页
      let current = 1;
      current = isNaN(current = parseInt(this.page.current)) ? 1 : current;

      // 最后页码
      let last = 1;
      last = isNaN(last = parseInt(this.page.last)) ? 1 : last;

      // 是否显示
      const show = last > 1;

      // 前三页
      let minPage = current - 3;
      minPage = minPage <= 1 ? 1 : minPage;

      // 后三页
      let maxPage = current + 3;
      maxPage = maxPage >= last ? last : maxPage;

      // 是否有上一页
      let isPrevPage = false;
      // 前页页码
      let prevPages = [];

      // 前页计算
      if (current > minPage) {
        // 有上一页按钮
        isPrevPage = current - 1; // 如果有，传入上一页页码.

        // 回归第一页
        if (minPage > 1) {
          prevPages.push({
            name: current < 6 ? 1 : '1...',
            page: 1,
          });
        }

        // 前页码
        for (let i = minPage; i < current; i++) {
          prevPages.push({
            name: i,
            page: i
          });
        }
      }

      // 是否有下一页
      let isNextPage = false;
      // 后页页码
      let nextPages = [];

      // 后页计算
      if (current < maxPage) {
        // 后页码
        for (let i = current + 1; i <= maxPage; i++) {
          nextPages.push({
            name: i,
            page: i,
          });
        }

        // 进入最后页
        if (maxPage < last) {
          nextPages.push({
            name: current + 4 === last ? last : '...'+last,
            page: last,
          })
        }

        // 是否有下一页按钮
        isNextPage = current + 1;
      }

      return {
        isPrevPage,
        isNextPage,
        current,
        show,
        prevPages,
        nextPages,
      }
    },
    searchQuery () {
      return {
        ...this.query,
        ...this.queryTemp,
        page: 1,
      };
    }
  },
  watch: {
    '$route' (to) {
      const { page = this.page.current, ...query = this.query } = this.resolveQueryString(to);
      this.query = query;
      this.page.current = page;
      this.requestCashes({
        ...query,
        page
      });
    }
  },
  methods: {
    /**
     * 驳回提现申请
     *
     * @param {Number} id
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashRefuse(id) {
      // 备注
      const { [id]: remark } = this.remarks;
      if (! remark) {
        this.sendModal('请输入备注内容', false);

        return;
      }

      // 添加到正在被执行当中
      this.actions = {
        ...this.actions,
        [id]: 2
      }

      // 请求通过
      request.patch(
        createRequestURI(`wallet/cashes/${id}/refuse`),
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
          if (id === cash.id) {
            cash.remark = remark;
            cash.status = 2;
          }
          cashes.push(cash);

          return cashes;
        }, []);
        this.sendModal('操作成功！');
      }).catch(({ response: { data: { remark = [], message = [] } = {} } = {} } = {}) => {
        const [ currentMessage = '提交失败，请刷新网页重试！' ] = [ ...remark, ...message ];
        this.actions = lodash.reduce(this.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        this.sendModal(currentMessage, false);
      });
    },

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
        createRequestURI(`wallet/cashes/${id}/passed`),
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
          if (id === cash.id) {
            cash.remark = remark;
            cash.status = 1;
          }
          cashes.push(cash);

          return cashes;
        }, []);
        this.sendModal('审核成功！');
      }).catch(({ response: { data: { remark = [], message = [] } = {} } = {} } = {}) => {
        const [ currentMessage = '提交失败，请刷新网页重试！' ] = [ ...remark, ...message ];
        this.actions = lodash.reduce(this.actions, function (actions, item, key) {
          if (parseInt(id) !== parseInt(key)) {
            actions[key] = item;
          }

          return actions;
        }, {});
        this.sendModal(currentMessage, false);
      });
    },

    /**
     * 请求列表数据.
     *
     * @param {Object} query
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashes(query = {}) {
      this.loading = true;
      this.cashes = [];
      this.alert.status = false;
      request.get(
        createRequestURI('wallet/cashes'),
        {
          params: this.resolveStatus2Query(query),
          validateStatus: status => status === 200
        }
      ).then(({ data = {} }) => {
        const {
          ratio = 100,
          cashes = [],
          current_page: current = this.page.current,
          first_page: first = this.page.first,
          last_page: last = thus.page.last,
        } = data;
        this.loading = false;
        this.cashes = cashes;
        this.page = { last, current, first };
        this.ratio = ratio;
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} } = {}) => {
        this.loading = false;
        this.page = { last: 0, current: 1, first: 1 };
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
    },

    /**
     * 将状态转换为可供查询的查询对象.
     *
     * @param {Object} query
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveStatus2Query(query = {}) {
      return { ...this.query, page: this.page.current, ...query };
    },

    /**
     * 解决网页请求参数.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveQueryString(route = false) {
      const {
        page = this.page.current,
        user,
        status,
        order,
      } = route !== false ? route.query : this.$route.query;

      let query = {};

      // 用户
      if (!! user) {
        query['user'] = user;
      }

      // 状态
      if (!! status) {
        query['status'] = status;
      }

      // 排序
      if (!! order) {
        query['order'] = order;
      }

      query['page'] = parseInt(page);

      return query;
    },
  },
  created() {
    this.requestCashes(
      this.resolveQueryString()
    );
    const { user, status, order } = this.$route.query;
    this.queryTemp = { user, status, order };
  },
};
</script>
