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
        <div class="panel-body" v-if="loading">
          <ui-loading></ui-loading>
        </div>
        <module-cdn-filesystem v-else-if="selecetd === 'filesystem'" :handle-select="handleSelect"></module-cdn-filesystem>
        <module-cdn-qiniu v-else-if="selecetd === 'qiniu'" :handle-select="handleSelect"></module-cdn-qiniu>
        <module-cdn-alioss v-else-if="selecetd === 'alioss'" :handle-select="handleSelect"></module-cdn-alioss>
      </div>

    </div>
  </div>
</template>

<script>
import components from "../modules/cdn";
import Alert from "../modules/Alert";
import request, { createRequestURI } from "../../util/request";
export default {
  name: "page-cdn",
  components: {
    ...components,
    [Alert.name]: Alert
  },
  data: () => ({
    selecetd: "filesystem",
    loading: false
  }),
  methods: {
    handleSelect(cdn) {
      this.selecetd = cdn;
    }
  },
  created() {
    this.loading = true;
    request
      .get(createRequestURI("cdn/selected"), {
        validateStatus: status => status === 200
      })
      .then(({ data: { seleced: cdn = "filesystem" } }) => {
        this.selecetd = cdn;
        this.loading = false;
      })
      .catch(({ response: { data = { message: "获取失败" } } = {} }) => {
        this.loading = false;
        this.$store.dispatch("alert-open", { type: "danger", message: data });
      });
  }
};
</script>
