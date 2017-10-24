<template>
  <div class="clearfix">
    <div class="container-fluid" style="padding-top: 70px; position: relative;">
      
      <nav class="navbar navbar-default navbar-fixed-top" style="position: absolute;">
        <div class="navbar-header">

          <span class="navbar-brand">
            <span class="glyphicon glyphicon-cloud"></span>
            CDN
          </span>

        </div>
      </nav>

      <module-alert></module-alert>

      <div class="panel panel-default">
        <div class="panel-heading">CDN 设置</div>
        <div class="panel-body" v-if="loadding">
          <ui-loadding></ui-loadding>
        </div>
        <module-cdn-local v-else-if="selecetd === 'local'" :handle-select="handleSelect"></module-cdn-local>
        <module-cdn-qiniu v-else-if="selecetd === 'qiniu'" :handle-select="handleSelect"></module-cdn-qiniu>
      </div>

    </div>
  </div>
</template>

<script>
import components from '../modules/cdn';
import Alert from '../modules/Alert';
import request, { createRequestURI } from '../../util/request';
export default {
  name: 'page-cdn',
  components: {
    ...components,
    [Alert.name]: Alert,
  },
  data: () => ({
    selecetd: 'local',
    loadding: false,
  }),
  methods: {
    handleSelect (cdn) {
      this.selecetd = cdn;
    }
  },
  created () {
    this.loadding = true;
    request.get(createRequestURI('cdn/seleced'), {
      validateStatus: status => status === 200,
    }).then(({ data: { cdn = 'local' } }) => {
      this.selecetd = cdn;
      this.loadding = false;
    }).catch(({ response: { data = { message: '获取失败' } } = {} }) => {
      this.loadding = false;
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
