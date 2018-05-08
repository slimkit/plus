<template>
    <div class="container-fluid" style="margin-top:10px;">
    <!-- error -->
    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
          检索用户
          <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/users/add" role="button">
            <span class="glyphicon glyphicon-plus"></span>
            添加
          </router-link>
          <span class="pull-right">&nbsp;\&nbsp;</span>
          <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/users/trashed" role="button">
            <span class="glyphicon glyphicon-warning-sign"></span>
            停用管理
          </router-link>
        </div>
      <div class="panel-heading">
        <!-- 搜索用户 -->
        <div class="form-horizontal">
          <div class="form-group">
            <label for="search-input-id" class="col-sm-2 control-label">用户ID</label>
            <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    排序 <span class="glyphicon" :class="(sort === 'up' ? 'glyphicon-triangle-top' : 'glyphicon-triangle-bottom')"></span>
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
                <input v-model="userId" type="number" class="form-control" id="search-input-id" placeholder="按照用户ID搜索">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="search-input-email" class="col-sm-2 control-label">注册时间</label>
            <div class="col-sm-8">
              <div class="input-group">
                  <input type="date" class="form-control" v-model="regist_start_date">
                  <div class="input-group-addon">-</div>
                  <input type="date" class="form-control" v-model="regist_end_date">
              </div>
            </div>  
          </div>
          <div class="form-group">
            <label for="search-input-email" class="col-sm-2 control-label">区域</label>
            <area-linkage v-model='location'></area-linkage>
          </div>
          <div class="form-group">
            <label for="search-input-email" class="col-sm-2 control-label">邮箱</label>
            <div class="col-sm-8">
              <input v-model="email" type="text" class="form-control" id="search-input-email" placeholder="请输入搜索邮箱地址，支持模糊搜索">
            </div>
          </div>
          <div class="form-group">
            <label for="search-input-phone" class="col-sm-2 control-label">手机号码</label>
            <div class="col-sm-8">
              <input v-model="phone" type="tel" class="form-control" id="search-input-phone" placeholder="请输入搜索手机号码，支持模糊搜索">
            </div>
          </div>
          <div class="form-group">
            <label for="search-input-name" class="col-sm-2 control-label">用户名</label>
            <div class="col-sm-8">
              <input v-model="name" type="text" class="form-control" id="search-input-name" placeholder="请输入搜索用户名，支持模糊搜索">
            </div>
          </div>
          <div class="form-group">
            <label for="search-input-name" class="col-sm-2 control-label">角色</label>
            <div class="col-sm-8">
              <select v-model="role" class="form-control" id="search-input-name">
                <option value="0">全部</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.display_name }}</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="search-input-name" class="col-sm-2 control-label">关注设置</label>
            <div class="col-sm-8">
              <select v-model="follow" class="form-control" id="search-input-name">
                <option value="0">全部</option>
                <option value="1">注册时被关注</option>
                <option value="2">注册时相互关注</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <router-link class="btn btn-default" tag="button" :to="{ path: '/users', query: searchQuery }">
                搜索
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <!-- 用户列表 -->
        <table class="table table-striped">
          <thead>
            <tr>
              <th>用户ID</th>
              <th>用户名</th>
              <th>邮箱</th>
              <th>手机号码</th>
              <th>地理位置</th>
              <th>注册时间</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <table-loading :loadding="loadding" :colspan-num="6"></table-loading>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.phone }}</td>
              <td>{{ user.location }}</td>
              <td>{{ user.created_at | localDate }}</td>
              <td>
                <!-- 编辑 -->
                <router-link type="button" class="btn btn-primary btn-sm" :to="`/users/manage/${user.id}`" >编辑</router-link>

                <button type="button" class="btn btn-primary btn-sm" v-if="user.recommended === null" @click="handleRecommend(user.id)">推荐Ta</button>
                <button v-else type="button" class="btn btn-danger btn-sm" @click="handleUnRecommend(user.id)">不推荐了</button>

                <button type="button" class="btn btn-primary btn-sm" v-if="user.famous === null" @click="handleFollowedFamous(user.id)">设置被关注</button>
                <button type="button" class="btn btn-primary btn-sm" v-if="user.famous === null" @click="handleEachFamous(user.id)">设置相互关注</button>
                <button v-else type="button" class="btn btn-danger btn-sm" @click="handleUnFamous(user.id)">取消关注设置</button>
                <!-- 删除 -->
                <button v-if="deleteIds.indexOf(user.id) !== -1" type="button" class="btn btn-danger btn-sm" disabled="disabled">
                  <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                </button>
                <button v-else type="button" class="btn btn-danger btn-sm" @click="deleteUser(user.id)">禁用</button>
              </td>
            </tr>
          </tbody>
        </table>
        <ul class="pager" v-show="page >= 1 && lastPage > 1">
          <li class="previous" :class="page <= 1 ? 'disabled' : ''">
            <router-link :to="{ path: '/users', query: prevQuery }">
              <span aria-hidden="true">&larr;</span>
              上一页
            </router-link>
          </li>
          <p style="display: initial">共{{total}}个用户，{{lastPage}}页，当前为第{{current_page}}页</p>
          <li class="next" :class="page >= lastPage ? 'disabled': ''">
            <router-link :to="{ path: '/users', query: nextQuery }">
              下一页
              <span aria-hidden="true">&rarr;</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import lodash from 'lodash';
