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
        <div v-show="successMessage" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ successMessage }}
        </div>
        <div v-show="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ errorMessage }}
        </div>
        <div class="panel panel-default">
          <!-- 添加广告 -->
          <div class="panel-heading">
            <router-link type="button" class="btn btn-success btn-sm" to="ad/add">添加广告</router-link>
          </div>
          <!-- 广告列表 -->
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>广告标题</th>
                        <th>广告位</th>
                        <th>创建是时间</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="loadding">
                        <!-- 加载动画 -->
                        <td :class="$style.loadding" colspan="3">
                            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                        </td>
                    </tr>
                    <!-- 数据存在 -->
                    <template  v-if="ads.length">
                      <tr v-for="ad in ads">
                        <td>{{ ad.title }}</td>
                        <td>{{ ad.space.alias }}</td>
                        <td>{{ ad.created_at }}</td>
                      </tr>
                    </template>
                    <!-- 数据为空 -->
                    <template v-else>
                      <tr> <td colspan="3" style="text-align:center;">暂无数据</td> </tr>
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

      paginate: {
        currentPage: 1,
        lastPage: '',
      },

      successMessage: null,

      errorMessage: null,
    
    }),
    
    methods: {

      getAds () {
        this.loadding = true;
        request.get(
          createRequestURI('ads'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;

          let { 
            data: data, 
            current_page: currentPage, 
            last_page: lastPage, 
          } = response.data;

          this.paginate.currentPage = currentPage;
          this.paginate.lastPage = lastPage;
          this.ads = data;

        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
          this.loadding = false;
          console.log(errors);
        });
      },



    },

    created () {
      this.getAds();
    },
};

export default ManageComponent;
</script>
