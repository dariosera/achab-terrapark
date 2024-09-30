<script setup>
import Player from '@vimeo/player';
import { defineProps, ref, reactive, onMounted, onBeforeUnmount, watch} from 'vue';
import { request } from '@/utils/request';
import Quiz from './Quiz.vue';

const emit = defineEmits(["opened"]);

const props = defineProps(["data","autoOpenPdf","coursePermalink"])
import VueAudioPlayer from '@liripeng/vue-audio-player'
const audioPlayer = ref(null)
const vimeoPlayer = ref(null)
let vimeoPlayerApi = null;


const currentTime = ref(0);
const savedTime = ref(0);

const iframe = ref(null);

let waitBeforeHistory = null;

onMounted(() => {
    //:src="`https://player.vimeo.com/video/${}`"   frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write"

    setup()

    waitBeforeHistory = setTimeout(() => {
        request({
            task : "userActions/historyPush",
            data : {
                permalink : props.data.permalink
            },
            callback : () => {},
            hideLoader : true,
        })
    }, 5000);
})

onBeforeUnmount(() => {
    if (waitBeforeHistory) clearTimeout(waitBeforeHistory)
})

watch(() => props.data,(prev,next) => {
    console.log("changed")
    setup()
}, {deep: true})

const setup = () => {
    if (props.data.media.mediaType === 'vimeo') {
            if (vimeoPlayerApi) {
                vimeoPlayerApi.destroy()
            }
            vimeoPlayerApi = new Player(vimeoPlayer.value, {
                id: props.data.media.vimeo_data.video_id,
                width : vimeoPlayer.value.clientWidth
            });
            
            vimeoPlayerApi.on('timeupdate', (event) => {
                currentTime.value = event.seconds;
            });

            request({
                task : "userActions/getMediaPosition",
                data : {
                    permalink : props.data.permalink,
                },
                callback : function(dt) {
                    if (dt.position > 0) {
                        vimeoPlayerApi.setCurrentTime(dt.position).then(function(seconds) {
                        
                        }).catch(function(error) {
                            console.log(`Wrong position`)
                        })
                    }
                }
            })
    } else {
        if (vimeoPlayerApi) {
            vimeoPlayerApi.destroy()
        }
    }

    if (props.data.media.mediaType === 'audio') {
        request({
                task : "userActions/getMediaPosition",
                data : {
                    permalink : props.data.permalink,
                },
                callback : function(dt) {
                    if (dt.position > 0) {
                        audioPlayer.value.currentTime = dt.position;
                    }
                }
            })
    }

    if (props.data.media.mediaType === 'pdf' && props.autoOpenPdf === true) {
        pdfViewer.show()
    }
}

watch(currentTime, (prev,next) => {
    if (Math.abs(next-savedTime.value) > 2) {
        request({
            task : "userActions/updateMediaPosition",
            data : {
                permalink : props.data.permalink,
                position : parseInt(next)
            },
            callback : () => {
                savedTime.value = next;
            },
            hideLoader: true,
        })
        
    }
})

onBeforeUnmount(() => {
    if (vimeoPlayerApi) {
        vimeoPlayerApi.destroy()
    }
})

const pdfViewer = reactive({
    isVisible: false,
    show : function() {
        // pdfViewer.isVisible = true
        // document.querySelector("body").style.overflow = 'hidden';
        // history.pushState({closeViewer: true},"")

        // addEventListener("popstate", (event) => { if (event.state.closeViewer) pdfViewer.hide() }, {once: true});

        window.open(pdfViewer.src())
    },
    hide : function() {
        pdfViewer.isVisible = false
        document.querySelector("body").style.overflow = 'auto';
    },
    src : () => {
        return `${window.location.protocol}//${window.location.host}/pdfviewer_legacy/web/viewer.html?url=${btoa(props.data.media.pdf_data.url)}`

    }
})

const audioTimeUpdate = (event) => {
    currentTime.value = event.target.currentTime;
}

function goFullscreen() {
    if (iframe.value.requestFullscreen) {
        iframe.value.requestFullscreen();
    } else if (iframe.value.mozRequestFullScreen) { // Firefox
        iframe.value.mozRequestFullScreen();
    } else if (iframe.value.webkitRequestFullscreen) { // Chrome, Safari and Opera
        iframe.value.webkitRequestFullscreen();
    } else if (iframe.value.msRequestFullscreen) { // IE/Edge
        iframe.value.msRequestFullscreen();
    }
}
</script>
<template><div class="content">
    
    
        <div ref="vimeoPlayer"></div>
    
        <div v-if="props.data.media.mediaType == 'embed'" class="embed-container">
            <iframe ref="iframe" :src="props.data.media.embed_data.url"
                frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write"
                ></iframe>
            <button @click="goFullscreen" v-if="props.data.media.embed_data?.fullscreenButton == true" class="fs-button">
                <span class="material-symbols-outlined">
