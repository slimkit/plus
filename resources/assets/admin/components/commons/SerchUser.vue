<style scoped>
.avatar {
  height:20px;
  width:20px;
  display:inline-block;
}
</style>
<template>
	<div style="position: relative;">
	  <input type="text" 
	    class="form-control dropdown-toggle" 
	    data-toggle="dropdown" 
	    aria-haspopup="true" 
	    aria-expanded="false"
	    v-model="username" 
	    @input="searchUser"
	    placeholder="请输入用户名">
	    <ul class="dropdown-menu" style="max-height: 200px;overflow: auto;">
	      <template v-if="users.length">
	        <li  v-for="user in users" @click.prevent="choiceUser(user.id)" :key="user.id">
	          <a href="javascript:;">
	            <img :src="`${user.avatar}?w=40&height=40`" class="img-circle avatar" v-if="user.avatar">
	            <i class="glyphicon glyphicon-user" v-else></i>
	            <span>{{ user.name }} #ID {{ user.id }}</span>
	          </a>
	        </li>
	      </template>
	      <template v-else>
	        <li @click.prevent="choiceUser(0)"><a href="javascript:;">无相关用户</a></li>
	      </template>
	    </ul>
	</div>
</template>
<script>
import request, { createRequestURI } from '../../util/request.js';

export default {
  name: 'search-user',
  props: {
    getUserId: { type: Function, required: true },
  },
  data:() => ({ username: null, users: [] }),
  methods: {
    searchUser () {
      request.get(
        createRequestURI('find/nocertification/users?keyword=' + this.username),
        { validateStatus: status => status === 200 }
      ).then(response => {
        this.users = response.data;
      }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
        window.alert(message);
      });
    },
    choiceUser (id) {
      this.getUserId(parseInt(id));
    }
  }
}
</script>