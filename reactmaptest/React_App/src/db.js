import Dexie from 'dexie';

const db = new Dexie('stgo');
db.version(1).stores({ pois: '++id , lat , lon , desc' });

export default db;