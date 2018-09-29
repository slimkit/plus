<template>
  <div  class="container-fluid" style="margin-top:10px;">
    <!-- cors setting panel -->
    <cors />

    <!-- 支付验证密码开关 -->
    <pay-switch />

  </div>
</template>

<script>
import Cors from '../../components/modules/setting/Cors';
import PaySwitch from '../../components/modules/setting/security-pay-validate-password';
export default {
  components:{
    Cors,
    PaySwitch,
  },
};
</script>
