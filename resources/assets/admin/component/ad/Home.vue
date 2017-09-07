<style lang="css" module>
    .container {
        padding: 15px;
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
</style>

<template>
    <div :class="$style.container">
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
            <router-link type="button" class="btn btn-primary btn-sm" to="ad/add">添加</router-link>
          </div>
          <!-- 添加广告 -->
          <div class="panel-heading">
            <div  class="form-inline">
              <div class="input-group">
                <select class="form-control" v-model="filter.space_id" @change="getAds">
                  <option value="">全部</option>
                  <option v-for="space in spaces" :value="space.id">{{ space.alias }}</option>
                </select>
              </div>
              <div class="input-group">
                <input type="text" class="form-control" placeholder="广告搜索" v-model="filter.keyword">
                <span class="input-group-btn">
                  <button class="btn btn-default" @click="getAds">搜索</button>
                </span>
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
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="loadding">
                        <!-- 加载动画 -->
                        <td :class="$style.loadding" colspan="6">
                            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                        </td>
                    </tr>
                    <!-- 数据存在 -->
                    <template  v-if="ads.length">
                      <tr v-for="ad in ads">
                        <td>{{ ad.title }}</td>
                        <td>{{ ad.space.alias }}</td>
                        <td>
                          <a :href="ad.data.image" class="thumbnail" target="_blank">
                            <img :src="ad.data.image" style="max-width:40px;max-height:40px;">
                          </a>
                        </td>
                        <td>{{ ad.data.link }}</td>
                        <td>{{ ad.created_at }}</td>
                        <td>
                          <router-link type="button" class="btn btn-primary btn-sm" to="ad/add">编辑</router-link>
                          <button type="button" class="btn btn-danger btn-sm" @click="delAd(ad.id)">删除</button>
                        </td>
                      </tr>
                    </template>
                    <!-- 数据为空 -->
                    <template v-else>
                      <tr> <td colspan="6" style="text-align:center;">暂无数据</td> </tr>
                    </template>
                </tbody>
            </table>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
const ManageComponent = {
    data: () => ({

      loadding: true,
      
      ads: {},

      spaces: {},

      paginate: {
        current_page: 1,
        last_page: '',
        per_page: 20,
      },

      filter: {
        keyword: '',
        space_id: '',
      },

      message: {
        error: null,
        success: null,
      }
    
    }),
    
    methods: {  

      getAds () {
        this.ads = {};
        this.loadding = true;
        request.get(
          createRequestURI('ads' + this.getQueryParams()),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;

          let { 
            data: data, 
            current_page: current_page, 
            last_page: last_page, 
          } = response.data;

          this.paginate.current_page = current_page;
          this.paginate.last_page = last_page;
          this.ads = data;

        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = errors;
        });
      },

      getAdSpaces () {
        request.get(
          createRequestURI('ads/spaces'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.spaces = response.data;
        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
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
            this.message.error = message;
          });
        }
      },

      getQueryParams () {
        let filter = this.filter;
        let paginate = this.paginate;
        let params = '?';

        params += 'space_id=' + filter.space_id;
        params += '&keyword=' + filter.keyword;
        params += '&per_page=' + paginate.per_page;
        params += '&page=' + paginate.current_page;

        return params; 
      },
 
    },

    created () {
      this.getAdSpaces();
      this.getAds();
    },
};

export default ManageComponent;
</script>
