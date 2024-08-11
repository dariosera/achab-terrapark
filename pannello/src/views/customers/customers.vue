<script setup>
import TabellaClienti from './TabellaClienti.vue'
import TabellaProgetti from './TabellaProgetti.vue'
</script>
<template>
    <div class="row p-3">
        <div class="col-lg-4">
            <TabellaClienti></TabellaClienti>
        </div>
        <div class="col-lg-8">
            <TabellaProgetti/>
        </div>
    </div>
</template>