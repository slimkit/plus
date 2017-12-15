<template>
    <div id="v-sel">
        <div v-if="group">
            <div class="group" v-for="(group, index) in options" :key="group[trackBy]">
                <label class="labelForGroup">{{group[label]}}</label>
                <ul class="v-input">
                    <li v-for='option in group[children]' :key='option.id' @click='changeValue(option[trackBy])' :class="{active: isSelected(option[trackBy])}">
                        {{option[label]}}
                    </li>
                </ul>
            </div>
        </div>
        <ul v-else class="v-input">
            <li v-for='(option, index) in options' :key='index' @click='changeValue(option[trackBy])' :class="{active: isSelected(option[trackBy])}">
                {{option[label]}}
            </li>
        </ul>
    </div>
</template>
<script>
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(searchElement, fromIndex) {
        var k;
        if (this == null) {
            throw new TypeError('"this" is null or not defined');
        }
        var o = Object(this);
        var len = o.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = fromIndex | 0;
        if (n >= len) {
            return -1;
        }
        k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);
        while (k < len) {
            if (k in o && o[k] === searchElement) {
                return k;
            }
            k++;
        }
        return -1;
    };
}

export default {
    name: 'v-select',
    props: {
        value: {
            type: null,
            default: ""
        },
        label:{
          type: String,
          default: "name"
        },
        trackBy:{
          type: String,
          default: "id"
        },
        options: {
            type: Array,
            default: []
        },
        multiple: {
            type: Boolean,
            default: false
        },
        group: {
            type: Boolean,
            default: false,
        },
        children:{
          type: String,
          default: 'children'
        }
    },
    data() {
        return {
            selected: this.value || this.multiple ? []:''
        }
    },
    computed: {},
    watch: {
        value(val){
          this.selected = val === null || val === undefined ?
                '' :
                this.multiple ?
                [...val] : val;
        }
    },
    methods: {
        changeValue(id) {
            if (this.multiple) {
                let index = this.selected.indexOf(id);
                index > -1 ?
                    this.selected.splice(index, 1) :
                    this.selected = Array.from(new Set([...this.selected, id]));
            } else {
                this.selected = id;
            }
            this.$emit('input', this.selected);
        },
        isSelected(id) {
            return this.multiple ?
                this.selected.indexOf(id) > -1 :
                id === this.selected;
        },
    }
}
</script>
<style lang="css">
#v-sel {
    width: 100%;
    height: auto;
    min-height: 41px;
    vertical-align: middle;
}

#v-sel .group{
  padding: 20px 0;
  border-bottom: 1px solid #ededed;
}
#v-sel .group:first-child{
  margin-top: -12px;
}

#v-sel .labelForGroup{
  margin-bottom: 14px;
  padding-left: 10px;
  border-left: 3px solid #59b6d7;
}

#v-sel .v-input {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    text-align: center;
    margin: 0;
    padding: 0;
    margin-left: 0;
}

#v-sel .v-input li {
    cursor: pointer;
    margin: 0 5px 5px 0;
    transition: all .3s;
    height: 36px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.6;
    color: #555555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccd0d2;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}

#v-sel .v-input li:hover {
    opacity: .8;
    color: #3097D1;
    border-color: #3097D1;
}

#v-sel .v-input li.active {
    color: #fff;
    background-color: #3097D1;
    border: 1px solid rgba(48, 151, 209, 0.50);
}
</style>