<template>
  <transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutLeft">
    <div class="p-signup">

      <common-header>
        {{ _$type.label }}注册
        <a
          v-if="allowType === 'all'"
          slot="right"
          @click.prevent="changeType">{{ _$type.label2 }}</a>
      </common-header>

      <main>
        <div class="m-form-row m-main">
          <label for="username">用户名</label>
          <div class="m-input">
            <input
              id="username"
              v-model.trim="name"
              type="text"
              placeholder="用户名不能低于2个中文或4个英文"
              maxlength="8">
          </div>
          <svg
            v-show="name.length > 0"
            class="m-style-svg m-svg-def"
            @click="name = ''">
            <use xlink:href="#icon-clean"/>
          </svg>
        </div>
        <div
          v-if="verifiable_type === 'sms'"
          class="m-form-row m-main">
          <label for="phone">手机号</label>
          <div class="m-input">
            <input
              id="phone"
              v-model="phone"
              type="number"
              pattern="[0-9]*"
              oninput="value=value.slice(0, 11)"
              placeholder="输入11位手机号">
              <!-- maxlength="11" -->
          </div>
          <span
            :class="{ disabled: phone.length < 11 || countdown > 0 }"
            class="code-text"
            @click="getCode">
            {{ codeText }}
          </span>
        </div>
        <div v-if="verifiable_type === 'mail'" class="m-form-row m-main">
          <label for="email">邮箱</label>
          <div class="m-input">
            <input
              id="email"
              v-model.trim="email"
              type="email"
              placeholder="输入邮箱地址">
          </div>
          <span
            :class="{ disabled: email.length < 4 || countdown > 0 }"
            class="code-text"
            @click="getCode">
            {{ codeText }}
          </span>
        </div>
        <div class="m-form-row m-main">
          <label for="code">验证码</label>
          <div class="m-input">
            <input
              id="code"
              v-model="verifiable_code"
              type="number"
              pattern="[0-9]*"
              oninput="value=value.slice(0, 6)"
              placeholder="输入4-6位验证码" >
          </div>
          <svg
            v-show="verifiable_code.length > 0"
            class="m-style-svg m-svg-def"
            @click="verifiable_code = ''">
            <use xlink:href="#icon-clean"/>
          </svg>
        </div>

        <div class="m-form-row m-main">
          <label for="password">密码</label>
          <div class="m-input">
            <input
              v-if="eye"
              id="password"
              v-model="password"
              type="text"
              maxlength="16"
              placeholder="输入6位以上登录密码" >
            <input
              v-else
              id="password"
              v-model="password"
              maxlength="16"
              type="password"
              placeholder="输入6位以上登录密码" >
          </div>
          <svg class="m-style-svg m-svg-def" @click="eye = !eye">
            <use :xlink:href="eye ? '#eye-open' : '#eye-close'"/>
          </svg>
        </div>
        <div class="m-box m-aln-center m-text-box m-form-err-box">
          <span>{{ error | plusMessageFirst }}</span>
        </div>
        <div class="m-form-row" style="border: 0">
          <button
            :disabled="loading||disabled"
            class="m-long-btn m-signin-btn"
            @click="signUp">
            <circle-loading v-if="loading" />
            <span v-else>注册</span>
          </button>
        </div>
      </main>
      <footer>
        <template v-if="showProtocol">
          <router-link to="/signup/protocol" class="register-protocol">
            点击注册即代表同意《ThinkSNS+用户使用协议》
          </router-link>
        </template>
      </footer>
    </div>
  </transition>
</template>

<script>
import { mapState } from "vuex";

function strLength(str) {
  let totalLength = 0;
  let i = 0;
  let charCode;
  for (; i < str.length; i++) {
    charCode = str.charCodeAt(i);
    if (charCode < 0x007f) {
      totalLength = totalLength + 1;
    } else if (charCode >= 0x0080 && charCode <= 0x07ff) {
      totalLength += 2;
    } else if (charCode >= 0x0800 && charCode <= 0xffff) {
      totalLength += 3;
    }
  }
  return totalLength;
}
const prefixCls = "signup";
const SMS = "sms"; // 手机
const EMAIL = "mail"; // 邮箱

// 手机号码规则
const phoneReg = /^1[3-9]\d{9}$/;
// 邮箱验证
const emailReg = /^\w+((-\w+)|(\.\w+))*@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
// 用户名验证
const usernameReg = /^[a-zA-Z_\u4E00-\u9FA5\uF900-\uFA2D][a-zA-Z0-9_\u4E00-\u9FA5\uF900-\uFA2D]*$/;
// 验证码
// const codeReg = /^[0-9]{4,6}$/;

