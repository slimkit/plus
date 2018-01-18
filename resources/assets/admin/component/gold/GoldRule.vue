<template>
    <div class="container-fluid" style="margin:15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            金币规则列表
            <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/gold/rules/add" role="button">
              <span class="glyphicon glyphicon-plus"></span>
              添加
            </router-link>
          </div>
          <div class="panel-heading">
            <div class="form-inline">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="规则搜索" v-model="filter.keyword">
                <span class="input-group-btn">
                  <button class="btn btn-default" @click="getRules">搜索</button>
                </span>
              </div>
            </div>
          </div>
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
                      <table-loading :loadding="loadding" :colspan-num="5"></table-loading>
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
      },

      filter: {
        keyword: '',
      }
    
    }),

    methods: {

      getRules () {
        this.loadding = true;
        let keyword = this.filter.keyword;
        request.get(
          createRequestURI('gold/rules?keyword=' + keyword),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.rules = response.data;
        }).catch(({ response: { data: { errors = ['获取金币类型失败'] } = {} } = {} }) => {
          this.loadding = false;
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
    },

    created () {
      
      this.getRules();

    },
};

export default GoldRuleComponent;
</script>
