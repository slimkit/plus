<style lang="css" module>
    .loadding {
        text-align: center;
        font-size: 42px;
        padding-top: 100px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    .image {
        max-width:200px;
        margin-bottom: 10px;
    }
</style>

<template>
<div class="container-fluid" style="margin:15px;">
    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
        <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
    <div class="panel panel-default" v-show="!loadding">
      <div class="panel-heading">
        金币规则添加
        <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/gold/rules" role="button">
          返回
        </router-link>
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
              <label class="control-label col-md-2">名称</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="rule.name" placeholder="名称">
              </div>
              <div class="col-md-4">
                <span class="help-block">类型名称</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">别名</label>
              <div class="col-md-6">
                <select class="form-control" v-model="rule.alias">
                  <option v-for="ability in abilities" :value="ability.name">{{ ability.display_name }}</option>
                </select>
              </div>
              <div class="col-md-4">
                <span class="help-block">规则别名</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">增量</label>
              <div class="col-md-6">
                <input type="number" class="form-control" placeholder="增量" value="1"  v-model="rule.incremental">
              </div>
              <div class="col-md-4">
                <span class="help-block">规则增量</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">描述</label>
              <div class="col-md-6">
                <textarea placeholder="描述" class="form-control" v-model="rule.desc"></textarea>
              </div>
              <div class="col-md-4">
                <span class="help-block">规则描述</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2"></label>
              <div class="col-md-6">
                <button class="btn btn-primary btn-sm" data-loading-text="提交中..." id="submit-btn" @click.prevent="storeGoldRule">确认</button>
              </div>
              <div class="col-md-4">
                 <span class="text-success"  v-show="message.success">{{ message.success }}</span>
                 <span class="text-danger" v-show="message.error">{{ message.error }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';

const AddGoldTypeCompnent = {
    
    data: () => ({

        loadding: false,

        abilities: [],

        rule: {
          name: '',
          alias: '',
          incremental: 1,
          desc: '',
        },

        message: {
          error: null,
          success: null,
        }

    }),

    methods: {

      storeGoldRule () {

        this.hiddenMessage();
        let btn = $('#submit-btn');
        btn.button('loading');
        request.post(
          createRequestURI('gold/rules'),
          { ...this.rule },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {
          btn.button('reset');
          this.message.success = message;
          let _vue = this;
          setTimeout(() => {
            _vue.$router.replace({ path: '/gold/rules' });
          }, 500);
        }).catch(({ response: { data = {} } = {} }) => {
          btn.button('reset');
          this.message.error = plusMessageFirst(data);
        });
      },
      // 获取权限节点
      getAbilities () {

        this.loadding = true;
        
        request.get(
          createRequestURI('gold/rules/abilities'),
          { validateStatus: status => status === 200 }
        ).then(response => {

          this.loadding = false;
          this.abilities = response.data;

        }).catch(({ response: { data: { errors = ['获取金币类型失败'] } = {} } = {} }) => {

          this.message.error = errors;

        });

      },

      hiddenMessage () {
        this.message.error = this.message.success = null;
      }
    },

    created () {
      this.getAbilities();
    },

};
export default AddGoldTypeCompnent;
</script>