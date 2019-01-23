import Dexie from 'dexie';


var db = new Dexie("officeTourisme");
db.version(1).stores({
    "cities": 'id,name,lat,lon,games',
    "games": 'id,name,age,desc,city_id',
    "points" : 'id,lat,lon,desc,city_id',
    "questions" : "id,content,expe,point_id,game_id",
    "answers" : "id,content,valid,question_id"
});

export default db;