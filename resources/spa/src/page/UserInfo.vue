<template>
  <div class="m-box-model p-user-info">

    <common-header>
      个人资料
      <template slot="right">
        <circle-loading v-if="loading" />
        <a
          v-else
          :class="{ disabled }"
          class="m-send-btn"
          @click.prevent="handleOk">完成</a>
      </template>
    </common-header>

    <main>

      <form>
        <form-avatar-item
          v-model="avatar"
          label="更换头像"
          type="storage"/>

        <form-input-item
          v-model="name"
          placeholder="请输入用户名"
          label="用户名"/>

        <form-select-item
          v-model="sexMap[sex]"
          label="性别"
          @click="switchSex"/>

        <form-location-item
          v-model="location"
          label="城市"/>

        <!-- form-tags-selected -->

        <section class="m-box m-aln-stre m-justify-bet p-info-row m-bb1" @click="switchTags">
          <label>标签</label>
          <div class="m-box m-aln-center m-justify-bet m-flex-grow1 m-flex-shrink1 input">
            <span v-if="tags.length === 0" class="placeholder">选择标签</span>
            <div v-else class="m-tag-list m-tags">
              <span
                v-for="tag in tags"
                :key="tag.id"
                class="m-tag">{{ tag.name }}</span>
            </div>
            <svg class="m-style-svg m-svg-def m-entry-append">
              <use xlink:href="#icon-arrow-right"/>
            </svg>
          </div>
        </section>

        <form-input-item
          v-model="bio"
          :maxlength="50"
          :warnlength="40"
          type="textarea"
          label="简介"
          placeholder="编辑简介"/>

      </form>
    </main>

  </div>
</template>

<script>
import { mapState } from "vuex";

/**
 * Canvas toBlob
 */
if (!HTMLCanvasElement.prototype.toBlob) {
  Object.defineProperty(HTMLCanvasElement.prototype, "toBlob", {
    value: function(callback, type, quality) {
      var binStr = atob(this.toDataURL(type, quality).split(",")[1]),
        len = binStr.length,
        arr = new Uint8Array(len);

      for (var i = 0; i < len; i++) {
        arr[i] = binStr.charCodeAt(i);
      }

      callback(new Blob([arr], { type: type || "image/png" }));
    }
  });
}

const sexMap = {
  0: "保密",
  1: "男",
  2: "女"
};

export default {
  name: "UserInfo",
  data() {
    return {
      loading: false,
      scrollHeight: 0,
      showCleanName: false,

      sexMap,
      sex: 0,
      bio: "",
      name: "",
      tags: [],
      location: { label: "请选择地理位置" },
      avatar: {},
      avatarNode: "",
      change: false,

      showPosition: false
    };
  },
  computed: {
    ...mapState(["CURRENTUSER"]),
    disabled() {
      if (!this.bio || !this.name) return true;
      if (this.location.label !== this.CURRENTUSER.location) return false;
      return !["sex", "bio", "name", "avatar", this.change].some(
        key =>
          typeof key === "string"
            ? this.$data[key] !== this.CURRENTUSER[key]
            : key
      );
    },
    sexTxt() {
      const sex = ["保密", "男", "女"];
      return sex[this.sex] || "选择性别";
    }
  },
  created() {
    const {
      sex = 0,
      bio = "",
      location = "",
      avatar = "",
      tags = [],
      name = ""
    } = this.CURRENTUSER;
    this.name = name;
    this.sex = sex;
    this.bio = bio || "";
    this.tags = tags || [];
    this.avatar = avatar;
    this.location.label = location || "";
    this.$http
      .get(`users/${this.CURRENTUSER.id}/tags`)
      .then(({ data = [] }) => {
        this.tags = data;
        this.CURRENTUSER.tags = data;
        this.$store.commit("SAVE_CURRENTUSER", this.CURRENTUSER);
      });
  },
  methods: {
    handleOk() {
      if (this.disabled) return;
      if (this.loading) return;
      this.change = false;
      this.loading = true;

      const param = {
        name: this.name,
        bio: this.bio,
        sex: this.sex,
        location: this.location.label
      };
      if (typeof this.avatar === "string") param.avatar = this.avatar;
      this.$http
        .patch("/user", param, {
          validateStatus: s => s === 204
        })
        .then(() => {
          this.$store.commit(
            "SAVE_CURRENTUSER",
            Object.assign(this.CURRENTUSER, param)
          );
          this.goBack();
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
          return err;
        });
    },
    switchTags() {
      const chooseTags = this.tags.map(t => t.id);
      const nextStep = tags => {
        this.change =
          tags.map(n => n.id).join(",") !== this.CURRENTUSER.tags.join(",");
        this.tags = tags;
      };
      const onSelect = tagId => {
        this.$http.put(`/user/tags/${tagId}`);
      };
      const onRemove = tagId => {
        this.$http.delete(`/user/tags/${tagId}`);
      };
      this.$bus.$emit("choose-tags", {
        chooseTags,
        nextStep,
        onSelect,
        onRemove
      });
    },
    switchPosition(val) {
      this.showPosition = !this.showPosition;
      val && (this.location = val.label);
    },
    switchSex() {
      const options = [
        { text: "男", method: () => (this.sex = 1) },
        { text: "女", method: () => (this.sex = 2) },
        { text: "保密", method: () => (this.sex = 0) }
      ];
      this.$bus.$emit("actionSheet", options, "取消");
    }
  }
};
</script>

<style lang="less" scoped>
.p-user-info {
  main {
    background-color: #fff;
  }

  input[type="text"] {
    height: 100%;
    font-size: 28px;
    line-height: normal;
    vertical-align: middle;
  }

  textarea {
    font-size: inherit;
    font-family: inherit;
    line-height: inherit;
    background-color: transparent;
    outline: 0;
    border: 0;
    resize: none;
    padding: 0;
    margin: 0;
    width: 100%;
    box-sizing: border-box;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
  }

  .m-avatar-box {
    margin-right: 35px;
  }

  .m-send-btn {
    color: @primary;

    &.disabled {
      color: @gray;
    }
  }

  .m-entry-append {
    margin-right: 20px;
  }
}

.p-info-row {
  position: relative;
  padding: 35px 0 35px 0;
  margin-left: 140px;
  min-height: 100px;
  .input {
    font-size: 30px;
    line-height: 1;
  }
  label {
    display: flex;
    align-items: center;
    margin-left: -110px;
    width: 110px;
    font-size: 30px;
    line-height: inherit;
    color: @text-color3;
  }
  .m-wz-def {
    font-size: 30px;
    line-height: inherit;
    font-weight: 400;
    word-wrap: break-word;
  }
  .placeholder {
    color: #ccc;
  }
}
</style>

<style lang="less">
.c-textarea-input {
  padding-top: 28px;
}
</style>