export default {
  name: "Signup",
  data() {
    return {
      prefixCls,
      eye: false,
      error: "",
      loading: false,

      name: "",
      email: "",
      phone: "",
      countdown: 0,
      password: "",
      verifiable_type: SMS,
      verifiable_code: ""
    };
  },
  computed: {
    ...mapState(["CONFIG"]),
    allowType() {
      // mobile-only | mail-only | all
      return this.CONFIG.registerSettings.method;
    },
    currentType: {
      get() {
        if (this.allowType === "all") return this.verifiable_type || SMS;
        return this.allowType === "mail-only" ? EMAIL : SMS;
      },
      set(val) {
        this.verifiable_type = val;
      }
    },
    showProtocol() {
      const registerSettings = this.CONFIG.registerSettings || {};
      return registerSettings.showTerms || false;
    },
    codeText() {
      return this.countdown > 0 ? `${this.countdown}s后重发` : "获取验证码";
    },
    canGetCode() {
      return (
        (this.phone.length === 11 || this.email.length > 4) &&
        this.countdown === 0
      );
    },
    disabled() {
      const {
        name,
        phone,
        email,
        password,
        verifiable_code: verifiableCode,
        verifiable_type: verifiableType
      } = this.$data;

      const res = [name, password, verifiableCode, verifiableType].every(
        i => i !== ""
      );

      if (!res) return true;

      return this.verifiable_type === "sms"
        ? phone.length !== 11
        : email.length <= 4;
    },
    _$type: {
      get() {
        let label = "";
        let label2 = "";
        switch (this.currentType) {
          case SMS:
            label = "手机";
            label2 = "邮箱";
            break;
          case EMAIL:
            label = "邮箱";
            label2 = "手机";
            break;
        }
        return {
          value: this.currentType,
          label,
          label2
        };
      },
      set(val) {
        this.currentType = val;
      }
    }
  },
  methods: {
    countDown() {
      const t = setInterval(() => {
        if (--this.countdown <= 0) {
          this.countdown = 0;
          clearInterval(t);
        }
      }, 1000);
    },
    getCode() {
      if (!this.canGetCode) return;
      const phone = this.phone;
      const email = this.email;
      let params = this.verifiable_type === SMS ? { phone } : { email };
      this.$http
        .post("verifycodes/register", params, {
          validateStatus: status => status === 202
        })
        .then(() => {
          this.countdown = 60;
          this.countDown();
          this.error = "";
        })
        .catch(err => {
          this.$Message.error(err.response.data);
        });
    },
    signUp() {
      const {
        name,
        phone,
        email,
        password,
        verifiable_code: verifiableCode,
        verifiable_type: verifiableType
      } = this.$data;

      // 判断首字符是否为数字
      if (!isNaN(name[0]))
        return this.$Message.error({ name: "用户名不能以数字开头" });

      // 判断特殊字符及空格
      if (!usernameReg.test(name))
        return this.$Message.error({ name: "用户名不能包含特殊符号以及空格" });

      // 判断字节数
      if (strLength(name) > 48 || strLength(name) < 4)
        this.$Message.error({ name: "用户名不能少于2个中文或4个英文" });

      // 手机号
      if (verifiableType === SMS && !phoneReg.test(phone))
        return this.$Message.error({ phone: "请输入正确的手机号码" });

      // 邮箱
      if (verifiableType !== SMS && !emailReg.test(email))
        return this.$Message.error({ email: "请输入正确的邮箱号码" });

      // 密码长度
      if (password.length < 6)
        return this.$Message.error({ password: "密码长度必须大于6位" });

      let param = {
        name,
        phone,
        email,
        verifiable_code: verifiableCode,
        password,
        verifiable_type: verifiableType,
        validateStatus: s => s === 201
      };
      this.loading = true;
      verifiableType === SMS ? delete param.email : delete param.phone;
      this.$http
        .post("users", param)
        .then(({ data: { token } = {} }) => {
          if (token) {
            this.$Message.success("注册成功, 请登陆");
            this.$router.push("/signin");
          }
        })
        .finally(() => {
          this.loading = false;
          this.disable = true;
        });
    },
    changeType() {
      switch (this.currentType) {
        case SMS:
          this._$type = EMAIL;
          break;
        case EMAIL:
          this._$type = SMS;
          break;
      }
    },
    popProtocol() {}
  }
};
</script>

<style lang="less" scoped>
.p-signup {
  display: flex;
  flex-direction: column;
  height: 100%;

  > * {
    width: 100%;
  }

  .m-form-row {
    label {
      flex: 0 0 30 * 4px;
      width: 30 * 4px;
    }
    .m-input {
      padding: 0 30px 0 0;
    }

    .code-text {
      color: @primary;

      &.disabled,
      &[disabled] {
        color: #ccc;
      }
    }

    &-append {
      flex: 0 0 170px;
      width: 170px;
      text-align: right;
      svg {
        width: 38px;
        height: 38px;
        fill: #b3b3b3;
      }
    }
  }

  footer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    text-align: center;
    padding: 0.2rem;

    .register-protocol {
      font-size: 0.3rem;
      color: #666;
    }
  }
}
</style>
