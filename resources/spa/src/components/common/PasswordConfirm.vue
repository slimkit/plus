<template>
  <div class="c-password-confirm" @touchmove.prevent>
    <!-- 遮罩层 -->
    <transition name="toast">
      <div
        v-if="visible"
        class="m-pop-box"
        @click="cancel"/>
    </transition>

    <transition name="pop">
      <div v-if="visible" class="wrap">
        <common-header class="common-header">
          输入密码
          <a slot="left" @click="cancel">取消</a>
        </common-header>
        <main>
          <form onsubmit="return false">
            <input
              ref="content"
              v-model="password"
              type="password"
              minlength="6"
              maxlength="16">
            <button
              :disabled="disabled"
              type="submit"
              @click="submit" >确认</button>
          </form>
          <a :class="{disabled}" @click="onForgotClick">忘记密码?</a>
        </main>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "PasswordConfirm",
  data() {
    return {
      visible: false,
      password: ""
    };
  },
  computed: {
    disabled() {
      return this.password.length < 6;
    },
    needValidate() {
      const config = this.$store.state.CONFIG;
      return config["pay-validate-user-password"] || false;
    }
  },
  methods: {
    show() {
      if (!this.needValidate) return this.$emit("submit");
      this.visible = true;
      this.$nextTick(() => {
        this.$refs.content.focus();
      });
    },
    cancel() {
      this.$emit("cancel");
      this.visible = false;
      this.password = "";
    },
    submit() {
      this.$emit("submit", this.password);
      this.visible = false;
      this.password = "";
    },
    onForgotClick() {
      this.$router.push({ path: "/forgot" });
      this.cancel();
    }
  }
};
</script>

<style lang="less" scoped>
.c-password-confirm {
  .wrap {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: @gray-bg;
    z-index: 102;
  }

  .common-header {
    margin-bottom: -1px;
    font-size: 30px;
  }

  main {
    display: flex;
    flex-direction: column;
    padding: 20px;
    background-color: #fff;

    > form {
      display: flex;
      margin-bottom: 20px;
      height: 60px;

      input[type="password"] {
        flex: auto;
        border: 1px solid @border-color;
        border-radius: 16px 0 0 16px;
      }

      button[type="submit"] {
        flex: none;
        width: 6em;
        background-color: @primary;
        color: #fff;
        border-radius: 0 16px 16px 0;

        &:disabled {
          background-color: #999;
        }
      }
    }

    > a {
      align-self: flex-end;
      font-size: 22px;
      margin-bottom: 20px;
    }
  }
}
</style>
