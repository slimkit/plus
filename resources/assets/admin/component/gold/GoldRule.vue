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
            <router-link class="btn btn-primary btn-sm" to="rules/add">添加</router-link>
          </div>
          <!-- 广告列表 -->
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>名称</th>
                        <th>别名</th>
                        <th>增量</th>
                        <th>描述</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-show="loadding">
                        <!-- 加载动画 -->
                        <td :class="$style.loadding" colspan="5">
                            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                        </td>
                    </tr>
                      <!-- 数据存在 -->
                      <template v-if="rules.length">
                        <tr v-for="rule in rules">
                          <td>{{ rule.name }}</td>
                          <td>{{ rule.alias }}</td>
                          <td>{{ rule.incremental }}</td>
                          <td>{{ rule.desc }}</td>
                          <td>
                            <router-link class="btn btn-primary btn-sm" :to="`rules/${rule.id}/update`">编辑</router-link>
                            <button class="btn btn-danger btn-sm" @click.prevent="delGoldRule(rule.id)">删除</button>
                          </td>
                        </tr>
                      </template>
                      <template v-else-if="!loadding">
                        <!-- 数据为空 -->
                        <tr><td colspan="7" style="text-align:center;">无数据</td></tr>
                      </template>
                </tbody>
            </table>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
const GoldRuleComponent = {
    data: () => ({

      loadding: true,

      rules:[],
    
      message: {
        error: null,
        success: null,
      }
    
    }),

    methods: {
      // 获取权限节点
      getRules () {

        this.loadding = true;
        
        request.get(
          createRequestURI('gold/rules'),
          { validateStatus: status => status === 200 }
        ).then(response => {

          this.loadding = false;
          this.rules = response.data;

        }).catch(({ response: { data: { errors = ['获取金币类型失败'] } = {} } = {} }) => {

          this.message.error = errors;

        });

      },

      delGoldRule (id) {

        let comfirm = confirm('确认要删除？');
        
        if (comfirm) {
          request.delete(
            createRequestURI(`gold/rules/${id}`),
            { validateStatus: status => status === 204 }
          ).then(response => {
            this.message.success = '删除成功';
            this.getRules();
          }).catch(({ response: { data : { message } } = {} }) => {
            this.message.error = message;
          });
        }
      },

      offAlert () {
        let msg = this.message;
        msg.error = msg.success = null;
      } 
    },

    created () {
      
      this.getRules();

    },
};

export default GoldRuleComponent;
</script>
