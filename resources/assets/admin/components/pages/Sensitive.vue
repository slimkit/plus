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
      ></module-sensitive-list>

    </div>
  </div>
</template>

<script>
import lodash from 'lodash';
import components from '../modules/sensitive';
export default {
  name: 'page-sensitive',
  components,
  data: () => ({
    sensitives: [],
    loading: false,
  }),
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
    }
  },
};
</script>
