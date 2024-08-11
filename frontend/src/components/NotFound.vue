<script setup>

</script>
<template>
    <div class="container">
        <div>
            <h1>404</h1>
            <p>Pagina non trovata</p>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.container {
    min-height: calc(100vh - 100px);
    display: grid;
    place-items: center;
    text-align: center;

    h1 {
        font-size: 100px;
        font-weight: 900;
        letter-spacing: 1rem;
        color: rgba(var(--bs-body-color-rgb), .5);
    }

    p {
        font-size: 18px;
        letter-spacing: 2px;
        color: rgba(var(--bs-body-color-rgb), .7);
    }
}
</style>