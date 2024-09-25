<script setup>
import { useRoute } from 'vue-router';
import { request } from '@/utils/request';
import Dettaglio from '@/components/Dettaglio.vue';
import { reactive, onMounted } from 'vue';

const route = useRoute()
const c = reactive({})

onMounted(() => {
    request({
    task : "contents/search",
    data : {
        target: "single",
        permalink : route.params.permalink,
    },
    callback : function(dt) {
        Object.assign(c,dt[0])
    }
})
})

</script>
<template>
    <Dettaglio v-if="c.permalink" :data="c" :displayMode="'fullpage'"></Dettaglio>
</template>
<style scoped lang="scss">

</style>