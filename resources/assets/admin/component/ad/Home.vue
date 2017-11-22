<template>
    <div class="container-fluid" style="margin-top:10px;">
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
          <!-- 添加广告 -->
          <div class="panel-heading">
            广告列表
            <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/ad/add" role="button">
              <span class="glyphicon glyphicon-plus"></span>
              添加
            </router-link>
          </div>
          <!-- 添加广告 -->
          <div class="panel-heading">
            <div  class="form-inline">
              <div class="form-group">
                <select class="form-control" v-model="filter.space_id">
                  <option value="">全部</option>
                  <option v-for="space in spaces" :value="space.id">{{ space.alias }}</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="广告搜索" v-model="filter.keyword">
                  <router-link class="btn btn-default" tag="button" :to="{ path: '/ad', query: searchQuery }">
                    搜索
                  </router-link>
              </div>
            </div>
          </div>
          <!-- 广告列表 -->
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>广告标题</th>
                        <th>广告位</th>
                        <th>广告图</th>
                        <th>广告链接</th>
                        <th>广告排序</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                      <!-- loading -->
                      <table-loading :loadding="loadding" :colspan-num="7"></table-loading>
                      <!--  list -->
                      <template v-if="ads.length">
                        <tr v-for="ad in ads">
                          <td>{{ ad.title }}</td>
                          <td>{{ ad.space.alias }}</td>
                          <td>
                            <a :href="ad.data.image" class="img-thumbnail" target="_blank">
                              <img :src="ad.data.image" style="max-width:40px;max-height:40px;">
                            </a>
                          </td>
                          <td>{{ ad.data.link }}</td>
                          <td>{{ ad.sort }}</td>
                          <td>{{ ad.created_at | localDate }}</td>
                          <td>
                            <router-link type="button" class="btn btn-primary btn-sm" :to="`ad/${ad.id}/update`">编辑</router-link>
                            <button type="button" class="btn btn-danger btn-sm" @click="delAd(ad.id)">删除</button>
                          </td>
                        </tr>
                      </template>
                      <template v-else-if="!loadding">
                        <!-- 数据为空 -->
                        <tr> <td colspan="7" style="text-align:center;">暂无数据</td> </tr>
                      </template>
                </tbody>
            </table>
          </div>
          <!-- 分页 -->
          <div class="text-center">
            <offset-paginator class="pagination" :total="total" :offset="offset" :limit="15">
              <template slot-scope="pagination">
                <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
                  <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
                  <router-link v-else :to="offsetPage(pagination.offset)">{{ pagination.page }}</router-link>
                </li>
              </template>
            </offset-paginator>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';

const ManageComponent = {
    data: () => ({
      loadding: true,
      ads: [],
      spaces: [],
      total: 0,
      filter: {
        keyword: '',
        space_id: '',
      },
      message: {
        error: null,
        success: null,
      }
    }),
    watch: {
      '$route': function ($route) {
        this.total = 0;
        this.getAds({ ...$route.query });
      },
    },
    computed: {
      offset () {
        const { query: { offset = 0 } } = this.$route;
        return parseInt(offset);
      },
      searchQuery () {
        return { ...this.filter, offset: 0 };
      },
    },
    methods: {  
      getAds (query = {}) {
        this.ads = {};
        this.loadding = true;
        request.get(
          createRequestURI('ads'),
          { 
            validateStatus: status => status === 200,
            params: { ...query, limit: 15 },
          }
        ).then(({ data = [], headers: { 'x-ad-total': total } }) => {
          this.loadding = false;
          this.total = parseInt(total);
          this.ads = data;
        }).catch(({ response: { data: { errors = ['加载失败'] } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = plusMessageFirst(errors);
        });
      },
      getAdSpaces () {
        request.get(
          createRequestURI('ads/spaces'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.spaces = response.data;
        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
          this.message.error = plusMessageFirst(errors);
        });
      },
      delAd (id) {
        let comfirm = confirm('确认要删除？');
        if (comfirm) {
          request.delete(
            createRequestURI(`ads/${id}`),
            { validateStatus: status => status === 204 }
          ).then(response => {
            this.message.success = '删除成功';
            this.getAds();
          }).catch(({ response: { data : { message } } = {} }) => {
            this.message.error = plusMessageFirst(message);
          });
        }
      },
      offsetPage(offset) {
        return { path: '/ad', query: { ...this.filter, offset } };
      },
      offAlert () {
        this.message.error = null;
        this.message.success = null;
      },
    },
    created () {
      this.getAdSpaces();
      this.getAds(this.$route.query);
    },
};

export default ManageComponent;
</script>
