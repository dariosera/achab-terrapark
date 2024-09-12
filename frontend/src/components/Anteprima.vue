<script setup>

import {ref} from 'vue';
import  {useRouter} from 'vue-router'
import Actions from './Actions.vue';
import { isLogged } from '@/utils/auth';

import { useTerraParkStore } from '@/stores/commons';

const tps = useTerraParkStore()

const ready = ref(false)
const props = defineProps(["data","selected","isPublic"]);
const emit = defineEmits(["mostraDettaglio"])





const router = useRouter()

const clickAnteprima = () => {

    if (props.isPublic) {
        window.open(`https://terrapark.it`)
        return;
    }

    if (props.data.isCourse) {
        router.push(`/corso/${props.data.permalink}`)
    } else {
        emit('mostraDettaglio', props.data)
    }
}
</script>
<template>
    
    <div class="anteprima shadow-sm" :class="{'is-course': props.data.isCourse, 'selected' : props?.selected}" :data-permalink="props.data.permalink">
        <div class="img" @click="clickAnteprima">
            <img :src="props.data.image">
            <div class="tags">
                <div v-if="props.data.isCourse" class="tag badge badge-pill">Corso</div>
            </div>
        </div>
        <div v-if="props.data.position > 0 " class="custom-progress">
            <div class="custom-progress-inner" :style="`width: ${parseInt((props.data.position / props.data.meta.duration) * 100)}%`"></div>
        </div>
        <div v-if="props.data.progress > 0 " class="custom-progress">
            <div class="custom-progress-inner" :style="`width: ${parseInt(props.data.progress * 100)}%`"></div>
        </div>

        <div class="content">

            <div class="middle" @click="clickAnteprima">

                <div class="avanzamento">
                    <span v-if="props.data.isCourse && props.data.progress > 0">{{parseInt(props.data.progress * 100) }}% completato</span>
                </div>

                <div class="outer-title">
                    <h3 class="title">{{ props.data.title }}</h3>
                </div>
                <div class="subtitle" v-if="props.data.topicID !== null">{{ tps.getTopic(props.data.topicID).title }}</div>
                <div class="subtitle" v-else>{{ tps.getTheme(props.data.themeID).title }}</div>

                <div v-if="props.data.isCourse" class="icons">
                    <!-- <span v-for="i in ['video','pdf','test']" :key="i" class="material-symbols-outlined">{{icone_tipologie_contenuto[i]}}</span> -->
                </div>
                <div v-else class="icons">
                    <span class="material-symbols-outlined">{{ tps.getTypology(props.data.typologyID).icon}}</span>
                </div>
                <div class="description">
                    {{ props.data.description.split('|')[0].substring(0,130) }}
                </div>
            </div>

            <div class="bottom">
                <!-- <RouterLink v-if="props.data.isCourse" to="./corso/1" class="cta" @click.prevent="clickAnteprima">{{$t('common.scopriDiPiu')}}</RouterLink> -->
                <!-- <a href="#" class="cta" @click.prevent="clickAnteprima">{{$t('common.scopriDiPiu')}}</a> -->

                <Actions v-if="!props.isPublic" :permalink="props.data.permalink" />

            </div>

        </div>
        
    </div>

</template>
<style lang="scss" scoped>


.anteprima {
    transition-property: all;
    transition-duration: .15s;
    background: var(--bs-body-bg);
    
    * {
        user-select: none;
    }

    &:hover, &.selected {
        background-color: rgba(var(--bs-body-color-rgb),.02);
        box-shadow: var(--bs-box-shadow) !important;
    }

    &.selected {
        transform: scale(1.05);
        z-index: 999;
    }

    .img {
        aspect-ratio: 16/9;
        position: relative;
        cursor: pointer;

        img {
            position: absolute;
            display: block;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin-bottom: 3px;
        }

        .tags {
            bottom: 1rem;
            left: 1rem;
            position: absolute;
            

            .tag {
                float:left;
                clear: both;
                display: inline;
                margin-top: 5px;
                background: rgba(var(--bs-body-color-rgb), .6);
                color: var(--bs-body-bg);
                text-transform: uppercase;
                font-size: 10px;
            }
        }

        
    }

    .custom-progress {
            position: relative;
            display: block;
            width: 100%;
            height: 3px;
            background-color: rgba(0,0,0,.15);
            border-radius: 0;

            .custom-progress-inner {
                height: 3px;
                background: red;
                border-radius: 0;
                max-width: 100%;
            }
        }

    .content {
        padding: 0 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        

        .middle {
            cursor: pointer;

            .avanzamento {
                text-transform: uppercase;
                font-size: 12px;
                height: 22px;
                line-height: 22px;
            }
        }
        
        .outer-title {
            height: 32px;
            
        }

       .title {
        font-size: 15px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 700;
        margin-bottom: 0;
        padding-bottom: 0;
        line-height: 16px;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        --webkit-line-clamp: 2;
        line-clamp: 2;
       }

       .subtitle {
        margin-top: .2rem;
        font-size: 12px;
        text-transform: uppercase;
        color: rgba(var(--bs-body-color-rgb),.5);
        height: 21px;
        line-height: 1.2;
       }

       .icons {
        margin: .4rem 0;
        display: flex;
        height: 24px;
        gap: .4rem;

        button {
                background: none;
                border: 0;
                outline: 0;
                margin: 0;
                padding: 0;
                height: 24px;
                width: 24px;
            }

       }

       .description {
        font-size: 13px;
        height: 64px;
        line-height: 16px;
        overflow: hidden;
       }

       .bottom {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
        padding-bottom: 1rem;

       }


    }


}
</style>