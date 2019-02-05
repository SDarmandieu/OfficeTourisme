import db from './db'

/**
 * get all answers of a specific question
 * @param question_id
 * @returns {Dexie.Promise<Array<T>>}
 */
export const answerIndex = async (question_id) => await db.answers.where('question_id').equals(+question_id).toArray()