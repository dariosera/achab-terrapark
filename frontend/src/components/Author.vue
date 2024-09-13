<script setup>
import { useTerraParkStore } from '@/stores/commons';
import { ref, watch} from 'vue';
const props = defineProps(["authors"])

const tps = useTerraParkStore();

const authors = ref([])

watch(() => props.authors, (a,b) => {
    get()
})

function get() {
    props.authors.forEach(id => {
        authors.value.push(tps.getAuthor(id))
    })
}

get()
</script>
<template>
    <div class="authors">
        <div v-if="authors.length > 0">
            {{ authors.length == 1 ? 'Autore' : 'Autori' }}:
            <span v-for="author in authors" :key="author.authorID" class="author me-1">
                <strong><router-link :to="`/autore/${author.authorID}`">{{ author.name }} {{ author.surname }}</router-link></strong>
            </span>
        </div>

    </div>
    
</template>
<style scoped lang="scss">
strong {
    color: var(--bs-dark);

    a {
        font-weight: 500;
        color: var(--bs-primary)!important;
        text-decoration: none;

        &:hover {
            text-decoration: underline!important;
        }

       
    }
}

.authors {
    .author:not(:last-child) {
        &::after {
            content: ","
        }
    }
}
</style>