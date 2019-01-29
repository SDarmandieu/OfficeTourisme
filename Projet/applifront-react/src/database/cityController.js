import db from './db'

/**
 * return all cities from Dexie
 * @returns {Promise<*>}
 */
export const cityIndex = async () => await db.cities.toArray()

/**
 * return the chosen city
 * @param id
 * @returns {Promise<*>}
 */
export const cityShow = async (id) => await db.cities.get(+id)