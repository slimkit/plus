<template>
  <div class="panel panel-default">
      
      <!-- title -->
      <div class="panel-heading">Web 终端</div>

      <!-- body -->
      <div class="panel-body">
        
        <!-- loading -->
        <sb-ui-loading v-if="loading" />

        <!-- form -->
        <div class="form-horizontal" v-else>
          
          <!-- desktop client watch -->
          <div class="form-group">
            <label class="col-sm-2 control-label">开关</label>
            <div class="col-md-6">
              <label class="radio-inline">
                <input type="radio" :value="true" v-model="web.open" :disabled="!web.url">&nbsp;开启
              </label>
              <label class="radio-inline">
                <input type="radio" :value="false" v-model="web.open" :disabled="!web.url">&nbsp;关闭
              </label>
            </div>
            <span class="col-sm-4 help-block">
              是否开启大屏客户端？
            </span>
          </div>

          <!-- desktop client url -->
          <div class="form-group">
            <label class="col-sm-2 control-label">地址</label>
            <div class="col-md-6">
              <input type="url" class="form-control" placeholder="请输入桌面客户端地址" v-model="web.url">
            </div>
            <span class="col-sm-4 help-block">
              请输入大屏客户端地址
            </span>
          </div>

          <!-- SPA client watch -->
          <div class="form-group">
            <label class="col-sm-2 control-label">开关</label>
            <div class="col-md-6">
              <label class="radio-inline">
                <input type="radio" :value="true" v-model="spa.open" :disabled="!spa.url">&nbsp;开启
              </label>
              <label class="radio-inline">
                <input type="radio" :value="false" v-model="spa.open" :disabled="!spa.url">&nbsp;关闭
              </label>
            </div>
            <span class="col-sm-4 help-block">
              是否开启 SPA 客户端？
            </span>
          </div>

          <!-- desktop client url -->
          <div class="form-group">
            <label class="col-sm-2 control-label">地址</label>
            <div class="col-md-6">
              <input type="url" class="form-control" placeholder="请输入 SPA 地址" v-model="spa.url">
            </div>
            <span class="col-sm-4 help-block">
              请输入 SPA 地址
            </span>
          </div>

          <!-- submit button -->
          <div class="col-sm-10 col-sm-offset-2">
            <sb-ui-button class="btn btn-primary" @click="handleSubmit" />
          </div>

        </div>
        <!-- end form -->

      </div>
      <!-- end body -->

  </div>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
export default {
  data: () => ({
    web: {
      open: false,
      url: '',
    },
    spa: {
      open: false,
      url: '',
    },
    loading: false,
  }),
  methods: {
    handleSubmit(event) {
      const web = this.web;
      const spa = this.spa;
      request.patch(createRequestURI('settings/web-clients'), { web, spa }, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.$store.dispatch('alert-open', { type: 'success', message: { message: '设置成功' } });
        event.stopProcessing();
      }).catch(({ response: { data } }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
        event.stopProcessing();
      });
    }
  },
  created() {
    this.loading = true;
    request.get(createRequestURI('settings/web-clients'), {
      validateStatus: status => status === 200,
    }).then(({ data: { web, spa } }) => {
      this.web = web;
      this.spa = spa;
      this.loading = false;
    }).catch(({ response: { data } }) => {
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
