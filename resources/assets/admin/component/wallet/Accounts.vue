<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">订单流水</div>

      <!-- Search -->
      <div class="panel-body form-horizontal">

        <!-- 用户 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">用户</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" v-model="search.user">
          </div>
          <span class="help-block col-sm-6">输入交易账户的用户 ID</span>
        </div>

        <!-- 交易账户 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">交易账户</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" v-model="search.account">
          </div>
          <span class="col-sm-6 help-block">输入交易账户，应用内交易则为用户ID</span>
        </div>

        <!-- Ping++ 凭据 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Ping++ 凭据</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" v-model="search.chargeId">
          </div>
          <span class="col-sm-6 help-block">输入来自于 Ping++ 平台的凭据 ID</span>
        </div>

        <!-- 订单记录，来自第三方平台 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">订单 ID</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" v-model="search.transactionNo">
          </div>
          <span class="col-sm-6 help-block">输入第三方平台的订单 ID，例如支付宝订单</span>
        </div>

        <!-- 增还是减？ -->
        <div class="form-group">
          <label class="col-sm-2 control-label">类型</label>
          <div class="col-sm-4">
            <select class="form-control" v-model="search.action">
              <option :value="null">全部</option>
              <option value="1">增项</option>
              <option value="0">减项</option>
            </select>
          </div>
          <span class="col-sm-6 help-block">选择订单交易类型</span>
        </div>

        <!-- 状态 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">状态</label>
          <div class="col-sm-4">
            <select class="form-control" v-model="search.status">
              <option :value="null">全部</option>
              <option value="0">等待</option>
              <option value="1">成功</option>
              <option value="2">失败</option>
            </select>
          </div>
          <span class="col-sm-6 help-block">选择订单的交易状态</span>
        </div>

        <!-- 搜索按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <router-link class="btn btn-primary" tag="button" :to="{ path: '/wallet/accounts', query: searchQuery }">搜索</router-link>
          </div>
        </div>

      </div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- List table -->
      <table v-else-if="load.status === 1" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>用户（ID）</th>
            <th>支付频道</th>
            <th>交易账户</th>
            <th>Ping++</th>
            <th>订单ID</th>
            <th>金额</th>
            <th>交易信息</th>
            <th>状态</th>
            <th>时间</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="charge in charges" :key="charge.id" :class="charge.status === 2 ? 'danger' : charge.status === 1 ? 'success' : ''">
            <td>{{ charge.id }}</td>
            <td>{{ resolveChargeUserDisplay(charge.user) }}</td>
            <td>{{ resolvePayChannel(charge.channel) }}</td>
            <td>{{ charge.account }}</td>
            <td>{{ charge.charge_id }}</td>
            <td>{{ charge.transaction_no }}</td>
            <td>{{ charge.action == 1 ? '+' : '-' }}{{ charge.amount / 100 }}</td>
            <td>
              {{ charge.subject }}<br>
              <small>{{ charge.body }}</small>
            </td>
            
            <!-- 状态 -->
            <td v-if="charge.status === 0">等待</td>
            <td v-else-if="charge.status === 1">成功</td>
            <td v-else-if="charge.status === 2">失败</td>
            <td v-else>未知</td>

            <td>{{ charge.created_at }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Load error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ load.message }}</div>
        <button type="button" class="btn btn-primary" @click="requestRefresh">重试</button>
      </div>

    </div>

    <!-- 分页 -->
    <div class="text-center" v-show="pagination.show">
      <ul class="pagination">
        <!-- 上一页按钮 -->
        <router-link
          v-show="!!pagination.isPrevPage"
          tag="li"
          :to="resolvePaginationRoute(pagination.isPrevPage)"
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
          :to="resolvePaginationRoute(item.page)"
        >
          <a>{{ item.name }}</a>
        </router-link>

        <!-- 当前页码 -->
        <router-link class="active" tag="li" :to="resolvePaginationRoute(pagination.current)">
          <a>
            {{ pagination.current }}
          </a>
        </router-link>

        <!-- 后页码 -->
        <router-link
          v-for="item in pagination.nextPages"
          tag="li"
          :key="item.page"
          :to="resolvePaginationRoute(item.page)"
        >
          <a>{{ item.name }}</a>
        </router-link>

        <!-- 下一页按钮 -->
        <router-link
          v-show="!!pagination.isNextPage"
          tag="li"
          :to="resolvePaginationRoute(pagination.isNextPage)"
        >
          <a aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </router-link>
        
      </ul>
    </div>

  </div>
