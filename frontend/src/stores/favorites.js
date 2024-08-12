import { reactive } from 'vue'
import { defineStore } from 'pinia'
import { request } from '@/utils/request'

export const useFavoritesStore = defineStore('favorites', () => {

  const contents = reactive({})
  const contents_to_fetch = [];
  const requests_to_resolve = [];
  let ready = false;

  let loop = false;


  const getFavorite = (permalink) => {
    return new Promise(resolve => {
      if (permalink in contents) {
          console.log(`Trovato ${permalink} in cache`)
          resolve(contents[permalink])
      } else {
        console.log(`Non trovato ${permalink} in cache`)
        contents_to_fetch.push(permalink)
        requests_to_resolve.push({
          resolve,
          permalink,
        });

        if (loop === false) {
          loop = setTimeout(fetchAll,500);
        }
        
      }
    })
  }

  const setFavorite = (permalink,obj) => {
    contents[permalink] = obj;
    console.log(contents);
  }

  const fetchAll = () => {

    console.log(`fetch contents`)
    
    request({
      hideLoader : true,
      task : "userActions/getFavorites",
      data : {
        permalinks: contents_to_fetch,
      },
      callback : function(dt) {

        dt.forEach(c => {
          contents[c.permalink] = c.isFavorite
        })

        Object.assign(contents_to_fetch,[])
    
        requests_to_resolve.forEach(req => {
          req.resolve(contents[req.permalink])
        })
    
        Object.assign(requests_to_resolve,[])
        
        loop = false;
    
        console.log(contents);

      }
    })


  }

  return { contents, getFavorite, setFavorite}
})
