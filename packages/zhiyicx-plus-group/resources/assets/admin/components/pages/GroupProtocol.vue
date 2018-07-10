<template>
	<ui-panle title="圈子协议">
		<div class="form-horizontal container-fluid">
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-12">
		 			<mavon-editor style="width: 100%" placeholder="圈子使用协议" :toolbars="toolbars" v-model="protocol" ref="editor" :apiHost="apiHost" />
		 		</div>
		 		<div class="form-group col-lg-8">
		 			<button class="btn btn-primary" @click="handleSubmit">提交</button>
		 		</div>
			</div>
		</div>
	</ui-panle>
</template>
<script>
import { admin, api } from "../../axios";
import { mavonEditor } from "@slimkit/plus-editor";
import "@slimkit/plus-editor/dist/css/index.css";
import "highlight.js/styles/github.css";

export default {
	components: {
		mavonEditor
	},
	data: () => ({
		apiHost: "",
		protocol: "",
		toolbars: {
			bold: true, // 粗体
			italic: true, // 斜体
			header: true, // 标题
			underline: true, // 下划线
			strikethrough: true, // 中划线
			mark: true, // 标记
			superscript: true, // 上角标
			subscript: true, // 下角标
			quote: true, // 引用
			ol: true, // 有序列表
			ul: true, // 无序列表
			link: true, // 链接
			code: true, // code
			table: true, // 表格
			fullscreen: true, // 全屏编辑
			readmodel: true, // 沉浸式阅读
			htmlcode: true, // 展示html源码
			help: true, // 帮助
			/* 1.3.5 */
			undo: true, // 上一步
			redo: true, // 下一步
			trash: true, // 清空
			save: true, // 保存（触发events中的save事件）
			/* 1.4.2 */
			navigation: true, // 导航目录
			/* 2.1.8 */
			alignleft: true, // 左对齐
			aligncenter: true, // 居中
			alignright: true, // 右对齐
			/* 2.2.1 */
			subfield: true, // 单双栏模式
			preview: true // 预览
		}
	}),

	methods: {
		getProtocol() {
			admin
				.get("protocol", {
					validateStatus: status => status === 200
				})
				.then(({ data }) => {
					this.protocol = data.protocol;
				})
				.catch(({ response: { data = { message: "获取失败" } } = {} }) => {
					this.$store.dispatch("alert-open", { type: "danger", message: data });
				});
		},

		handleSubmit() {
			admin
				.patch(
					"protocol",
					{ protocol: this.protocol },
					{ validateStatus: status => status === 201 }
				)
				.then(({ data }) => {
					this.$store.dispatch("alert-open", {
						type: "success",
						message: data
					});
				})
				.catch(({ response: { data = { message: "提交失败" } } = {} }) => {
					this.$store.dispatch("alert-open", { type: "danger", message: data });
				});
		}
	},

	created() {
		this.getProtocol();
	}
};
</script>
