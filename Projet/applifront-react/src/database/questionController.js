import db from './db'

/**
 * return the question of the chosen point of the current game
 * @param marker_id
 * @returns {Dexie.Promise<T>}
 */
export const questionShow = async marker_id => {
    let questions = await db.questions.where('point_id').equals(+marker_id[0]).toArray()
    return questions.find(q => +q.game_id === +marker_id[1])
}