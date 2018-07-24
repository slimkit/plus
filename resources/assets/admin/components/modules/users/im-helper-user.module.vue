<template>
  <div class="panel panel-default">

    <!-- title -->
    <div class="panel-heading">小助手</div>

    <!-- body -->
    <div class="panel-body">

      <!-- loading -->
      <sb-ui-loading v-if="loading" />

      <!-- form -->
      <div class="form-horizontal" v-else>
        
        <!-- im helper user -->
        <div class="form-group">
          <label class="col-sm-2 control-label">小助手用户</label>
          <div class="col-sm-6">
            <input class="form-control" type="number" min="1" placeholder="输入小助手" v-model="user">
          </div>
          <div class="col-sm-4 help-block">
            请设置小助手，小助手输入为用户 ID。
          </div>
        </div>

        <!-- submit -->
        <div class="col-sm-10 col-sm-offset-2">
          <sb-ui-button
            class="btn btn-primary"
            label="提交设置"
            proces-label="提交中..."
            @click="handleSubmit"
          >
          </sb-ui-button>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from "../../../util/request";
export default {
  data: () => ({
    loading: true,
    user: null
  }),
  methods: {
    handleSubmit(event) {
      // if (!this.user) {
      //   this.$store.dispatch('alert-open', { type: 'danger', message: '请输入正确的小助手用户 ID' });
      //   event.stopProcessing();
      //   return;
      // }

      request
        .put(
          createRequestURI("im/helper-user"),
          { user: this.user },
          {
            validateStatus: status => status === 204
          }
        )
        .then(() => {
          this.$store.dispatch("alert-open", {
            type: "success",
            message: "设置成功！"
          });
        })
        .catch(({ response: { data: message = "提交失败！" } }) => {
          this.$store.dispatch("alert-open", { type: "danger", message });
        })
        .finally(() => {
          event.stopProcessing();
        });
    }
  },
  created() {
    request
      .get(createRequestURI("im/helper-user"), {
        validateStatus: status => status === 200
      })
      .then(({ data }) => {
        this.user = data.user;
        this.loading = false;
      })
      .catch(() => {
        this.$store.dispatch("alert-open", {
          type: "danger",
          message: "获取小助手失败！"
        });
      });
  }
};
</script>
