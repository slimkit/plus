<template>
  <ui-panle title="创建圈子">
    <router-link to="/groups" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading">
      <i class="glyphicon glyphicon-menu-left"></i>返回</router-link>
	 <div class="form-horizontal">
	 	<!-- 名称 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>名称</label>
	     <div class="col-xs-7">
	     	<input type="text" class="form-control" placeholder="圈子名称" v-model="group.name" maxlength="20">
	     </div>
	     <div class="col-xs-3 help-block">
	     	输入圈子名称 长度20字符之内
	     </div>
	   </div>

	   <!-- 头像 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>头像</label>
	     <div class="col-xs-7">
			<input type="file" @change="uploadAvatar" ref="uploadFile" class="form-control">
			<img :src="avatarUrl" v-show="avatarUrl" style="max-height:100px;" class="img-thumbnail">
	     </div>
	     <div class="col-xs-3 help-block">
	     	上传圈子头像，头像最好正方形，大小2M之内
	     </div>
	   </div>

	   <!-- 分类 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>分类</label>
	     <div class="col-xs-7">
	     	<select name="" id="" class="form-control" v-model="group.category_id">
	     		<option :value="cate.id" v-for="cate in cates">{{ cate.name }}</option>
	     	</select>
	     </div>
	     <div class="col-xs-3 help-block">
	     	选择圈子分类
	     </div>
	   </div>

	   <!-- 圈主 -->
<!-- 	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>圈主</label>
	     <div class="col-xs-4">
	     	<input type="number" class="form-control" placeholder="圈主" v-model="group.user_id">
	     </div>
	     <div class="col-xs-3">
	     	<module-search-user :handleSelected="searchUserCall"></module-search-user>
	     </div>
	     <div class="col-xs-3 help-block">
			用户 ID，如果不知道用户 ID，请选择输入框后面的搜索框搜索用户
	     </div>
	   </div> -->

	   <!-- 标签 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>标签</label>
	     <div class="col-xs-7">
			<div class="input-group">
		      <div class="form-control">
		      	<span class="label label-success" v-for="tag in checkedTags" style="margin-left:2px;">{{ tag }}</span>
		      </div>	
		      <span class="input-group-btn">
		     	<button class="btn btn-default" @click="tagShow=!tagShow">
		     	<i class="glyphicon glyphicon-tags"></i>
		     	</button>
		      </span>
		    </div>
	     	<div v-for="tag in tags" v-show="tagShow">
				<span class="help-block">{{ tag.name }}</span>
				<label class="checkbox-inline" v-for="item in tag.tags">
				  <input type="checkbox" :value="item.id" @click="handleCheckbox($event, item)" :checked="lodashIndexOf(item.name, checkedTags) !== -1 "> {{ item.name }}
				</label>
	     	</div>
	     </div>
	     <div class="col-xs-3 help-block">
	     	圈子标签 最多只能选择五个标签
	     </div>
	   </div>

	   <!-- 位置 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2">位置</label>
	     <div class="col-xs-7">
			<div class="input-group">
		      <div class="form-control" style="font-size:6px;">{{ group.location }}</div>
		      <span class="input-group-btn">
		        <button class="btn btn-default" @click="mapShow=!mapShow"><i class="glyphicon glyphicon-map-marker"></i></button>
		      </span>
		    </div>
		    <geo-map @locationCall="getLocationCall" v-show="mapShow"></geo-map>
	     </div>
	     <div class="col-xs-3 help-block">
	     	圈子所在的地理位置
	     </div>
	   </div>

	   <!-- 类型 -->
	   <div class="form-group" v-if="group.mode !== 'paid'">
	     <label class="control-label col-xs-2">类型</label>
	     <div class="col-xs-3">
			<label class="radio-inline">
			  <input type="radio" name="mode" value="public" v-model="group.mode"> 公开
			</label>
			<label class="radio-inline">
			  <input type="radio" name="mode" value="private" v-model="group.mode"> 私密
			</label>
			<label class="radio-inline">
			  <input type="radio" name="mode" value="paid" v-model="group.mode"> 收费
			</label>
	      </div>
	      <div class="col-xs-4">
	      	<input type="number" class="form-control" v-show="paid" v-model="group.money" placeholder="金额">
	      </div>
	     <div class="col-xs-3 help-block">
	     	圈子类型 收费需要填写加圈金额
	     </div>
	   </div>

	   <!-- 权限 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2">权限</label>
	     <div class="col-xs-7">
			<label class="radio-inline">
			  <input type="radio" name="permissions" value="3" v-model="group.permissions"> 所有
			</label>
			<label class="radio-inline">
			  <input type="radio" name="permissions" value="2" v-model="group.permissions"> 管理员和圈主
			</label>
			<label class="radio-inline">
			  <input type="radio" name="permissions" value="1" v-model="group.permissions"> 圈主
			</label>
	     </div>
	     <div class="col-xs-3 help-block">
	     	发帖权限 限制用户发帖
	     </div>
	   </div>

	   <!-- 推荐 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2">推荐</label>
	     <div class="col-xs-7">
			<label class="radio-inline">
			  <input type="radio" name="pinned" value="1" v-model="group.recommend"> 是
			</label>
			<label class="radio-inline">
			  <input type="radio" name="pinned" value="0" v-model="group.recommend"> 否
			</label>
	     </div>
	     <div class="col-xs-3 help-block">
	     	圈子是否推荐 圈子首页显示推荐列表
	     </div>
	   </div>

	   <!-- 状态 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2">状态</label>
	     <div class="col-xs-7">
			<label class="radio-inline">
			  <input type="radio" name="audit" value="1" v-model="group.audit"> 启用
			</label>
			<label class="radio-inline">
			  <input type="radio" name="audit" value="3" v-model="group.audit"> 禁用
			</label>
	     </div>
	     <div class="col-xs-3 help-block">
	     	是否启用 启用用户正常使用
	     </div>
	   </div>

	   <!-- 简介 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>简介</label>
	     <div class="col-xs-7">
	     	<textarea class="form-control" maxlength="255" v-model="group.summary"></textarea>
	     </div>
	     <div class="col-xs-3 help-block">
	     	圈子简介描述 长度255字符之内
	     </div>
	   </div>

	   <!-- 公告 -->
	   <div class="form-group">
	     <label class="control-label col-xs-2"><span class="text-danger">*</span>公告</label>
	     <div class="col-xs-7">
	     	<textarea class="form-control" maxlength="2000" v-model="group.notice"></textarea>
	     </div>
	     <div class="col-xs-3 help-block">
	     	圈子公告 长度2000字符之内
	     </div>
	   </div>
	   <div class="form-group">
	     <label class="control-label col-xs-2"></label>
	     <div class="col-xs-7">
	     	<button class="btn btn-primary btn-sm" @click="handleSubmit">提交</button>
	     </div>
	     <div class="col-xs-3 help-block">
	     </div>
	   </div>
	 </div>
  </ui-panle>
