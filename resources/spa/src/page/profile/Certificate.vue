<template>
  <div class="p-profile-certificate">

    <common-header :pinned="true" :back="back">
      {{ title }}
      <span
        v-show="step === 2"
        slot="right"
        :class="['btn-submit', disabled]"
        @click="validate(onSubmit)">提交</span>
    </common-header>

    <transition-group
      :enter-active-class="animated.enter"
      :leave-active-class="animated.leave"
      tag="main"
      class="m-box-model m-flex-grow1 m-flex-shrink1 main">
      <template v-if="step === 1">
        <div key="step1" class="step1">
          <template v-if="type ==='org'">
            <!-- 机构名称 -->
            <div class="m-form-row m-main">
              <label for="org-name">{{ formInfo[type].orgName.label }}</label>
              <div class="m-input">
                <input
                  id="org-name"
                  v-model.trim="orgFields.org_name"
                  :placeholder="formInfo[type].orgName.placeholder"
                  type="text"
                  maxlength="20">
              </div>
              <svg
                v-show="orgFields.org_name.length > 0"
                class="m-style-svg m-svg-def"
                @click="orgFields.org_name = ''">
                <use xlink:href="#icon-clean"/>
              </svg>
            </div>
            <!-- 机构地址 -->
            <div class="m-form-row m-main">
              <label for="org-address">{{ formInfo[type].orgAddress.label }}</label>
              <div class="m-input">
                <input
                  id="org-address"
                  v-model.trim="orgFields.org_address"
                  :placeholder="formInfo[type].orgAddress.placeholder"
                  type="text"
                  maxlength="20">
              </div>
              <svg
                v-show="orgFields.org_address.length > 0"
                class="m-style-svg m-svg-def"
                @click="orgFields.org_address = ''">
                <use xlink:href="#icon-clean"/>
              </svg>
            </div>
          </template>

          <!-- 真实姓名 / 负责人姓名 -->
          <div class="m-form-row m-main">
            <label for="username">{{ formInfo[type].name.label }}</label>
            <div class="m-input">
              <input
                id="username"
                v-model.trim="fields.name"
                :placeholder="formInfo[type].name.placeholder"
                maxlength="8"
                type="text">
            </div>
          </div>
          <!-- 证件号码 -->
          <div class="m-form-row m-main">
            <label for="number">{{ formInfo[type].number.label }}</label>
            <div class="m-input">
              <input
                id="number"
                v-model.trim="fields.number"
                :placeholder="formInfo[type].number.placeholder"
                maxlength="18"
                type="text"
                pattern="[0-9x]*">
            </div>
          </div>
          <!-- 手机号码 -->
          <div class="m-form-row m-main">
            <label for="phone">{{ formInfo[type].phone.label }}</label>
            <div class="m-input">
              <input
                id="phone"
                v-model="fields.phone"
                :placeholder="formInfo[type].phone.placeholder"
                type="number"
                pattern="[0-9]*"
                oninput="value=value.slice(0, 11)">
            </div>
          </div>
          <!-- 认证描述 -->
          <div class="m-form-row m-main auto-height">
            <label for="desc">{{ formInfo[type].desc.label }}</label>
            <div class="m-input">
              <textarea-input
                id="desc"
                v-model="fields.desc"
                :maxlength="200"
                :warnlength="150"
                :placeholder="formInfo[type].desc.placeholder"/>
            </div>
          </div>
          <div class="m-box m-aln-center m-text-box m-form-err-box">
            <!-- <span>{{ error | plusMessageFirst }}</span> -->
          </div>
          <div class="m-form-row" style="border: 0">
            <button
              :disabled="loading||disabled"
              class="m-long-btn m-signin-btn"
              @click="validate(() => {step = 2})">
              <circle-loading v-if="loading" />
              <span v-else>下一步</span>
            </button>
          </div>
        </div>
      </template>

      <template v-if="step === 2">
        <div
          key="step2"
          class="step2">
          <p
            v-if="type === 'user'"
            class="poster-tips">请上传正反面身份证照片</p>
          <p
            v-else
            class="poster-tips">上传企业机构营业执照</p>
          <image-poster
            :poster="poster1"
            @uploaded="uploaded1">
            <span>点击上传正面身份证照片</span>
          </image-poster>
          <template v-if="type=='user' && files.length > 0">
            <image-poster
              :poster="poster2"
              @uploaded="uploaded2">
              <span>点击上传反面身份证照片</span>
            </image-poster>
          </template>
        </div>
      </template>
    </transition-group>
  </div>
</template>

<script>
/**
 * 认证表单页面
 */

import ImagePoster from "@/components/ImagePoster.vue";
import TextareaInput from "@/components/common/TextareaInput.vue";
import * as api from "@/api/user.js";
import { noop } from "@/util";

const formInfo = {
  user: {
    name: { label: "真实姓名", placeholder: "输入真实姓名" },
    number: { label: "身份证号码", placeholder: "输入正确的身份证号码" },
    phone: { label: "手机号码", placeholder: "输入11位手机号码" },
    desc: { label: "认证描述", placeholder: "该描述会影响审核，请慎重填写" }
  },
  org: {
    name: { label: "负责人", placeholder: "输入机构负责人" },
    number: { label: "身份证号码", placeholder: "输入负责人身份证号码" },
    phone: { label: "手机号码", placeholder: "输入11位手机号码" },
    desc: { label: "认证描述", placeholder: "该描述会影响审核，请慎重填写" },
    orgName: { label: "机构名称", placeholder: "输入机构名称" },
    orgAddress: { label: "机构地址", placeholder: "输入机构地址" }
  }
};

