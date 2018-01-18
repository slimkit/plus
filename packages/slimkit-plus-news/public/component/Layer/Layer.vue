<template>
    <div id="Layer" v-show="isopen" :class="{in: isopen}" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: block">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- header -->
                <slot name="header">
                    <div class="modal-header">
                        <button type="button" class="close" @click='close'>
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">{{ title }}</h4>
                    </div>
                </slot>
                <!-- /header -->
                <!-- body -->
                <div class="modal-body" style="min-height:120px;">
                    <slot>body...</slot>
                </div>
                <!-- /body -->
                <!-- footer -->
                <slot name="footer">
                    <div class="modal-footer">
                        <input type="button" value="关闭面板" class="btn btn-default" @click='close' />
                    </div>
                </slot>
                <!-- /footer -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'Layer',
    props: {
        value: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            default: "这是一个提示框",
        }
    },
    data() {
        return {
            body: $("body"),
            isopen: this.open,
            layerback: $("<div id='lb' class='modal-backdrop fade in'></div>"),
        };
    },
    watch: {
        value(val) {
            this.isopen = val;
        },
        isopen(val) {
            if (val === true) {
                this.body.addClass('modal-open');
                this.layerback.appendTo(this.body);
            } else {
                this.body.removeClass('modal-open');
                this.layerback.remove();
            }
            this.$emit("input", val);
        }
    },
    methods: {
        close() {
            this.$emit("update");
            this.isopen = false;
        }
    }
}
</script>