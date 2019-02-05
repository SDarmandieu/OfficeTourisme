import db from './db'

/**
 * return all games of chosen city
 * @param city_id
 * @returns {Dexie.Promise<Array<T>>}
 */
export const gameCityIndex = async(city_id)=> await db.games.where('city_id').equals(+city_id).toArray()

/**
 * return games from a list of game id
 * @param array_of_game_id
 * @returns {Dexie.Promise<Array<T>>}
 */
export const gameSearch = async (array_of_game_id) => await db.games.where('id').anyOf(array_of_game_id).toArray()