<style>
.amap-demo {
   height: 300px;
}
.search-box {
  position: absolute;
  top: 10px;
  left: 15px;
}

.amap-page-container {
  position: relative;
}
</style>
<template>
	<div>
		<el-amap-search-box class="search-box form-control" :search-option="searchOption" :on-search-result="onSearchResult">
		</el-amap-search-box>
		<el-amap vid="amapDemo" :events="events" :zoom="10" :center="mapCenter" class="form-control" style="height:400px;margin-top:-40px;">
			<el-amap-marker v-for="(marker, index) in markers" :position="marker" :key="index">
			</el-amap-marker>
		</el-amap>
	</div>	
</template>
<script>
export default({
  props: {
  	location: {
      type: Object,
  	}
  },
  data(){
  	const _self = this;
  	return {
	    lng: '',
	    lat: '',
		address: '',
	    markers: [],
	    searchOption: {
	      citylimit: false,
	    },
	    mapCenter: [121.59996, 31.197646],
	    events: {
	      click(e) {
	        let { lng, lat } = e.lnglat;
	        _self.lng = lng.toString();
	        _self.lat = lat.toString();
	        var geocoder = new AMap.Geocoder({
	          radius: 1000,
	          extensions: "all"
	        });        
	        geocoder.getAddress([lng ,lat], function(status, result) {
	          if (status === 'complete' && result.info === 'OK') {
	            if (result && result.regeocode) {
	              _self.address = result.regeocode.formattedAddress;
	              _self.$nextTick();
	        	  _self.locationCall();
	            }
	          }
	        });      
	      }
	    },
  	}
  },
  methods: {
  	locationCall() {
  	  const { lng, lat, address } = this;
  	  console.log(lng, lat, address);
  	  this.$emit('locationCall', { lng, lat, address });
  	},
	onSearchResult(pois) {
      let latSum = 0;
      let lngSum = 0;
      if (pois.length > 0) {
        pois.forEach(poi => {
          let {lng, lat} = poi;
          lngSum += lng;
          latSum += lat;
          this.markers.push([poi.lng, poi.lat]);
        });
        let center = {
          lng: lngSum / pois.length,
          lat: latSum / pois.length
        };
        this.mapCenter = [center.lng, center.lat];
      }
    }
  }
});
</script>