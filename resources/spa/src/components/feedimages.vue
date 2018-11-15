<template>
  <div :id="`m-pics${id}`" :class="['m-pics',`m-pics-${pics.length}`]">
    <ul class="m-pics-list">
      <li v-for="(img, index) in pics" :key="`pics-${id}-${index}`">
        <div
          :class="['m-pics-box',{ 'long': isLongImg(img) }]"
          :style="pics.length === 1 ? longStyle(img.w, img.h) : &quot;&quot;">
          <async-file :file="img.file" >
            <div
              slot-scope="props"
              :style="{ backgroundImage: `url(${props.src})` }"
              class="m-pic"
              @click.stop="handleClick($event, index)" />
          </async-file>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "MPics",
  props: {
    id: { type: Number, required: true },
    pics: { type: Array, default: () => [] }
  },
  created() {
    this.$bus.$on("updateFile", ({ fid, index }) => {
      if (fid === this.id) {
        this.pics[index].paid = true;
        this.$children[index].fetch();
        setTimeout(() => {
          this.$bus.$emit("updatePhoto", this.$children[index].src);
        }, 1500);
      }
    });
  },
  methods: {
    handleClick($event, index) {
      const els = this.$children;
      const images = this.pics.map((img, index) => {
        return {
          ...img,
          el: els[index].$el,
          src: els[index].src,
          index
        };
      });
      this.$bus.$emit("mvGallery", { fid: this.id, index, images });
    },
    isLongImg(img) {
      const [w, h] = img.size.split("x");
      img.title = img.paid_node > 0 && !img.paid ? "购买查看" : "";
      img.w = parseInt(w);
      img.h = parseInt(h);
      return w > 3 * h || h > 3 * w;
    },

    longStyle(w, h) {
      w = w > 518 ? 518 : w;
      h = h > 960 ? 960 : h;
      return {
        width: w + "px",
        paddingBottom: (h / w) * 100 + "%"
      };
    }
  }
};
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
      width: 1/3 * 100%;
      display: inline-block;
      vertical-align: top;
      padding: 0 4px 4px 0;
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
    &.long {
      .m-pic:after {
        display: block;
        position: absolute;
        bottom: 10px;
        right: 5px;
        content: "长图";
        background-color: #c8a06c;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
        background-image: -webkit-linear-gradient(
          25deg,
          #e8d1b3 0%,
          rgba(232, 209, 179, 0.6) 100%
        );
        background-image: -o-linear-gradient(
          25deg,
          #e8d1b3 0%,
          rgba(232, 209, 179, 0.6) 100%
        );
        background-image: linear-gradient(
          115deg,
          #e8d1b3 40%,
          rgba(232, 209, 179, 0.6) 50%
        );
        text-align: center;
        width: 60px;
        padding: 5px;
        height: 30px;
        line-height: 20px;
        font-size: 20px;
        color: #fff;
      }
      .m-pic {
        background-position: top center;
        max-height: 690px;
      }
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
