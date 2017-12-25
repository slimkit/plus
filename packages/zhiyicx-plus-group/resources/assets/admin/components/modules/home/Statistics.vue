<template>
<div>
  <ul class="list-group">
    <li class="list-group-item"><span class="badge">{{ group.all }}</span>圈子总数</li>
    <li class="list-group-item"><span class="badge">{{ group.pub }}</span>公开圈子</li>
    <li class="list-group-item"><span class="badge">{{ group.pri }}</span>私密圈子</li>
    <li class="list-group-item"><span class="badge">{{ group.pai }}</span>收费圈子</li>
    <li class="list-group-item"><span class="badge">{{ group.post }}</span>全部帖子</li>
  </ul>
</div>
</template>
<script>
import { admin } from '../../../axios';
export default({
  data:()=>({
    group: {
      all: 0,
      pub: 0,
      pri: 0,
      pai: 0,
      post: 0,
    },
  }),
  methods: {
  	getStatistics() {
  		admin.get('statistics')
  		.then(({ data }) => {
        this.group = data;
  		}).catch(error =>{

  		})
  	},
    refresh() {
      this.getStatistics();
    }
  },
  created() {
  	this.getStatistics();
  }
})
</script>