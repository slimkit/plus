<template>
  <!-- 基本信息 -->
  <div class="panel panel-default">
    <div class="panel-heading">
        
      <router-link to="/topics">
        <span class="glyphicon glyphicon-menu-left"></span>
        返回列表
      </router-link>

      <router-link class="pull-right" :to="`/topics/${id}/edit`">
        <span class="glyphicon glyphicon-edit"></span>
        编辑话题
      </router-link>

    </div>

    <ul class="list-group" v-show="!loading">
      <li class="list-group-item" v-show="topic.avatar">头像：<img :src="topic.avatar" width="100px" height="100px">  </li>
      <li class="list-group-item">ID：{{ id }}</li>
      <li class="list-group-item">话题：{{ topic.name }}</li>
      <li class="list-group-item">描述：{{ topic.description }}</li>
      <li class="list-group-item">成员数：{{ topic.follows_count | thousands }}</li>
      <li class="list-group-item">专家数：{{ topic.experts_count | thousands }}</li>
      <li class="list-group-item">问题数：{{ topic.questions_count | thousands }}</li>
    </ul>

    <div class="panel-body" v-show="loading || !message.open">
      <ui-loading v-show="loading"></ui-loading>

      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('加载失败！') }}
      </ui-alert>

      <router-link class="btn btn-default" :to="{ path: '/questions', query: { topic: id } }">问题管理</router-link>
      <router-link class="btn btn-default" :to="`/topics/${id}/followers`">成员管理</router-link>
      <router-link class="btn btn-default" :to="`/topics/${id}/experts`">专家管理</router-link>

    </div>
  </div>
</template>

<script>
import { admin } from '../../axios';
export default {
  name: 'topic-base',
  data: () => ({
    topic: {},
    loading: false,
    message: {
      open: false,
      type: '',
      data: {},
    },
    interval: null,
  }),
  computed: {
    id () {
      const { id } = this.$route.params;

      return parseInt(id);
    }
  },
  methods: {
    publishMessage (data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },
  },
  created () {
    this.loading = true;
    admin.get(`/topics/${this.id}`, {
      validateStatus: status => status === 200,
    }).then(({ data }) => {
      this.loading = false;
      this.topic = data;
    }).catch(({ response: { data } = {} }) => {
      this.publishMessage(data, 'danger', 5000);
    });
  }
};
</script>
