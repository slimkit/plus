<template>
  <div>
    <div class="image-list">
      <div
        v-for="(img, index) in pics"
        :key="img.src"
        :class="[picClass, { error: img.error }, { loading: img.loading }, { edit }]"
        class="m-box-center m-box-center-a image-wrap"
      >
        <div class="image-placeholder" />
        <img
          :id="`compose-photo-${img.id}`"
          :src="img.src"
          :class="{loading: img.loading }"
          class="compose-image"
          @load.stop="loadedImg(img)"
          @error="reUpload(pics, index)"
          @click="onClickThumb(index)"
        >
        <div
          v-if="edit && !img.loading"
          class="m-rpic-edit-wrap m-rpic-edit m-box m-aln-center m-justify-center m-trans"
          @click="editImg(img, index)"
        >
          <svg
            v-if="img.amount > 0"
            viewBox="0 0 1024 1024"
            class="m-style-svg m-svg-def"
            fill="#fff"
          >
            <path d="M112.127 284.09a390.766 200.815 0 1 0 799.746 0 390.766 200.815 0 1 0-799.746 0z" />
            <path d="M512 551.409c-163.335 0-303.792-50.328-365.87-122.452-21.857 25.394-34.003 53.489-34.003 83.043 0 113.492 179.03 205.495 399.873 205.495S911.873 625.492 911.873 512c0-29.554-12.145-57.649-34.003-83.043C815.792 501.08 675.335 551.409 512 551.409z" />
            <path d="M512 784.985c-165.467 0-307.456-51.648-368.263-125.285-20.35 24.644-31.61 51.752-31.61 80.21 0 113.492 179.03 205.495 399.873 205.495s399.873-92.003 399.873-205.495c0-28.46-11.26-55.566-31.61-80.21C819.456 733.337 677.467 784.985 512 784.985z" />
          </svg>
          <svg
            v-else
            viewBox="0 0 1024 1024"
            class="m-style-svg m-svg-def"
          >
            <path d="M823.672974 299.313993L679.488107 155.13936l72.121598-72.086805c23.899316-23.86657 62.591547-23.86657 86.48677 0l57.665351 57.659211c23.901363 23.901363 23.901363 62.654992 0 86.549192L823.672974 299.313993 823.672974 299.313993zM404.795884 718.17164L260.615111 573.995983l391.976416-388.894218 144.18282 144.175657L404.795884 718.17164 404.795884 718.17164zM144.786059 836.410578l87.722924-234.313583L375.482255 745.103012 144.786059 836.410578 144.786059 836.410578zM792.286126 885.688911c20.181645 0 36.519752 16.33913 36.519752 36.520775 0 20.152992-16.33913 36.494169-36.519752 36.494169L146.485771 958.703855c-20.147876 0-36.494169-16.341177-36.494169-36.494169 0-20.182668 16.34527-36.520775 36.494169-36.520775L792.286126 885.688911 792.286126 885.688911zM792.286126 885.688911" />
          </svg>
          <span> {{ img.amount || "设置金额" }} </span>
        </div>
        <button
          class="m-rpic-close m-trans m-box"
          @click.stop="delPhoto(pics, index)"
        >
          <svg
            viewBox="0 0 46 72"
            class="m-style-svg m-flex-grow1 m-svg-def"
          >
            <path d="M27.243 36l14.88-14.88c1.17-1.17 1.17-3.07 0-4.24-1.172-1.173-3.072-1.173-4.243 0L23 31.757 8.122 16.878c-1.17-1.17-3.07-1.17-4.242 0-1.172 1.172-1.172 3.072 0 4.243L18.758 36 3.878 50.88c-1.17 1.17-1.17 3.07 0 4.24.587.587 1.355.88 2.123.88s1.536-.293 2.122-.88L23 40.243l14.88 14.88c.585.585 1.353.878 2.12.878.768 0 1.535-.293 2.12-.88 1.173-1.17 1.173-3.07 0-4.24L27.244 36z" />
          </svg>
        </button>
        <div
          v-if="!img.error && img.loading"
          class="fixed-loading"
        >
          <div
            class="u-loading"
            style="height: 58px;width: 58px; background-color: transprent"
          >
            <svg
              class="loading"
              width="100%"
              height="100%"
              viewBox="0 0 29 29"
            >
              <circle
                class="c1"
                cx="14.5"
                cy="14.5"
                r="12.5"
                fill="none"
                stroke-width="4"
                stroke="#b1b1b1"
              />
              <circle
                class="c2"
                cx="14.5"
                cy="14.5"
                r="12.5"
                fill="none"
                stroke-width="4"
                stroke="#c7c7c7"
              />
            </svg>
          </div>
        </div>
      </div>
      <label
        v-if="showLabel"
        :class="picClass"
        for="selectphoto"
        class="m-box-center m-box-center-a image-wrap more-image"
      >
        <div class="image-placeholder" />
        <svg
          viewBox="0 0 24 24"
          class="m-style-svg m-flex-grow1 m-svg-big"
        >
          <path d="M21.8,20.8H2.1c-0.5,0-1.1-0.4-1.1-1V6.3c0-0.5,0.4-1.1,1.1-1.1h4.3L8,3.6c0.1-0.3,0.5-0.4,0.8-0.4H15 c0.3,0,0.5,0.1,0.7,0.3l1.9,1.8h4.3c0.5,0,1.1,0.4,1.1,1.1V20C22.9,20.4,22.5,20.8,21.8,20.8L21.8,20.8z M12,6.6 c-3.4,0-6.1,2.7-6.1,6.1s2.7,6.1,6.1,6.1s6.1-2.7,6.1-6.1S15.4,6.6,12,6.6L12,6.6z M12,16.8c-2.3,0-4.1-1.8-4.1-4.1S9.7,8.6,12,8.6 s4.1,1.8,4.1,4.1S14.3,16.8,12,16.8L12,16.8z" />
        </svg>
      </label>
    </div>
    <input
      id="selectphoto"
      ref="imagefile"
      :multiple="multiple"
      :accept="acceptType"
      type="file"
      class="m-rfile"
      @change="selectPhoto"
    >
    <ImagePaidOption ref="imageOption" />
  </div>
