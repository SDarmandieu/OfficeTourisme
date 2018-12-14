import Dexie from 'dexie';

const db = new Dexie('stgo');
db.version(1).stores({ 
	pois1: '++id , lat , lon , desc',
	pois2: '++id , lat , lon , desc'
});

export default db;