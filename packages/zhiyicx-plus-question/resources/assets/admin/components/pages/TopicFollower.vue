<template>
  <div class="panel panel-default">

    <div class="panel-heading">
      <router-link :to="`/topics/${id}`">
        <span class="glyphicon glyphicon-menu-left"></span>
        返回话题
      </router-link>
    </div>
    <div class="panel-heading">
      <form class="form-inline">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="用户名" v-model="filter.name">
          <router-link class="btn btn-default" tag="button" :to="{ path: `/topics/${id}/followers`, query: searchQuery }">
            搜索
          </router-link>
        </div>
      </form>
    </div>
    <table class="table table-hover table-responsive">
      
      <thead>
        <tr>
          <th># ID</th>
          <th>用户名</th>
          <th>提问数量</th>
          <th>回答数量</th>
          <th>财富收入(金额)</th>
          <th>关注时间</th>
          <th>操作</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.extra ? user.extra.questions_count : 0 }}</td>
          <td>{{ user.extra ? user.extra.answers_count : 0 }}</td>
          <td>
            <span class="text-primary">邀请问答：¥{{ user.invited_income / 100 | money }}</span>
            <span class="text-primary">公开悬赏：¥{{ user.adoption_income / 100 | money }}</span><br>
            <span class="text-primary">用户打赏：¥{{ user.reward_income / 100 | money }}</span>
            <span class="text-primary">用户围观：¥{{ user.onlooker_income / 100 | money }}</span>
          </td>
          <td>{{ user.follow_time | localDate }}</td>
          <td>

            <ui-process-button type="button" class="btn btn-primary btn-sm" @click="handleSetExpert($event, user.id)">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  设置中...
                </template>
                <template v-else>设置专家</template>
              </template>
            </ui-process-button>

            <ui-process-button type="button" class="btn btn-danger btn-sm" @click="handleRemove($event, user.id)">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  移除中...
                </template>
                <template v-else>移出话题</template>
              </template>
            </ui-process-button>

          </td>
        </tr>
      </tbody>

    </table>

    <div class="panel-body">
        
      <ui-loading v-show="loading"></ui-loading>

      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('加载失败！') }}
      </ui-alert>

      <ui-offset-paginator class="pagination" :total="total" :offset="offset" :limit="limit">
        <template slot-scope="pagination">
          <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
            <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
            <router-link v-else :to="buildLocalhost(pagination.offset)">{{ pagination.page }}</router-link>
          </li>
        </template>
      </ui-offset-paginator>

    </div>

  </div>
</template>

<script>
import lodash from 'lodash';
import { admin } from '../../axios';
export default {
  name: 'topic-follower',
  data: () => ({
    loading: false,
    message: {
      open: false,
      type: '',
      data: {},
    },
    filter: {
      name: '',
    },
    interval: null,
    users: [],
    limit: 15,
    total: 0
  }),
  watch: {
    '$route': function ($route) {
      this.total = 0;
      this.getTopicFollowers({ ...$route.query });
    },
  },
  computed: {
    id () {
      const { id } = this.$route.params;

      return parseInt(id);
    },
    offset () {
      const { offset = 0 } = this.$route.query;

      return parseInt(offset);
    },
    searchQuery () {
      return { ...this.filter, offset: 0 };
    },
  },
  methods: {
    getTopicFollowers (query = {}) {
      admin.get(`/topics/${this.id}/followers`, {
        validateStatus: status => status === 200,
        params: { ...query, limit: this.limit },
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.users = data;
        this.loading = false;
        this.total = parseInt(total);
      }).catch(({ response: { data } = {} }) => {
        this.loading = false;
        this.publishMessage(data, 'danger');
      });
    },

    publishMessage (data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },

    buildLocalhost (offset) {
      const { query } = this.$route;

      return { path: `/topics/${this.id}/followers`, query: { ...query, offset, limit: this.limit, ...this.filter } };
    },

    handleSetExpert ({ stopProcessing = () => {} }, user_id) {
      admin.post(`/topics/${this.id}/experts/${user_id}`, {}, {
        validateStatus: status => status === 201,
      }).then(() => {
        this.publishMessage({ message: '设置成功！' }, 'success');
        stopProcessing();
      }).catch(({ response: { data } = {} }) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },

    handleRemove ({ stopProcessing = () => {} }, user_id) {
      admin.delete(`/topics/${this.id}/followers/${user_id}`, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.users = lodash.reduce(this.users, (reduce, user) => {
          if (user.id != user_id) {
            reduce.push(user);
          }

          return reduce;
        }, []);
      }).catch(({ response: { data } = {} }) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },
  },
  created () {
    this.getTopicFollowers(this.$route.query);
  }
};
</script>
