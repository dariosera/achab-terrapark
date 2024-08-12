import { reactive } from 'vue'
import { defineStore } from 'pinia'
import { request } from '@/utils/request'

export const useTerraParkStore = defineStore('schema', () => {
  const schema = reactive({
    themes : [],
    topics : [],
    typologies: [],
    languages : [],
    authors: [],
  })

  const requests_to_resolve = [];

  let ready = false;


  const init = async () => {
    request({
      task : "public/schema",
      callback : async function(dt) {
        Object.assign(schema, dt);

        schema.themes.forEach(async (tema, i) => {
          try {
            let img = await import(`@/assets/theme_icons/${tema.url}.svg`)
            schema.themes[i].url_img = img.default
          } catch (e) {
            schema.themes[i].url_img = "";
          }
        })

        if (requests_to_resolve.length > 0) {
          requests_to_resolve.forEach(req => {
            console.log(`Ho i dati!!!`)
            req.resolve(req.query());
          })
        }

        ready = true;
      }
    })
  }

  const getTheme = (id) => {
    const filtered = schema.themes.filter(t => t.themeID == id);

    if (filtered.length === 1) {
      return filtered[0]
    } else {
      return { themeID : id, title : ""}
    }

  }

  const getThemes = async () => {

    return new Promise(resolve => {
      if (ready) {
        resolve(schema.themes);
        console.log(`Ho già i dati :)`)
      } else {
        console.log(`Non ho ancora i dati :()`)
        requests_to_resolve.push({
          resolve,
          query : () => schema.themes
        })
      }
    })
  }

  const getTopic = (id) => {
    const filtered = schema.topics.filter(t => t.topicID == id);

    if (filtered.length === 1) {
      return filtered[0]
    } else {
      return { topicID : id, themeID: null, title : ""}
    }
  }

  const getTopics = () => {
    return schema.topics;
  }

  const getTypology = (id) => {
    const filtered = schema.typologies.filter(t => t.typologyID == id);

    if (filtered.length === 1) {
      return filtered[0]
    } else {
      return { typologyID : id, description: "", icon : ""}
    }
  }

  const getTypologies = () => {
    return schema.typologies;
  }

  const getLanguages = () => {
    return schema.languages;
  }

  const getAuthor = (id) => {
    const filtered = schema.authors.filter(t => t.authorID == id);

    if (filtered.length === 1) {
      return filtered[0]
    } else {
      return { authorID : id, name : null, surname: null}
    }
  }


  return { schema, init, getTheme, getThemes, getTopic, getTopics, getTypology, getTypologies, getLanguages, getAuthor}
})
