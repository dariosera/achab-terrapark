<script setup>
import { useTerraParkStore } from '@/stores/commons';
import { reactive, watch} from 'vue';
const props = defineProps(["authorID"])

const tps = useTerraParkStore();

const author = reactive({})

watch(() => props.authorID, (a,b) => {
    get()
})

function get() {
    Object.assign(author,tps.getAuthor(props.authorID))
}

get()
</script>
<template>
    <div v-if="author.name">Relatore: <strong><router-link :to="`/relatore/${author.authorID}`">{{ author.name }} {{ author.surname }}</router-link></strong></div>
</template>
<style scoped lang="scss">
strong {
    color: var(--bs-dark);

    a {
        color: var(--bs-dark);
        text-decoration: none;

        &:hover {
            text-decoration: underline;
        }
    }
}
</style>