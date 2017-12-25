<template>
  <div class="dropdown search-input-dropdown-box">

    <input
      :id="`search-${_uid}-dropdown`"
      :placeholder="placeholder || '请输入搜索内容'"
      v-model="keyword"
      class="form-control dropdown-toggle"
      type="text"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
    
    <ul class="dropdown-menu" :class="getDropdownClass" :aria-labelledby="`search-${_uid}-dropdown`">
      <li v-if="watching">
        <a href="#" @click.prevent.stop="() => {}">输入中...</a>
      </li>
      <li v-else-if="searching">
        <a href="#" @click.prevent.stop="() => {}">搜索中...</a>
      </li>
      <li v-else-if="! data.length">
        <a href="#" @click.prevent.stop="() => {}">暂无结果</a>
      </li>
      <template v-else>
        <li v-for="item in data">
          <a href="#" @click.prevent.stop="handleSelect(item)">
            <slot :data="item"></slot>
          </a>
        </li>
      </template>
    </ul>

  </div>
</template>

<script>
import lodash from 'lodash';
export default {
  name: 'module-search-input',
  props: {
    placeholder: String,
    search: { type: Function, required: true },
    handleSelected: { type: Function, required: true }
  },
  data: () => ({
    keyword: '',
    show: false,
    watching: false,
    searching: false,
    data: []
  }),
  computed: {
    getDropdownClass() {
      return this.show ? 'search-input-dropdown-list' : '';
    }
  },
  watch: {
    keyword(keyword) {
      this.show = true;
      if (! keyword) {
        this.show = false;
        return ;
      }

      this.watching = true;
      this.handleSearch(keyword);
    }
  },
  methods: {
    handleSelect(item) {
      this.show = false;
      this.keyword = '';
      this.data = [];
      this.handleSelected(item);
    },
    handleSearch: lodash.debounce(function (keyword) {
      this.search( keyword,
        (data = []) => {
          this.data = data;
          this.searching = false;
        },
        () => {
          this.searching = true;
          this.watching = false;
        },
      );
    }, 600)
  }
};
</script>

<style>
.dropdown-menu {
  max-height: 200px;
  overflow: scroll;
}
.search-input-dropdown-list {
  display: block !important;
}
.search-input-dropdown-box .dropdown-menu {
  display: none;
}
</style>
