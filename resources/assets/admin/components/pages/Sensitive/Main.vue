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
