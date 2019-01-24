import Dexie from 'dexie';


var db = new Dexie("officeTourisme");
db.version(1).stores({
    "cities": 'id,name,lat,lon',
    "games": 'id,name,age,desc,city_id,points,files',
    "points": 'id,lat,lon,desc',
    "questions": "id,content,expe,point_id,game_id,file_id",
    "answers": "id,content,valid,question_id,file_id",
    "files": "id,path,type,extension,alt,imagetype_id",
    "imagetypes": "id,title",
    "user": "++id,name,expe,games_done,questions_done"
});

export default db;