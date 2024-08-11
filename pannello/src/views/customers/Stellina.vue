<script setup>
import {defineProps, reactive, defineEmits} from 'vue';
import {request} from 'kadro-core'

const props = defineProps(["params"]);

console.log(props)

const data = props.params.data

const event = {
    type: 'toggleFavorite',
    data: {}
};

const toggle = function() {
    if (data.preferito == 0) {
        request({
            task : "progetti/addToFavorites",
            data : {IDprogetto : data.IDprogetto},
            callback : function() {
                data.preferito = 1;
                props.params.api.dispatchEvent(event);
            },
            hideLoader: true,
        })
        
    } else {
        request({
            task : "progetti/removeFromFavorites",
            data : {IDprogetto : data.IDprogetto},
            callback : function() {
                data.preferito = 0;
                props.params.api.dispatchEvent(event);
            },
            hideLoader: true,
        })
    }
}
</script>
<template>
    <div class="d-block" @click="toggle" :style="{width: '1.5rem', textAlign: 'center'}">
        <i class="bi" :class="{'bi-star' : (data.preferito == 0), 'bi-star-fill text-primary' : (data.preferito == 1)}"></i>
    </div>
</template>