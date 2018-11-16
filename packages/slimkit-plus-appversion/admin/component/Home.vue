<template>
  <div>
  	<h4>App版本管理</h4>
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="开关设置" name="status">
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
      </el-tab-pane>
      <el-tab-pane label="当前版本" name="current">
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
      </el-tab-pane>
      <el-tab-pane label="历史版本" name="versions">
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
      </el-tab-pane>
      <el-tab-pane label="添加版本" name="add">
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<style lang="less" module>
  .textinput, .upload {
    padding: 8px 0;
  }
  .pagination {
    margin: 8px 0;
  }
</style>
<script>
	const Home = {
    data: () => ({
      data: {
        _token: window.Version.csrf_token
      },
      activeName: 'status',
      type: 'android',
      current:{},
      versions: [],
      news: {
        link: '',
        version: '',
        version_code: 0,
        type: '',
        is_forced: 0,
        storage: 0,
        description: ''
      },
      total: 0,
      currentPage: 1,
      open: false
    }),


	}

	export default Home;
</script>
