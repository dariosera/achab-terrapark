<script setup>
import {request, SearchSelect} from 'kadro-core'
import { defineProps, ref, reactive} from 'vue';

const props = defineProps(["project"]);


const visibleThemes = ref([])


request({
    task : "project/getVisibleThemes",
    data : {
        projectID : props.project.projectID
    },
    callback : function(dt) {
        visibleThemes.value = dt
    }
})

const removeTheme = (themeID) => {
    request({
        task : "project/removeVisibleTheme",
        data : {
            projectID : props.project.projectID,
            themeID
        },
        callback : function(dt) {
            visibleThemes.value = dt
        }
    }) 
}

const addTheme = reactive({
    themeID : undefined,
    submit : function() {
        request({
        task : "project/addVisibleTheme",
            data : {
                projectID : props.project.projectID,
                themeID: this.themeID
            },
            callback : function(dt) {
                visibleThemes.value = dt
                addTheme.themeID = undefined;
            }
        }) 
    }
})


const customerContents = reactive({
    standalone : 0,
    courses : 0
})

request({
    task : "project/getCustomerContents",
    data : {
        customerID : props.project.customerID
    },
    callback : function(dt) {
        customerContents.courses = dt.courses[0].n
        customerContents.standalone = dt.standalone[0].n
    }
})


const visibleContents = ref([])

request({
    task : "project/getVisibleContents",
    data : {
        projectID : props.project.projectID
    },
    callback : function(dt) {
        visibleContents.value = dt
    }
})

const removeContent = (contentID) => {
    request({
        task : "project/removeVisibleContent",
        data : {
            projectID : props.project.projectID,
            contentID
        },
        callback : function(dt) {
            visibleContents.value = dt
        }
    }) 
}


const addContent = reactive({
    permalink : undefined,
    submit : function() {
        request({
        task : "project/addVisibleContent",
            data : {
                projectID : props.project.projectID,
                permalink: this.permalink
            },
            callback : function(dt) {
                visibleContents.value = dt
                addContent.permalink = undefined;
            }
        }) 
    }
})

</script>
<template>
    <h5 class="card-title">Contenuti visibili</h5>

    <div class="border-bottom py-3">
        <strong>Temi visibili</strong>

        <div class="mt-2">
            <span v-for="theme,i in visibleThemes" :key="i" class="badge border text-muted px-2 me-2">
                {{ theme.title }}
                <i style="cursor: pointer" @click="removeTheme(theme.themeID)" class="bi bi-x-lg ms-2"></i></span>

            <small v-if="visibleThemes.length === 0">Nessun tema</small>
        </div>
        <form @submit.prevent="addTheme.submit" class="d-flex mt-3">
            <SearchSelect class="" required placeholder="Cerca un tema" task="themes.search" v-model="addTheme.themeID" />
            <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-plus"></i> Aggiungi</button>
        </form>
    </div>

    <div class="border-bottom py-3">
        <strong>Contenuti del cliente</strong>

        <div class="mt-3">
            <b>{{ customerContents.courses }}</b> corsi e <b>{{  customerContents.standalone }}</b> contenuti singoli.
        </div>
        
        <small>Questi contenuti sono visibili indipendentemente dalle selezioni per tema</small>
        
    </div>

    <div class="py-3">
        <strong>Singoli contenuti o corsi abilitati</strong>

        <div class="mt-2">
            <span v-for="con,i in visibleContents" :key="i" class="badge border text-muted px-2 me-2">
                {{ con.standalone ? `Singolo: ` : `` }}
                {{ con.isCourse ? `Corso: ` : `` }}
                {{ con.title }}
                <i style="cursor: pointer" @click="removeContent(con.contentID)" class="bi bi-x-lg ms-2"></i></span>

            <small v-if="visibleContents.length === 0">Nessun contenuto</small>
        </div>
        <form @submit.prevent="addContent.submit" class="d-flex mt-3">
            <input type="text" class="form-control" placeholder="Inserisci un permalink di un corso o contenuto singolo" v-model="addContent.permalink"> 
            <button type="submit" class="btn btn-primary ms-2" style="white-space: nowrap;"><i class="bi bi-plus"></i> Aggiungi</button>
        </form>
        
    </div>
</template>
<style scoped lang="scss">
.badge {
    font-size: 15px;
}
</style>