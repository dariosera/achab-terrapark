import {LoremIpsum} from 'lorem-ipsum'
const lorem = new LoremIpsum()

export default function getTemi() {
    return [
        {
            id : 0,
            url : "mobilita",
            title : "Mobilità",
            short_description : lorem.generateParagraphs(1),
            long_description : lorem.generateParagraphs(8),
        },
        {
            id : 1,
            url : "economia-circolare",
            title : "Economia circolare",
            short_description : lorem.generateParagraphs(1),
            long_description : lorem.generateParagraphs(8),
        },
        {
            id : 2,
            url: "cambiamento-climatico",
            title : "Cambiamento climatico",
            short_description : lorem.generateParagraphs(1),
            long_description : lorem.generateParagraphs(8),
        },
        {
            id : 3,
            url : "biodiversita",
            title : "Biodiversità",
            short_description : lorem.generateParagraphs(1),
            long_description : lorem.generateParagraphs(8),
        },{
            id : 4,
            url : "transizione-energetica",
            title : "Transizione energetica",
            short_description : lorem.generateParagraphs(1),
            long_description : lorem.generateParagraphs(8),
        }
    ]
}