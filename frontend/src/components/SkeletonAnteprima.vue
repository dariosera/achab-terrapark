<script setup>

</script>
<template>
    
    <div class="anteprima shadow-sm">
        <div class="img skeleton">
        </div>
        <div class="custom-progress">
            <div class="custom-progress-inner" style="width: 0;"></div>
        </div>
        <div class="content">

            <div class="middle" >

                <div class="avanzamento"></div>

                <h3 class="title skeleton"></h3>
                <div class="subtitle skeleton"></div>
                <div class="icons">
                    <button class="skeleton"></button>
                </div>
                <div class="description skeleton"> </div>
            </div>

            <div class="bottom">
                <!-- <RouterLink v-if="props.data.isCourse" to="./corso/1" class="cta" @click.prevent="clickAnteprima">{{$t('common.scopriDiPiu')}}</RouterLink> -->
                <!-- <a href="#" class="cta" @click.prevent="clickAnteprima">{{$t('common.scopriDiPiu')}}</a> -->

                <div class="actions">
                    <button class="skeleton"></button>
                    <button class="skeleton"></button>
                    <button class="skeleton"></button>
                    <button class="skeleton"></button>
                </div>

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
        

       .title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 0;
        padding-bottom: 0;
        height: 26px;
        line-height: 26px;
       }

       .subtitle {
        margin-top: .2rem;
        font-size: 12px;
        text-transform: uppercase;
        color: rgba(var(--bs-body-color-rgb),.5);
        height: 21px;
        line-height: 21x;
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
        height: 75px;
       }

       .bottom {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
        padding-bottom: 1rem;

        .actions {
            display: flex;
            gap: .3rem;

            button {
                background: none;
                border: 0;
                outline: 0;
                margin: 0;
                padding: 0;
                height: 30px;
                width: 30px;
            }
        }


       }


    }


}
</style>