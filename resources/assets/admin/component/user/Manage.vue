<style lang="css" module>
.container {
  padding-top: 15px;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">
    <div class="well well-sm">
      检索用户
      <a class="btn btn-link pull-right btn-xs" href="#" role="button">
        <span class="glyphicon glyphicon-plus"></span>
        添加用户
      </a>
    </div>

    <!-- 搜索用户 -->
    <form class="form-horizontal" @submit.prevent="getUsers">
      <div class="form-group">
        <label for="search-input-id" class="col-sm-2 control-label">用户ID</label>
        <div class="col-sm-10">
          <div class="input-group">
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                排序 <span class="glyphicon" :class="(search.sort === 'up' ? 'glyphicon-triangle-top' : 'glyphicon-triangle-bottom')"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#" @click.prevent="changeUserIdSort('up')">
                  <span class="glyphicon glyphicon-triangle-top"></span>
                  由小到大
                </a></li>
                <li><a href="#" @click.prevent="changeUserIdSort('down')">
                  <span class="glyphicon glyphicon-triangle-bottom"></span>
                  由大到小
                </a></li>
              </ul>
            </div>
            <input v-model="search.user_id" type="number" class="form-control" id="search-input-id" placeholder="按照用户ID搜索">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-email" class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
          <input v-model="search.email" type="text" class="form-control" id="search-input-email" placeholder="请输入搜索邮箱地址，支持模糊搜索">
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-phone" class="col-sm-2 control-label">手机号码</label>
        <div class="col-sm-10">
          <input v-model="search.phone" type="tel" class="form-control" id="search-input-phone" placeholder="请输入搜索手机号码，支持模糊搜索">
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-name" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-10">
          <input v-model="search.name" type="text" class="form-control" id="search-input-name" placeholder="请输入搜索用户名，支持模糊搜索">
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-name" class="col-sm-2 control-label">角色</label>
        <div class="col-sm-10">
          <select v-model="search.role" class="form-control" id="search-input-name">
            <option value="">无</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">搜索</button>
        </div>
      </div>
    </form>

    <!-- 用户列表 -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>用户ID</th>
          <th>用户名</th>
          <th>邮箱</th>
          <th>手机号码</th>
          <th>注册时间</th>
          <th>操作</th>
        </tr>
      </thead>
    </table>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const ManageComponent = {
  /**
   * 定义当前组件状态数据
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  data: () => ({
    search: {
      user_id: '',
      sort: 'up',
      email: '',
      name: '',
      role: '',
      phone: '',
      users: []
    }
  }),
  /**
   * 定义方法组.
   *
   * @type {Object}
   */
  methods: {
    /**
     * 改变用户排序状态方法.
     *
     * @enum {up, down}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    changeUserIdSort (sort) {
      this.search.sort = sort;
    },
    /**
     * 获取列表用户.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    getUsers () {
      const { user_id, sort, email, name, phone, role } = this.search;
      request.get(
        createRequestURI('users'),
        {
          params: { user_id, sort, email, name, phone, role },
          validateStatus: status => status === 201
        }
      ).then(({ data }) => console.log(data)).catch(({ response }) => console.log(response));
    }
  },
  /**
   * 组件创建完成后.
   *
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  created () {
    this.getUsers();
  }
};

export default ManageComponent;
</script>
