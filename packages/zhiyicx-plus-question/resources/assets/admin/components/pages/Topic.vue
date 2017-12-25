<template>
  <div>
    <ui-topic-nav-bar></ui-topic-nav-bar>
    <!-- Search -->
    <module-topic-search :searching="searching" :query="$route.query"></module-topic-search>

    <div class="panel panel-default">
      <module-topic-list :topics="topics" :publish-message="publishMessage" :update-topic="updateTopic"></module-topic-list>

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
import { admin } from '../../axios';
import components from '../modules/topic';
export default {
  name: 'topics',
  components,
  data: () => ({
    searching: true,
    interval: null,
    topics: [],
    total: 0,
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
      this.fetch($route.query);
    }
  },
  methods: {
    fetch (query = {}) {
      admin.get('/topics', {
        validateStatus: status => status === 200,
        params: query,
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.searching = false;
        this.topics = data;
        this.total = parseInt(total)
      }).catch(({ response: { data = {} } = {} }) => {
        this.searching = false;
        this.publishMessage(data, 'danger');
      });
    },

    publishMessage(data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },

    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/topics', query: { ...query, offset } };
    },
    /**
     * 更新话题列表节点.
     * 
     * @param  {int}  id     话题ID
     * @param  {int}  sort   排序权重
     * @param  {Boolean} isOpen 为true 更新节点status状态
     * @return {void}         
     */
    updateTopic(id, sort = '', isOpen=false) {
      let _this = this;
      this.topics.forEach((element, index) => {
        if (element.id == id) {
          if (isOpen) {
            element.status = element.status ? 0 : 1;
          } else {
            element.sort = parseInt(sort)
          }
        }
      });
      this.topics.sort((a, b) => {
        return b.sort - a.sort;
      });
    }
  },
  created () {
    this.fetch(this.$route.query);
  }
}
</script>
