<template>
  <div>
    <module-excellence-search :query="$route.query" :searching="searching"></module-excellence-search>

    <div class="panel panel-default">

      <module-excellence-list :excellences="excellences" :pass="handlePass" :deny="handleDeny" :publish-message="publishMessage"></module-excellence-list>

      <div class="panel-body">

        <!-- Alert -->
        <ui-alert :type="message.type" v-show="message.open">
          {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
        </ui-alert>

        <!-- 分页 -->
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

  </div>
</template>

<script>
import lodash from 'lodash';
import { localDateToUTC } from '../../filters';
import { admin } from '../../axios';
import components from '../modules/excellence';
export default {
  name: 'excellence',
  components,
  data: () => ({
    searching: true,
    total: 0,
    limit: 15,
    excellences: [],
    message: {
      open: false,
      type: '',
      data: {},
    },
    interval: null,
  }),
  computed: {
    offset() {
      const { query: { offset = 0 } } = this.$route;

      return parseInt(offset);
    }
  },
  watch: {
    '$route': function ($route) {
      this.searching = true;
      this.fetch($route.query);
    }
  },
  methods: {
    publishMessage(data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },

    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/excellences', query: { ...query, offset } };
    },

    fetch(query = {}) {
      const { start_date, end_date } = query;
      admin.get('/application-records', {
        validateStatus: status => status === 200,
        params: {
          ...query,
          start_date: start_date ? localDateToUTC(start_date) : '',
          end_date: end_date ? localDateToUTC(end_date) : '',
          limit: this.limit,
        }
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.searching = false;
        this.excellences = data;
        this.total = parseInt(total);
      }).catch(({ response: { data = {} } = {} }) => {
        this.searching = false;
        this.publishMessage(data, 'danger');
      });
    },

    handlePass (id) {
      this.excellences = lodash.map(this.excellences, (excellence) => {
        if (excellence.id === id) {
          excellence.status = 1;
        }

        return excellence;
      });
    },

    handleDeny (id) {
      this.excellences = lodash.map(this.excellences, (excellence) => {
        if (excellence.id === id) {
          excellence.status = 2;
        }

        return excellence;
      });
    },
  },
  created() {
    this.fetch(this.$route.query);
  }
};
</script>
