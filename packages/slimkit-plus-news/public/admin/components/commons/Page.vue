<template>
    <div>
        <ul class="pagination ma0">
            <!-- v-show="page.show" -->
            <!-- 上一页按钮 -->
            <li>
                <a href="#" @click.prevent.stop="change('pre')" :disabled="cur === 1">
                <span><<</span>
            </a>
            </li>
            <li>
                <a href="#" @click.prevent.stop="change(+1)" :disabled="cur === 1">首页</a>
            </li>
            <li v-for="page in pages" :class="{active: cur === page}">
                <a href="#" @click.prevent.stop="change(+page)">{{ page }}</a>
            </li>
            <li>
                <a href="#" @click.prevent.stop="change(+last)" :disabled="cur === last">尾页</a>
            </li>
            <!-- 下一页按钮 -->
            <li>
                <a href="#" @click.prevent.stop="change('next')" :disabled="cur === last">
                <span>>></span>
            </a>
            </li>
            <li class="goto">
                <div class="input-group">
                    <input type="text" class="form-control" v-model.number='goto'>
                    <div class="input-group-btn">
                        <input class="btn btn-default" type="button" @click="change(goto)" value='前往' />
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    name: 'ui-page',
    props: {
        value: {
            type: Number,
            required: true,
        },
        last: {
            type: Number,
            required: true,
        }
    },
    data() {
        return({
            cur: 1,
            len: 5,
            goto: 1,
        });
    },

    watch: {
        value(val) {
            this.cur = val
        },
        cur(val){
            this.$emit('input', val);
        }
    },
    computed: {
        pages() {
            var pag = [];
            if(this.cur < this.len) {
                var i = Math.min(this.len, this.last);
                while(i) {
                    pag.unshift(i--);
                }
            } else {
                var middle = this.cur - Math.floor(this.len / 2),
                    i = this.len;
                if(middle > (this.last - this.len)) {
                    middle = (this.last - this.len) + 1
                }
                while(i--) {
                    pag.push(middle++);
                }
            }
            return pag
        },
    },

    methods: {
        change() {
            const t = arguments[0];
            return typeof t === "number" ?
                this.cur = t :
                this.changeByType(t);
        },
        changeByType(type) {
            switch(type) {
                case 'pre':
                    return this.cur = this.cur > 1 ? --this.cur : 1;
                case 'next':
                    return this.cur = this.cur < this.last ? ++this.cur : this.last;
            }
        }
    }
}
</script>
<style lang="scss">
.input-group.inflex {
    display: inline-block
}

.pagination.ma0 {
    margin: 0;
    a[disabled="disabled"],
    a[disabled] {
        color: #777777;
        background-color: #fff;
        border-color: #ddd;
        cursor: not-allowed;
    }
    .goto {
        display: inline-block;
        width: 100px;
    }
}
</style>