<script setup>
import { defineProps } from 'vue';

const props = defineProps(["meta"])
const meta = props.meta;

</script>
<template>
    <div class="row">
        <div class="col-6">
            Tema: {{ meta.tema }}
        </div>
    </div>
</template>