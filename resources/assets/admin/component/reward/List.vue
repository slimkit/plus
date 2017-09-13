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
              <router-link type="button" class="btn btn-primary btn-sm" to="ad/add">导出</router-link>
            </div>
            <!-- 添加广告 -->
            <div class="panel-body">
              <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>打赏用户</th>
                            <th>被打赏用户</th>
                            <th>打赏金额(元)</th>
                            <th>打赏应用</th>
                            <th>打赏时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-show="loadding">
                            <!-- 加载动画 -->
                            <td :class="$style.loadding" colspan="6">
                                <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                            </td>
                        </tr>
                        <tr v-for="reward in rewards">
                          <td>{{ reward.id }}</td>
                          <td>{{ reward.user.name }}</td>
                          <td>{{ reward.target.name }}</td>
                          <td>{{ reward.amount/100 }}</td>
                          <td v-if="reward.rewardable_type=='feeds'">动态</td>
                          <td v-else-if="reward.rewardable_type=='news'">咨询</td>
                          <td v-else-if="reward.rewardable_type=='users'">用户</td>
                          <td v-else-if="reward.rewardable_type=='question-answers'">问答</td>
                          <td>{{ reward.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
const ListComponent = {
    data: () => ({

      loadding: false,
      
      rewards: {},

      paginate: {
        current_page: 1,
        last_page: '',
        per_page: 20,
      },

      filter: {

      },

      message: {
        error: null,
        success: null,
      }
    
    }),
    
    methods: {  
      getRewards () {
        this.rewards = {};
        this.loadding = true;
        request.get(
          createRequestURI('rewards'),
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
          this.rewards = data;
          console.log(this.rewards);

        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = errors;
        });
      }
    },

    created () {
      this.getRewards();
    },
};

export default ListComponent;
</script>
