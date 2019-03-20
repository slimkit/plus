<template>
  <div :id="`m-pics${id}`" :class="['m-pics',`m-pics-${pics.length}`]">
    <ul class="m-pics-list">
      <li v-for="(img, index) in pics.slice(0, 9)" :key="`pics-${id}-${index}`">
        <div
          :class="['m-pics-box',{ 'long': isLongImg(img) }, { 'gif': (img.mime || '').indexOf('gif') > -1 }, {'need-pay': img.paid === false && img.type !== 'download'}]"
          :style="pics.length === 1 ? longStyle(img.w, img.h) : &quot;&quot;"
        >
          <div
            v-async-image="img"
            :data-src="img.file"
            class="m-pic"
            @click.stop="handleClick($event, index)"
          />
        </div>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  name: 'FeedImage',
  props: {
    id: { type: Number, required: true },
    pics: { type: Array, default: () => [] },
  },
  methods: {
    handleClick ($event, index) {
      const component = this.$parent
      const els = this.$el.querySelectorAll('.m-pic')
      const images = this.pics.map((img, index) => {
        const el = els[index]
        const src = `${this.$http.defaults.baseURL}/files/${img.file}`
        return {
          ...img,
          el,
          src,
          index,
        }
      })
      // const { paid_node, paid, type } = this.pics[index];
      // paid_node > 0 && type === "read" && paid
      //   ? this.payForImg(currItem)
      //   : this.$bus.$emit("mvGallery", { component, index, images });

      this.$bus.$emit('mvGallery', { component, index, images })
    },
    isLongImg (img) {
      const [w, h] = img.size.split('x')
      img.w = parseInt(w)
      img.h = parseInt(h)
      return h > 3 * w
    },
    longStyle (w, h) {
      return {
        width: w > 518 ? '518px' : w + 'px',
        paddingBottom: (h / w) * 100 + '%',
      }
    },
  },
}
</script>
<style lang='less'>
.m-pic {
  cursor: pointer;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  position: absolute;
  width: 100%;
  height: 100%;
}
.m-pics {
  width: 100%;
  overflow-y: hidden;
  &-list {
    text-align: left;
    margin-bottom: -4px;
    font-size: 0;
    max-width: 518px;
    max-height: 692px;
    li {
      font-size: 0;
      line-height: 1;
      width: 1/3 * 100%;
      // vertical-align: top;
      display: inline-block;
      padding: 0 2px 2px 0; /*no*/
      margin: 0 !important;
    }
  }
  &-box {
    display: inline-block;
    position: relative;
    padding-bottom: 100%;
    width: 100%;
    height: 0;
    max-width: 100%;
    background-color: #f4f5f6;
    .m-pic:after {
      display: flex;
      align-items: center;
      justify-content: center;

      position: absolute;
      bottom: 0;
      right: 0;

      width: 60px;
      height: 30px;
      border-radius: 1px; /* no */
      font-size: 20px;
      line-height: normal;
      color: #fff;
    }

    &.long {
      .m-pic:after {
        content: "长图";
        background-color: #c8a06c;
        background-image: linear-gradient(135deg, #cfac7d 50%, #c8a06c 50%);
      }
    }
    &.gif:not(.playing) {
      .m-pic:after {
        content: "Gif";
        background-color: #5dc8ab;
        background-image: linear-gradient(135deg, #60ceb0 50%, #5dc8ab 50%);
      }
    }
    .m-pic {
      max-height: 690px;
    }
  }
  &-1 {
    text-align: left;
    .m-pics-list {
      display: inline-block;
      overflow-y: hidden;
    }
    li {
      width: 100%;
      .m-pics-box {
        max-width: 100%;
      }
    }
    .long {
      max-width: 100%;
    }
  }
  &-2,
  &-4 {
    li {
      width: 50%;
    }
  }
  &-5 {
    position: relative;
    li:nth-child(1),
    li:nth-child(2) {
      width: 50%;
    }
  }
  &-8 {
    li:nth-child(4),
    li:nth-child(5) {
      width: 50%;
    }
  }
  &-7 {
    li:nth-child(1),
    li:nth-child(2),
    li:nth-child(6),
    li:nth-child(7) {
      width: 50%;
    }
  }
}
</style>
