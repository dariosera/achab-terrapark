<script setup>
import { useProjectStore } from '@/stores/project';

const ps = useProjectStore()
</script>
<template>
    <div class="container p-5">
        <h1>Termini e condizioni</h1>

        <iframe :src="ps.getPrivacy().termsAndConditions"></iframe>
    </div>
</template>
<style lang="scss" scoped>

iframe {
    border: 0;
    outline: 0;
    width: 100%;
    height: calc(100vh - 300px);
}

</style>