fullscreen
</span>
            </button>
        </div>
    
        <div v-if="props.data.media.mediaType == 'quiz'">
            <Quiz :data="props.data.media.quiz_data" :permalink="props.data.permalink" @success="emit('opened')"/>
        </div>
    
    
    
            <div v-if="props.data.media.mediaType == 'audio'" class="audioplayer">
                <!-- <VueAudioPlayer
                      ref="audioPlayer"
                      :audio-list="[props.data.media.audio_data.url]"
                      :show-play-loading="false"
                      :theme-color="'rgba(0,0,0,.7)'"
                      :show-prev-button="false"
                      :show-next-button="false"
                     @timeupdate="audioTimeUpdate"
                    /> -->
    
                <audio ref="audioPlayer" :src="props.data.media.audio_data.url" controls @timeupdate="audioTimeUpdate"></audio>
            </div>
    
            <div v-if="props.data.media.mediaType == 'pdf'" class="pdfpreview" @click="pdfViewer.show()" >
                <div class="card-top"><span class="material-symbols-outlined me-2">description</span>  <div class="text">{{ props.data.title }}</div></div>
                <img v-if="!props.autoOpenPdf && !props.coursePermalink" :src="props.data.image">
                <div v-else class="small-pdf-viewer">
                    <iframe :src="pdfViewer.src()"></iframe>
                </div>
    
                <div class="card-bottom">
                    Documento pdf &middot; <span>{{props.data.meta.pages}} pagin{{ props.data.meta.pages == 1 ? 'a' : 'e' }}</span> &middot; Clicca per visualizzare
                    <!-- <span>Download consentito</span> -->
                </div>
            </div>
    
    
        
    
        <div id="pdfreader" v-if="pdfViewer.isVisible">
            <div class="inner"><iframe :src="pdfViewer.src()"></iframe>
    
                <button @click="pdfViewer.hide()" type="button" class="btn-close" aria-label="Close"></button>
            </div>
        </div>
    
    
</div>
    
</template>
<style lang="scss" scoped>

.content {
    aspect-ratio: 16/9;
}

.embed-container {

    position: relative;


    iframe {
    display: block;
    aspect-ratio: 16/9;
    width: 100%;
}
    .fs-button {
        display: block;
        position: absolute;
        bottom: 0;
        right: 0;
        background: none;
        border: none;
        outline: none;

        &:hover {
            transform: scale(1.1);
        }
    }
}




.audioplayer {
    display: grid;
    place-items: center;
    aspect-ratio: 16/9;
    width: 100%;
    padding: 0 3rem;
    background: rgba(0,0,0,.03);
    border-radius: 1rem;

    .vue-audio-player {
        width: 100%;
    }

}

.pdfpreview {
    width: 100%;
    position: relative;
    padding: 1rem;
    background: rgba(0,0,0,.05);
    border-radius: 1rem;
    cursor: pointer;

    &:hover {
        background: rgba(0,0,0,.08);
    }
    
    img {
        width: 100%;
    }

    .card-top {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        overflow: hidden;
        width: 100%;

        .text {
            font-size: 14px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            flex:1;
        }

    }


    .card-bottom {
        display: flex;
        align-items: center;
        margin-top: 1rem;
        gap: .5rem;
        font-size: 14px;

        span:not(:last-child) {
            border-right: 1px solid rgba(0,0,0,.15);
            padding-right: .5rem;
        }
    }

   
}


.test {
    width: 100%;
    aspect-ratio: 16/9;
    display: block;
    background: var(--bs-primary);

}

#pdfreader {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0;
    background: rgba(var(--bs-body-color-rgb), .15);
    overflow: hidden;
    z-index: 105;

    // &.visible {
    //     display: block;
    // }
    
    .inner {
        background: white;
        padding: 3rem 0 0 0 ;
        position: relative;
        height: 100%;
    }
    
    iframe {
        background: white;
        height: 100%;
        width: 100%;

    }


    .btn-close {
        position: absolute;
        top: .5rem;
        right: .5rem;
    }
}

.small-pdf-viewer {
    filter : blur(2px);
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;

    &::after {
        display: block; 
        content : "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    iframe {
        transform: translateY(-32px);
        height: 100%;
        width: 100%;
    }
}

</style>