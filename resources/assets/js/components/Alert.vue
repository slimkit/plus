<template>
<div
    class="alert"
    :class="type | createClass"
    role="alert">
        {{ node }}
    </div>
</template>

<script>
import store from '../store/alertStore';

window.demo = {
    type: 'success',
    node: '',
};

export default {
    data: () => (demo),

    filters: {
        createClass:(type) => {
            console.log(type, this);
            return `alert-${type}`;
        },
    },
};

</script>
