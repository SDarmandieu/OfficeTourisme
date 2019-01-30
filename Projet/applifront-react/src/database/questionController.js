import db from './db'

/**
 * return the question of the chosen point of the current game
 * @param marker
 * @returns {Dexie.Promise<T>}
 */
export const questionShow = async marker => {
    let questions = await db.questions.where('point_id').equals(+marker.point.id).toArray()
    return questions.find(q => +q.game_id === +marker.game.id)
}