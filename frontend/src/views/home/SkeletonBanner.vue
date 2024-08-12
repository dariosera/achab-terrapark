<script setup>
</script>
<template>

    <div class="row banner bg-light">
        <div class="col-lg-6 order-lg-last image skeleton">
            
        </div>
        <div class="col-lg-6 text">

            <h2 class="skeleton"></h2>

            <p class="skeleton"></p>

            <a class="cta skeleton"></a>

        </div>
       
    </div>
    
</template>
<style lang="scss" scoped>

$size : 15px;


.banner .image {
    opacity: 0;
    transition: opacity .1s ease-in-out;
}
.splide__slide.is-visible {
    .banner .image {
        opacity: 1;
    }
}


.image {
    aspect-ratio: 16/9;
    background-size: cover;
}

.text {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 1rem;
    padding: $size*3 $size*3 $size*3 $size*6;

    h2 {
        font-weight: 700;
        font-size: $size*3;
        min-height: $size*3;
    }

    p {
        font-size: $size*1.3;
        min-height: $size*8;
    }

    .cta {
        font-size: $size;
        font-weight: bold;
        text-transform: uppercase;
        color: var(--bs-body-color);
        min-height: $size;
    }
}


@media (min-width: 992px)  {
  .image {
    aspect-ratio: 1;
  }
}
</style>
