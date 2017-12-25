<template>
  <div>
    <module-question-search :query="$route.query" :searching="searching"></module-question-search>
    <div class="panel panel-default">
      <div class="panel-heading">筛选结果</div>

      <module-question-list :questions="questions" :update-question="updateQuestion" :publish-message="publishMessage"></module-question-list>

      <div class="panel-body" v-show="total || message.open">

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
import components from '../modules/question';
export default {
  components,
  name: 'question',
  data: () => ({
    searching: false,
    total: 0,
    questions: [],
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

      const { start_date, end_date } = query;

      admin.get('/questions', {
        validateStatus: status => status === 200,
        params: {
          ...query,
          limit: 15,
          start_date: start_date ? localDateToUTC(start_date) : '',
          end_date: end_date ? localDateToUTC(end_date) : ''
        }
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.total = parseInt(total);
        this.searching = false;
        this.questions = data;
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

    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/questions', query: { ...query, offset } };
    },

    updateQuestion(id, question = {}, isDelete = false) {
      this.questions = lodash.reduce(this.questions, (reduce, item) => {
        if (item.id === id) {
          if (isDelete) {
            return reduce;
          }

          item = { ...item, ...question };
        }

        reduce.push(item);

        return reduce;
      }, []);
    }
  },

  created() {
    this.fetchQuertion(this.$route.query);
  }
};
</script>
