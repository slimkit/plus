<template>
<!-- 分页 -->
<div v-show="pagination.show">
  <ul class="pagination ma0">
    <!-- 上一页按钮 -->
    <li>
      <a href="#" aria-label="Previous" :disabled="!!!pagination.isPrevPage" @click.prevent.stop="pageGo(pagination.isPrevPage)">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <!-- 前页码 -->
    <li v-for="item in pagination.prevPages" :key="item.page">
      <a href="#" @click.prevent.stop="pageGo(item.page)">{{ item.name }}</a>
    </li>
    <!-- 当前页码 -->
    <li class="active">
      <a href="#" @click.prevent.stop="pageGo(pagination.current)">{{ pagination.current }}</a>
    </li>
    <!-- 后页码 -->
    <li v-for="item in pagination.nextPages" :key="item.page">
      <a href="#" @click.prevent.stop="pageGo(item.page)">{{ item.name }}</a>
    </li>
    <!-- 下一页按钮 -->
    <li>
      <a href="#" aria-label="Next" :disabled="!!!pagination.isNextPage" @click.prevent.stop="pageGo(pagination.isNextPage)">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</div>
</template>
<script>
  export default {
    name: 'Page',
    props: ['current', 'last'],
    computed: {
      pagination() {
        // 当前页
        let current = 1;
        current = isNaN(current = ~~(this.current)) ? 1 : current;
        // 最后页码
        let last = 1;
        last = isNaN(last = ~~(this.last)) ? 1 : last;
        // 是否显示
        const show = last > 1;
        // 前三页
        let minPage = current - 3;
        minPage = minPage <= 1 ? 1 : minPage;
        // 后三页
        let maxPage = current + 3;
        maxPage = maxPage >= last ? last : maxPage;
        // 是否有上一页
        let isPrevPage = false;
        // 前页页码
        let prevPages = [];
        // 前页计算
        if (current > minPage) {
          // 有上一页按钮
          isPrevPage = current - 1; // 如果有，传入上一页页码.
          // 回归第一页
          if (minPage > 1) {
            prevPages.push({
              name: current < 6 ? 1 : '1...',
              page: 1
            });
          }
          // 前页码
          for (let i = minPage; i < current; i++) {
            prevPages.push({
              name: i,
              page: i
            });
          }
        }
        // 是否有下一页
        let isNextPage = false;
        // 后页页码
        let nextPages = [];
        // 后页计算
        if (current < maxPage) {
          // 后页码
          for (let i = current + 1; i <= maxPage; i++) {
            nextPages.push({
              name: i,
              page: i
            });
          }
          // 进入最后页
          if (maxPage < last) {
            nextPages.push({
              name: current + 4 === last ? last : '...' + last,
              page: last,
            })
          }
          // 是否有下一页按钮
          isNextPage = current + 1;
        }
        return {
          show,
          current,
          prevPages,
          nextPages,
          isNextPage,
          isPrevPage,
        }
      }
    },
    methods: {
      pageGo(page) {
        return page && this.$emit('pageGo', page);
      }
    }
  };

</script>
