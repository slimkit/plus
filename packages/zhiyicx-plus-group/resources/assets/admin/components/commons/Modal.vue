<template>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ title }}</h4>
      </div>
      <div class="modal-body">
        <slot name="modal-body"></slot>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" @click="cancel">取消</button>
        <button type="button" class="btn btn-primary" @click="save">确认</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
const fuc = () => ({});
export default({
  name: 'ui-modal',
  props: {
    title: {
      type: String,
      required: true,
    },
    cancel: {
      type: Function,
      default: fuc
    },
    save: {
      type: Function,
      default: fuc
    }
  }
});
</script>