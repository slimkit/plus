<template>
	<table class="table table-bordered">
        <slot name="table-loading"></slot>
		<thead>
			<slot name="table-thead-th"></slot>
		</thead>
		<tbody>
			<slot name="table-tbody-tr"></slot>
		</tbody>
	</table>
</template>
<script>
export default{ name: 'ui-table' }
</script>