<template>
  <div class="container-fluid">
    <div class="panel panel-default">
      
      <!-- heading. -->
      <div class="panel-heading">敏感词管理</div>

      <!-- body. -->
      <div class="panel-body">
          
        <!-- Search. -->
        <module-sensitive-search :searching="loading"></module-sensitive-search>

      </div>

      <!-- Table. -->
      <module-sensitive-list
        v-if="! loading"
        :handle-append="handleAppend"
        :handle-change="handleChange"
        :handle-delete="handleDelete"
        :sensitives="sensitives"
      ></module-sensitive-list>

      <div class="panel-body">
        <ui-offset-paginator class="pagination" :total="total" :offset="offset" :limit="limit">
          <template slot-scope="pagination">
            <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
              <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
              <router-link v-else :to="buildRoute(pagination.offset)">{{ pagination.page }}</router-link>
            </li>
          </template>
        </ui-offset-paginator>
      </div>

    </div>
  </div>
</template>

<script>
import lodash from 'lodash';
import components from '../modules/sensitive';
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';
export default {
  name: 'page-sensitive',
  components,
  data: () => ({
    sensitives: [],
    loading: false,
    limit: 15,
    offset: 0,
    total: 0,
  }),
  watch: {
    '$route': function ({ query }) {
      const { offset } = query;
      this.offset = parseInt(offset);

      this.total = 0;
      this.fetchSensitives(query);
    }
  },
  methods: {
    handleChange ({ id, ...sensitive }) {
      this.sensitives = lodash.map(this.sensitives, (item) => {
        if (parseInt(item.id) === parseInt(id)) {
          item = { ...item, ...sensitive, id: parseInt(id) };
        }

        return item;
      });
    },

    handleDelete (id) {
      this.sensitives = lodash.reduce(this.sensitives, (sensitives, sensitive) => {
        if (parseInt(sensitive.id) !== parseInt(id)) {
          sensitives.push(sensitive);
        }

        return sensitives;
      }, []);
    },

    handleAppend (sensitive) {
      this.sensitives = [ ...this.sensitives, sensitive ];
    },

    fetchSensitives (query = {}) {
      this.loading = true;
      request.get(createRequestURI('sensitives'), {
        validateStatus: status => status === 200,
        params: { ...query, limit: this.limit, offset: this.offset },
      }).then(({ data, headers: { 'x-total': total = 0 } }) => {
        this.sensitives = data;
        this.loading = false;
        this.total = parseInt(total);
      }).catch(({ response: { data } = {} }) => alert(plusMessageFirst(data, '加载数据失败，请刷新重试！')));
    },

    buildRoute (offset) {
      let query = { offset };
      const { word, type } = this.$route;

      if (word) {
        query.word = word;
      }

      if (type === 'replace' || type === 'warning') {
        query.type = type;
      }

      return { path: '/setting/sensitives', query };
    },
  },
  created () {
    const { offset = 0 } = this.$route;
    this.offset = parseInt(offset);

    this.fetchSensitives(
      this.$route.query
    );
  }
};
</script>
