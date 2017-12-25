<template>
<ui-panle title="圈子协议">
	<div class="form-horizontal">
 		<div class="form-group col-lg-8">
 			<label>协议：</label>
 			<textarea class="form-control" rows="20" placeholder="协议" v-model="protocol"></textarea>
 		</div>
 		<div class="form-group col-lg-8">
 			<button class="btn btn-primary" @click="handleSubmit">提交</button>
 		</div>
	</div>
</ui-panle>	
</template>
<script>
import { admin, api } from '../../axios';
export default {
	data:()=>({
		protocol: null,
	}),

	methods: {
		getProtocol() {
			admin.get('protocol', {
				validateStatus: status => status === 200
			}).then(({ data }) => {
				this.protocol = data.protocol;
			}).catch(({ response: { data = { message: '获取失败' } } = {} }) => {
				this.$store.dispatch('alert-open', { type: 'danger', message: data });
	      	});
		},

		handleSubmit() {
			admin.patch('protocol', { protocol: this.protocol },
				{ validateStatus: status => status === 201 }
			).then(({ data }) => {
				this.$store.dispatch('alert-open', { type: 'success', message: data });
			}).catch(({ response: { data = { message: '提交失败' } } = {} }) => {
				this.$store.dispatch('alert-open', { type: 'danger', message: data });
	      	});
		},
	},

	created() {
		this.getProtocol();
	}
}
</script>