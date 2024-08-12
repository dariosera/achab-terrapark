import { reactive } from 'vue'
import { defineStore } from 'pinia'
import { request } from '@/utils/request'

export const useTagsStore = defineStore('tags', () => {

  const contents = reactive({})
  const contents_to_fetch = [];
  const requests_to_resolve = [];
  let ready = false;

  let loop = false;


  const getTags = async (permalink) => {
    return new Promise(resolve => {
      if (permalink in contents) {
          resolve(contents[permalink])
      } else {
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

  const fetchAll = () => {
    
    request({
      hideLoader : true,
      task : "contents/getTags",
      data : {
        permalinks: contents_to_fetch,
      },
      callback : function(dt) {

        dt.forEach(c => {
          contents[c.permalink] = c.tags
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

  return { contents, getTags}
})
