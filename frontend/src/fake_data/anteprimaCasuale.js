import {LoremIpsum} from 'lorem-ipsum'
import getTemi from './temi';
import { icone_tipologie_contenuto } from '@/utils/icone';

import pdfsample from './sample.pdf';

const lorem = new LoremIpsum()
const temi = getTemi()

const limiti = {
    titolo: 65,
    descrizione_breve : 130,
    descrizione_totale : 400,
}


export default function anteprimaCasuale(opts = {}) {

    const isCourse = opts?.corso === true ? true : Math.random() > 0.8

    const permalinks = ["aw6G3Twx","YvBY3Xqs","l0LeolR5","sbiRNHlG","R9uv6kp1","HqvrldOJ","es2DIwAC","r50GRlgx","GTamqEWV","CSEOjoW4","quUJYNIj","o8HlKCOK","iAjHUPU0","RdLC8k2K","7wBEbFLt","Cr4Es52C","g2IgGdIe","hjGKCYhC","WYnUBhm3","Ed7Eqj7R","TQa1MSBp","9LrUxyMS","MLEKSKy0","V5PoxJEU","BZSdmxvJ","mnCobJkv","Lc0oESX5","16JyR5BD","NxU1vIa7","jbqS9Dja","CRZEziYI","YkO0xdrO","TC19Pynz","hKBnQdb2","OUCKiUNo","T7N6D5lP","jJLC9yaA","RCkFYQFN","7hL70ROY","i4gKgFCY","GRyl1uxt","B3jNgZEM","6vz7kE2r","AgpuhzjS","NofRPgc3","cRjp0pQP","ro3MJQ0m","xypsOO7y","vG7tcE2e","OIgHrfIj"];

    const content_types = Object.keys(icone_tipologie_contenuto)

    const argomenti = ["Prevenzione rifiuti e riuso","Riduzione degli sprechi e consumo consapevole","Stili di vita sostenibili e decoro urbano","Raccolta differenziata di qualità"];

    console.log()

    const tema_casuale = temi[parseInt(Math.random()*temi.length)]

    return {
        permalink : 'rand'+parseInt(Math.random()*10000).toString().padStart(5,"0"),
        courseID : parseInt(Math.random()*100),
        title : lorem.generateSentences(2).substring(0,limiti.titolo),
        subtitle : argomenti[parseInt(Math.random()*argomenti.length)],
        description: `${lorem.generateSentences(2).substring(0,limiti.descrizione_breve)} | ${lorem.generateSentences(4).substring(0,limiti.descrizione_totale - limiti.descrizione_breve)}`,
        image : `https://scuolapark.fra1.cdn.digitaloceanspaces.com/c/${permalinks[parseInt(Math.random()*permalinks.length)]}/i/640x360.jpg`,
        progress : 0.5, //Math.random() > 0.5 ? Math.random() : null,
        isCourse,
        contentType: content_types[parseInt(Math.random()*content_types.length)],
        meta : {
            theme : {
                id : tema_casuale.id,
                url : tema_casuale.url,
                title : tema_casuale.title,
            },
            topic : {
                id : parseInt(Math.random()*100),
                title : lorem.generateWords(2) ,
            },
            duration: {
                value : parseInt(Math.random()*60),
            }
        },
        media : {
            pdf : {
                src : pdfsample
            }
        }

    }
}


