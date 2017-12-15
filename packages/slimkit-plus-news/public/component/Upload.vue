<template>
  <div id="file_upload" class="source">
    <div>
      <ul class="upload-list" v-if='imgInfo.showImg'>
        <!--<li class="upload-list__item is-success" v-for='img in imgs'>-->
        <!--封面图 只有一张-->
        <li class="upload-list__item is-success">
          <img :src="imgInfo.imgSrc" alt="" class="upload-list__item-thumbnail">
          <label class="upload-list__item-status-label" v-if='is_ok'>
            <i class="glyphicon glyphicon-ok"></i>
          </label>
          <span class="upload-list__item-actions">
              <span class="upload-list__item-preview">
                <!--查看图片-->
                <a :href="imgInfo.imgSrc" target="_blank">
                  <i class="glyphicon glyphicon-eye-open"></i>
                </a>
              </span>
          <span class="upload-list__item-delete">
              <!--删除图片-->
                <a href="" @click.prevent='updata'>
                  <i class="glyphicon glyphicon-trash"></i>
                </a>
              </span>
          </span>
        </li>
      </ul>
      <div class="upload upload--picture-card" @click='upLoadFile' v-else>
        <i class="glyphicon glyphicon-plus"></i>
        <!--单张图片上传-->
        <!--<input ref='input_img' multiple type="file" name="file" class="upload__input">-->
        <input ref='input_img' type="file" name="file" @change='filesChange' class="upload__input">
      </div>
    </div>
  </div>
</template>
<script>
import fileUpload from './file_upload.js'
export default {
  name: 'file_upload',
  props: ['imgs'],
  data: () => ({
    up_imgs: null,
    task_id: null,
    is_ok: false
  }),
  computed: {
    imgInfo() {
      let r = {
        showImg: false,
        imgSrc: null
      }
      if (this.imgs || this.up_imgs) {
        let imgSrc = this.imgs ? ('/api/v1/storages/' + this.imgs) : (this.up_imgs);
        r.showImg = true;
        r.imgSrc = imgSrc;
      }
      return r;
    }
  },
  methods: {
    upLoadFile() {
      this.$refs.input_img.click();
    },
    filesChange(e) {
      let file = e.target.files[0];
      fileUpload(file, (task_id) => {
          if (task_id) this.setTask_id(task_id);
        })
        .then(imgsrc => {
          this.up_imgs = imgsrc || '';
        })
    },
    updata() {
      this.up_imgs = null;
      this.$emit('updata');
    },
    setTask_id(task_id) {
      this.is_ok = true;
      this.$emit("getTask_id", task_id)
    }
  }
}
</script>
<style lang="css">
.upload {
  display: inline-block;
  text-align: center;
  cursor: pointer;
  position: relative;
}

.upload--picture-card {
  background-color: #fbfdff;
  border: 1px dashed #c0ccda;
  border-radius: 6px;
  box-sizing: border-box;
  width: 120px;
  height: 120px;
  cursor: pointer;
  line-height: 118px;
  vertical-align: top;
}

.upload--picture-card i {
  font-size: 28px;
  color: #8c939d;
  vertical-align: middle;
}

.upload--picture-card .upload__input {
  cursor: pointer;
  display: none;
}

.upload--picture-card:hover {
  border-color: #20a0ff;
  color: #20a0ff;
}

.upload-list {
  margin: 0;
  padding: 0;
  list-style: none;
  display: inline;
  vertical-align: top;
}

.upload-list__item {
  transition: all .5s cubic-bezier(.55, 0, .1, 1);
  font-size: 14px;
  color: #48576a;
  box-sizing: border-box;
  border-radius: 4px;
  width: 100%;
  position: relative;
}

.upload-list .upload-list__item {
  overflow: hidden;
  background-color: #fff;
  border: 1px solid #c0ccda;
  border-radius: 6px;
  box-sizing: border-box;
  width: 120px;
  height: 120px;
  box-sizing: border-box;
}

.upload-list .upload-list__item-thumbnail {
  width: 100%;
  height: 100%;
}

.upload-list__item-status-label {
  position: absolute;
  right: -15px;
  top: -6px;
  width: 40px;
  height: 24px;
  background: #13ce66;
  text-align: center;
  transform: rotate(45deg);
  box-shadow: 0 0 1pc 1px rgba(0, 0, 0, .2);
}

.upload-list__item-status-label i {
  color: #fff;
  font-size: 12px;
  margin-top: 11px;
  transform: rotate(-45deg) scale(.8);
}

.upload-list__item-actions {
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  cursor: default;
  text-align: center;
  color: #fff;
  opacity: 0;
  font-size: 16px;
  background-color: rgba(0, 0, 0, .5);
  transition: opacity .3s;
}

.upload-list__item-actions span {
  display: none;
  cursor: pointer;
  line-height: 118px;
}

.upload-list__item-actions span+span {
  margin-left: 15px;
}

.upload-list__item-actions:hover {
  opacity: 1;
}

.upload-list__item-actions:hover span {
  display: inline-block;
  line-height: 118px;
}
</style>
