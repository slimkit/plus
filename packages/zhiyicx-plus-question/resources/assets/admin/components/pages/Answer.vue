<template>
  <div>
    <module-answer-search :query="$route.query" :searching="searching"></module-answer-search>

    <div class="panel panel-default">

      <div class="panel-heading">筛选结果</div>

      <module-answer-list
        :answers="answers"
        :update-answer="updateAnswer"
        :publish-message="publishMessage"
      >
      </module-answer-list>

      <div class="panel-body">
        <ui-alert :type="message.type" v-show="message.open">
          {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
        </ui-alert>

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
import lodash from 'lodash';
import { localDateToUTC } from '../../filters';
import { admin } from '../../axios';
import components from '../modules/answer';
export default {
  components,
  name: 'answer',
  data: () => ({
    total: 0,
    searching: false,
    answers: [],
    message: {
      open: false,
      type: '',
      data: {}
    },
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
      this.answers = [];
      this.fetchAnswerData($route.query);
    }
  },
  methods: {
    fetchAnswerData(query = {}) {
      const { start_date, end_date } = query;
      admin.get('/answers', {
        validateStatus: status => status === 200,
        params: {
          ...query,
          start_date: start_date ? localDateToUTC(start_date) : '',
          end_date: end_date ? localDateToUTC(end_date) : '',
          limit: 15
        }
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.answers = data;
        this.total = parseInt(total);
        this.searching = false;
      }).catch(({ response: { data = {} } = {} } = {}) => {
        this.searching = false;
        this.publishMessage(data, 'danger');
      });
    },

    publishMessage (data, type, ms = 3000) {
      this.message = { open: true, data, type };
      setTimeout(() => {
        this.message.open = false;
      }, ms);
    },

    updateAnswer(id, answer = {}, isDelete = false) {
      this.answers = lodash.reduce(this.answers, (reduce, item) => {
        if (item.id === id) {
          if (isDelete) {
            return reduce;
          }

          item = { ...item, ...answer };
        }

        reduce.push(item);

        return reduce;
      }, []);
    },

    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/answers', query: { ...query, offset } };
    },
  },
  created() {
    this.fetchAnswerData(this.$route.query);
  }
};
</script>
