<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">
		<div class="well well-sm">
      用户注册设置
    </div>
	</div>
</template>

<script>
	
	const RegisterSetting = {

	};

	export default RegisterSetting;
</script>