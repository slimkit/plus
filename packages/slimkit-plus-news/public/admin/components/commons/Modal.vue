<template>
    <!-- Modal -->
    <div ref="modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <slot name="header">
                    <div class="modal-header">
                        <button type="button" ref="close" class="close" data-dismiss="modal" aria-label="Close" @click="onClose">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">{{title || '提示框'}}</h4>
                    </div>
                </slot>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <slot name="footer">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="onClose">关闭</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click.stop.prevent="save">确定</button>
                    </div>
                </slot>
            </div>
        </div>
    </div>
</template>
<script>
const fuc = () => ({});
export default {
    name: 'ui-modal',
    props: {
        title: String,
        doSave: { type: Function, default: fuc },
        onClose: { type: Function, default: fuc },
    },

    methods: {
        save() {
            this.doSave();
        }
    }
}
</script>
