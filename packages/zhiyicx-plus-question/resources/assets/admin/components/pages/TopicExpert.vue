<template>
  <div class="panel panel-default">

    <div class="panel-heading">
      <router-link :to="`/topics/${id}`">
        <span class="glyphicon glyphicon-menu-left"></span>
        返回话题
      </router-link>
    </div>

    <table class="table table-hover table-responsive">

      <thead>
        <tr>
          <th># ID</th>
          <th>用户名</th>
          <th>提问数量</th>
          <th>回答数量</th>
          <th>财富收入</th>
          <th>排序</th>
          <th>成为专家时间</th>
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
          <td class="col-md-1"><input type="text" :value="user.expert_sort" class="form-control" @change="handleExpertSort(user.id, $event.target.value)"></td>
          <td>{{ user.expert_time | localDate }}</td>
          <td>
            <ui-process-button type="button" class="btn btn-danger btn-sm" @click="handleRemoveExpert($event, user.id)">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  撤销中...
                </template>
                <template v-else>撤销专家</template>
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
  name: 'topic-expert',
  data: () => ({
    loading: false,
    message: {
      open: false,
      type: '',
      data: {},
    },
    interval: null,
    limit: 15,
    total: 0,
    users: [],
  }),
  watch: {
    '$route': function ($route) {
      this.total = 0;
      this.getTopicExperts({ ...$route.query });
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
    }
  },
  methods: {
    getTopicExperts (query = {}) {
      this.loading = true;
      admin.get(`/topics/${this.id}/experts`, {
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

      return { path: `/topics/${this.id}/experts`, query: { ...query, offset, limit: this.limit } };
    },

    handleRemoveExpert ({ stopProcessing }, user_id) {
      admin.delete(`topics/${this.id}/experts/${user_id}`, {
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

    handleExpertSort (userId, sort) {
      admin.patch(`topics/${this.id}/experts/${userId}/sort`,
        { sort: sort }, 
        { validateStatus: status => status === 204 }
      ).then(response => {
        console.log(response);
      }).catch(({ response: { data } = {} }) => {
        this.loading = false;
        this.publishMessage(data, 'danger');
      });
    }
  },
  created () {
    this.getTopicExperts(this.$route.query);
  }
};
</script>
