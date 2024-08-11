<script setup>

import { defineProps, ref, reactive, onMounted } from 'vue';
import { request, Modal, quickTable, AgGridVue} from 'kadro-core';
import SlideEditor from './SlideEditor.vue';

const props = defineProps(["project"])
const slides = ref([])
const tdata = reactive({rowData : [], columnDefs: [], rowSelection : 'multiple'})
const testoNoRisultati = ref(null);
const grid = ref(null)

const getSlides = () => {
    request({
        task : "project/getSlides",
        data : {
            projectID : props.project.projectID,
        },
        callback : function(dt) {
            slides.value = dt;
        }
    })
}

onMounted(() => {
    getSlides();
})



const cercaSlide = reactive({
    modal : null,
    searchString : null,
    actions : [
        {
            text : "Seleziona almeno una slide",
            class : "btn-primary",
            callback : function() {

                request({
                    task: "project/addSlides",
                    data: {
                        slides: cercaSlide.selectedContents.map(x => x.slideID),
                        projectID: props.project.projectID,
                    },
                    callback: function () {
                        getSlides()
                    }
                })

            }
        }
    ],
    selectionChanged : function() {
        cercaSlide.selectedContents = grid.value.api.getSelectedRows()
        if (cercaSlide.selectedContents.length > 0)
            cercaSlide.actions[0].text = `Aggiungi ${cercaSlide.selectedContents.length} contenut${cercaSlide.selectedContents.length==1?'o':'i'}`
        else 
            cercaSlide.actions[0].text = "Seleziona almeno un contenuto"
    },
    selectedContents : [],
    open:  () => {
        //if (cercaSlide.searchString.length < 4) return;
        request({
        task: "homepage/slidesCatalogue",
        data: {
            customerID : props.project.customerID,
            projectID : props.project.projectID,
        },
            callback: function (dt) {
                if (dt.length === 0) {
                    testoNoRisultati.value = "Nessun contenuto trovato con il filtro impostato";
                }
                quickTable(tdata, dt, {
                    setToAllColumns: {
                        filter: true,
                        select: true
                    },
                    columnDefs : {
                        'contentID' : {
                            hide : true,
                        },
                        'permalink' : {
                            checkboxSelection: true,
                            //headerCheckboxSelection: true,
                            //showDisabledCheckboxes: true,
                        },
                        
                    },
                    });

                cercaSlide.modal.show()

                tdata.columnDefs.unshift({field : "", headerName : "", checkboxSelection : true  });

        }
    })
}
})

const editSlide = reactive({
    modal: null,
    editing : {},
    new : function() {
        request({
            task : "homepage/newSlide",
            callback : function(dt) {
                editSlide.editing = {
                    isNew : true,
                    slideID : dt.slideID,
                }
                editSlide.modal.show()
            }
        })
        

    },
    fromID : function(i) {
        this.editing = {
            ...slides.value[i]
        }
        this.modal.show()
    }

})

function move(i, dir) {
    let array = slides.value;
  // Check if the move is within bounds
  if (i < 0 || i >= array.length || (dir !== -1 && dir !== 1)) {
    console.log('Invalid index or direction');
    return;
  }

  // Calculate the new index
  const newIndex = i + dir;

  // Ensure the new index is within array bounds
  if (newIndex < 0 || newIndex >= array.length) {
    console.log('Move is out of bounds');
    return;
  }

  // Swap elements
  [array[i], array[newIndex]] = [array[newIndex], array[i]];

  const newOrder = [];
  array.forEach((c,i) => {
    newOrder.push({
        slideID : c.slideID,
        customOrder : i
    })
  })
  request({
    task : "project/updateSlidesOrder",
    data : {
        newOrder,
        projectID : props.project.projectID,
    },
    callback: function() {
        //
    }
  })
}

const addSlide = (slideID) => {
    request({
        task : "project/addSlides",
        data : {
            slides: [slideID],
            projectID : props.project.projectID,
        },
        callback: function() {
            getSlides()
        }
    })
}

const removeSlide = (slideID) => {
    request({
        task : "project/removeSlide",
        data : {
            slideID,
            projectID : props.project.projectID,
        },
        callback: function() {
            getSlides()
        }
    })
}


</script>created
<template>
    <div class="actions mb-2">
        <button class="btn btn-primary me-2" @click="editSlide.new"><i class="bi bi-plus"></i> Nuova slide</button>
        <button class="btn btn-outline-primary" @click="cercaSlide.open"><i class="bi bi-search"></i> Cerca nel catalogo delle slides</button>
    </div>

    <div v-if="slides.length === 0" class="text-center">
        <div>Nessuna slide</div>
    </div>
    <div v-else class="content-list">
        <div v-for="(sli, i) in slides" :key="i" class="content">
            <div class="updown">
                <div class="btn-group-vertical btn-group-sm" role="group" aria-label="Vertical button group">
                    <button type="button" class="btn btn-link" @click="move(i, -1)"><i
                            class="bi bi-chevron-up"></i></button>
                    <button type="button" class="btn btn-link" @click="move(i, 1)"><i
                            class="bi bi-chevron-down"></i></button>

                </div>
            </div>
            <div class="img">
                <img :src="sli.image_url" class="w-100">
            </div>
            <div class="info">
                <div><strong>{{ sli.title }}</strong></div>
                <div><em>{{ sli?.content }}</em></div>
                <div><a :href="sli?.link_href" _target="blank">{{ sli?.link_text }}</a></div>
            </div>
            <div class="actions">
                <button class="d-block btn btn-sm btn-outline-danger" @click="removeSlide(sli.slideID)">
                    <i class="bi bi-trash"></i>
                </button>

                <button class="d-block mt-1 btn btn-sm btn-outline-primary"
                    @click="editSlide.fromID(i)">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
        </div>
    </div>

    <Modal title="Cerca una slide" :actions="cercaSlide.actions" @hidden="cercaSlide.onHidden"
        :canDismiss="true" size="xl" @ready="(t) => cercaSlide.modal = t">

        <div>
          

            <AgGridVue v-if="tdata.rowData.length>0" ref="grid"
                class="ag-theme-quartz ag-full-width" :autoSizeStrategy="{type: 'fitCellContents'}" pagination="true"
                style="height: 300px" :rowData="tdata.rowData" :columnDefs="tdata.columnDefs"
                :rowSelection="tdata.rowSelection" @selection-changed="cercaSlide.selectionChanged"></AgGridVue>
            <div class="text-center testo-no-risultati" v-if="tdata.rowData.length === 0">
                {{ testoNoRisultati }}
            </div>
        </div>
    </Modal>

    <SlideEditor @ready="(t) => editSlide.modal = t" :slide="editSlide.editing" @created="addSlide" />
</template>
<style lang="scss" scoped>
.content-list {
    .content {
        display: flex;
        padding: .4rem;

        &:not(:last-child) {
            border-bottom: 1px solid rgba(var(--bs-body-color-rgb),.2);
        }

        .updown {
            padding-right: .4rem;
            display: flex;
            flex-direction: column;

            .btn-group-vertical {
                height: 100%;
            }
        }

        .img {
            max-width: 150px;
            
            img {
                display: block;
            }
        }

        .info {
            padding-left: .5rem;
            flex: 1;
        }
    }
}</style>