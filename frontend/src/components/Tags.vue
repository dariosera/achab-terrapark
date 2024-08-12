<script setup>
    import { useTagsStore } from '@/stores/tags';
    import { ref, watch } from 'vue';
    const props = defineProps(["permalink"])

    const ts = useTagsStore()

    const tags = ref([])
    watch(props, get)
    get()
    
    function get() {
        ts.getTags(props.permalink).then(t => tags.value = t)
    }
</script>
<template>
    <div v-if="tags.length > 0">
        Tag: 
        <span v-for="tag,i in tags" :key="i" class="badge border text-muted px-2 me-2">{{ tag.description }}</span>
        
    </div>
</template>
<style scoped lang="scss">
span {
    user-select: none;
}
</style>