<template>
  <ul v-show="show" v-bind="props">
    <li class="page-item disabled">
      <a href="javascript:;"  style="border:none;">共 {{ total }} 条</a>
    </li>
    <!-- Previous Page Link -->
    <slot :disabled="onFirstPage" :offset="previousPageOffset" page="«">

      <li v-if="onFirstPage" class="page-item disabled">
        <span class="page-link">«</span>
      </li>
      <li v-else class="page-item">
        <a class="page-link" href="#" :data-offset="previousPageOffset">«</a>
      </li>

    </slot>

    <!-- Pagination Elements -->
    <slot v-for="element in elements" v-bind="element">
        
      <li v-if="element.currend" class="page-item active">
        <span class="page-link">{{ element.page }}</span>
      </li>
      <li v-else class="page-item">
        <a class="page-link" href="#" :data-offset="element.offset">{{ element.page }}</a>
      </li>

    </slot>

    <!-- Next Page Link -->
    <slot :disabled="!hasMorePages" :offset="nextPageOffset" page="»">
        
      <li v-if="hasMorePages" class="page-item">
        <a class="page-link" :data-offset="nextPageOffset">&raquo;</a>
      </li>
      <li v-else class="page-item disabled">
        <span class="page-link">&raquo;</span>
      </li>

    </slot>

  </ul>
</template>

<script>
export default {
  name: 'ui-offset-paginator',
  props: {
    total: { type: Number, default: 0 },
    offset: { type: Number, default: 45 },
    limit: { type: Number, default: 15 }
  },
  computed: {
    /**
     * Get pagination props.
     *
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    props() {
      const { total: {}, offset: {}, limit: {}, ...props } = this.$props;
      return props;
    },
    /**
     * Get pagination show condition.
     *
     * @return {Boolean}
     * @author Seven Du <shiweidu@outlook.com>
     */
    show() {
      return this.limit < this.total;
    },
    /**
     * 是否是第一页.
     *
     * @return {Boolean}
     * @author Seven Du <shiweidu@outlook.com>
     */
    onFirstPage() {
      return this.offset < this.limit;
    },
    /**
     * 获取前一页页码对应 offset.
     *
     * @return {Number}
     * @author Seven Du <shiweidu@outlook.com>
     */
    previousPageOffset() {
      if (this.offset > this.limit) {
        return this.offset - this.limit;
      }
      return 0;
    },
    /**
     * 是否有下一页.
     *
     * @return {Boolean}
     * @author Seven Du <shiweidu@outlook.com>
     */
    hasMorePages() {
      return (this.total - 15) > this.offset;
    },
    /**
     * 获取下一页的 offset.
     *
     * @return {Number}
     * @author Seven Du <shiweidu@outlook.com>
     */
    nextPageOffset() {
      if (this.hasMorePages) {
        return this.offset + this.limit;
      }
      return 0;
    },
    /**
     * 获取中间页码数据.
     *
     * @return {Array}
     * @author Seven Du <shiweidu@outlook.com>
     */
    elements() {
      let totalPage = this.total / this.limit;
      totalPage = totalPage < 1 ? 0 : totalPage;
      totalPage = totalPage > parseInt(totalPage) ? parseInt(totalPage) + 1 : totalPage;
      let currentPage = this.offset / this.limit;
      currentPage = currentPage < 1 ? 0 : currentPage;
      currentPage = currentPage > parseInt(currentPage) ? parseInt(currentPage) + 1 : currentPage;
      currentPage = currentPage > totalPage ? totalPage : currentPage;
      currentPage = currentPage + 1;
      let previousElements = [];
      let nextElements = [];
      let toFotTotal = ((currentPage - 2) < 1 ? 2 : 0) + ((currentPage + 2) > totalPage ? 2 : 0) + 2;
      for (let index = 1; index <= toFotTotal; index++) {
        if (currentPage - index > 1) {
          previousElements = [
            { page: currentPage - index, offset: (currentPage - index - 1) * this.limit, currend: false },
            ...previousElements
          ];
        }
        if (currentPage + index < totalPage) {
          nextElements.push({
            page: currentPage + index,
            offset: (currentPage + index - 1) * this.limit,
            currend: false
          });
        }
      }
      let previousStr = '';
      if (currentPage - 3 > 1) {
        previousStr = '...';
      }
      if (currentPage > 1) {
        previousElements = [
          {
            page: `1${previousStr}`,
            offset: 0,
            currend: false
          },
          ...previousElements
        ];
      }
      let nextStr = '';
      if (currentPage + 3 < totalPage) {
        nextStr = '...';
      }
      if (currentPage < totalPage) {
        nextElements.push({
          page: nextStr + totalPage,
          offset: (totalPage - 1) * this.limit,
          currend: false
        });
      }
      return [
        ...previousElements,
        { page: currentPage, currend: true },
        ...nextElements
      ];
    }
  },
};
</script>