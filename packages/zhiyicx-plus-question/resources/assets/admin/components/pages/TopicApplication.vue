<template>
  <div>
    <!-- 二级nav -->
    <ui-topic-nav-bar></ui-topic-nav-bar>
    <!-- 搜索 -->
    <module-topic-application-search :query="$route.query" :searching="searching"></module-topic-application-search>
    <ui-alert :type="message.type" v-show="message.open">
      {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
    </ui-alert>
    <div class="panel panel-default">
      <div class="panel-body">
        <!-- 列表 -->
        <module-topic-application-list :items="items" :publishMessage="publishMessage"></module-topic-application-list>
        <!-- 分页 -->
        <ui-offset-paginator class="pagination" :total="total" :offset="offset" :limit="15">
          <template slot-scope="pagination">
            <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
              <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
              <router-link v-else :to="buildLocalhost(pagination.offset)">{{ pagination.page }}</router-link>
            </li>
          </template>
        </ui-offset-paginator>
      </div>
    </div>
  </div>
</template>

<script>
import { admin } from '../../axios';
import components from '../modules/topic/application';

export default {
  name: 'topic-applications',
  components,
  data: () => ({
    searching: false,
    total: 0,
    items: [],
    message: {
      open: false,
      type: '',
      data: {}
    }
  }),
  computed: {
    offset () {
      const { query: { offset = 0 } } = this.$route;

      return parseInt(offset);
    }
  },
  watch: {
    '$route': function ($route) {
      this.searching = true;
      this.total = 0;
      this.fetchQuertion({ ...$route.query });
    },
  },
  methods: {
    fetchQuertion(query = {}) {

      admin.get('/topic-application-records', {
        validateStatus: status => status === 200,
        params: { ...query, limit: 15 }
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.total = parseInt(total);
        this.searching = false;
        this.items = data;
      }).catch(({ response: { data = {} } = {} } = {}) => {
        this.searching = false;
        this.publishMessage(data, 'danger');
      });
    },
    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/topics/applications', query: { ...query, offset } };
    },
    publishMessage (data, type, ms = 2000) {
      this.message = { open: true, data, type };
      setTimeout(() => {
        this.message.open = false;
      }, ms);
    },
  },
  created () {
    this.fetchQuertion(this.$route.query);
  }
}
</script>
