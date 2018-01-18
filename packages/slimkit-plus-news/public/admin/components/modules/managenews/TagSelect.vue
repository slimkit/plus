<template>
    <div id="tag-sel">
        <div class="group" v-for="(group, index) in tags" :key="group.id">
            <label class="labelForGroup">{{group["name"]}}</label>
            <div class="v-input">
                <div v-for='tag in group["tags"]' :key='tag.id' class="tag" :class="{active: isSelected(tag.id)}" @click="chooseTag(tag.id)">{{tag['name']}}</div>
            </div>
        </div>
    </div>
</template>
<script>
import { api } from "../../../axios";
export default {
    name: "ui-select",
    props: {
        value: Array,
        max: Number
    },
    data() {
        return({
            tags: [],
            selected: [],
        });
    },
    watch: {
        value(val) {
            this.selected = [...val];
        }
    },
    methods: {
        isSelected(id) {
            return this.selected.indexOf(id) > -1;
        },
        chooseTag(id) {
            let index = this.selected.indexOf(id);
            index > -1 ?
                this.selected.splice(index, 1) :
                ((this.selected.length >= this.max) ? this.$emit('tips', { tags: `标签最多选择${this.max}项` }, "danger") : this.selected = Array.from(new Set([...this.selected, id])));
            this.$emit('input', this.selected);
        }
    },
    created() {
        api.get('/tags', {
            validateStatus: status => status === 200,
        }).then(({ data = [] }) => {
            this.tags = [...data];
        }).catch(err => {
            console.log(err);
        });

        this.selected = [...this.value];
    }
}
</script>
<style lang="scss">
#tag-sel {
    .group {
        padding: 20px 0 0;
        &:first-child {
            margin-top: -12px;
        }
        .labelForGroup {
            margin-bottom: 14px;
            padding-left: 10px;
            border-left: 3px solid #59b6d7;
        }
        .v-input {
            display: flex;
            padding: 10px 10px 5px;
            margin-right: 0;
            margin-left: 0;
            border: 1px solid #ddd;
            border-radius: 4px 4px 0 0;
            flex-wrap: wrap;
            .tag {
                user-select: none;
                cursor: pointer;
                padding: .4em .6em .5em;
                margin: 0 5px 5px 0;
                font-size: 75%;
                line-height: 1;
                color: #555;
                text-align: center;
                vertical-align: baseline;
                border-radius: .25em;
                border: 1px solid #ccd0d2;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                background-color: #fff;
                &.active {
                    color: #fff;
                    background-color: #3097d1;
                    border-color: #3097d1;
                }
            }
        }
    }
}
</style>