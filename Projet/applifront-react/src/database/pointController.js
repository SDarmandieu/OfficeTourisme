import db from './db'

/**
 * return all points of the chosen game
 * @param game
 * @returns {Dexie.Promise<Array<T>>}
 */
export const pointIndex = async game => await db.points.where('id').anyOf(game.points).toArray()