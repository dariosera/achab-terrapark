<script setup>
import { request } from '@/utils/request';
import {reactive, ref, onMounted} from 'vue'
import { useLikesStore } from '@/stores/likes';
import { useFavoritesStore } from '@/stores/favorites';

const uls = useLikesStore()
const ufs = useFavoritesStore()

const props = defineProps(["permalink"])

const status = reactive({
    active : false,
    liked : false,
    nLikes : false
})


const isFavorite = ref(false);

onMounted(() => {
    uls.getLikes(props.permalink).then(s => Object.assign(status,s))
    ufs.getFavorite(props.permalink).then(s => isFavorite.value = s)
})

function like() {

    if (status.nLikes === false) {
        return;
    }

    if (status.active) {
        if (status.liked) {
            //remove like
            status.liked = false
            status.active = false
            status.nLikes--
            re(`removeLike`)
        } else {
            // remove dislike and do like
            status.liked = true
            status.nLikes++
            re(`like`)
        }
    } else {
        // do like
        status.liked = true;
        status.active = true;
        status.nLikes++
        re(`like`)
    }

    uls.setLikes(props.permalink, status)
}

function dislike() {

    if (status.nLikes === false) {
        return;
    }

    if (status.active) {
        if (status.liked) {
            //remove like and do dislike
            status.liked = false
            status.nLikes--
            re(`dislike`)
        } else {
            // remove dislike
            status.active = false
            re(`removeDislike`)
        }
    } else {
        // do dislike
        status.liked = false;
        status.active = true;
        re(`dislike`)
    }

    uls.setLikes(props.permalink, status)
}

function favorite() {
    if (isFavorite.value === true) {
        // remove
        isFavorite.value = false;
        re(`removeFromFavorites`)
    } else {
        isFavorite.value = true;
        re(`addToFavorites`)
    }

    ufs.setFavorite(props.permalink, isFavorite.value)
}

const re = (action) => {
    request({
        task : `userActions/${action}`,
        data : {
            permalink: props.permalink
        },
        callback : function() {
            //
        }
    })
}


</script>
<template>
    <div class="actions">
        <div v-if="status.nLikes===false" class="skeleton-like">
            <span class="material-symbols-outlined" >thumb_up</span>
        </div>
        <button v-else @click="like" class="like">
            <span class="material-symbols-outlined" :class="{'fill' : status.liked && status.active}" >thumb_up</span> <b v-if="status.nLikes>0">{{ status.nLikes.toLocaleString("it-IT") }}</b>
        </button>

        <div v-if="status.nLikes===false" class="skeleton-like">
            <span class="material-symbols-outlined" >thumb_down</span>
        </div>
        <button v-else @click="dislike" class="dislike">
            <span class="material-symbols-outlined"  :class="{'fill' : !status.liked && status.active}" >thumb_down</span>
        </button>

        <div v-if="status.nLikes===false" class="skeleton-like">
            <span class="material-symbols-outlined" >favorite</span>
        </div>
        <button v-else @click="favorite" class="favorite">
            <span class="material-symbols-outlined" :class="{'fill' : isFavorite}">favorite</span>
        </button>
        <button disabled style="opacity: .5">
            <span class="material-symbols-outlined">ios_share</span>
        </button>
    </div>
</template>
<style scoped lang="scss">
.actions {
    display: flex;

    button {
        background: none;
        border: 0;
        outline: 0;
        margin: 0;
        padding: 0;
        height: 24px;
        width: 24px;
        line-height: 24px;

        span {
            vertical-align: middle;
        }


        &.like {
            display: flex;
            gap: .3rem;

            &:has(b) {
                padding-right: .3rem;
                width: auto;
            }

            b {
                font-size: 13px;
                font-weight: 400;
                margin-left: -3px;
            }

        }

        &.like .fill, &.dislike .fill {
            color: var(--bs-primary);
        }

        &.favorite .fill {
            color: var(--bs-danger);
        }
    }
}

.skeleton-like {
    color: rgba(0,0,0,.25);
    width: 24px;
    height: 24px;
    border-radius: 4px;
}
</style>