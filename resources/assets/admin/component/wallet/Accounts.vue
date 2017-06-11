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
          <span class="help-block col-sm-6">输入交易账户的用户 ID,如果要检索不属于用户的订单，请输入 null</span>
        </div>

        <!-- 交易账户 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">交易账户</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" v-model="search.account">
          </div>
          <span class="col-sm-6 help-block">输入交易账户，应用内交易则为用户ID，例如支付宝则输入支付宝，无法获取的交易账户输入 null 查询</span>
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

    </div>
  </div>
</template>

<script>
import lodash from 'lodash';
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
    }
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
      console.log(params);
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