</template>
<script>
import { mapActions } from 'vuex'
import sendImage from '@/util/SendImage.js'
import ImagePaidOption from './ImagePaidOption.vue'
import { checkImageType } from '@/util/imageCheck.js'

export default {
  name: 'ImageList',
  components: {
    ImagePaidOption,
  },
  props: {
    edit: {
      type: Boolean,
      default: false,
    },
    limit: {
      type: Number,
      default: 9,
    },
    accept: { type: [Array, String], default: 'image/*' },
  },
  data () {
    return {
      pics: [],
    }
  },
  computed: {
    acceptType () {
      return typeof this.accept === 'string'
        ? this.accept
        : this.accept.join(',')
    },
    showLabel () {
      return this.pics.length < this.limit
    },
    multiple () {
      return this.limit > 1
    },
    picClass () {
      // return `img${this.pics.length > 1 ? 3 : 1}`;
      return `img3`
    },
  },
  watch: {
    pics () {
      this.updateComposePhoto(this.pics)
    },
    edit (val) {
      val ||
        this.pics.forEach((pic /*, index */) => {
          delete pic.amount
          delete pic.amountType
          // this.$set(this.pics, index, pic);
        })
    },
  },
  destroyed () {
    this.updateComposePhoto(null)
  },
  methods: {
    ...mapActions(['updateComposePhoto']),
    imgFormat () {
      return {
        id: '',
        src: '',
        type: '',
        width: 0,
        height: 0,
        file: null,
        error: false,
        loading: false,
      }
    },
    selectPhoto () {
      this.addPhoto(this.$refs.imagefile)
    },
    addPhoto ($input) {
      const files = $input.files
      if (files && files.length > 0) {
        if (files.length + this.pics.length > this.limit) {
          this.$Message.error(`最多只能上传${this.limit}张图片`)
          $input.value = ''
          return false
        }
        checkImageType(files)
          .then(() => {
            for (let i = 0; i < files.length; i++) {
              const imgObj = {
                file: files[i],
                type: files[i].mimeType,
                src: window.URL.createObjectURL(files[i]),
                loading: true,
              }
              this.pics.push(Object.assign({}, this.imgFormat(), imgObj))
            }
            $input.value = ''
          })
          .catch(() => {
            this.$Message.info('请上传正确格式的图片文件')
            $input.value = ''
          })
      }
    },
    loadedImg (img) {
      // TODO
      // 前端图片压缩
      sendImage(img.file)
        .then(id => {
          Object.assign(img, {
            id,
            file: null,
            loading: false,
            error: false,
          })
          this.updateComposePhoto(this.pics)
        })
        .catch(() => {
          img.error = true
        })
    },
    delPhoto (pics, index) {
      pics.splice(index, 1)
    },
    reUpload (pics, index) {
      pics[index].error = false
      this.loadedImg(pics[index])
    },
    editImg (img, index) {
      this.$refs.imageOption.show(img, index)
    },
    onClickThumb (index) {
      // 图片上传失败时点击会重新上传，否则打开预览
      this.pics[index].error
        ? this.reUpload(this.pics, index)
        : this.thumbnails(index)
    },
    // 图片预览
    thumbnails (index) {
      const images = this.pics.map((img, index) => {
        const el = this.$el.querySelectorAll(`img.compose-image`)[index]
        return {
          el,
          index,
          ...img,
          w: el.naturalWidth,
          h: el.naturalHeight,
        }
      })
      this.$bus.$emit('mvGallery', {
        component: this,
        index,
        images,
      })
    },
  },
}
</script>

