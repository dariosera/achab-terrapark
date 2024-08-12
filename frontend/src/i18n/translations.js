import i18n from "@/i18n"
import useToasts from "@/stores/toasts"
const Trans = {
    get supportedLocales() {
      return import.meta.env.VITE_SUPPORTED_LOCALES.split(",")
    },

    set currentLocale(newLocale) { // <--- 2
        i18n.global.locale.value = newLocale
    },

    async switchLanguage(newLocale) { // <--- 3
        Trans.currentLocale = newLocale
        document.querySelector("html").setAttribute("lang", newLocale)
        localStorage.setItem("user-locale", newLocale)

        if (newLocale !== "it") {
          useToasts().addToast({
            title : "Traduzioni non disponibili",
            content : `Le traduzioni in <b>${newLocale}</b> non sono complete. Alcune parti del sito potrebbero non essere tradotte!`
          })
        }

    },
    isLocaleSupported(locale) { // <--- 1
        return Trans.supportedLocales.includes(locale)
      },
      getUserLocale() { // <--- 2
        const locale = window.navigator.language ||
          window.navigator.userLanguage ||
          Trans.defaultLocale
        return {
          locale: locale,
          localeNoRegion: locale.split('-')[0]
        }
      },
      
      getPersistedLocale() { // <--- 3
        const persistedLocale = localStorage.getItem("user-locale")
        if(Trans.isLocaleSupported(persistedLocale)) {
          return persistedLocale
        } else {
          return null
        }
      },
      guessDefaultLocale() {
        const userPersistedLocale = Trans.getPersistedLocale()
        if(userPersistedLocale) {
          return userPersistedLocale
        }
        const userPreferredLocale = Trans.getUserLocale()
        if (Trans.isLocaleSupported(userPreferredLocale.locale)) {
          return userPreferredLocale.locale
        }
        if (Trans.isLocaleSupported(userPreferredLocale.localeNoRegion)) {
          return userPreferredLocale.localeNoRegion
        }
        
        return Trans.defaultLocale
      },
      get defaultLocale() {
        return import.meta.env.VITE_DEFAULT_LOCALE
      },
  }
  export default Trans