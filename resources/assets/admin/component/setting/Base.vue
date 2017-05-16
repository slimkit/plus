<style lang="scss" module>
.container {
  padding: 15px;
  .containerAround {
    animation-name: "TurnAround";
    animation-duration: 1.6s;
    animation-timing-function: linear;
    // animation-direction: alternate;
    animation-iteration-count: infinite;
  }
}
</style>

<template>
  <form class="form-horizontal" :class="$style.container" @submit.prevent="submit">
    <!-- Site name. -->
    <div class="form-group">
      <label for="site-name" class="col-sm-2 control-label">应用名称</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="site-name" aria-describedby="site-name-help-block" placeholder="输入网站标题" v-model="name">
      </div>
      <span class="col-sm-4 help-block" id="site-name-help-block">
        应用名称，将在网页中显示在title的基本信息。也是搜索引擎为搜录做筛选标题的重要信息。
      </span>
    </div>
    <!-- End site name. -->

    <!-- Site keywords -->
    <div class="form-group">
      <label for="site-keywords" class="col-sm-2 control-label">关键词</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="site-keywords" aria-describedby="site-keywords-help-block" placeholder="网站关键词" v-model="keywords">
      </div>
      <span class="col-sm-4 help-block" id="site-keywords-help-block">
        网站关键词，是通过搜索引擎检索网站的重要信息，多个关键词使用英文半角符号“<strong>,</strong>”分割。
      </span>
    </div>
    <!-- End site keywords -->

    <!-- Site description -->
    <div class="form-group">
      <label for="site-description" class="col-sm-2 control-label">描述</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="site-description" aria-describedby="site-description-help-block" placeholder="网站描述" v-model="description">
      </div>
      <span class="col-sm-4 help-block" id="site-description-help-block">
        描述用于简单的介绍站点，在搜索引擎中用于搜索结果的概述。
      </span>
    </div>
    <!-- End site description -->

    <!-- ICP 备案信息 -->
    <div class="form-group">
      <label for="site-icp" class="col-sm-2 control-label">ICP 备案信息</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="site-icp" aria-describedby="site-icp-help-block" placeholder="网站描述" v-model="icp">
      </div>
      <span class="col-sm-4 help-block" id="site-icp-help-block">
        填写 ICP 备案的信息，例如: 浙ICP备xxxxxxxx号
      </span>
    </div>
    <!-- End ICP 备案信息 -->

    <!-- Button -->
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button v-if="loadding" class="btn btn-primary" disabled="disabled">
          <span class="glyphicon glyphicon-refresh" :class="$style.containerAround"></span>
        </button>
        <button v-else-if="error" @click.prevent="requestSiteInfo" class="btn btn-danger">{{ error_message }}</button>
        <button v-else type="submit" class="btn btn-primary">{{ message }}</button>
      </div>
    </div>
    <!-- End button -->
  </form>
</template>

<script>
import { SETTINGS_SITE_UPDATE } from '../../store/types';
import request, { createRequestURI } from '../../util/request';

const settingBase = {
  data: () => ({
    loadding: true,
    error: false,
    error_message: '重新加载',
    message: '提交'
  }),
  computed: {
    name: {
      get () {
        return this.$store.state.site.name;
      },
      set (name) {
        this.$store.commit(SETTINGS_SITE_UPDATE, { name });
      }
    },
    keywords: {
      get () {
        return this.$store.state.site.keywords;
      },
      set (keywords) {
        this.$store.commit(SETTINGS_SITE_UPDATE, { keywords });
      }
    },
    description: {
      get () {
        return this.$store.state.site.description;
      },
      set (description) {
        this.$store.commit(SETTINGS_SITE_UPDATE, { description });
      }
    },
    icp: {
      get () {
        return this.$store.state.site.icp;
      },
      set (icp) {
        this.$store.commit(SETTINGS_SITE_UPDATE, { icp });
      }
    }
  },
  methods: {
    requestSiteInfo () {
      request.get(createRequestURI('site/baseinfo'), {
        validateStatus: status => status === 200
      }).then(({ data = {} }) => {
        this.$store.commit(SETTINGS_SITE_UPDATE, { ...data });
        this.loadding = false;
      }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
        this.loadding = false;
        this.error = true;
        window.alert(message);
        // this.error_message
      });
    },
    submit () {
      const { name, keywords, description, icp } = this;
      this.loadding = true;
      request.patch(createRequestURI('site/baseinfo'), { name, keywords, description, icp }, {
        validateStatus: status => status === 201
      }).then(() => {
        this.message = '执行成功';
        this.loadding = false;
        setTimeout(() => {
          this.message = '提交';
        }, 1000);
      }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
        this.loadding = false;
        this.error = true;
        this.error_message = message;
        setTimeout(() => {
          this.error = false;
          this.error_message = '重新加载';
        }, 1000);
      });
    }
  },
  created () {
    this.requestSiteInfo();
  }
};

export default settingBase;
</script>