import { mapGetters } from 'vuex';
import request, { createRequestURI } from '../../util/request';
import AreaLinkage from './AreaLinkage';

const ManageComponent = {
  components: {
    'area-linkage': AreaLinkage
  },
  /**
   * 定义当前组件状态数据
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  data: () => ({
    userId: '',
    sort: 'down',
    email: '',
    name: '',
    role: 0,
    phone: '',
    lastPage: 1,
    page: 1,
    perPage: 20,
    total: 0,
    current_page: 1,
    users: [],
    loadding: false,
    showRole: true,
    roles: [],
    deleteIds: [],
    error: null,
    follow: 0,
    regist_start_date: '',
    regist_end_date: '',
    location: '',
  }),
  computed: {
    queryParams () {
      const { 
        userId, 
        sort, 
        email, 
        name, 
        phone, 
        role, 
        perPage, 
        page, 
        follow, 
        regist_start_date, 
        regist_end_date,
        location,
      } = this;
      return { 
        userId, 
        sort, 
        email, 
        name, 
        phone, 
        role, 
        perPage, 
        page, 
        follow,
        regist_start_date,
        regist_end_date,
        location,
      };
    },
    prevQuery () {
      const page = parseInt(this.page);
      return {
        ...this.queryParams,
        lastPage: this.lastPage,
        page: page > 1 ? page - 1 : page
      };
    },
    nextQuery () {
      const page = parseInt(this.page);
      const lastPage = parseInt(this.lastPage);
      return {
        ...this.queryParams,
        lastPage: lastPage,
        page: page < lastPage ? page + 1 : lastPage
      };
    },
    searchQuery () {
      return {
        ...this.queryParams,
        page: 1
      };
    },
  },
  watch: {
    '$route' (to) {
      const {
        email = '',
        name = '',
        phone = '',
        role = 0,
        sort = 'down',
        userId = '',
        lastPage = 1,
        perPage = 20,
        page = 1,
        follow = 0,
        regist_start_date = '',
        regist_end_date = '',
        location = '',
      } = to.query;

      this.email = email;
      this.name = name;
      this.phone = phone;
      this.role = role;
      this.sort = sort;
      this.userId = userId;
      this.lastPage = parseInt(lastPage);
      this.perPage = parseInt(perPage);
      this.page = parseInt(page);
      this.regist_start_date = regist_start_date;
      this.regist_end_date = regist_end_date;
      this.location = location;

      this.getUsers();
    }
  },
  /**
   * 定义方法组.
   *
   * @type {Object}
   */
  methods: {
    deleteIdsUnTo (userId) {
      let deleteIds = [];
      this.deleteIds.forEach(id => {
        if (parseInt(id) !== parseInt(userId)) {
          deleteIds.push(id);
        }
      });
      this.deleteIds = deleteIds;
    },

    handleUnFamous (user) {
      request.delete(createRequestURI(`users/famous/${user}`), {
        validateStatus: status => status === 204
      })
      .then ( () => {
        let index = lodash.findIndex(this.users, (u) => {
          return u.id == user;
        });

        this.users[index].famous = null;
      })
      .catch( error => {

      });
    },

    handleEachFamous (user) {
      request.post(createRequestURI('users/famous'), {
        user,
        type: 2
      }, {
        validateStatus: status => status === 201
      })
      .then ( () => {
        let index = lodash.findIndex(this.users, (u) => {
          return u.id == user;
        });

        this.users[index].famous = true;
      })
      .catch( error => {

      });
    },

    handleFollowedFamous (user) {
      request.post(createRequestURI('users/famous'), {
        user,
        type: 1
      }, {
        validateStatus: status => status === 201
      })
      .then ( () => {
        let index = lodash.findIndex(this.users, (u) => {
          return u.id == user;
        });

        this.users[index].famous = true;
      })
      .catch( error => {

      });
    },

    handleRecommend (user) {
      request.post(createRequestURI('users/recommends'), {
        user
      }, {
        validateStatus: status => status === 201
      })
      .then( () => {
        let index = lodash.findIndex(this.users, (u) => {
          return u.id === user;
        });
        this.users[index].recommended = true;
      })
    },
    handleUnRecommend (user) {
      request.delete(createRequestURI(`users/recommends/${user}`), {
        validateStatus: status => status === 204
      })
      .then( () => {
        let index = lodash.findIndex(this.users, (u) => {
          return u.id === user;
        });
        this.users[index].recommended = null;
      });
    },
    deleteUser (userId) {
      if (window.confirm('确定要禁止用户吗？')) {
        this.deleteIds = [ ...this.deleteIds, userId ];
        request.delete(
          createRequestURI(`users/${userId}`),
          { validateStatus: status => status === 204 }
        ).then(() => {
          this.deleteIdsUnTo(userId);
          let users = [];
          this.users.forEach(user => {
            if (user.id !== userId) {
              users.push(user);
            }
          });
          this.users = users;
        }).catch(({ response: { data: { errors = ['操作失败'] } = {} } = {} }) => {
          this.deleteIdsUnTo(userId);
          this.error = lodash.values(errors).pop();
        });
      }
    },
    /**
     * 改变用户排序状态方法.
     *
     * @enum {up, down}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    changeUserIdSort (sort) {
      this.sort = sort;
    },
    /**
     * 获取列表用户.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    getUsers () {
      this.loadding = true;
      request.get(
        createRequestURI('users'),
        {
          params: { ...this.queryParams, show_role: this.showRole },
          validateStatus: status => status === 200
        }
      ).then(response => {
        const { users: data, roles, page } = response.data;

        this.users = data || [];
        this.lastPage = parseInt(page.last_page);
        this.total = parseInt(page.total);
        this.current_page = parseInt(page.current_page);
        this.loadding = false;
        this.showRole = false;

        this.roles = roles;
      }).catch(({ response: { data: { errors = ['加载用户失败'] } = {} } = {} }) => {
        this.error = lodash.values(errors).pop();
        this.loadding = false;
      });
    },
    dismisError () {
      this.error = null;
    }
  },
  /**
   * 组件创建完成后.
   *
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  created () {
    const {
      email = '',
      name = '',
      phone = '',
      role = 0,
      sort = 'down',
      userId = '',
      lastPage = 1,
      perPage = 20,
      page = 1,
      follow = 0,
    } = this.$route.query;
    // set state.
    this.email = email;
    this.name = name;
    this.phone = phone;
    this.role = role;
    this.sort = sort;
    this.userId = userId;
    this.lastPage = parseInt(lastPage);
    this.perPage = parseInt(perPage);
    this.page = parseInt(page);
    this.showRole = true;

    this.getUsers();
  }
};

export default ManageComponent;
</script>
