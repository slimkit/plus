<template>
	<div>
		<div class="col-md-2">
			<select class="form-control" v-model="cityPid" @change="changeProvince">
				<option :value="area.id" v-for="area in provinces">{{ area.name }}</option>
			</select>
		</div>
		<div class="col-md-3" v-show="cities.length">
			<select class="form-control" v-model="areaPid" @change="changeCity">
				<option :value="area.id" v-for="area in cities">{{ area.name }}</option>
			</select>
		</div>
		<div class="col-md-3" v-show="areas.length">
			<select class="form-control" v-model="pid">
				<option :value="area.id" v-for="area in areas">{{ area.name }}</option>
			</select>
		</div>
	</div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
export default {
  components: {
    name: 'area-linkage',
  },
  data: () => ({
  	areaItems: [],
  	provincePid: 1,
  	cityPid: '',
  	areaPid: '',
  	pid: '',
  	// location: '',
  }),
  computed: {
    provinces () {
      return this.byPidGetAreas(this.provincePid);
    },
    cities () {
	  return this.byPidGetAreas(this.cityPid);
    },
    areas () {
      return this.byPidGetAreas(this.areaPid);
    },
    location(){
    	return (`${this.byPidGetAreaName(this.cityPid)} ${this.byPidGetAreaName(this.areaPid)} ${this.byPidGetAreaName(this.pid)}`).trim();
    }
  },
  watch: {
  	location(val){
      this.$emit('input', val);
  	}
  },
  methods: {
  	byPidGetAreas(pid) {
      let trees = [
      	{ name: '全部', id: '' }
      ];
      this.areaItems.forEach(area => {
        if (parseInt(area.pid) === pid) {
          trees.push(area);
        }
      });
      return trees;
  	},
  	byPidGetAreaName(pid) {
  	  let name = '';
  	  this.areaItems.forEach(area => {
  	  	if (parseInt(area.id) === pid) {
  	  		name = area.name;
  	  	}
  	  });
  	  return name;
  	},
    getAreas () {
	  request.get(
	  	createRequestURI('site/areas'),
        { validateStatus: status => status === 200 }
      ).then(({ data = [] }) => {
        this.areaItems = data;
      }).catch(({ response: { data: { message = '获取地区失败' } = {} } = {} }) => {
        window.alert(message);
      });
    },
    changeProvince () {
      this.areaPid = this.pid = '';
    },
    changeCity () {
      this.pid = '';
    },
    changeArea () {
    }
  },
  created () {
    this.getAreas();
  }
};
</script>
