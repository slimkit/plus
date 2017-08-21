<style lang="scss" scoped>
  .list-group-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
</style>

<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">服务器信息</div>
      <ul class="list-group">
        <li v-for="(per, index) in system" :key="index" class="list-group-item">
          <span style="font-size: 16px;">{{ translates[index] }} : </span>
          <span>{{ per }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  import { SETTINGS_SYSTEM_UPDATE } from '../../store/types';
  import request, { createRequestURI } from '../../util/request';

  const System = {
    data: () => ({
      translates: {
        php_version: 'PHP版本',
        os: '操作系统',
        server: '运行环境',
        domain_ip: '域名/IP',
        db: '数据库',
        root: '根目录',
        laravel_version: 'Laravel版本',
        max_upload_size: '最大上传限制',
        server_date: '服务器时间',
        local_date: '本地时间',
        protocol: '通信协议',
        port: '监听端口',
        method: '请求方法',
        execute_time: '执行时间',
        agent: '你使用的浏览器',
        user_ip: '你的IP',
        disk: '服务端剩余磁盘空间' 
      }
    }),
    created () {
      this.getSystemInfo();
    },

    computed: {
      system () {
        return this.$store.state.system;
      }
    },

    methods : {
      getSystemInfo () {
        request.get(createRequestURI('site/systeminfo'), {
          validateStatus: status => status === 200
        }).then(({ data = {} }) => {
          this.$store.commit(SETTINGS_SYSTEM_UPDATE, { ...data });
          this.loadding = false;
        }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
          this.loadding = false;
          this.error = true;
          window.alert(message);
        });
      }
    }
  }

  export default System;
</script>