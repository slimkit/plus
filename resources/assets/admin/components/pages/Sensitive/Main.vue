<template>
  <div class="container-fluid">
    <div class="panel panel-default">
      
      <!-- heading. -->
      <div class="panel-heading">敏感词管理</div>

      <!-- body. -->
      <div class="panel-body">
          
        <!-- Search. -->
        <module-sensitive-search></module-sensitive-search>

      </div>

      <!-- Table. -->
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>敏感词</th>
            <th>类型</th>
            <th>替换词</th>
            <th>操作</th>
          </tr>
        </thead>
      </table>

    </div>
  </div>
</template>

<script>
import components from '../../modules/sensitive';
export default {
  name: 'page-sensitive',
  components,
};
</script>
