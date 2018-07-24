<template>
  <div  class="container-fluid" style="margin-top:10px;">
    <!-- cors setting panel -->
    <cors />

  </div>
</template>

<script>
import Cors from '../../components/modules/setting/Cors';
export default {
  components:{
    'cors': Cors,
  },
};
</script>