</template>

<script>
import lodash from 'lodash';
import request, { createRequestURI } from '../../util/request';

const channelTypes = {
  applepay_upacp: 'Apple Pay',
  alipay: '支付宝 APP',
  alipay_pc_direct: '支付宝电脑网站',
  alipay_qr: '支付宝扫码',
  alipay_wap: '支付宝手机网页',
  wx: '微信 APP',
  wx_wap: '微信 WAP'
};

export default {
  data: () => ({
    // Search state.
    search: {
      user: null, // 搜索用户
      account: null, // 搜索账户
      chargeId: null, // Ping++ 凭据ID
      transactionNo: null, // 第三方平台订单ID
      action: null, // 动作
      status: null // 状态
    },

    // Page
    page: {
      current: 1,
      total: 1,
    },

    // 凭据
    charges: [],

    // Load
    load: {
      status: 0,
      message: ''
    },
  }),
  computed: {
    /**
     * Compute search query.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    searchQuery() {
      return lodash.reduce(this.search, function (search, value, key) {
        if (!! value) {
          search[key] = value;
        }
        return search;
      }, {});
    },
    /**
     * Pagination.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    pagination() {
      // 当前页
      let current = 1;
      current = isNaN(current = parseInt(this.page.current)) ? 1 : current;

      // 最后页码
      let last = 1;
      last = isNaN(last = parseInt(this.page.total)) ? 1 : last;

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
    }
  },
  watch: {
    /**
     * The this.$route watcher.
     *
     * @param {Object} options.query
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    '$route': function ({ query = {} } = {}) {
      this.requestCharge(
        this.resolveQuery(query)
      );
    }, 
  },
  methods: {
    /**
     * Builder page route object.
     *
     * @param {Number} page
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolvePaginationRoute(page) {
      return {
        path: '/wallet/accounts',
        query: { ...this.resolveQuery(), page }
      };
    },

    /**
     * 返回显示的频道类型.
     *
     * @param {String} channel
     * @return {String}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolvePayChannel(channel) {
      const { [channel]: displayChannel = '余额' } = channelTypes;

      return displayChannel;
    },
    /**
     * 解决用户信息显示.
     *
     * @param {Object} user
     * @return {String|null}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveChargeUserDisplay(user = {}) {
      const { id, name } = user;
      if (!! id) {
        return `${name} (${id})`;
      }

      return null;
    },
    /**
     * Request refresh.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestRefresh() {
      this.requestCharge({
        page: this.page.current,
        ...this.resolveQuery(),
      });
    },
    /**
     * Resolve route query.
     *
     * @param {Object} query
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    resolveQuery(query = this.$route.query) {
      return lodash.reduce(query, function (query, value, key) {
        switch (key) {
          case 'user':
          case 'action':
          case 'status':
            if (!! value) {
              query[key] = isNaN(value = parseInt(value)) ? null : value;
              break;
            }
            query[key] = value;
            break;

          case 'page':
            query[key] = (isNaN(value = parseInt(value)) || value <= 1) ? 1 : value;

          default:
            query[key] = value;
            break;
        }

        return query;
      }, {});
    },

    /**
     * Request the charges.
     *
     * @param {Object} params
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCharge(params = {}) {
      this.load.status = 0;
      request.get(
        createRequestURI('wallet/charges'),
        { params, validateStatus: status => status === 200 }
      ).then(({ data: { total = 1, current = 1, items = [] } }) => {
        this.load.status = 1;
        this.page = { total, current };
        this.charges = items;
      }).catch(({ response: { data: { message: [ anyMessage = '加载失败，请刷新重试' ] = [] } = {} } = {} } = {}) => {
        this.load = { message: anyMessage, status: 2 };
      });
    }
  },
  /**
   * The component created handle.
   * Init the component.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created() {
    const { page = 1, ...query } = this.resolveQuery();
    this.search = {
      ...this.search,
      ...query
    };
    this.page.current = page;

    // Request Charges.
    this.requestCharge({ ...query, page });
  }
};
</script>
