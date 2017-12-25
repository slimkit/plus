<template>
<div>	
	<ui-panle title="基础设置">
	  <basic-setting></basic-setting>
	</ui-panle>
	<ui-panle title="统计信息">
	  <div class="col-lg-4">
	  	<statistics></statistics>
	  </div>
	</ui-panle>
</div>
</template>
<script>
import components from '../modules/home';
export default({ components: components });
</script>