<style lang='less'>
.image-list {
  display: flex;
  flex-wrap: wrap;
  user-select: none;
}
.image-wrap {
  transition: all 0.4s ease;
  overflow: hidden;
  position: relative;
  margin: 0.5%;
  box-sizing: border-box;
  border: 1px solid @border-color; /*no*/
  &.img1 {
    flex-basis: 99%;
  }
  &.img3 {
    flex-basis: 24%;
  }
  &.loading:before {
    content: "";
  }
  &.error {
    &:after {
      content: "上传失败";
    }
  }
  // &.edit:before {
  //   content: "";
  //   position: absolute;
  //   top: 0;
  //   left: 0;
  //   bottom: 0;
  //   right: 0;
  //   z-index: 2;
  //   background-color: rgba(0, 0, 0, 0.4);
  //   pointer-events: none;
  // }
}
.image-placeholder {
  padding-top: 100%;
}
.compose-image {
  position: absolute;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
  object-fit: cover;
  transition: all 0.4s ease;

  &.loading {
    opacity: 0.3;
  }
}
.m-rpic-edit {
  z-index: 9;
  position: relative;
  background: transparent;
  transition: all 0.4s ease;
  &-wrap {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;
    background-color: rgba(0, 0, 0, 0.3);
    font-size: 24px;
    color: #fff;
    z-index: 1;
    .m-svg-def {
      width: 32px;
      height: 32px;
      margin-right: 10px;
    }
  }
}
.m-rpic-close {
  overflow: hidden;
  position: absolute;
  top: 0;
  right: 0;
  background-color: #000;
  opacity: 0.4;
  padding: 0;
  border: 0;
  outline: none;
  color: #fff;
  z-index: 3;
  .m-style-svg {
    width: 42px;
    height: 42px;
  }
}
.more-image {
  border: 1px dashed @gray; /*no*/
  color: @gray;
}
</style>
