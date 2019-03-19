<template>
    <el-card class="box-card" header="管理下载页面" shadow="hover">
        <el-form :model="form" ref="form" label-width="100px">
            <el-form-item label="二维码" prop="qr">
                <el-input type="file" @blur="e => onChange('qr', e)"></el-input>
            </el-form-item>
            <el-form-item label="背景图" prop="bg">
                <el-input type="file" accept="image/*" @blur="e => onChange('bg', e)"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmit">提交</el-button>
            </el-form-item>
        </el-form>
    </el-card>
</template>

<script>
import request, { createRequestURI } from '../utils/request.js';
export default {
    data: () => ({
        form: {
            bg: '',
            qr: '',
        },
    }),
    methods: {
        onChange(name, event) {
            let [ file ] = event.target.files;
            this.form[name] = file;
        },
        onSubmit() {
            let form = new FormData();
            form.append('qr', this.form.qr);
            form.append('bg', this.form.bg);
            request.post(createRequestURI('admin/plus-appversion/download-manage'), form, {
                validateStatus: status => status === 204,
            }).then(() => alert('提交成功')).catch(() => alert('提交失败'));
        }
    }
}
</script>

