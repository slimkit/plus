<template>
  <div class="panel-body">
    <div class="form-horizontal">
      
      <module-cdn-select :handle-select="handleSelect" value="local"></module-cdn-select>

      <!-- 提交按钮 -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">

          <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>

        </div>
      </div>

    </div>
  </div>
</template>

<script>
import Select from './Select';
export default {
  name: 'module-cdn-local',
  components: {
    [Select.name]: Select,
  },
  props: {
    handleSelect: { type: Function, required: true },
  },
  methods: {
    handleSubmit ({ stopProcessing }) {
      console.log(stopProcessing);
    }
  },
};
</script>
