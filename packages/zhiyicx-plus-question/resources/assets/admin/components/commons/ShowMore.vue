<template>
<p class="comment-content" :title='content' :class='{all: isShow}' @click='showAll' v-if="content.length>30">
	<a href="javascript:;">{{ content }}</a>
</p>
<p v-else>{{ content }}</p>
</template>
<script>
export default {
	name: 'ui-show-more',
	data:()=>({
	  isShow: false,
	}),
	props: {
	  content: {type: String, required: true}
	},
	methods:{
	  showAll(){
	    this.isShow = !this.isShow;
	  },

	},
}
</script>

<style>
	.comment-content{
		max-width: 300px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.comment-content.all{
		white-space: normal;
	}
</style>