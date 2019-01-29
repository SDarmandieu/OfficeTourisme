import db from './db'

export const answerIndex = async (question_id) => await db.answers.where('question_id').equals(+question_id).toArray()