export default {
  name: "Certificate",
  components: {
    ImagePoster,
    TextareaInput
  },
  data() {
    return {
      loading: false,
      step: 1,
      formInfo,
      status: 0, // 认证状态
      files: [], // 认证图片
      fields: {
        name: "",
        number: "",
        phone: "",
        desc: ""
      },
      orgFields: {
        org_name: "", // ignore camelcase
        org_address: "" // ignore camelcase
      },
      animated: {
        enter: "animated slideInRight",
        leave: "animated slideOutLeft"
      }
    };
  },
  computed: {
    title() {
      return this.step === 1
        ? this.type === "user"
          ? "个人认证"
          : "企业认证"
        : "上传资料";
    },
    /**
     * 认证类型. 必须是 (user|org)
     * @returns {string}
     */
    type: {
      get() {
        return this.$route.query.type || "user";
      },
      set(val) {
        const { path, query } = this.$route;
        query.type = val;
        this.$router.push({ path, query });
      }
    },
    /**
     * 待提交表单
     * @returns {Object}
     */
    formData: {
      get() {
        const ret =
          this.type === "user"
            ? this.fields
            : Object.assign({}, this.fields, this.orgFields);
        ret.type = this.type;
        return ret;
      },
      set(val) {
        // TODO: 优化这里
        const {
          name,
          phone,
          number,
          desc,
          files = [],
          org_name,
          org_address
        } = val; // ignore camelcase
        this.files = files;
        this.fields = Object.assign({}, this.fields, {
          name,
          phone,
          desc,
          number
        });
        this.orgFields = Object.assign({}, this.orgFields, {
          org_name,
          org_address
        });
      }
    },
    /**
     * 下一步可用性
     */
    disabled() {
      return !Object.values(this.formData).every(v => v);
    },
    poster1() {
      const id = this.files[0];
      if (!id) return;
      return { id, src: `${this.$http.defaults.baseURL}/files/${id}?w=600` };
    },
    poster2() {
      const id = this.files[1];
      if (!id) return;
      return { id, src: `${this.$http.defaults.baseURL}/files/${id}?w=600` };
    }
  },
  watch: {
    /**
     * 步骤切换动画
     */
    step(to, from) {
      to > from
        ? (this.animated = {
            enter: "animated slideInRight",
            leave: "animated slideOutLeft"
          })
        : (this.animated = {
            enter: "animated slideInLeft",
            leave: "animated slideOutRight"
          });
    }
  },
  mounted() {
    this.$store.dispatch("FETCH_USER_VERIFY").then(data => {
      this.formData = data.data || {};
      this.type = data.certification_name;
      this.status = data.status || 0;
    });
  },
  methods: {
    back() {
      this.step > 1 ? this.step-- : this.goBack();
    },
    onSubmit() {
      const postData = Object.assign({ files: this.files }, this.formData);
      if (this.status === 0) {
        api.postCertification(postData).then(() => {
          this.$Message.success("提交成功，请等待审核");
          this.goBack();
        });
      } else {
        api.patchCertification(postData).then(() => {
          this.$Message.success("提交成功，请等待审核");
          this.goBack();
        });
      }
    },
    uploaded1(poster) {
      this.$set(this.files, 0, poster.id);
    },
    uploaded2(poster) {
      if (this.type === "org") return;
      this.$set(this.files, 1, poster.id);
    },
    /**
     * @param {Function} next
     */
    validate(next = noop) {
      let failed = false;
      const match = {
        phone: /^1[3456789]\d{9}$/, // 手机号正则
        number: /(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/ // 身份证号正则
      };
      switch (this.step) {
        case 1: {
          if (!this.fields.number.match(match.number))
            failed = "请检查身份证号码是否正确";
          if (!this.fields.phone.match(match.phone))
            failed = "请检查手机号码是否正确";
          break;
        }
        case 2:
          if (
            (this.type === "user" && this.files.length !== 2) ||
            (this.type === "org" && this.files.length !== 1)
          )
            failed = "请上传证件照片";
          break;
      }
      if (!failed) next();
      else this.$Message.error(failed);
    }
  }
};
</script>

<style lang="less" scoped>
.p-profile-certificate {
  header {
    .btn-submit {
      width: 2em;
    }
  }

  main {
    [class*="step"] {
      position: absolute;
      top: 0.9rem;
      width: 100%;
      background-color: #fff;
    }

    .m-form-row {
      label {
        width: 6em;
        flex: none;

        &::before {
          content: "*";
          color: red;
        }
      }

      .m-input input {
        text-align: right;
      }

      &.auto-height {
        align-items: flex-start;
        min-height: 1.1rem;
        height: auto;
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;

        textarea {
          width: 100%;
          line-height: 1.4;
          font-size: 0.28rem;
        }
      }
    }

    .step2 {
      padding: 20px;
    }

    .poster-tips {
      color: #666;
      font-size: 80%;
    }
  }
}
</style>

<style lang="less">
.p-profile-certificate {
  .textarea-wrap {
    padding-right: 0;

    .c-textarea-input {
      text-align: right;
      width: 100%;
      font-size: 28px;
    }
  }
}
</style>
