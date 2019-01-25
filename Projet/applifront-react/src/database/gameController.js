import db from './db'

export const gameIndex = async(city_id)=> await db.games.where('city_id').equals(+city_id).toArray()