</template>
<script>
import SearchUser from '../modules/SearchUser';
import plueMessageBundle from 'plus-message-bundle';
import GeoMap from '../modules/GeoMap';
import { admin, api } from '../../axios';
import lodash from 'lodash';

const getFileUrl = (file) => {
    let url = null;
    if(window.createObjectURL != undefined) { // basic  
        url = window.createObjectURL(file);
    } else if(window.URL != undefined) { // mozilla(firefox)  
        url = window.URL.createObjectURL(file);
    } else if(window.webkitURL != undefined) { // webkit or chrome  
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

export default({
  components: {
    'module-search-user': SearchUser,
    'geo-map': GeoMap
  },
  data(){
  	const _self = this;
  	return {
  	  id: null,
	  cates: [],
	  tags: [],
	  tagShow: false,
	  checkedTags: [],
	  mapShow: false,
	  paid: false,
	  avatarUrl: null,
      group: {
        name: null,
      	avatar: null,
        user_id: null,
        category_id: null,
        mode: 'public',
        permissions: 3,
        recommend: 0,
        audit: 1,
        tags:[],
        money: 0,
        summary: null,
        notice: null,
        location: null,
        latitude: null,
        longitude: null,
      },
	  message: {
	    open: false,
	    type: '',
	    data: {},
	  },
    }
  },
  watch: {
    'group.mode'(val) {
      this.paid =  (val == 'paid');
    }
  },
  methods: {
    /**
     * Get categories.
     * @return void
     */
    getCategories() {
      admin.get(
        'categories?type=all',{
          validateStatus: status => status === 200,
          params: {},
      })
      .then(({ data = [] }) => {
        this.cates = data;
      })
    },
    /**
     * Get tags.
     * @return void
     */
    getTags() {
      api.get(
        'tags',{
          validateStatus: status => status === 200,
          params: {},
      }).then(({ data = [] }) => {
        this.tags = data;
      });
    },
   getGroup() {
	  admin.get(
	  `groups/${this.id}`,{
		  validateStatus: status => status === 200,
		  params: {},
	  }).then(({ data = [] }) => {
	  	const { 
	  		name, 
	  		category_id,
	  		mode,
	  		money,
	  		avatar,
	  		user_id,
	  		summary,
	  		notice,
	  		recommend,
	  		permissions,
	  		tags,
	  		location,
        	latitude,
        	longitude,
        	audit,
	  	} = data;
	  	let group = this.group;

	  	if (avatar) {
	  		this.avatarUrl = `${avatar}`;
	  	}
	  	
	  	group.name = name;
	  	group.category_id = category_id;
	  	group.mode = mode;
	  	group.money = money;
	  	group.user_id = user_id;
	  	group.summary = summary;
	  	group.notice = notice;
	  	group.recommend = recommend ? 1 : 0;
	  	group.permissions = permissions.split(',').length;
	  	group.tags = this.setTag(tags);
	  	group.location = location;
	  	group.latitude = latitude;
	  	group.longitude = longitude;
	  	group.audit = audit;
	  });
    },
    setTag(tags) {	
      let ids = [], _this = this;
      tags.forEach(item=>{
        _this.checkedTags.push(item.name);
        ids.push(item.id);
      });
      return ids;
    },
    /**
     * Search user call.
     * @param  options.id: user
     * @return void
     */
    searchUserCall({ id: user }) {
       this.group.user_id = user;
    },
    /**
     * Check tag.
     * @param  item
     * @return void
     */
    handleCheckbox(e, item) {
      let index = lodash.indexOf(this.group.tags, item.id);
      let nameIndex = lodash.indexOf(this.checkedTags, item.name);

      if (index === -1) {
      	if( this.group.tags.length > 4) return e.target.checked = false;
      	this.checkedTags = [item.name, ...this.checkedTags];
      	this.group.tags = [item.id, ... this.group.tags];
      } else {
      	this.group.tags.splice(index, 1);
      	this.checkedTags.splice(nameIndex, 1);
      }
    },
    /**
     * 高德定位回调.
     * @param  val
     * @return void
     */
    getLocationCall (val) {
      const { lat, lng, address } = val;
      this.group.latitude = lat;
      this.group.longitude = lng;
      this.group.location = address;	
    },
    /**
     * Handle sumbit.
     * @return void
     */
    handleSubmit() {
	  let params = new FormData();
	  Array.from(Object.keys(this.group)).filter((key)=> !lodash.isNull(this.group[key])).forEach(k=>{
	  	params.append(k, this.group[k]);
	  });
      admin.post(
        `groups/${this.id}`, params,
        { validateStatus: status => status === 201 })
      .then(({ data }) => {
      	 this.$store.dispatch('alert-open', { type: 'success', message: data });
      	 setTimeout(()=>{
      	 	this.$router.replace({ path: '/groups' });
      	 }, 3000);
      }).catch(({ response: { data = { message: '创建失败' } } = {} }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    },
    uploadAvatar(e) {
      let files = e.target.files;
      if (!files.length) {
      	return this.$store.dispatch('alert-open', { type: 'danger', message: { 'message': '请选择图片' } });
      }
      if (files[0].size/1024000 > 2) {
      	return this.$store.dispatch('alert-open', { type: 'danger', message: { 'message': '请选择图片' } });
      }
      let vm = this;
      this.$ImgCropper.show({
        url: getFileUrl(files[0]),
        round: false,
        onCancel(){
            vm.$refs.uploadFile.value = null;
        },
        onOk(data){
          vm.avatarUrl = data;
          vm.group.avatar = vm.dataURItoBlob(data);
          vm.$refs.uploadFile.value = null;
        },
      });
    },
    dataURItoBlob (dataURI) {
      let byteString = atob(dataURI.split(',')[1]);
      let mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
      let ab = new window.ArrayBuffer(byteString.length);
      let ia = new window.Uint8Array(ab);
      for (let i = 0; i < byteString.length; i++) {
          ia[i] = byteString.charCodeAt(i);
      }
      return new window.Blob([ab], {type: mimeString});
    },
    lodashIndexOf(id, ids) {
      return lodash.indexOf(ids, id);
    }
  },
  created() {
    this.id = this.$route.params.id;
  	this.getCategories();
  	this.getTags();
	this.getGroup();
  }
});
</script>