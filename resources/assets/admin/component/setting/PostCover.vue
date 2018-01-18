<template>
    <div id="file_upload">
        <div class="upload-list__item is-success" v-if="imgSrc">
            <img :src="imgSrc" class="upload-list__item-thumbnail">
            <label class="upload-list__item-status-label">
                <i class="glyphicon glyphicon-ok"></i>
            </label>
            <div class="upload-list__item-actions">
                <span class="upload-list__item-preview">
                    <!--查看图片-->
                    <a :href="imgSrc" target="_blank" title="预览">
                      <i class="glyphicon glyphicon-eye-open"></i>
                    </a>
                  </span>
                <span class="upload-list__item-delete">
                  <!--删除图片-->
                    <a href="#" title="删除" @click.stop.prevent="imgSrc = null">
                      <i class="glyphicon glyphicon-trash"></i>
                    </a>
                  </span>
            </div>
        </div>
        <div class="upload upload--picture-card" @click="chooseImg" v-else>
            <i class="glyphicon glyphicon-plus"></i>
            <input ref='input_img' type="file" name="file" @change="uploadImg" class="upload__input">
        </div>
    </div>
</template>
<script>
import fileUpload from './file_upload_v2';

export default {
    name: "module-post-cover",
    props: {
        img: [Number, String]
    },
    data() {
        return({
            imgSrc: null
        });
    },
    watch:{
        img(val){
            this.imgSrc = val;
        },
        imgSrc(val){
            this.$emit("input", val);
        }
    },
    methods: {

        chooseImg() {
            this.$refs.input_img.click();
        },

        uploadImg(e) {
            let file = e.target.files[0];
            fileUpload(file, (id) => {
                this.imgSrc = `/api/v2/files/${id}`;
            });
        },

    },
    created() {
        this.imgSrc = this.img || '';
    }

}
</script>
<style lang="scss">
.upload--picture-card {
    background-color: #fbfdff;
    border: 1px dashed #c0ccda;
    border-radius: 6px;
    box-sizing: border-box;
    width: 230px;
    height: 163px;
    cursor: pointer;
    line-height: 163px;
    text-align: center;
    position: relative;
    i {
        font-size: 28px;
        color: #8c939d;
        vertical-align: middle;
    }
    .upload__input {
        cursor: pointer;
        display: none;
    }
    &:hover {
        border-color: #20a0ff;
        color: #20a0ff;
    }
}


.upload-list__item {
    position: relative;
    overflow: hidden;
    transition: all .5s cubic-bezier(.55, 0, .1, 1);
    font-size: 14px;
    color: #48576a;
    border: 1px solid #c0ccda;
    border-radius: 6px;
    width: 230px;
    height: 163px;
    background-color: #fff;
    box-sizing: border-box;
    .upload-list__item-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
        i {
            color: #fff;
            font-size: 12px;
            margin-top: 11px;
            transform: rotate(-45deg) scale(.8);
        }
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
        line-height: 163px;
        span {
            cursor: pointer;
            +span {
                margin-left: 15px;
            }
        }
    }
    &:hover .upload-list__item-actions {
        opacity: 1;
    }
}
</style>