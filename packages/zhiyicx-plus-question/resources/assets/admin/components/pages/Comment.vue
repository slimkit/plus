<template>
  <div>
    
    <module-comment-search :query="$route.query" :searching="searching"></module-comment-search>

    <div class="panel panel-default">

      <div class="panel-body">

        <module-comment-list :comments="comments" :handle-remove="removeComment" :handle-message="publishMessage"></module-comment-list>

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
import components from '../modules/comment';
export default {
  components,
  name: 'comment',
  data: () => ({
    searching: true,
    total: 0,
    comments: [],
    message: {
      open: false,
      type: '',
      data: {},
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
      this.fetchComments($route.query);
    }
  },
  methods: {
    fetchComments(query = {}) {
      const { start_date, end_date } = query;
      admin.get('/comments', {
        validateStatus: status => status === 200,
        params: {
          ...query,
          start_date: start_date ? localDateToUTC(start_date) : '',
          end_date: end_date ? localDateToUTC(end_date) : '',
          limit: 15
        }
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.total = parseInt(total);
        this.comments = data;
        this.searching = false;
      }).catch(({ response: { data = {} } = {} }) => {
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

      return { path: '/comments', query: { ...query, offset } };
    },

    removeComment(id) {
      this.comments = lodash.reduce(this.comments, (reduce, comment) => {
        if (comment.id === id) {
          return reduce;
        }

        reduce.push(comment);

        return reduce;
      }, []);
    }
  },
  created() {
    this.fetchComments(this.$route.query);
  }
};
</script>
