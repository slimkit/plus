<style lang="css" module>
    .image {
        max-width:200px;
        margin-bottom: 10px;
    }
    .help-block {
      font-size: 12px !important;
    }
</style>

<template>
<div class="container-fluid" style="margin-top:15px;">
    <div class="panel panel-default">
      <div class="panel-heading">
        站点配置
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <loading :loadding="loadding"></loading>
          <div v-show="!loadding">
            <div class="form-group">
              <label class="control-label col-md-2">金币</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on"  v-model="site.gold.status" > 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off" v-model="site.gold.status" > 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">启动规则，用户完成相应的节点操作可以获取对应的奖励，关闭规则，用户完成相应的节点操作不能获取对应的奖励</span>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-2">打赏</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on"  v-model="site.reward.status" > 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off"  v-model="site.reward.status" > 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">用户打赏开启与关闭</span>
              </div>
            </div>
            <div class="form-group" v-if="site.reward.status">
              <label class="control-label col-md-2">打赏金额</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.reward.amounts">
              </div>
              <div class="col-md-4">
                <span class="help-block">打赏金额配置，单位(分)。最少为1分，多个金额用英文半角符号“,”分割 例如：5,10,15。</span>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="control-label col-md-2">匿名开关</label>
              <div class="col-md-6">
                <label class="radio-inline">
                  <input type="radio" :value="radio.on"  v-model="site.anonymous.status" > 开启
                </label>
                <label class="radio-inline">
                  <input type="radio" :value="radio.off"  v-model="site.anonymous.status" > 关闭
                </label>
              </div>
              <div class="col-md-4">
                <span class="help-block">站点是否开启匿名，开启后用户可以选择匿名操作部分内容</span>
              </div>
            </div>
            <!-- <div class="form-group" v-if="site.anonymous.status">
              <label class="control-label col-md-2">匿名规则说明</label>
              <div class="col-md-6">
                <textarea class="form-control" v-model="site.anonymous.rule"></textarea>
              </div>
              <div class="col-md-4">
                <span class="help-block">用于开启匿名时，给用户说明匿名规则</span>
              </div>
            </div> -->
            <div class="form-group">
              <label class="control-label col-md-2">预留呢称</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.reserved_nickname">
              </div>
              <div class="col-md-4">
                <span class="help-block">预留呢称，多个呢称用英文半角符号","分割</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">客户邮箱</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.client_email">
              </div>
              <div class="col-md-4">
                <span class="help-block">客户邮箱</span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">用户邀请模版</label>
              <div class="col-md-6">
                <textarea class="form-control" v-model="site.user_invite_template">
                </textarea>
              </div>
              <div class="col-md-4">
                <span class="help-block">用户邀请模版</span>
              </div>
            </div>
            <!-- <div class="form-group">
              <label class="control-label col-md-2">关于我们</label>
              <div class="col-md-6">
                <input type="text" class="form-control" v-model="site.about_url" placeholder="例如: http://www.about.com" />
              </div>
              <div class="col-md-4">
                <span class="help-block">关于我们链接地址</span>
              </div>
            </div> -->
            <div class="form-group">
              <label class="control-label col-md-2"></label>
              <div class="col-md-6">
                <button class="btn btn-primary btn-block" 
                @click.prevent="updateSiteConfigure" 
                data-loading-text="提交中" 
                autocomplete="off" id="submit-btn">确认</button>
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

  <!-- Clear cache -->
  <clear-cache></clear-cache>

</div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import PlusMessageBundle from 'plus-message-bundle';
import ClearCache from '../../components/modules/setting/ClearCache';
export default {
  components: {
    'clear-cache': ClearCache,
  },
  data: () => ({
      loadding: true,
      radio: {
        on: true,
        off: false,
      },
      site: {
        gold: {
          status: true,
        },
        reward: {
          status: true,
          amounts: '',
        },
        reserved_nickname: '',
        client_email: '',
        user_invite_template: '',
        anonymous: {
          status: false,
          rule: ''
        },
        about_url:null,
      },
      message: {
        error: null,
        success: null,
      }
  }),
  methods: {
    getSiteConfigures () {
      this.loadding = true;
      request.get(createRequestURI('site/configures'), {
        validateStatus: status => status === 200,
      }).then(({ data = {} }) => {
        this.loadding = false;
        this.site = { ...this.site, ...data };
      }).catch(({ response: { data = { message: '加载站点配置失败' } } = {} }) => {
        this.loadding = false;
        this.message.error = (new PlusMessageBundle).getMessage();
      });
    },
    updateSiteConfigure () {
      $("#submit-btn").button('loading');
      request.put(
        createRequestURI('update/site/configure'),
        { site: this.site },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [ message ] = [] } }) => {
        $("#submit-btn").button('reset');
        this.message.success = message;
      }).catch(({ response: { data = {} } = {} }) => {
        let Message = new PlusMessageBundle(data);
        this.message.error = Message.getMessage();
      });
    },
  },
  created () {
    this.getSiteConfigures();
  },
};
</script>