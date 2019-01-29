import db from './db'

/**
 * return all games of chosen city
 * @param city_id
 * @returns {Dexie.Promise<Array<T>>}
 */
export const gameIndex = async(city_id)=> await db.games.where('city_id').equals(+city_id).toArray()