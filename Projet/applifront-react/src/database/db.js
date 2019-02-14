import Dexie from 'dexie';


let db = new Dexie("officeTourisme");
db.version(1).stores({
    "cities": 'id,name,lat,lon,games',
    "games": 'id,name,age,desc,city_id,points,files,questions,length',
    "points": 'id,lat,lon,desc',
    "questions": "id,content,expe,point_id,game_id,file",
    "answers": "id,content,valid,question_id,file",
    "files": "id,path,type,extension,alt,imagetype_id,games_to_id,questions_to_game_id,answers_to_game_id",
    "imagetypes": "id,title",
    "user": "++id,name,expe,games_done,questions_done,games_doing"
});

export default db;