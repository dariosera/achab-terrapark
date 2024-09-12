<script setup>
import { useI18n } from 'vue-i18n'
import Tr from "../i18n/translations"

const { t, locale } = useI18n()
const supportedLocales = Tr.supportedLocales
const switchLanguage = async (newLocale) => {
    await Tr.switchLanguage(newLocale)
}
</script>
<!-- <template>
<div class="dropdown">
  <button id="language-switcher" class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    {{locale.toUpperCase()}}
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    <li v-for="sLocale in supportedLocales" :key="`locale-${sLocale}`">
        <a class="dropdown-item" :class="{bold : (locale === sLocale)}" href="#" @click="switchLanguage(sLocale)">{{ $t(`locale.${sLocale}`) }}</a>
    </li>
  </ul>
</div>
  </template> -->
<template></template>
<style scoped lang="scss">

#language-switcher {
    text-decoration: none;
    color: var(--bs-body-color);
}

.dropdown-item.bold {
    font-weight: bold;
}

.dropdown-menu {
    margin-top: 3px;
    transform: translateX(12px);
    border-top: 3px solid var(--bs-body-color);
    border-radius: 0;
}

</style>